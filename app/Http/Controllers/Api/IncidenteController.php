<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\IncidentesExport;
use Maatwebsite\Excel\Facades\Excel;

class IncidenteController extends Controller
{
    /**
     * Lista de incidentes paginada (JSON)
     */
    public function index(Request $request)
    {
        try {
            // 1. Simulación de Autenticación (Requerimiento del PDF)
            $userId = $request->header('X-Usuario-Simulado', 1);
            $usuarioAutenticado = DB::table('empleados')->where('id', $userId)->first();

            if (!$usuarioAutenticado) {
                return response()->json(['success' => false, 'error' => 'Usuario no encontrado'], 404);
            }

            // 2. Construcción de la Consulta (Query Builder Puro - Cero Eloquent)
            $query = $this->getBaseQuery();

            // 3. Lógica de Negocio (Filtrado Contextual de Seguridad)
            if ($usuarioAutenticado->permission_id !== 1) {
                // Si NO es SuperAdmin, filtramos estrictamente por su planta
                $query->where('empleados.planta_id', $usuarioAutenticado->planta_id);
            }

            // 4. Ejecución Optimizada (Paginación)
            $incidentes = $query->orderBy('incidentes.fecha_incidente', 'desc')->paginate(50);

            return response()->json([
                'success' => true,
                'data' => $incidentes,
                'contexto_sesion' => [
                    'simulando_id' => $usuarioAutenticado->id,
                    'nombre' => $usuarioAutenticado->nombre,
                    'rol' => $usuarioAutenticado->permission_id === 1 ? 'SuperAdmin' : 'Usuario de Planta',
                    'planta_id' => $usuarioAutenticado->planta_id
                ]
            ]);

        } catch (\Exception $e) {
            $this->registrarFallo($e);
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error interno. El evento ha sido registrado en el historial de fallos.'
            ], 500);
        }
    }

    /**
     * Exportación masiva a Excel
     */
    public function exportar(Request $request)
    {
        try {
            // Reutilizamos la lógica de simulación de usuario
            $userId = $request->header('X-Usuario-Simulado', 1);
            $usuarioAutenticado = DB::table('empleados')->where('id', $userId)->first();

            if (!$usuarioAutenticado) {
                return response()->json(['error' => 'Usuario no autorizado'], 403);
            }

            // Ejecutamos la descarga usando la clase especializada
            return Excel::download(new IncidentesExport($usuarioAutenticado), 'reporte_incidentes_' . now()->format('Ymd_His') . '.xlsx');

        } catch (\Exception $e) {
            $this->registrarFallo($e, 'Error en Exportación');
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el Excel. Revise el historial de fallos.'
            ], 500);
        }
    }

    /**
     * Query base reutilizable para mantener consistencia entre API y Excel
     */
    private function getBaseQuery()
    {
        return DB::table('incidentes')
            ->join('empleados', 'incidentes.empleado_id', '=', 'empleados.id')
            ->join('plantas', 'empleados.planta_id', '=', 'plantas.id')
            ->join('empresas', 'plantas.empresa_id', '=', 'empresas.id')
            ->leftJoin('especialidades', 'empleados.especialidad_id', '=', 'especialidades.id')
            ->select(
                'incidentes.id',
                'incidentes.descripcion',
                'incidentes.fecha_incidente',
                'empleados.nombre as empleado',
                'plantas.nombre as planta',
                'empresas.nombre as empresa',
                'especialidades.nombre as especialidad'
            );
    }

    /**
     * Sistema de Resiliencia: Registro de errores en DB
     */
    private function registrarFallo(\Exception $e, $contexto = 'Error API')
    {
        DB::table('historial_fallos')->insert([
            'mensaje' => '[' . $contexto . '] ' . $e->getMessage(),
            'traza' => $e->getTraceAsString(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}