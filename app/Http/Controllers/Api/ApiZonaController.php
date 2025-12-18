<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransferZona;
use App\Models\TransferReserva;

class ApiZonaController extends Controller
{
    public function index()
    {
        //Obtener el Total Global de reservas excluyendo las canceladas
        $totalReservasGlobal = TransferReserva::where('status', '!=', 'cancelada')->count();

        // Evitar división por cero si no hay datos
        if ($totalReservasGlobal == 0) {
            return response()->json(['mensaje' => 'No hay reservas registradas'], 200);
        }

        // Obtener todas las zonas
        $zonas = TransferZona::all();

        // Preparar el array de resultados
        $resultado = [];

        foreach ($zonas as $zona) {
            // CONTAR RESERVAS DE UNA ZONA

            // Obtener IDs de los hoteles de esta zona
            $idsHotelesEnZona = $zona->hoteles()->pluck('id_hotel');
            // Contar reservas que van a esos hoteles
            $numTraslados = TransferReserva::whereIn('id_destino', $idsHotelesEnZona)->count();

            // Calcular porcentaje
            // (Numtraslados / Total) * 100
            $porcentaje = ($numTraslados / $totalReservasGlobal) * 100;

            $resultado[] = [
                'zona' => $zona->descripcion,
                'numero_traslados' => $numTraslados,
                'porcentaje_total' => round($porcentaje, 2) . '%'
            ];
        }

        // Devolver JSON
        return response()->json([
            'status' => 'success',
            'total_global' => $totalReservasGlobal,
            'data' => $resultado
        ]);
    }
}
