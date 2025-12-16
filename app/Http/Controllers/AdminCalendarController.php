<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\TransferReserva;

class AdminCalendarController extends Controller
{
    public function index(Request $request)
    {
        // 1. Recoger parámetros (Vista, Año, Mes, Día)
        $vista = $request->input('vista', 'mes'); // Por defecto 'mes'
        
        $anio = $request->input('ano', now()->year);
        $mes = $request->input('mes', now()->month);
        $dia = $request->input('dia', now()->day); // Necesario para la vista diaria

        // Creamos la fecha central según lo seleccionado
        $fechaBase = Carbon::createFromDate($anio, $mes, $dia);

        // 2. Calcular inicio y fin del Grid según la vista
        if ($vista === 'semana') {
            $inicioGrid = $fechaBase->copy()->startOfWeek();
            $finGrid = $fechaBase->copy()->endOfWeek();
            $tituloCalendario = 'Semana ' . $fechaBase->weekOfYear . ' - ' . ucfirst($fechaBase->translatedFormat('F Y'));
            
            // Navegación
            $navAnterior = $fechaBase->copy()->subWeek();
            $navSiguiente = $fechaBase->copy()->addWeek();

        } elseif ($vista === 'dia') {
            $inicioGrid = $fechaBase->copy(); // Solo hoy
            $finGrid = $fechaBase->copy();
            $tituloCalendario = ucfirst($fechaBase->translatedFormat('l, d F Y')); // Ej: Lunes, 27 Noviembre...
            
            // Navegación
            $navAnterior = $fechaBase->copy()->subDay();
            $navSiguiente = $fechaBase->copy()->addDay();

        } else { // VISTA MES (Default)
            $inicioGrid = $fechaBase->copy()->startOfMonth()->startOfWeek();
            $finGrid = $fechaBase->copy()->endOfMonth()->endOfWeek();
            $tituloCalendario = ucfirst($fechaBase->translatedFormat('F Y'));
            
            // Navegación
            $navAnterior = $fechaBase->copy()->subMonth();
            $navSiguiente = $fechaBase->copy()->addMonth();
        }

        // 3. Buscar Reservas en el rango calculado
        $reservas = TransferReserva::with(['destino', 'hotel'])
            ->where('status', '!=', 'cancelada')
            ->where(function($query) use ($inicioGrid, $finGrid) {
                // Ajustamos para incluir horas extremas del día final
                $finQuery = $finGrid->copy()->endOfDay();
                $query->whereBetween('fecha_entrada', [$inicioGrid, $finQuery])
                      ->orWhereBetween('fecha_vuelo_salida', [$inicioGrid, $finQuery]);
            })
            ->get();

        // 4. Agrupar por día
        $reservasPorDia = [];
        foreach ($reservas as $reserva) {
            if ($reserva->fecha_entrada) {
                $d = Carbon::parse($reserva->fecha_entrada)->format('Y-m-d');
                $reservasPorDia[$d][] = $reserva;
            }
            if ($reserva->fecha_vuelo_salida) {
                $d = Carbon::parse($reserva->fecha_vuelo_salida)->format('Y-m-d');
                if (!isset($reservasPorDia[$d]) || !in_array($reserva, $reservasPorDia[$d])) {
                    $reservasPorDia[$d][] = $reserva;
                }
            }
        }

        return view('admin.calendar', compact(
            'reservasPorDia', 'fechaBase', 'tituloCalendario', 
            'inicioGrid', 'finGrid', 'navAnterior', 'navSiguiente', 'vista'
        ));
    }
}