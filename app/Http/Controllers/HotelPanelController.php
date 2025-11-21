<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TransferReserva;

class HotelPanelController extends Controller
{
    /**
     * Muestra el Panel de Control del Hotel logueado.
     */
    public function index()
    {
        //  Identificar hotel. Usamos el guard específico 'hotel'.
        $hotel = Auth::guard('hotel')->user();

        // Buscamos en transfer_reservas dódne el id_hotel coincida
        $reservas = TransferReserva::query()
            ->where('id_hotel', $hotel->id_hotel)
            ->with(['vehiculo', 'tipo'])
            ->orderBy('fecha_reserva', 'desc') // Las más recientes primero
            ->get();

        // Calcular comisiones
        $totalReservas = $reservas->count();
        $comisionPorReserva = $hotel->Comision;

        $totalComisiones = $totalReservas * $comisionPorReserva;


        return view('hotel.dashboard', compact('hotel', 'reservas', 'totalComisiones'));
    }

    /**
     * Muestra el formulario para que el HOTEL cree una reserva nueva.
     */
    public function createReserva()
    {
        // obtenemos las zonas, tipos de reserva i vehículos
        $zonas = \App\Models\TransferZona::all();
        $tipos = \App\Models\TransferTipoReserva::all();
        $vehiculos = \App\Models\TransferVehiculo::all();
        // obtenemos la lista de hoteles DESTINO (excluyendo al propio hotel origen)
        $hotelesDestino = \App\Models\TransferHotel::where('id_hotel', '!=', Auth::guard('hotel')->id())->get();

        return view('hotel.reservas.create', compact('zonas', 'tipos', 'vehiculos', 'hotelesDestino'));
    }
}
