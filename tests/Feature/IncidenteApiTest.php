<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder; // Importamos el seeder principal

class IncidenteApiTest extends TestCase
{
    /**
     * RefreshDatabase recrea la estructura de la base de datos (migraciones)
     * en cada ejecución de test para garantizar un ambiente limpio.
     */
    use RefreshDatabase;

    /**
     * El método setUp se ejecuta antes de cada test individual.
     * Aquí poblamos la base de datos con los seeders para que existan
     * los registros necesarios (ID 1, plantas, incidentes, etc.).
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Poblamos la DB de prueba con los datos definidos en tus Seeders
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Prueba que la API de incidentes responda correctamente para un Admin.
     */
    public function test_api_lista_incidentes_exitosamente()
    {
        $response = $this->withHeaders([
            'X-Usuario-Simulado' => 1,
            'Accept' => 'application/json'
        ])->getJson('/api/incidentes');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'current_page',
                    'data' => [
                        '*' => ['id', 'descripcion', 'empleado', 'planta']
                    ]
                ]
            ]);
    }

    /**
     * Prueba que el sistema falle (400) si el ID enviado no es un número.
     */
    public function test_api_falla_con_id_no_numerico()
    {
        $response = $this->withHeaders([
            'X-Usuario-Simulado' => 'TEXTO_INVALIDO',
            'Accept' => 'application/json'
        ])->getJson('/api/incidentes');

        $response->assertStatus(400);
    }

    /**
     * Prueba que el sistema falle (404) si el usuario numérico no existe.
     */
    public function test_api_falla_con_usuario_inexistente()
    {
        $response = $this->withHeaders([
            'X-Usuario-Simulado' => 999999, // ID que no existe
            'Accept' => 'application/json'
        ])->getJson('/api/incidentes');

        $response->assertStatus(404);
    }

    /**
     * Prueba que la exportación a Excel inicie correctamente y devuelva el archivo.
     */
    public function test_exportacion_excel_retorna_archivo()
    {
        $response = $this->withHeaders([
            'X-Usuario-Simulado' => 1
        ])->get('/api/incidentes/exportar');

        $response->assertStatus(200);

        // Verificamos que el tipo de contenido sea el de un archivo Excel .xlsx
        $this->assertEquals(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            $response->headers->get('content-type')
        );
    }
}