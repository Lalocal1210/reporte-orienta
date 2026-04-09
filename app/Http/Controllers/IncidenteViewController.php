<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class IncidenteViewController extends Controller
{
    public function index()
    {
        // Consulta principal
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

        // Estadísticas rápidas
        $stats = [
            'total' => DB::table('incidentes')->count(),
            'plantas' => DB::table('plantas')->count(),
            'fallos' => DB::table('historial_fallos')->count(),
        ];

        $incidentes = $query->orderBy('incidentes.fecha_incidente', 'desc')->paginate(12);

        return view('incidentes.index', compact('incidentes', 'stats'));
    }
}