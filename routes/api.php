<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiViajeroController;
use App\Http\Controllers\Api\ApiZonaController;
use App\Http\Controllers\Api\ApiReservaController;


Route::post('/registro', [ApiAuthController::class, 'registerApi']);
Route::post('/login', [ApiAuthController::class, 'loginApi']);
Route::post('/logout', [ApiAuthController::class, 'logoutApi'])->middleware('auth:sanctum'); 


Route::get('/zonas-stats', [ApiZonaController::class, 'index']);


Route::middleware('auth:sanctum')->group(function() {
    // Usuario
    Route::get('/usuario/perfil', [ApiViajeroController::class, 'mostrarPerfilApi']); 
    Route::post('/usuario/actualizar-datos', [ApiViajeroController::class, 'actualizarDatosApi']);
    Route::post('/usuario/actualizar-contrasena', [ApiViajeroController::class, 'actualizarContrasenaApi']);
    Route::delete('/usuario/eliminar', [ApiViajeroController::class, 'eliminarUsuarioApi']); 

    // Reservas
    Route::get('/reservas/form-data', [ApiReservaController::class, 'createDataApi']);
    Route::post('/reservas/crear', [ApiReservaController::class, 'storeApi']);
    Route::get('/reservas/misReservas', [ApiReservaController::class, 'misReservasApi']);
    Route::get('/reservas/{id}', [ApiReservaController::class, 'editApi']);
    Route::put('/reservas/{id}', [ApiReservaController::class, 'updateApi']);
    Route::delete('/reservas/{id}', [ApiReservaController::class, 'cancelApi']);
    

});
