<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferVehiculo extends Model
{
    protected $table = 'transfer_vehiculos';
    protected $primaryKey = 'id_vehiculo';

    protected $fillable = [
        'descripcion',
        'email_conductor',
        'password'
    ];

    public $timestamps = false;

    protected $hidden = ['password'];
}
