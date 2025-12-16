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
        $trayectos = TransferTipoReserva::all();
        $hoteles   = TransferHotel::all();

        return view('hotel.reservas.create', [
            'trayectos' => $trayectos,
            'hoteles'   => $hoteles,
            'mensaje'   => session('mensaje_error'),
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'id_tipo_reserva' => 'required',
            'id_destino'      => 'required',
            'num_viajeros'    => 'required|integer|min:1',
        ]);

         $user = Auth::user();

        if ($user->email !== "admin@islatransfers.com" &&
            (!$user->nombre || !$user->apellido1 || !$user->email ||
             !$user->direccion || !$user->codigoPostal || !$user->ciudad || !$user->pais)) {

            return redirect()->route('usuario.perfil')
                ->with('mensaje', 'Debes completar todos los datos de tu perfil antes de hacer una reserva.');
        }

        if ($user->email === "admin@islatransfers.com") {

            if (!$request->email_cliente || !$request->codigo_admin) {
                return back()->with('mensaje_error', 'Debes indicar email y código admin.');
            }

            $cliente = TransferViajero::where('email', $request->email_cliente)->first();

            if (!$cliente ||
                !$cliente->nombre || !$cliente->apellido1 || !$cliente->email ||
                !$cliente->direccion || !$cliente->codigoPostal || !$cliente->ciudad || !$cliente->pais) {
                
                return back()->with('mensaje_error', 'El perfil del cliente está incompleto.');
            }

            $emailCliente = $request->email_cliente;

        } else {
            $emailCliente = $user->email;
        }


        TransferReserva::create([
            'id_tipo_reserva'      => $request->id_tipo_reserva,
            'id_destino'           => $request->id_destino,
            'num_viajeros'         => $request->num_viajeros,
            'id_vehiculo'          => $request->id_vehiculo ?? null,
            'fecha_entrada'        => $request->fecha_entrada ?? '',
            'hora_entrada'         => $request->hora_entrada ?? '',
            'numero_vuelo_entrada' => $request->numero_vuelo_entrada ?? '',
            'origen_vuelo_entrada' => $request->origen_vuelo_entrada ?? '',
            'fecha_vuelo_salida'   => $request->fecha_vuelo_salida ?? '',
            'hora_vuelo_salida'    => $request->hora_vuelo_salida ?? '',
            'numero_vuelo_salida'  => $request->numero_vuelo_salida ?? '',
            'hora_recogida'        => $request->hora_recogida ?? '',
            'email_cliente'        => $emailCliente,
        ]);

        return redirect()
            ->route('hotel.reservas.create')
            ->with('mensaje_exito', ProfileMessageHelper::EXITO_RESERVA);
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
