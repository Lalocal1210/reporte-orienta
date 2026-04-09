<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IncidenteController;


// Aquí es donde registramos las rutas de la API para la aplicación.
//Estas rutas son cargadas por el RouteServiceProvider y todas ellas
//serán asignadas al grupo de middleware "api".

//Reto

// Endpoint principal: Extrae y filtra los incidentes según el rol y planta
Route::get('/incidentes', [IncidenteController::class, 'index']);
Route::get('/incidentes/exportar', [App\Http\Controllers\Api\IncidenteController::class, 'exportar']);
Route::get('/debug-errores', function () {
    return DB::table('historial_fallos')->latest()->get();
});
//rutas defecto
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');