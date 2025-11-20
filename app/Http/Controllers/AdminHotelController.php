<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransferHotel;
use App\Models\TransferZona;

class AdminHotelController extends Controller
{
    /**
     * Mostrar lista de hoteles
     */
    public function index()
    {
        //obtener los datos
        $hoteles = TransferHotel::query()
            ->with('zona') //con los datos de la zona asociada
            ->orderBy('usuario', 'asc') //ordenamos por nombre
            ->get();

        //crea un array de hoteles con compact
        return view('admin.hoteles.index', compact('hoteles'));
    }
}
