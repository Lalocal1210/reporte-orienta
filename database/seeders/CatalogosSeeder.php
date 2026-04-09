<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatalogosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insertar Roles
        \Illuminate\Support\Facades\DB::table('roles')->insert([
            ['id' => 1, 'nombre' => 'SuperAdmin', 'descripcion' => 'Administrador general del sistema', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nombre' => 'EmpleadoPlanta', 'descripcion' => 'Usuario operativo de planta', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Insertar Empresas
        $empresas = [
            ['nombre' => 'Empresa Alfa S.A. de C.V.', 'rfc' => 'ALFA123456789', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Empresa Beta S.A. de C.V.', 'rfc' => 'BETA123456789', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Empresa Gamma S.A. de C.V.', 'rfc' => 'GAMA123456789', 'created_at' => now(), 'updated_at' => now()],
        ];
        \Illuminate\Support\Facades\DB::table('empresas')->insert($empresas);

        // Insertar Especialidades
        $especialidades = [
            ['nombre' => 'Médico', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Psicólogo', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Veterinario', 'created_at' => now(), 'updated_at' => now()],
        ];
        \Illuminate\Support\Facades\DB::table('especialidades')->insert($especialidades);

        // Insertar 5 Plantas
        $empresaIds = \Illuminate\Support\Facades\DB::table('empresas')->pluck('id')->toArray();
        $plantas = [];
        for ($i = 1; $i <= 5; $i++) {
            $plantas[] = [
                'nombre' => 'Planta ' . $i,
                'empresa_id' => $empresaIds[array_rand($empresaIds)],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        \Illuminate\Support\Facades\DB::table('plantas')->insert($plantas);
    }
}
