<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class TransferViajero extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'transfer_viajeros';
    protected $primaryKey = 'id_viajero';

    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = null;

    protected $fillable = [
        'nombre',
        'apellido1',
        'apellido2',
        'direccion',
        'codigoPostal',
        'ciudad',
        'pais',
        'email',
        'password',
        'status',
        'fecha_creacion'
    ];

    protected $attributes = [
        'apellido2'    => '',
        'direccion'    => '',
        'codigoPostal' => '',
        'ciudad'       => '',
        'pais'         => '',
        'status'       => 'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password'       => 'hashed',
        'fecha_creacion' => 'datetime',
    ];

    public function esAdmin()
    {
        return str_ends_with($this->email, '@islatransfers.com');
    }
}
