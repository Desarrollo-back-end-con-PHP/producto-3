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

    /**
     * Guarda la reserva creada por el hotel.
     */
    public function store(Request $request)
    {
        // VALIDACIÓN
        $validated = $request->validate([
            'email_cliente' => 'required|email|exists:transfer_viajeros,email', //el email debe existir en la tabla viajeros sino se solicitará que se registre
            'id_tipo_reserva' => 'required|exists:transfer_tipo_reservas,id_tipo_reserva',
            'id_destino' => 'required|exists:transfer_hotels,id_hotel', // El destino debe ser un hotel válido
            'id_vehiculo' => 'required|exists:transfer_vehiculo,id_vehiculo',
            'fecha_entrada' => 'required|date|after:today', // Fecha futura
            'hora_entrada' => 'required',
            'num_viajeros' => 'required|integer|min:1|max:50',
            'numero_vuelo_entrada' => 'nullable|string|max:50',
            'origen_vuelo_entrada' => 'nullable|string|max:50',
        ]);

        // Obtenemos el ID del hotel que está logueado en este momento.
        // Así aseguramos que la comisión vaya al hotel correcto y no a otro.
        $idHotelLogueado = Auth::guard('hotel')->id();

        //GENERAR LOCALIZADOR ÚNICO
        $localizador = 'HTL-' . strtoupper(substr(md5(uniqid()), 0, 6));

        //GUARDAR EN BASE DE DATOS
        TransferReserva::create([
            'localizador' => $localizador,

            // Aquí asignamos la reserva a ESTE hotel (para su comisión)
            'id_hotel' => $idHotelLogueado,

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
