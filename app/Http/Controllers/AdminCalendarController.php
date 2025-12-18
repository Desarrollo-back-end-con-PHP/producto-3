<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\TransferReserva;

class AdminCalendarController extends Controller
{
    public function index(Request $request)
    {
        // 1. Parámetros de navegación, vista y búsqueda
        $vista = $request->input('vista', 'mes');
        $anio = $request->input('ano', now()->year);
        $mes = $request->input('mes', now()->month);
        $dia = $request->input('dia', now()->day);
        $busqueda = $request->input('busqueda'); 

        $fechaBase = Carbon::createFromDate($anio, $mes, $dia);

        // 2. Definición de límites del Grid (Mantiene tu lógica de Mes, Semana y Día)
        if ($vista === 'semana') {
            $inicioGrid = $fechaBase->copy()->startOfWeek();
            $finGrid = $fechaBase->copy()->endOfWeek();
            $tituloCalendario = 'Semana ' . $fechaBase->weekOfYear . ' - ' . ucfirst($fechaBase->translatedFormat('F Y'));
            $navAnterior = $fechaBase->copy()->subWeek();
            $navSiguiente = $fechaBase->copy()->addWeek();
        } elseif ($vista === 'dia') {
            $inicioGrid = $fechaBase->copy()->startOfDay();
            $finGrid = $fechaBase->copy()->endOfDay();
            $tituloCalendario = ucfirst($fechaBase->translatedFormat('l, d F Y'));
            $navAnterior = $fechaBase->copy()->subDay();
            $navSiguiente = $fechaBase->copy()->addDay();
        } else {
            $inicioGrid = $fechaBase->copy()->startOfMonth()->startOfWeek();
            $finGrid = $fechaBase->copy()->endOfMonth()->endOfWeek();
            $tituloCalendario = ucfirst($fechaBase->translatedFormat('F Y'));
            $navAnterior = $fechaBase->copy()->subMonth();
            $navSiguiente = $fechaBase->copy()->addMonth();
        }

        // 3. Consulta Unificada (Igual que en Total Reservas)
        // Buscamos en el rango de fechas visible en el calendario
        $query = TransferReserva::with(['destino', 'hotel'])
            ->where(function($q) use ($inicioGrid, $finGrid) {
                $inicio = $inicioGrid->format('Y-m-d');
                $fin = $finGrid->copy()->endOfDay();
                
                $q->whereBetween('fecha_entrada', [$inicio, $fin])
                  ->orWhereBetween('fecha_vuelo_salida', [$inicio, $fin]);
            });

        // Aplicamos el filtro de búsqueda por Hotel o Localizador (como en el listado global)
        if ($busqueda) {
            $query->where(function($q) use ($busqueda) {
                $q->whereHas('destino', function($sub) use ($busqueda) {
                    $sub->where('usuario', 'like', "%{$busqueda}%");
                })
                ->orWhere('localizador', 'like', "%{$busqueda}%")
                ->orWhere('email_cliente', 'like', "%{$busqueda}%");
            });
        }

        // Ordenamos por hora para que el calendario se vea organizado
        $reservas = $query->orderBy('hora_entrada', 'asc')->get();

        // 4. Agrupación por día (Lógica para mostrar la reserva en cada fecha correspondiente)
        $reservasPorDia = [];
        foreach ($reservas as $reserva) {
            // Si tiene fecha de entrada, la añadimos a ese día
            if ($reserva->fecha_entrada) {
                $dEntrada = $reserva->fecha_entrada->format('Y-m-d');
                $reservasPorDia[$dEntrada][] = $reserva;
            }
            
            // Si tiene fecha de salida, la añadimos también (evitando duplicados en el mismo día)
            if ($reserva->fecha_vuelo_salida) {
                $dSalida = $reserva->fecha_vuelo_salida->format('Y-m-d');
                
                // Solo la añadimos si el día es distinto al de entrada o si no tenía fecha de entrada
                if (!isset($reservasPorDia[$dSalida]) || !collect($reservasPorDia[$dSalida])->contains('id_reserva', $reserva->id_reserva)) {
                    $reservasPorDia[$dSalida][] = $reserva;
                }
            }
        }

        // Enviamos todo a la vista
        return view('admin.calendar', compact(
            'reservasPorDia', 'fechaBase', 'tituloCalendario', 
            'inicioGrid', 'finGrid', 'navAnterior', 'navSiguiente', 'vista', 'busqueda'
        ));
    }
}