<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiViajeroController;
use App\Http\Controllers\Api\ApiZonaController;
use App\Http\Controllers\Api\ApiReservaController;


Route::post('/registro', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/logout', [ApiAuthController::class, 'logout'])->middleware('auth:sanctum');


Route::get('/zonas-stats', [ApiZonaController::class, 'index']);


Route::middleware('auth:sanctum')->group(function() {
    // Usuario
    Route::get('/usuario/perfil', [ApiViajeroController::class, 'mostrarPerfil']);
    Route::post('/usuario/actualizar-datos', [ApiViajeroController::class, 'actualizarDatos']);
    Route::post('/usuario/actualizar-contrasena', [ApiViajeroController::class, 'actualizarContrasena']);
    Route::delete('/usuario/eliminar', [ApiViajeroController::class, 'eliminarUsuario']);

    // Reservas
    Route::get('/reservas/form-data', [ApiReservaController::class, 'createData']);
    Route::post('/reservas/crear', [ApiReservaController::class, 'store']);
    Route::get('/reservas', [ApiReservaController::class, 'misReservas']);
    Route::get('/reservas/{id}', [ApiReservaController::class, 'edit']);
    Route::put('/reservas/{id}', [ApiReservaController::class, 'update']);
    Route::delete('/reservas/{id}', [ApiReservaController::class, 'cancel']);
    

});
