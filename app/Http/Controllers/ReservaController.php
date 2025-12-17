<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\TransferReserva;
use App\Models\TransferTipoReserva;
use App\Models\TransferHotel;
use App\Models\TransferViajero;
use App\Models\TransferVehiculo;
use App\Helpers\ProfileMessageHelper;

class ReservaController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        return redirect()->route('reservas.create');
    }

    public function create()
    {
        $trayectos = TransferTipoReserva::all();
        $hoteles   = TransferHotel::all();
        $vehiculos = TransferVehiculo::all();

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
            'id_tipo_reserva' => 'required|integer',
            'id_destino'      => 'required|integer',
            'num_viajeros'    => 'required|integer|min:1',
        ]);

        if ($user->email !== "admin@islatransfers.com" &&
            (!$user->nombre || !$user->apellido1 || !$user->email ||
             !$user->direccion || !$user->codigoPostal || !$user->ciudad || !$user->pais)) {
            return redirect()->route('usuario.perfil')
                ->with('mensaje', 'Completa tu perfil antes de reservar.');
        }

        if ($user->email === "admin@islatransfers.com") {
            if (!$request->email_cliente || !$request->codigo_admin) {
                return redirect()->back()->with('mensaje', 'Debes indicar email y código admin.');
            }

            $cliente = TransferViajero::where('email', $request->email_cliente)->first();
            if (!$cliente ||
                !$cliente->nombre || !$cliente->apellido1 || !$cliente->email ||
                !$cliente->direccion || !$cliente->codigoPostal || !$cliente->ciudad || !$cliente->pais) {
                return redirect()->back()->with('mensaje', 'El perfil del cliente está incompleto.');
            }

            $emailCliente = $request->email_cliente;
            $codigo_admin = $request->codigo_admin;
        } else {
            $emailCliente = $user->email;
            $codigo_admin = null;
        }

        $localizador = TransferReserva::crearReserva(
            $request->id_tipo_reserva,
            $request->id_destino,
            $request->fecha_entrada ?? null,
            $request->hora_entrada ?? null,
            $request->num_viajeros,
            $request->id_vehiculo ?? null,
            $request->numero_vuelo_entrada ?? null,
            $request->origen_vuelo_entrada ?? null,
            $request->fecha_vuelo_salida ?? null,
            $request->hora_vuelo_salida ?? null,
            $emailCliente,
            $request->numero_vuelo_salida ?? null,
            $request->hora_recogida ?? null
        );

        if (!$localizador) {
            return redirect()->back()->with('mensaje', 'Error al crear la reserva.');
        }

        if ($user->email === "admin@islatransfers.com") {
            $reserva = TransferReserva::where('localizador', $localizador)->first();

            \App\Models\TransferReservaAdmin::create([
                'id_reserva'     => $reserva->id_reserva,
                'id_admin'       => $codigo_admin,
                'fecha_creacion' => now(),
            ]);
        }

        return redirect()->route('mis.reservas')
            ->with('mensaje_exito', '¡Reserva creada correctamente!');
    }

public function misReservas()
{
    return redirect()->route('usuario.perfil');
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

        $vehiculos = TransferVehiculo::all();

        return view('reservas.editar_reserva', [
            'reserva'   => $reserva,
            'hoteles'   => TransferHotel::all(),
            'trayectos' => TransferTipoReserva::all(),
            'vehiculos' => $vehiculos,
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

    // Esta función es para BORRAR completamente (quizás solo admins)
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

    public function cancelarUsuario($id)
    {
        $user = Auth::user();
        $reserva = TransferReserva::findOrFail($id);
        if ($reserva->email_cliente !== $user->email) {
            return redirect()->route('usuario.perfil')
                ->with('mensaje', 'No tienes permiso para anular esta reserva.');
        }
        $reserva->status = 'cancelada';
        $reserva->fecha_modificacion = now();
        $reserva->save();
        return redirect()->route('usuario.perfil')
            ->with('success_pass', 'La reserva ha sido anulada correctamente.');
    }
}