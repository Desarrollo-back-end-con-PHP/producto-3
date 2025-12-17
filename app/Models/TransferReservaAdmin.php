<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferReservaAdmin extends Model
{
    protected $table = 'reserva_admin';

    protected $fillable = [
        'id_reserva',
        'id_admin',
        'fecha_creacion',
    ];

    public $timestamps = true; 
}
