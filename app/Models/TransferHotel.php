<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// se hace authenticatable para que los hoteles puedan usar el panel corporativo al iniciar sesión
class TransferHotel extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'transfer_hotels';
    protected $primaryKey = 'id_hotel';

    // Los campos que permitimos escribir
    protected $fillable = [
        'id_zona',
        'nombre',
        'usuario',
        'Comision',
        'password',
        'status'
    ];

    protected $hidden = ['password'];
    protected function casts(): array
    {
        return ['password' => 'hashed',];
    }


    // Relación con Zona (Para poder hacer $hotel->zona->descripcion)
    public function zona()
    {
        return $this->belongsTo(TransferZona::class, 'id_zona', 'id_zona');
    }

    // Filtro para buscar solamente hoteles activos no eliminados
    public function scopeActivos($query)
    {
        return $query->where('status', 'activo');
    }
}
