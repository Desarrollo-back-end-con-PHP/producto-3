<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferZona extends Model
{
    use HasFactory;

    protected $table = 'transfer_zonas';
    protected $primaryKey = 'id_zona';

    protected $fillable = ['descripcion'];

    /**
     * Ver los hoteles de una zona.
     */
    public function hoteles()
    {
        return $this->hasMany(TransferHotel::class, 'id_zona', 'id_zona');
    }
}
