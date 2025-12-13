<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class TransferReserva extends Model
{
    use HasFactory;

    protected $table = "transfer_reservas";
    protected $primaryKey = "id_reserva";

    protected $fillable = [
        'localizador',
        'id_tipo_reserva',
        'email_cliente',
        'fecha_reserva',
        'fecha_modificacion',
        'id_destino',
        'fecha_entrada',
        'hora_entrada',
        'numero_vuelo_entrada',
        'origen_vuelo_entrada',
        'fecha_vuelo_salida',
        'hora_vuelo_salida',
        'numero_vuelo_salida',
        'hora_recogida',
        'num_viajeros',
        'id_vehiculo',
        'status'
    ];

    protected $casts = [
        'fecha_reserva' => 'datetime',
        'fecha_modificacion' => 'datetime',
        'fecha_entrada' => 'date',
        'fecha_vuelo_salida' => 'date',
    ];


    public static function getReservasPorEmail($email)
    {
        return self::where('email_cliente', $email)
            ->where('status', '!=', 'cancelada')
            ->get();
    }

    public static function getTodasReservas()
    {
        return self::where('status', '!=', 'cancelada')
            ->orderBy('fecha_reserva', 'desc')
            ->get();
    }

    public static function getReservasPorRango($inicio, $fin)
    {
        return self::where('status', '!=', 'cancelada')
            ->where(function ($query) use ($inicio, $fin) {
                $query->whereBetween('fecha_entrada', [$inicio, $fin])
                    ->orWhereBetween('fecha_vuelo_salida', [$inicio, $fin]);
            })
            ->with('hotel')
            ->orderBy('fecha_entrada')
            ->orderBy('hora_entrada')
            ->orderBy('fecha_vuelo_salida')
            ->get();
    }

    public static function crearReserva(
    int $id_tipo_reserva,
    int $id_destino,
    ?string $fecha_entrada = null,
    ?string $hora_entrada = null,
    int $num_viajeros = 1,
    ?int $id_vehiculo = null,
    ?string $numero_vuelo_entrada = null,
    ?string $origen_vuelo_entrada = null,
    ?string $fecha_vuelo_salida = null,
    ?string $hora_vuelo_salida = null,
    string $email_cliente,
    ?string $numero_vuelo_salida = null,
    ?string $hora_recogida = null
) {
    try {
        if (empty($email_cliente)) {
            return false; 
        }

        $localizador = uniqid("LOC-");
        $fecha_actual = now();

    
        if ($id_tipo_reserva == 1) { 
            $fecha_vuelo_salida = $hora_vuelo_salida = $numero_vuelo_salida = $hora_recogida = null;
        } elseif ($id_tipo_reserva == 2) { 
            $fecha_entrada = $hora_entrada = $numero_vuelo_entrada = $origen_vuelo_entrada = null;
        }

        $reserva = self::create([
            'localizador' => $localizador,
            'id_tipo_reserva' => $id_tipo_reserva,
            'email_cliente' => $email_cliente,
            'fecha_reserva' => $fecha_actual,
            'fecha_modificacion' => $fecha_actual,
            'id_destino' => $id_destino,
            'fecha_entrada' => $fecha_entrada,
            'hora_entrada' => $hora_entrada,
            'numero_vuelo_entrada' => $numero_vuelo_entrada,
            'origen_vuelo_entrada' => $origen_vuelo_entrada,
            'fecha_vuelo_salida' => $fecha_vuelo_salida,
            'hora_vuelo_salida' => $hora_vuelo_salida,
            'numero_vuelo_salida' => $numero_vuelo_salida,
            'hora_recogida' => $hora_recogida,
            'num_viajeros' => $num_viajeros ?: 1,
            'id_vehiculo' => $id_vehiculo ?: 1
        ]);

        return $reserva ? $localizador : false;

    } catch (\Exception $e) {
        return false;
    }
}


    public static function actualizarReserva($id_reserva, $datos)
    {
        $reserva = self::find($id_reserva);
        if (!$reserva) return false;

        $reserva->fill($datos);
        $reserva->fecha_modificacion = now();

        if ($reserva->id_tipo_reserva == 1) {
            $reserva->fecha_vuelo_salida = $reserva->hora_vuelo_salida = $reserva->numero_vuelo_salida = $reserva->hora_recogida = null;
        } elseif ($reserva->id_tipo_reserva == 2) {
            $reserva->fecha_entrada = $reserva->hora_entrada = $reserva->numero_vuelo_entrada = $reserva->origen_vuelo_entrada = null;
        }

        return $reserva->save();
    }

    public static function cancelarReserva($id_reserva)
    {
        $reserva = self::find($id_reserva);
        if (!$reserva) return false;

        $reserva->status = 'cancelada';
        $reserva->fecha_modificacion = now();
        return $reserva->save();
    }



    public static function getReservaPorId($id_reserva)
    {
        return self::find($id_reserva);
    }

    public static function guardarReservaAdmin($id_reserva, $codigo_admin)
    {
        if (empty($id_reserva) || empty($codigo_admin)) return false;

        return DB::table('reserva_admin')->insert([
            'id_reserva' => $id_reserva,
            'id_admin' => $codigo_admin
        ]);
    }

    public static function getReservasAdminIds()
    {
        return DB::table('reserva_admin')->pluck('id_reserva')->toArray();
    }

    public function hotel()
    {
        return $this->belongsTo(TransferHotel::class, 'id_hotel', 'id_hotel');
    }


    public function vehiculo()
    {
        return $this->belongsTo(TransferVehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }


    public function tipo()
    {

        return $this->belongsTo(TransferTipoReserva::class, 'id_tipo_reserva', 'id_tipo_reserva');
    }

    
    public function destino()
    {
        return $this->belongsTo(TransferHotel::class, 'id_destino', 'id_hotel');
    }
}
