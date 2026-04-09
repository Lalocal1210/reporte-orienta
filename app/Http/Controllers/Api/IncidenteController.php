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
     * Implementa validación de cabeceras y jerarquía de seguridad.
     */
    public function index(Request $request)
    {
        try {
            // 1. Simulación de Autenticación y Validación de Header
            $userId = $request->header('X-Usuario-Simulado', 1);

            if (!is_numeric($userId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El encabezado X-Usuario-Simulado debe ser un valor numérico.'
                ], 400);
            }

            $usuarioAutenticado = DB::table('empleados')->where('id', $userId)->first();

            if (!$usuarioAutenticado) {
                return response()->json([
                    'success' => false,
                    'message' => 'El usuario simulado no existe en la base de datos.'
                ], 404);
            }

            // 2. Construcción de la Consulta (Query Builder Puro)
            $query = $this->getBaseQuery();

            // 3. Lógica de Jerarquía (Seguridad de Datos)
            if ($usuarioAutenticado->permission_id !== 1) {
                // Restricción: Los usuarios de planta solo ven datos de su propia planta
                $query->where('empleados.planta_id', $usuarioAutenticado->planta_id);
            }

            // 4. Ejecución con Ordenamiento y Paginación
            $incidentes = $query->orderBy('incidentes.fecha_incidente', 'desc')->paginate(50);

            return response()->json([
                'success' => true,
                'data' => $incidentes,
                'contexto_sesion' => [
                    'simulando_id' => $usuarioAutenticado->id,
                    'nombre' => $usuarioAutenticado->nombre,
                    'rol' => $usuarioAutenticado->permission_id === 1 ? 'SuperAdmin' : 'Operador de Planta',
                    'planta_id' => $usuarioAutenticado->planta_id
                ]
            ]);

        } catch (\Exception $e) {
            $this->registrarFallo($e, 'Error API Index');
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error interno. El evento ha sido registrado para revisión técnica.'
            ], 500);
        }
    }

    /**
     * Exportación masiva a Excel con protección de datos.
     */
    public function exportar(Request $request)
    {
        try {
            // Validación de identidad para el reporte
            $userId = $request->header('X-Usuario-Simulado', 1);

            if (!is_numeric($userId)) {
                return response()->json(['error' => 'ID de simulación inválido'], 400);
            }

            $usuarioAutenticado = DB::table('empleados')->where('id', $userId)->first();

            if (!$usuarioAutenticado) {
                return response()->json(['error' => 'Usuario no autorizado'], 403);
            }

            // Generación del archivo con timestamp para evitar colisiones
            $fileName = 'reporte_incidentes_' . now()->format('Ymd_His') . '.xlsx';

            return Excel::download(new IncidentesExport($usuarioAutenticado), $fileName);

        } catch (\Exception $e) {
            $this->registrarFallo($e, 'Error en Exportación');
            return response()->json([
                'success' => false,
                'message' => 'Error crítico al generar el reporte Excel.'
            ], 500);
        }
    }

    /**
     * Query base con Joins optimizados.
     * Centralizado para garantizar que la API y el Excel muestren la misma estructura.
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
     * Sistema de Resiliencia: Auditoría de fallos en base de datos.
     */
    private function registrarFallo(\Exception $e, $contexto)
    {
        DB::table('historial_fallos')->insert([
            'mensaje' => '[' . $contexto . '] ' . $e->getMessage(),
            'traza' => substr($e->getTraceAsString(), 0, 2000), // Limitamos para evitar saturación
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}