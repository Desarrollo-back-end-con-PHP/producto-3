<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferHotel extends Model
{
    use HasFactory;

    protected $table = 'transfer_hotels';
    protected $primaryKey = 'id_hotel';

    // Los campos que permitimos escribir
    protected $fillable = [
        'id_zona',
        'Comision',
        'usuario',
        'password',
        'status'
    ];

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
