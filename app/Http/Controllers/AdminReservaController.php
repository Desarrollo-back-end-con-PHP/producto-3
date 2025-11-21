<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransferReserva;
use App\Models\TransferHotel;

class AdminReservaController extends Controller
{
    /**
     * muestra el listado de reservas y el cálculo de comisiones
     */
    public function index(Request $request)
    {

        //buscar reservas
        $query = TransferReserva::query()->with(['hotel', 'vehiculo']);

        //opción de filtrar por hotel (seleccionarlo en el buscador)
        if ($request->has('id_hotel') && $request->id_hotel != '') {
            $query->where('id_hotel', $request->id_hotel);
        }

        //ordenar por fecha
        $reservas = $query->orderBy('fecha_reserva', 'desc')->get();

        //calcular comisiones

        $totalComisionPagar = 0;

        foreach ($reservas as $reserva) {

            //si la reserva tiene un hotel guardamos la comisión
            if ($reserva->hotel) {
                $totalComisionPagar += $reserva->hotel->Comision;
            }
        }

        $hoteles = TransferHotel::activos()->orderBy('usuario')->get();

        return view('admin.reservas.index', compact('reservas', 'totalComisionPagar', 'hoteles'));
    }
}
