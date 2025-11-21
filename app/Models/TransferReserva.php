<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * CONTENIDO HECHO POR IA PARA QUE PUEDAN FUNCIONAR ALGUNOS CONTROLLERS, QUIEN HAGA RESERVAS QUE REVISE EL CODIGO
 * OJO!!
 */
class TransferReserva extends Model
{
    use HasFactory;

    // 1. Configuración básica (Vital para que Laravel encuentre la tabla)
    protected $table = 'transfer_reservas';
    protected $primaryKey = 'id_reserva';

    // 2. Campos que permitimos guardar (Mass Assignment)
    // He incluido todos los que usas en el HotelPanelController@store
    protected $fillable = [
        'localizador',
        'id_hotel',          // Importante para las comisiones
        'id_tipo_reserva',
        'email_cliente',     // Ojo: es string, no ID
        'id_destino',
        'id_vehiculo',
        'fecha_reserva',
        'fecha_modificacion',
        'fecha_entrada',
        'hora_entrada',
        'numero_vuelo_entrada',
        'origen_vuelo_entrada',
        'fecha_vuelo_salida',
        'hora_vuelo_salida',
        'numero_vuelo_salida',
        'hora_recogida',
        'num_viajeros',
        'status'
    ];

    // --- RELACIONES (Imprescindibles para que funcionen los 'with' en tus controladores) ---

    // Relación con el Hotel (quien hizo la reserva)
    public function hotel()
    {
        // belongsTo(Modelo, 'clave_foranea_aqui', 'clave_primaria_alli')
        return $this->belongsTo(TransferHotel::class, 'id_hotel', 'id_hotel');
    }

    // Relación con el Vehículo
    public function vehiculo()
    {
        return $this->belongsTo(TransferVehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }

    // Relación con el Tipo de Reserva
    public function tipo()
    {
        // Nota: Asegúrate de que tu compañero cree el modelo 'TransferTipoReserva' 
        // o la tabla explotará al intentar acceder a $reserva->tipo->descripcion
        return $this->belongsTo(TransferTipoReserva::class, 'id_tipo_reserva', 'id_tipo_reserva');
    }

    // Relación con el Destino (que también es un hotel)
    public function destino()
    {
        return $this->belongsTo(TransferHotel::class, 'id_destino', 'id_hotel');
    }
}
