<?php

namespace Database\Seeders;

use App\Models\Empleado;
use App\Models\Incidente;
use App\Models\Planta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();

        // 1. Sembrar Catálogos
        $this->call(CatalogosSeeder::class);

        // 2. Empleados de prueba fijos (Para que tú inicies sesión)
        $planta = Planta::first();

        Empleado::create([
            'nombre' => 'Usuario SuperAdmin',
            'email' => 'admin@admin.com',
            'permission_id' => 1,
            'planta_id' => $planta->id,
            'especialidad_id' => null,
        ]);

        Empleado::create([
            'nombre' => 'Usuario de Planta',
            'email' => 'empleado@planta.com',
            'permission_id' => 2,
            'planta_id' => $planta->id,
            'especialidad_id' => null,
        ]);

        // 3. MEJORA: Generar 50 empleados aleatorios (usando el factory)
        Empleado::factory(50)->create();

        // 4. Generar 500 incidentes distribuidos entre todos los empleados
        // Nota Arquitectónica: Para 500 registros, el factory nativo es suficiente.
        // Si fueran 50,000, usaríamos DB::table('incidentes')->insert() en chunks de 1000.
        Incidente::factory(500)->create();
    }
}