<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminHotelController;
use App\Http\Controllers\AdminReservaController;
use App\Http\Controllers\AdminCalendarController;
use App\Http\Controllers\AdminViajeroController;
use App\Http\Controllers\HotelAuthController;
use App\Http\Controllers\HotelPanelController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ViajeroController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// =========================================================================
//  RUTAS PÚBLICAS (Invitados)
// =========================================================================
Route::middleware('guest')->group(function () {
    // Autenticación
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);

    // Registro
    Route::get('/registro', [AuthController::class, 'register'])->name('register');
    Route::post('/registro', [AuthController::class, 'store'])->name('registro.store');
});


// =========================================================================
//  RUTAS USUARIO (Viajeros Logueados)
// =========================================================================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Gestión de Perfil
    Route::controller(ViajeroController::class)->group(function () {
        Route::get('/usuario/perfil', 'mostrarPerfil')->name('usuario.perfil');
        Route::post('/usuario/datos', 'actualizarDatos')->name('usuario.datos.update');
        Route::post('/usuario/password', 'actualizarContrasena')->name('usuario.password.update');
    });

    // Gestión de Reservas
    Route::controller(ReservaController::class)->group(function () {
        Route::get('/reservas/crear', 'create')->name('reservas.create');
        Route::post('/reservas', 'store')->name('reservas.store');
        Route::get('/mis-reservas', 'misReservas')->name('mis.reservas');
        Route::get('/reservas/{id}/editar', 'edit')->name('reservas.edit');
        Route::put('/reservas/{id}', 'update')->name('reservas.update');
        Route::delete('/reservas/{id}', 'cancel')->name('reservas.cancel');
        Route::put('/mis-reservas/cancelar/{id}', 'cancelarUsuario')->name('usuario.reservas.cancelar');
    });
});


// =========================================================================
//  RUTAS ADMINISTRADOR
// =========================================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Panel Principal y Calendario
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/calendar', [AdminCalendarController::class, 'index'])->name('admin.calendar');

    // Reportes
    Route::get('/comisiones', [AdminReservaController::class, 'comisiones'])->name('admin.comisiones');


    //Gestion de Viajeros
    Route::get('/viajeros', [AdminViajeroController::class, 'index'])->name('admin.viajeros.index');
    Route::get('/viajeros/crear', [AdminViajeroController::class, 'create'])->name('admin.viajeros.create');
    Route::post('/viajeros', [AdminViajeroController::class, 'store'])->name('admin.viajeros.store');
    Route::get('/viajeros/{id}/editar', [AdminViajeroController::class, 'edit'])->name('admin.viajeros.edit');
    Route::put('/viajeros/{id}', [AdminViajeroController::class, 'update'])->name('admin.viajeros.update');
    Route::delete('/viajeros/{id}', [AdminViajeroController::class, 'destroy'])->name('admin.viajeros.destroy');

    // Gestión de Hoteles (CRUD)
    Route::controller(AdminHotelController::class)->group(function () {
        Route::get('/hoteles', 'index')->name('admin.hoteles.index');
        Route::get('/hoteles/crear', 'create')->name('admin.hoteles.create');
        Route::post('/hoteles', 'store')->name('admin.hoteles.store');
        Route::get('hoteles/{id}/editar', 'edit')->name('admin.hoteles.edit');
        Route::put('hoteles/{id}', 'update')->name('admin.hoteles.update');
        Route::delete('/hoteles/{id}', 'destroy')->name('admin.hoteles.destroy');
    });

    // Gestión de Reservas (CRUD)
    Route::controller(AdminReservaController::class)->group(function () {
        Route::get('/reservas', 'index')->name('admin.reservas.index');
        Route::get('/reservas/crear', 'create')->name('admin.reservas.create');
        Route::post('/reservas', 'store')->name('admin.reservas.store');
        Route::get('/reservas/{reserva}/editar', 'edit')->name('admin.reservas.edit');
        Route::put('/reservas/{reserva}', 'update')->name('admin.reservas.update');
        Route::delete('/reservas/{reserva}', 'destroy')->name('admin.reservas.destroy');
    });
});


// =========================================================================
//  RUTAS PANEL CORPORATIVO (Hoteles)
// =========================================================================
Route::prefix('hotel')->group(function () {

    // Invitados (Hotel Login)
    Route::middleware('guest:hotel')->group(function () {
        Route::get('/login', [HotelAuthController::class, 'showLoginForm'])->name('hotel.login');
        Route::post('/login', [HotelAuthController::class, 'login'])->name('hotel.login.post');
    });

    // Logueados (Hotel Panel)
    Route::middleware('auth:hotel')->group(function () {
        Route::post('/logout', [HotelAuthController::class, 'logout'])->name('hotel.logout');

        Route::controller(HotelPanelController::class)->group(function () {
            Route::get('/panel', 'index')->name('hotel.panel');
            Route::get('/reservas/crear', 'createReserva')->name('hotel.reservas.create');
            Route::post('/reservas', 'store')->name('hotel.reservas.store');
            Route::get('/reservas/{id}/editar', 'edit')->name('hotel.reservas.edit');
            Route::put('/reservas/{id}', 'update')->name('hotel.reservas.update');
            Route::post('/reservas/{id}/cancelar', 'cancel')->name('hotel.reservas.cancel');
        });
    });
});
