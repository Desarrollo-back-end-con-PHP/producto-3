<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransferReserva;
use App\Models\TransferHotel;
use App\Models\TransferVehiculo;
use App\Models\TransferTipoReserva;
use Illuminate\Support\Facades\DB;

class AdminReservaController extends Controller
{
    /**
     * Listado General
     */
    public function index(Request $request)
    {
        $query = TransferReserva::query()->with(['hotel', 'vehiculo']);
        if ($request->has('id_hotel') && $request->id_hotel != '') {
            $query->where('id_hotel', $request->id_hotel);
        }
        $reservas = $query->orderBy('fecha_reserva', 'desc')->get();

        $totalComisionPagar = 0;
        foreach ($reservas as $reserva) {
            if ($reserva->hotel) {
                $totalComisionPagar += $reserva->hotel->Comision ?? 10;
            }
        }
        $hoteles = TransferHotel::activos()->orderBy('usuario')->get();

        return view('admin.reservas.index', compact('reservas', 'totalComisionPagar', 'hoteles'));
    }

    /**
     * Reporte Comisiones (Rúbrica)
     */
    public function comisiones()
    {
        $informe = DB::table('transfer_reservas')
            ->join('transfer_hotels', 'transfer_reservas.id_hotel', '=', 'transfer_hotels.id_hotel')
            ->select(
                'transfer_hotels.usuario as nombre_hotel',
                DB::raw('COALESCE(transfer_hotels.Comision, 10) as comision_base'),
                DB::raw('YEAR(transfer_reservas.fecha_reserva) as anio'),
                DB::raw('MONTH(transfer_reservas.fecha_reserva) as mes'),
                DB::raw('COUNT(transfer_reservas.id_reserva) as total_reservas'),
                DB::raw('SUM(COALESCE(transfer_hotels.Comision, 10)) as total_pagar')
            )
            ->where('transfer_reservas.status', '!=', 'cancelada')
            ->groupBy('transfer_hotels.id_hotel', 'transfer_hotels.usuario', 'transfer_hotels.Comision', 'anio', 'mes')
            ->orderBy('anio', 'desc')
            ->orderBy('mes', 'desc')
            ->orderBy('total_pagar', 'desc')
            ->get();

        return view('admin.reservas.comisiones', compact('informe'));
    }

    /**
     * Formulario de creación (Admin)
     */
    public function create(Request $request)
    {
        // Recogemos la fecha que nos manda el calendario (si existe)
        $fechaPreseleccionada = $request->input('fecha');

        $hoteles = TransferHotel::activos()->orderBy('usuario')->get();
        $vehiculos = TransferVehiculo::all();
        $tipos = TransferTipoReserva::all();

        return view('admin.reservas.create', compact('hoteles', 'vehiculos', 'tipos', 'fechaPreseleccionada'));
    }

    /**
     * Guardar la reserva del Admin
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email_cliente' => 'required|email|max:100',
            'id_tipo_reserva' => 'required',
            'id_destino' => 'required',
            'id_vehiculo' => 'required',
            'fecha_entrada' => 'required|date',
            'hora_entrada' => 'required',
            'num_viajeros' => 'required|integer|min:1',
            'id_hotel_comision' => 'required|exists:transfer_hotels,id_hotel'
        ]);

        $localizador = 'ADM-' . strtoupper(substr(md5(uniqid()), 0, 6));

        TransferReserva::create([
            'localizador'          => $localizador,
            'id_hotel'             => $request->id_hotel_comision, // Importante para el reporte
            'email_cliente'        => $request->email_cliente,
            'id_tipo_reserva'      => $request->id_tipo_reserva,
            'id_destino'           => $request->id_destino,
            'id_vehiculo'          => $request->id_vehiculo,
            'fecha_reserva'        => now(),
            'fecha_modificacion'   => now(),
            'fecha_entrada'        => $request->fecha_entrada,
            'hora_entrada'         => $request->hora_entrada,
            'num_viajeros'         => $request->num_viajeros,
            'numero_vuelo_entrada' => $request->numero_vuelo_entrada,
            'origen_vuelo_entrada' => $request->origen_vuelo_entrada,
            'status'               => 'confirmada'
        ]);

        // Si veníamos del calendario, volvemos al calendario
        return redirect()->route('admin.calendar')
            ->with('success', 'Reserva creada manualmente.');
    }

    // Función dummy para editar (para que no falle el calendario al hacer clic en una reserva existente)
    public function edit($id) {
        $reserva = TransferReserva::findOrFail($id);
        $hoteles = TransferHotel::activos()->get();
        $vehiculos = TransferVehiculo::all();
        $tipos = TransferTipoReserva::all();
        return view('admin.reservas.edit', compact('reserva', 'hoteles', 'vehiculos', 'tipos'));
    }

    // Función update
     public function update(Request $request, $id) {
        // Lógica update...
        return redirect()->route('admin.calendar');
     }
}
