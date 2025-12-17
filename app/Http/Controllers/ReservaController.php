<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\TransferReserva;
use App\Models\TransferTipoReserva;
use App\Models\TransferHotel;
use App\Models\TransferViajero;
use App\Helpers\ProfileMessageHelper;

class ReservaController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        return redirect()->route('hotel.reservas.create');
    }

public function create()
    {
        $trayectos = \App\Models\TransferTipoReserva::all();
        $hoteles   = \App\Models\TransferHotel::all();
        $vehiculos = \App\Models\TransferVehiculo::all();

        // CAMBIO 1: Apuntamos a la vista NUEVA de usuario (reservas.create)
        // en lugar de la del hotel.
        return view('users.reservas.create', [
            'tipos'          => $trayectos,
            'hotelesDestino' => $hoteles,
            'vehiculos'      => $vehiculos,
            'mensaje'        => session('mensaje_error'),
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'id_tipo_reserva' => 'required',
            'id_destino'      => 'required',
            'num_viajeros'    => 'required|integer|min:1',
        ]);

        $localizador = 'LOC-' . strtoupper(uniqid());

        // Validación de perfil completo (mantener tu lógica)
        if ($user->email !== "admin@islatransfers.com" &&
            (!$user->nombre || !$user->apellido1 || !$user->email)) { // ... resto de validaciones
            return redirect()->route('usuario.perfil')
                ->with('mensaje', 'Completa tu perfil antes de reservar.');
        }

        // Lógica para definir el email del cliente
        if ($user->email === "admin@islatransfers.com") {
             // ... (Tu lógica de admin se mantiene igual)
             $emailCliente = $request->email_cliente;
        } else {
            // Si es usuario normal, el cliente es él mismo
            $emailCliente = $user->email;
        }

        TransferReserva::create([
            'localizador'          => $localizador,
            'id_tipo_reserva'      => $request->id_tipo_reserva,
            'id_destino'           => $request->id_destino,
            'num_viajeros'         => $request->num_viajeros,
            'id_vehiculo'          => $request->id_vehiculo ?? null,
            'fecha_entrada'        => $request->fecha_entrada ?? null, // Usar null si no hay dato
            'hora_entrada'         => $request->hora_entrada ?? null,
            'numero_vuelo_entrada' => $request->numero_vuelo_entrada ?? null,
            // ... resto de campos opcionales
            'email_cliente'        => $emailCliente,
            'id_hotel'             => null, // IMPORTANTE: Usuario normal no lleva comisión de hotel
            'status'               => 'confirmada'
        ]);

        // CAMBIO 2: Redirigir a "Mis Reservas" para ver lo que has creado
        return redirect()->route('mis.reservas')
            ->with('mensaje_exito', '¡Reserva creada correctamente!');
    }


    public function misReservas()
    {
        $user = Auth::user();

        if ($user->email === 'admin@islatransfers.com') {
            $reservas = TransferReserva::all();
        } else {
            $reservas = TransferReserva::where('email_cliente', $user->email)->get();
        }

        $hoteles = TransferHotel::all()->pluck('usuario', 'id_hotel');

        return view('user.mis_reservas', [
            'reservas'    => $reservas,
            'hotelesMap'  => $hoteles,
            'user_id'     => $user->id
        ]);
    }


    public function edit($id)
    {
        $reserva = TransferReserva::findOrFail($id);
        $user    = Auth::user();

        if ($user->email !== 'admin@islatransfers.com' &&
            $reserva->email_cliente !== $user->email) {

            return redirect()->route('mis.reservas')
                ->with('mensaje', 'no_autorizado');
        }

        return view('reservas.editar_reserva', [
            'reserva'   => $reserva,
            'hoteles'   => TransferHotel::all(),
            'trayectos' => TransferTipoReserva::all(),
        ]);
    }


    public function update(Request $request, $id)
    {
        $reserva = TransferReserva::findOrFail($id);
        $user    = Auth::user();

        if ($user->email !== 'admin@islatransfers.com' &&
            $reserva->email_cliente !== $user->email) {

            return redirect()->route('mis.reservas')
                ->with('mensaje', 'no_autorizado');
        }

        $reserva->update($request->all());

        return redirect()->route('mis.reservas')
            ->with('mensaje', 'actualizado_ok');
    }


    public function cancel($id)
    {
        $reserva = TransferReserva::findOrFail($id);
        $user    = Auth::user();

        if ($user->email !== 'admin@islatransfers.com' &&
            $reserva->email_cliente !== $user->email) {

            return redirect()->route('mis.reservas')
                ->with('mensaje', 'no_autorizado');
        }

        $reserva->delete();

        return redirect()->route('mis.reservas')
            ->with('mensaje', 'cancelado_ok');
    }
}
