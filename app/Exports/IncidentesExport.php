<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IncidentesExport implements FromQuery, WithHeadings, WithMapping
{
    protected $usuario;

    public function __construct($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * REGLA: Usar Query Builder para alto rendimiento (50,000+ registros)
     * NOTA: Es obligatorio incluir orderBy() al usar FromQuery para permitir el chunking.
     */
    public function query()
    {
        $query = DB::table('incidentes')
            ->join('empleados', 'incidentes.empleado_id', '=', 'empleados.id')
            ->join('plantas', 'empleados.planta_id', '=', 'plantas.id')
            ->join('empresas', 'plantas.empresa_id', '=', 'empresas.id')
            ->leftJoin('especialidades', 'empleados.especialidad_id', '=', 'especialidades.id')
            ->select(
                'incidentes.id',
                'incidentes.descripcion',
                'incidentes.fecha_incidente',
                'empleados.nombre as empleado',
                'especialidades.nombre as especialidad',
                'plantas.nombre as planta',
                'empresas.nombre as empresa'
            )
            // SOLUCIÓN AL ERROR: El motor de Excel requiere un orden para procesar por lotes
            ->orderBy('incidentes.fecha_incidente', 'desc');

        // Aplicamos el mismo filtro de jerarquía de seguridad que en la API
        if ($this->usuario && $this->usuario->permission_id !== 1) {
            $query->where('empleados.planta_id', $this->usuario->planta_id);
        }

        return $query;
    }

    /**
     * Encabezados del Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Descripción del Incidente',
            'Fecha de Reporte',
            'Empleado Responsable',
            'Especialidad',
            'Planta',
            'Empresa'
        ];
    }

    /**
     * Mapeo de datos para cada fila
     * TRAMPA DEL PDF: Se manejan las especialidades nulas con un valor por defecto
     */
    public function map($incidente): array
    {
        return [
            $incidente->id,
            $incidente->descripcion,
            $incidente->fecha_incidente,
            $incidente->empleado,
            // Si es null, mostramos N/A para que el reporte se vea limpio
            $incidente->especialidad ?? 'N/A',
            $incidente->planta,
            $incidente->empresa,
        ];
    }
}