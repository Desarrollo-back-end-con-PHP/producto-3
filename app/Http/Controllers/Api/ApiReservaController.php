<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\TransferReserva;
use App\Models\TransferTipoReserva;
use App\Models\TransferHotel;
use App\Models\TransferViajero;
use App\Helpers\ProfileMessageHelper;

class ApiReservaController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function createData()
    {
        return response()->json([
            'trayectos' => TransferTipoReserva::all(),
            'hoteles'   => TransferHotel::all(),
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

        // ADMIN CREA RESERVA PARA OTRO CLIENTE
        if ($user->email === "admin@islatransfers.com") {

            if (!$request->email_cliente || !$request->codigo_admin) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'Debes indicar email y código admin.'
                ], 422);
            }

            $cliente = TransferViajero::where('email', $request->email_cliente)->first();

            if (!$cliente || !$cliente->perfil_completo) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'El perfil del cliente está incompleto.'
                ], 422);
            }

            $emailCliente = $request->email_cliente;

        } else {
            // CLIENTE NORMAL CREA SU RESERVA
            if (!$user->perfil_completo) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'PROFILE_REQUIRED'
                ], 403);
            }

            $emailCliente = $user->email;
        }

        // GUARDAR RESERVA
        $reserva = TransferReserva::create([
            'id_tipo_reserva'      => $request->id_tipo_reserva,
            'id_destino'           => $request->id_destino,
            'num_viajeros'         => $request->num_viajeros,
            'id_vehiculo'          => $request->id_vehiculo ?? null,
            'fecha_entrada'        => $request->fecha_entrada,
            'hora_entrada'         => $request->hora_entrada,
            'numero_vuelo_entrada' => $request->numero_vuelo_entrada,
            'origen_vuelo_entrada' => $request->origen_vuelo_entrada,
            'fecha_vuelo_salida'   => $request->fecha_vuelo_salida,
            'hora_vuelo_salida'    => $request->hora_vuelo_salida,
            'numero_vuelo_salida'  => $request->numero_vuelo_salida,
            'hora_recogida'        => $request->hora_recogida,
            'email_cliente'        => $emailCliente,
        ]);

        return response()->json([
            'success' => true,
            'mensaje' => ProfileMessageHelper::EXITO_RESERVA,
            'reserva' => $reserva
        ]);
    }

    public function misReservas()
    {
        $user = Auth::user();

        if ($user->email === 'admin@islatransfers.com') {
            $reservas = TransferReserva::all();
        } else {
            $reservas = TransferReserva::where('email_cliente', $user->email)->get();
        }

        return response()->json([
            'reservas' => $reservas
        ]);
    }

    public function edit($id)
    {
        $reserva = TransferReserva::findOrFail($id);
        $user = Auth::user();

        if ($user->email !== 'admin@islatransfers.com' &&
            $reserva->email_cliente !== $user->email) {

            return response()->json([
                'success' => false,
                'mensaje' => 'no_autorizado'
            ], 403);
        }

        return response()->json([
            'reserva'  => $reserva,
            'hoteles'  => TransferHotel::all(),
            'trayectos'=> TransferTipoReserva::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $reserva = TransferReserva::findOrFail($id);
        $user = Auth::user();

        if ($user->email !== 'admin@islatransfers.com' &&
            $reserva->email_cliente !== $user->email) {

            return response()->json([
                'success' => false,
                'mensaje' => 'no_autorizado'
            ], 403);
        }

        $reserva->update($request->all());

        return response()->json([
            'success' => true,
            'mensaje' => 'actualizado_ok',
            'reserva' => $reserva
        ]);
    }

    public function cancel($id)
    {
        $reserva = TransferReserva::findOrFail($id);
        $user = Auth::user();

        if ($user->email !== 'admin@islatransfers.com' &&
            $reserva->email_cliente !== $user->email) {

            return response()->json([
                'success' => false,
                'mensaje' => 'no_autorizado'
            ], 403);
        }

        $reserva->delete();

        return response()->json([
            'success' => true,
            'mensaje' => 'cancelado_ok'
        ]);
    }
}
