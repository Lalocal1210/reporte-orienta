<?php

namespace Database\Factories;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Empleado>
 */
class EmpleadoFactory extends Factory
{
    protected $model = Empleado::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'permission_id' => 2, // EmpleadoPlanta por defecto
            // Asigna una planta existente al azar
            'planta_id' => \App\Models\Planta::inRandomOrder()->value('id'),
            // Aseguramos que la especialidad pueda ser nula en algunos casos (aprox 30% nulo)
            'especialidad_id' => $this->faker->boolean(70) ? \App\Models\Especialidad::inRandomOrder()->value('id') : null,
        ];
    }
}
