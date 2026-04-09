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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('email')->unique(); // Necesario para simular sesión/login
            
            // Llave foránea hacia ROLES (usamos el nombre que pide el PDF: permission_id)
            // default 2 asume que 1 es SuperAdmin y 2 es Usuario de Planta
            $table->foreignId('permission_id')->default(2)->constrained('roles')->onDelete('restrict');
            
            // Llave foránea hacia PLANTAS
            $table->foreignId('planta_id')->constrained('plantas')->onDelete('cascade');
            
            // REGLA DE ORO: Especialidad puede ser nula. Si se borra la especialidad, el empleado no se borra, solo queda en null.
            $table->foreignId('especialidad_id')->nullable()->constrained('especialidades')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
