<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncidenteViewController extends Controller
{
    /**
     * Muestra el Dashboard principal con filtros y simulación de roles.
     */
    public function index(Request $request)
    {
        // 1. Manejo del Usuario Simulado (Jerarquía de Seguridad)
        // Obtenemos el ID del request o de la sesión, por defecto ID 1 (SuperAdmin).
        $userId = $request->input('simular_usuario', session('user_id', 1));
        session(['user_id' => $userId]);

        $usuarioActivo = DB::table('empleados')->where('id', $userId)->first();

        // 2. Construcción de la Consulta Base (Query Builder Puro)
        $query = DB::table('incidentes')
            ->join('empleados', 'incidentes.empleado_id', '=', 'empleados.id')
            ->join('plantas', 'empleados.planta_id', '=', 'plantas.id')
            ->leftJoin('especialidades', 'empleados.especialidad_id', '=', 'especialidades.id')
            ->select(
                'incidentes.*',
                'empleados.nombre as empleado',
                'plantas.nombre as planta',
                'especialidades.nombre as especialidad'
            );

        // 3. Aplicar Regla de Seguridad (Jerarquía)
        // Si el usuario NO es SuperAdmin (permission_id != 1), restringimos a su planta.
        if ($usuarioActivo->permission_id !== 1) {
            $query->where('empleados.planta_id', $usuarioActivo->planta_id);
        }

        // 4. Aplicar Filtros Dinámicos desde la Interfaz
        if ($request->filled('planta')) {
            $query->where('plantas.id', $request->planta);
        }

        if ($request->filled('desde')) {
            $query->whereDate('incidentes.fecha_incidente', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('incidentes.fecha_incidente', '<=', $request->hasta);
        }

        // 5. Preparar Datos para los Selectores (Dropdowns)
        // CORRECCIÓN: Se eliminó el alias 'text' que causaba el error de "Undefined property: $nombre"
        $plantas = DB::table('plantas')->select('id', 'nombre')->get();

        // Usuarios para la demo: SuperAdmin (1), Usuario Planta A (2), Usuario Planta B (10)
        $usuarios_demo = DB::table('empleados')
            ->whereIn('id', [1, 2, 10])
            ->get();

        // 6. Estadísticas Contextuales
        // Clonamos la query para que los contadores reflejen los filtros aplicados en la tabla
        $stats = [
            'total' => (clone $query)->count(),
            'plantas' => DB::table('plantas')->count(),
            'fallos' => DB::table('historial_fallos')->count(),
            'rol_actual' => $usuarioActivo->permission_id === 1 ? 'SuperAdmin' : 'Operador de Planta',
            'usuario_nombre' => $usuarioActivo->nombre
        ];

        // 7. Ejecución con Paginación
        $incidentes = $query->orderBy('incidentes.fecha_incidente', 'desc')->paginate(12);

        // Mantenemos los parámetros de búsqueda activos en los links de paginación
        $incidentes->appends($request->all());

        return view('incidentes.index', compact('incidentes', 'stats', 'plantas', 'usuarios_demo'));
    }
}