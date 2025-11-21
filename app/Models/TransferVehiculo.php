<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * REVISAR
 * CONTENIDO MINIMO PARA QUE FUNCIONE EL MODELO DE TRANSFER RESERVA
 */
class TransferVehiculo extends Model
{
    protected $table = 'transfer_vehiculos'; // Ojo si es singular o plural en tu BBDD
    protected $primaryKey = 'id_vehiculo';
}
