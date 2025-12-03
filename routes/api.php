<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\TransferViajero;

use App\Http\Controllers\Api\ApiZonaController;
use App\Http\Controllers\ApiViajeroController;
use App\Http\Controllers\Api\ApiReservaController;

// 🌟 LOGIN - genera token
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string'
    ]);

    $user = TransferViajero::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Credenciales inválidas'
        ], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token
    ]);
});


// Laravel añade automáticamente el prefijo '/api' a estas rutas.
// URL Final: http://localhost/api/zonas-stats
Route::get('/zonas-stats', [ApiZonaController::class, 'index']);

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/usuario/perfil', [ApiViajeroController::class, 'mostrarPerfil']);
    Route::post('/usuario/actualizar-datos', [ApiViajeroController::class, 'actualizarDatos']);
    Route::post('/usuario/actualizar-contrasena', [ApiViajeroController::class, 'actualizarContrasena']);
    Route::delete('/usuario/eliminar', [ApiViajeroController::class, 'eliminarUsuario']);

    Route::get('/reservas/form-data', [ApiReservaController::class, 'createData']);
    Route::post('/reservas', [ApiReservaController::class, 'store']);
    Route::get('/reservas', [ApiReservaController::class, 'misReservas']);
    Route::get('/reservas/{id}', [ApiReservaController::class, 'edit']);
    Route::put('/reservas/{id}', [ApiReservaController::class, 'update']);
    Route::delete('/reservas/{id}', [ApiReservaController::class, 'cancel']);
});