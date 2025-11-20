<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminHotelController;

Route::get('/', function () {
    return view('welcome');
});

//Grupo de rutas para Admin con prefijo admin
Route::prefix('admin')->group(function () {

    //ruta para listar los hoteles con url /admin/hoteles
    Route::get('/hoteles', [AdminHotelController::class, 'index'])->name('admin.hoteles.index');
});
