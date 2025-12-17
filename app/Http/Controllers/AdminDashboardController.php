<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransferReserva;
use App\Models\TransferHotel;
use App\Models\TransferViajero; // Para contar usuarios

class AdminDashboardController extends Controller
{
    public function index()
    {
        //Contadores para las Tarjetas
        $totalReservas = TransferReserva::count();
        $totalHoteles = TransferHotel::activos()->count();
        $totalUsuarios = TransferViajero::count();

        // Tabla de Próximas Reservas
        $reservas = TransferReserva::query()
            ->with(['hotel', 'destino'])
            //->whereDate('fecha_entrada', '>=', now())
            ->orderBy('fecha_entrada', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('totalReservas', 'totalHoteles', 'totalUsuarios', 'reservas'));
    }
}
