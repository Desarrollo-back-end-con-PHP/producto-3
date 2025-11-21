<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiZonaController;

// Laravel añade automáticamente el prefijo '/api' a estas rutas.
// URL Final: http://localhost/api/zonas-stats
Route::get('/zonas-stats', [ApiZonaController::class, 'index']);
