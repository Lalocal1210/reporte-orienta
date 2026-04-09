<?php

namespace Database\Factories;

use App\Models\Incidente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Incidente>
 */
class IncidenteFactory extends Factory
{
    protected $model = Incidente::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'empleado_id' => \App\Models\Empleado::inRandomOrder()->value('id'),
            'descripcion' => $this->faker->realText(200),
            'fecha_incidente' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
