<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\TransferReserva;
use App\Models\TransferZona;
use App\Models\TransferTipoReserva;
use App\Models\TransferVehiculo;
use App\Models\TransferHotel;

class HotelPanelController extends Controller
{
    /**
     * Muestra el Panel de Control del Hotel logueado.
     * Muestra tanto reservas creadas por el hotel como las de usuarios dirigidas a él.
     */
    public function index()
    {
        $hotel = Auth::guard('hotel')->user();
        $comisionPorReserva = $hotel->Comision ?? 10;
        
        // Buscamos reservas donde el hotel sea el creador O sea el destino del servicio
        $reservas = TransferReserva::query()
            ->where(function($query) use ($hotel) {
                $query->where('id_hotel', $hotel->id_hotel)
                      ->orWhere('id_destino', $hotel->id_hotel);
            })
            ->with(['vehiculo', 'tipo', 'destino'])
            ->orderBy('fecha_entrada', 'desc') // Orden operativo por fecha de servicio
            ->paginate(10);

        // Cálculos basados en servicios que llegan o salen del hotel (id_destino)
        $reservasEsteMes = TransferReserva::where('id_destino', $hotel->id_hotel)
            ->where('status', '!=', 'cancelada')
            ->whereMonth('fecha_entrada', now()->month)
            ->whereYear('fecha_entrada', now()->year)
            ->count();

        $comisionMensual = $reservasEsteMes * $comisionPorReserva;

        $totalReservasHist = TransferReserva::where('id_destino', $hotel->id_hotel)
             ->where('status', '!=', 'cancelada')
             ->count();

        $totalComisiones = $totalReservasHist * $comisionPorReserva;

        $tipos = TransferTipoReserva::all();
        $hotelesDestino = TransferHotel::where('status', 'activo')->orderBy('usuario')->get();
        $vehiculos = TransferVehiculo::all();

        return view('hotel.dashboard', compact('hotel', 'reservas', 'comisionMensual', 'totalComisiones', 'tipos', 'hotelesDestino', 'vehiculos'));
    }

    /**
     * Actualizar Contraseña del Hotel
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password:hotel'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'current_password.current_password' => 'La contraseña actual no es correcta.',
            'password.confirmed' => 'Las nuevas contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.'
        ]);

        /** @var \App\Models\TransferHotel $hotel */
        $hotel = Auth::guard('hotel')->user();
        
