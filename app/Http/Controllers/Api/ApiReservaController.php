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
use App\Models\TransferReservaAdmin;

class ApiReservaController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function createDataApi()
    {
        return response()->json([
            'trayectos' => TransferTipoReserva::all(),
            'hoteles'   => TransferHotel::all(),
        ]);
    }

    public function storeApi(Request $request)
{
    $request->validate([
        'id_tipo_reserva' => 'required|integer',
        'id_destino'      => 'required|integer',
        'num_viajeros'    => 'required|integer|min:1',
    ]);

    $user = Auth::user();

    // Validación perfil usuario normal
    if ($user->email !== "admin@islatransfers.com" &&
        (!$user->nombre || !$user->apellido1 || !$user->email ||
         !$user->direccion || !$user->codigoPostal || !$user->ciudad || !$user->pais)) {
        return response()->json([
            'success' => false,
            'mensaje' => 'PROFILE_REQUIRED'
        ], 403);
    }

    // Determinar email del cliente
    if ($user->email === "admin@islatransfers.com") {
        if (!$request->email_cliente || !$request->codigo_admin) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Debes indicar email y código admin.'
            ], 400);
        }

        $cliente = TransferViajero::where('email', $request->email_cliente)->first();
        if (!$cliente ||
            !$cliente->nombre || !$cliente->apellido1 || !$cliente->email ||
            !$cliente->direccion || !$cliente->codigoPostal || !$cliente->ciudad || !$cliente->pais) {
            return response()->json([
                'success' => false,
                'mensaje' => 'El perfil del cliente está incompleto.'
            ], 400);
        }

        $emailCliente = $request->email_cliente;
        $codigo_admin = $request->codigo_admin;

    } else {
        $emailCliente = $user->email;
        $codigo_admin = null;
    }

    // Crear reserva normal
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
        return response()->json([
            'success' => false,
            'mensaje' => 'ERROR_CREACION'
        ], 500);
    }

    // Si es admin, guardar en tabla reserva_admin
    if ($user->email === "admin@islatransfers.com") {
        $reserva = TransferReserva::where('localizador', $localizador)->first();

        TransferReservaAdmin::create([
            'id_reserva' => $reserva->id_reserva,
            'id_admin'   => $codigo_admin,
        ]);
    }

    // Devolver localizador
    return response()->json([
        'success' => true,
        'mensaje' => ProfileMessageHelper::EXITO_RESERVA,
        'localizador' => $localizador,
    ]);
}

    
    
        public function misReservasApi()
    {
        $user = Auth::user();
        if ($user->email === 'admin@islatransfers.com') {
            $reservas = TransferReserva::all();
        } else {
            $reservas = TransferReserva::where('email_cliente', $user->email)->get();
        }

        return response()->json(['reservas' => $reservas]);
    }

    public function editApi($id)
    {
        $reserva = TransferReserva::findOrFail($id);
        $user = Auth::user();

         if ($user->email !== 'admin@islatransfers.com' &&
            $reserva->email_cliente !== $user->email) {
            return response()->json(['success' => false, 'mensaje' => 'no_autorizado'], 403);
        }

        return response()->json([
            'reserva'  => $reserva,
            'hoteles'  => TransferHotel::all(),
            'trayectos'=> TransferTipoReserva::all(),
        ]);
    }

    public function updateApi(Request $request, $id)
    {
        $reserva = TransferReserva::findOrFail($id);
        $user = Auth::user();

        if ($user->email !== 'admin@islatransfers.com' &&
            $reserva->email_cliente !== $user->email) {
            return response()->json(['success' => false, 'mensaje' => 'no_autorizado'], 403);
        }

        $reserva->update($request->all());

        return response()->json([
            'success' => true,
            'mensaje' => 'actualizado_ok',
            'reserva' => $reserva
        ]);
    }

    public function cancelApi($id)
    {
        $reserva = TransferReserva::findOrFail($id);
        $user = Auth::user();

        if ($user->email !== 'admin@islatransfers.com' &&
            $reserva->email_cliente !== $user->email) {
            return response()->json(['success' => false, 'mensaje' => 'no_autorizado'], 403);
        }
        $reserva->delete();

        return response()->json([
            'success' => true,
            'mensaje' => 'cancelado_ok'
        ]);
    }
}
