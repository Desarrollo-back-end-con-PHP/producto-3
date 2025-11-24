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

    /**
     * Guardar un nuevo hotel, el antiguo crearHotelPost
     */
    public function store(Request $request)
    {
        //comprobar los datos
        $validated = $request->validate([
            'usuario' => 'required|string|max:100',
            'password' => 'required|string|min:6',
            'comision' => 'nullable|integer|min:0|max:100',
            'id_zona' => 'required|exists:transfer_zonas,id_zona',
        ]);

        //encriptarContraseña
        $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);

        //marcar como activo
        $validated['status'] = 'activo';

        if (isset($validated['comision'])) {
            $validated['Comision'] = $validated['comision'];
            unset($validated['comision']); // Borramos la minúscula para limpiar
        }

        //Guardar, hace un INSERT
        \App\Models\TransferHotel::create($validated);

        return redirect()->route('admin.hoteles.index')
            ->with('success', 'Hotel creado correctamente');
    }

    /**
     * Modificar un hotel
     */

    public function update(Request $request, $id)
    {

        $hotel = \App\Models\TransferHotel::findOrFail($id);

        //comprobar datos
        $validated = $request->validate([
            'usuario' => 'required|string|max:100',
            'comision' => 'nullable|integer|min:0|max:100',
            'password' => 'nullable|string|min:6',
            'id_zona' => 'required|exists:transfer_zonas,id_zona',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if (isset($validated['comision'])) {
            $validated['Comision'] = $validated['comision']; // Mapear a mayúscula
            unset($validated['comision']); // Borrar minúscula
        }

        $hotel->update($validated);

        return redirect()->route('admin.hoteles.index')->with('success', 'Hotel actualizado');
    }

    /**
     * "Eliminar" un hotel, se marca inactivo
     */
    public function destroy($id)
    {
        $hotel = \App\Models\TransferHotel::findOrFail($id);

        $hotel->update(['status' => 'inactivo']);

        return redirect()->route('admin.hoteles.index')
            ->with('success', 'Hotel desactivado correctamente');
    }

    /**
     * Mostrar formulario para crear un nuevo hotel
     */
    public function create()
    {
        $zonas = TransferZona::all();
        return view('admin.hoteles.create', compact('zonas'));
    }

    /**
     * Muestra formulario para editar un hotel existente
     */
    public function edit($id)
    {

        $hotel = TransferHotel::findOrFail($id);

        $zonas = TransferZona::all();

        return view('admin.hoteles.edit', compact('hotel', 'zonas'));
    }

    /**
     * Fer la ficha del hotel
     */
    public function show($id)
    {

        $hotel = TransferHotel::with('zona')->findOrFail($id);
        return view('admin.hoteles.show', compact('hotel'));
    }
}