        $hotel->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('status_password', '¡Contraseña actualizada correctamente!');
    }

    /**
     * Formulario de creación
     */
    public function createReserva()
    {
        $tipos = TransferTipoReserva::all();
        $vehiculos = TransferVehiculo::all();
        $hotelesDestino = TransferHotel::where('status', 'activo')->orderBy('usuario')->get();

        return view('hotel.reservas.create', compact('tipos', 'vehiculos', 'hotelesDestino'));
    }

    /**
     * Guardar reserva nueva
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email_cliente' => 'required|email|max:100', 
            'id_tipo_reserva' => 'required',
            'id_vehiculo' => 'required', 
            'fecha_entrada' => 'required|date|after_or_equal:today',
            'hora_entrada' => 'required',
            'num_viajeros' => 'required|integer|min:1|max:50',
            'numero_vuelo_entrada' => 'nullable|string|max:50',
            'origen_vuelo_entrada' => 'nullable|string|max:50',
        ]);

        $idHotelLogueado = Auth::guard('hotel')->id();
        $localizador = 'HTL-' . strtoupper(substr(md5(uniqid()), 0, 6));

        TransferReserva::create([
            'localizador' => $localizador,
            'id_hotel' => $idHotelLogueado,
            'email_cliente' => $validated['email_cliente'],
            'id_tipo_reserva' => $validated['id_tipo_reserva'],
            'id_destino' => $idHotelLogueado,
            'id_vehiculo' => $validated['id_vehiculo'],
            'fecha_reserva' => now(),
            'fecha_modificacion' => now(), 
            'fecha_entrada' => $validated['fecha_entrada'],
            'hora_entrada' => $validated['hora_entrada'],
            'numero_vuelo_entrada' => $validated['numero_vuelo_entrada'] ?? null,
            'origen_vuelo_entrada' => $validated['origen_vuelo_entrada'] ?? null,
            'num_viajeros' => $validated['num_viajeros'],
            'status' => 'confirmada'
        ]);

        return redirect()->route('hotel.panel')
            ->with('success', 'Reserva creada correctamente.');
    }

    /**
     * Formulario de Edición
     */
    public function edit($id)
    {
        $hotel = Auth::guard('hotel')->user();

        // Validamos propiedad por creador o por ser el destino del servicio
        $reserva = TransferReserva::where('id_reserva', $id)
                    ->where(function($q) use ($hotel) {
                        $q->where('id_hotel', $hotel->id_hotel)
                          ->orWhere('id_destino', $hotel->id_hotel);
                    })
                    ->firstOrFail();

        $tipos = TransferTipoReserva::all();
        $vehiculos = TransferVehiculo::all();
        $hotelesDestino = TransferHotel::where('status', 'activo')->orderBy('usuario')->get();

        return view('hotel.reservas.edit', compact('reserva', 'tipos', 'vehiculos', 'hotelesDestino'));
    }

    /**
     * Actualizar reserva
     */
    public function update(Request $request, $id)
    {
        $hotel = Auth::guard('hotel')->user();
        
        $reserva = TransferReserva::where('id_reserva', $id)
                    ->where(function($q) use ($hotel) {
                        $q->where('id_hotel', $hotel->id_hotel)
                          ->orWhere('id_destino', $hotel->id_hotel);
                    })
                    ->firstOrFail();

        $validated = $request->validate([
            'email_cliente' => 'required|email|max:100', 
            'id_tipo_reserva' => 'required',
            'id_destino' => 'required',
            'id_vehiculo' => 'required', 
            'fecha_entrada' => 'required|date',
            'hora_entrada' => 'required',
            'num_viajeros' => 'required|integer|min:1',
        ]);

        $reserva->update([
            'email_cliente' => $validated['email_cliente'],
            'id_tipo_reserva' => $validated['id_tipo_reserva'],
            'id_destino' => $validated['id_destino'],
            'id_vehiculo' => $validated['id_vehiculo'],
            'fecha_entrada' => $validated['fecha_entrada'],
            'hora_entrada' => $validated['hora_entrada'],
            'num_viajeros' => $validated['num_viajeros'],
            'numero_vuelo_entrada' => $request->numero_vuelo_entrada,
            'origen_vuelo_entrada' => $request->origen_vuelo_entrada,
            'fecha_modificacion' => now()
        ]);

        return redirect()->route('hotel.panel')
            ->with('success', 'Reserva actualizada correctamente.');
    }

    /**
     * Cancelar reserva
     */
    public function cancel($id)
    {
        $hotel = Auth::guard('hotel')->user();

        $reserva = TransferReserva::where('id_reserva', $id)
                    ->where(function($q) use ($hotel) {
                        $q->where('id_hotel', $hotel->id_hotel)
                          ->orWhere('id_destino', $hotel->id_hotel);
                    })
                    ->firstOrFail();

        $reserva->status = 'cancelada';
        $reserva->fecha_modificacion = now();
        $reserva->save();

        return redirect()->route('hotel.panel')
            ->with('success', 'Reserva cancelada correctamente.');
    }

    /**
     * Aceptar reserva pendiente
     */
    public function aceptar($id)
    {
        $hotel = Auth::guard('hotel')->user();
        
        $reserva = TransferReserva::where('id_reserva', $id)
                    ->where(function($q) use ($hotel) {
                        $q->where('id_hotel', $hotel->id_hotel)
                          ->orWhere('id_destino', $hotel->id_hotel);
                    })
                    ->firstOrFail();

        $reserva->update([
            'status' => 'confirmada',
            'fecha_modificacion' => now()
        ]);

        return redirect()->route('hotel.panel')->with('success', 'Reserva aceptada y confirmada.');
    }
}