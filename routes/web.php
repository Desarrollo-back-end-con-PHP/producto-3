<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminHotelController;
use App\Http\Controllers\AdminReservaController;
use App\Http\Controllers\AdminCalendarController;
use App\Http\Controllers\HotelAuthController;
use App\Http\Controllers\HotelPanelController;
// 1. AÑADIDO: Importamos el controlador para Perfil y Contraseña
use App\Http\Controllers\Api\ApiViajeroController;

Route::get('/', function () {
    return view('welcome');
});

// -------------------- RUTAS PÚBLICAS -----------------------------------
//Rutas para invitados (NO han iniciado sesión)
Route::middleware('guest')->group(function () {
    //LOGIN
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

    // 2. MODIFICADO: Apuntamos al controlador para ver DATOS + RESERVAS
    Route::get('/usuario/perfil', [ApiViajeroController::class, 'mostrarPerfil'])->name('usuario.perfil');

    // 3. AÑADIDO: Ruta para guardar la nueva contraseña
    Route::post('/usuario/password', [ApiViajeroController::class, 'actualizarContrasenaWeb'])->name('usuario.password.update');
});

// ---------------------- RUTAS PROTEGIDAS ADMIN -------------------------------------
//Grupo de rutas para Admin con prefijo admin, valida por el correo admin@islatransfers.com
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    //DASHBOARD
    Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/calendar', [\App\Http\Controllers\AdminCalendarController::class, 'index'])->name('admin.calendar');

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


    //------------------------ RESERVAS   ------------------------------------------
    //MOSTRAR RESERVAS Y COMISIONES 
    Route::get('/reservas', [\App\Http\Controllers\AdminReservaController::class, 'index'])->name('admin.reservas.index');
    // RUTAS CRUD QUE FALTABAN PARA CREAR Y EDITAR:
    Route::get('/reservas/crear', [\App\Http\Controllers\AdminReservaController::class, 'create'])->name('admin.reservas.create');
    Route::post('/reservas', [\App\Http\Controllers\AdminReservaController::class, 'store'])->name('admin.reservas.store');
    Route::get('/reservas/{reserva}/editar', [\App\Http\Controllers\AdminReservaController::class, 'edit'])->name('admin.reservas.edit');
    Route::put('/reservas/{reserva}', [\App\Http\Controllers\AdminReservaController::class, 'update'])->name('admin.reservas.update');
    Route::delete('/reservas/{reserva}', [\App\Http\Controllers\AdminReservaController::class, 'destroy'])->name('admin.reservas.destroy');

    // REPORTE DE COMISIONES
    Route::get('/comisiones', [\App\Http\Controllers\AdminReservaController::class, 'comisiones'])->name('admin.comisiones');
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

        //RESERVAS
        Route::get('/reservas/crear', [\App\Http\Controllers\HotelPanelController::class, 'createReserva'])->name('hotel.reservas.create');
        Route::post('/reservas', [\App\Http\Controllers\HotelPanelController::class, 'store'])->name('hotel.reservas.store');
    });
});