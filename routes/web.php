<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminHotelController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// -------------------- RUTAS PÚBLICAS -----------------------------------
//Rutas para invitados (NO han iniciado sesión)
Route::middleware('guest')->group(function () {
    //LOGGIN
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);

    //REGISTRO
    Route::get('/registro', [AuthController::class, 'register'])->name('register');
    Route::post('/registro', [AuthController::class, 'store'])->name('registro.store');
});


// -------------------- RUTAS PROTEGIDAS USUARIO -----------------------------------
//Rutas protegidas (SI han iniciado sesión)
Route::middleware('auth')->group(function () {
    //LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Ruta temporal para ver que el login funciona (Perfil)
    Route::get('/usuario/perfil', function () {
        return "ESTAS EN TU PERFIL. Hola " . Auth::user()->nombre;
    })->name('usuario.perfil');
});

// ---------------------- RUTAS PROTEGIDAS ADMIN -------------------------------------
//Grupo de rutas para Admin con prefijo admin, valida por el correo admin@islatransfers.com
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    //------------------------ HOTELES -----------------------------------------------------------
    //LISTAR (GET)
    Route::get('/hoteles', [AdminHotelController::class, 'index'])->name('admin.hoteles.index');
    //FORMULARIO CREAR (GET)
    Route::get('/hoteles/crear', [AdminHotelController::class, 'create'])->name('admin.hoteles.create');
    //GUARDAR (POST)
    Route::post('/hoteles', [AdminHotelController::class, 'store'])->name('admin.hoteles.store');
    //FORMULARIO EDITAR (GET)
    Route::get('hoteles/{id}/editar', [AdminHotelController::class, 'edit'])->name('admin.hoteles.edit');
    //ACTUALIZAR (PUT)
    Route::put('hoteles/{id}', [AdminHotelController::class, 'update'])->name('admin.hoteles.update');
    //ELIMINAR (DELETE)
    Route::delete('/hoteles/{id}', [AdminHotelController::class, 'destroy'])->name('admin.hoteles.destroy');
});


// --------------------- RUTAS PANEL CORPORATIVO (HOTELES) -------------------------------
// Gestion ael acceso de los hoteles a su panel corporativo
Route::prefix('hotel')->group(function () {
    //--------------- Rutas públicas para hoteles (NO logueados)
    Route::middleware('guest:hotel')->group(function () {
        //LOGIN
        Route::get('/login', [\App\Http\Controllers\HotelAuthController::class, 'showLoginForm'])->name('hotel.login');
        Route::post('/login', [\App\Http\Controllers\HotelAuthController::class, 'login'])->name('hotel.login.post');
    });

    // -------------- Rutas protegidas para hoteles (SI logueados)
    Route::middleware('auth:hotel')->group(function () {
        //LOGOUT
        Route::post('/logout', [\App\Http\Controllers\HotelAuthController::class, 'logout'])->name('hotel.logout');

        //PANEL DE CONTROL CORPORATIVO
        Route::get('/panel', [\App\Http\Controllers\HotelPanelController::class, 'index'])->name('hotel.panel');

        //CREAR RESERVA
        Route::get('/reservas/crear', [\App\Http\Controllers\HotelPanelController::class, 'createReserva'])->name('hotel.reservas.create');
    });
});
