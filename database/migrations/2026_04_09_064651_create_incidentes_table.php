<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incidentes', function (Blueprint $table) {
            $table->id();
            
            // El foreignId ya crea un índice B-Tree automáticamente para los JOINs
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            
            $table->text('descripcion');
            
            // ¡Mejora Arquitectónica! Añadimos un índice a la fecha.
            // Si Orienta mañana pide un filtro "Desde - Hasta", la consulta volará.
            $table->dateTime('fecha_incidente')->index(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidentes');
    }
};
