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
        'id_tipo_reserva' => 'required|integer',
        'id_destino'      => 'required|integer',
        'num_viajeros'    => 'required|integer|min:1',
    ]);

    $user = Auth::user();

    if (!$user->nombre || !$user->apellido1 || !$user->email ||
        !$user->direccion || !$user->codigoPostal || !$user->ciudad || !$user->pais) {
        return response()->json([
            'success' => false,
            'mensaje' => 'PROFILE_REQUIRED'
        ], 403);
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
        $user->email,
        $request->numero_vuelo_salida ?? null,
        $request->hora_recogida ?? null
    );

    if (!$localizador) {
        return response()->json([
            'success' => false,
            'mensaje' => 'ERROR_CREACION'
        ], 500);
    }

    return response()->json([
        'success' => true,
        'mensaje' => ProfileMessageHelper::EXITO_RESERVA,
        'localizador' => $localizador
    ]);
}
    public function misReservas()
    {
        $user = Auth::user();
        $reservas = TransferReserva::where('email_cliente', $user->email)->get();
        return response()->json(['reservas' => $reservas]);
    }

    public function edit($id)
    {
        $reserva = TransferReserva::findOrFail($id);
        $user = Auth::user();

        if ($reserva->email_cliente !== $user->email) {
            return response()->json(['success' => false, 'mensaje' => 'no_autorizado'], 403);
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

        if ($reserva->email_cliente !== $user->email) {
            return response()->json(['success' => false, 'mensaje' => 'no_autorizado'], 403);
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

        if ($reserva->email_cliente !== $user->email) {
            return response()->json(['success' => false, 'mensaje' => 'no_autorizado'], 403);
        }

        $reserva->delete();

        return response()->json([
            'success' => true,
            'mensaje' => 'cancelado_ok'
        ]);
    }
}
