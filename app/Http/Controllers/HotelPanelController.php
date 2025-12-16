<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TransferReserva;
use App\Models\TransferZona;
use App\Models\TransferTipoReserva;
use App\Models\TransferVehiculo;
use App\Models\TransferHotel;

class HotelPanelController extends Controller
{
    /**
     * Muestra el Panel de Control del Hotel logueado.
     */
    public function index()
    {
        $hotel = Auth::guard('hotel')->user();
        $comisionPorReserva = $hotel->Comision ?? 10;

        // 1. Buscamos reservas históricas para el listado
        $reservas = TransferReserva::query()
            ->where('id_hotel', $hotel->id_hotel)
            ->with(['vehiculo', 'tipo', 'destino']) // Añadido destino para verlo en la tabla
            ->orderBy('fecha_reserva', 'desc')
            ->paginate(10); // Usamos paginación para que no sea infinito

        // 2. RÚBRICA: Calcular comisión ESPECÍFICA de "Este Mes"
        $reservasEsteMes = TransferReserva::where('id_hotel', $hotel->id_hotel)
            ->where('status', '!=', 'cancelada')
            ->whereMonth('fecha_reserva', now()->month)
            ->whereYear('fecha_reserva', now()->year)
            ->count();

        $comisionMensual = $reservasEsteMes * $comisionPorReserva;

        // 3. Comisión Total Histórica (Opcional, pero queda bien)
        $totalReservasHist = TransferReserva::where('id_hotel', $hotel->id_hotel)
             ->where('status', '!=', 'cancelada')
             ->count();
        $totalComisiones = $totalReservasHist * $comisionPorReserva;

        // ASEGÚRATE de que la vista existe en resources/views/hotel/dashboard.blade.php
        return view('hotel.dashboard', compact('hotel', 'reservas', 'comisionMensual', 'totalComisiones'));
    }

    /**
     * Muestra el formulario para que el HOTEL cree una reserva nueva.
     */
    public function createReserva()
    {
        $zonas = TransferZona::all();
        $tipos = TransferTipoReserva::all();
        $vehiculos = TransferVehiculo::all();
        
        // CORRECCIÓN: Quitamos el filtro '!='. 
        // Un hotel SÍ puede querer reservar un transfer HACIA sí mismo (Aeropuerto -> Mi Hotel)
        $hotelesDestino = TransferHotel::where('status', 'activo')->orderBy('usuario')->get();

        return view('hotel.reservas.create', compact('zonas', 'tipos', 'vehiculos', 'hotelesDestino'));
    }

    /**
     * Guarda la reserva creada por el hotel.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email_cliente' => 'required|email|max:100', 
            'id_tipo_reserva' => 'required|exists:transfer_tipo_reservas,id_tipo_reserva',
            'id_destino' => 'required|exists:transfer_hotels,id_hotel',
            'id_vehiculo' => 'required|exists:transfer_vehiculos,id_vehiculo', 
            'fecha_entrada' => 'required|date|after_or_equal:today', // after_or_equal permite reservas para hoy mismo
            'hora_entrada' => 'required',
            'num_viajeros' => 'required|integer|min:1|max:50',
            'numero_vuelo_entrada' => 'nullable|string|max:50',
            'origen_vuelo_entrada' => 'nullable|string|max:50',
        ]);

        $idHotelLogueado = Auth::guard('hotel')->id();
        $localizador = 'HTL-' . strtoupper(substr(md5(uniqid()), 0, 6));

        TransferReserva::create([
            'localizador' => $localizador,
            'id_hotel' => $idHotelLogueado, // <--- CLAVE: El hotel logueado se lleva la comisión
            'email_cliente' => $validated['email_cliente'],
            'id_tipo_reserva' => $validated['id_tipo_reserva'],
            'id_destino' => $validated['id_destino'],
            'id_vehiculo' => $validated['id_vehiculo'],
            'fecha_reserva' => now(),
            'fecha_modificacion' => now(), 
            'fecha_entrada' => $validated['fecha_entrada'],
            'hora_entrada' => $validated['hora_entrada'],
            'numero_vuelo_entrada' => $validated['numero_vuelo_entrada'] ?? null,
            'origen_vuelo_entrada' => $validated['origen_vuelo_entrada'] ?? null,
            'num_viajeros' => $validated['num_viajeros'],
            'status' => 'confirmada'
        ]);

        return redirect()->route('hotel.panel')
            ->with('success', 'Reserva creada correctamente. Comisión registrada.');
    }
}