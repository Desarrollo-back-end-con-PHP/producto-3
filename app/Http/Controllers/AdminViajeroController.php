<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransferViajero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminViajeroController extends Controller
{
    // Constructor para asegurar que solo admins entren aquí
    public function __construct()
    {
        $this->middleware(['auth', 'admin']); 
    }

    /**
     * Muestra la lista de todos los viajeros.
     */
    public function index()
    {
        // Paginamos de 10 en 10
        $viajeros = TransferViajero::orderBy('fecha_creacion', 'desc')->paginate(10);
        return view('admin.viajeros.index', compact('viajeros'));
    }

    /**
     * Muestra el formulario para crear un nuevo viajero manualmente.
     */
    public function create()
    {
        return view('admin.viajeros.create');
    }

    /**
     * Guarda el nuevo viajero en la base de datos.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:100',
            'apellido1' => 'required|string|max:100',
            'email'     => 'required|email|max:100|unique:transfer_viajeros,email',
            'password'  => 'required|string|min:8',
        ]);

        TransferViajero::create([
            'nombre'    => $data['nombre'],
            'apellido1' => $data['apellido1'],
            'apellido2' => $request->apellido2, // Opcional
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'status'    => 1, // Asumimos 1 como activo por defecto
        ]);

        return redirect()->route('admin.viajeros.index')
            ->with('success', 'Viajero creado correctamente.');
    }

    /**
     * Muestra el formulario para editar un viajero existente.
     */
    public function edit($id)
    {
        // Buscamos por id_viajero
        $viajero = TransferViajero::where('id_viajero', $id)->firstOrFail();
        return view('admin.viajeros.edit', compact('viajero'));
    }

    /**
     * Actualiza los datos del viajero.
     */
    public function update(Request $request, $id)
    {
        $viajero = TransferViajero::where('id_viajero', $id)->firstOrFail();

        $data = $request->validate([
            'nombre'    => 'required|string|max:100',
            'apellido1' => 'required|string|max:100',
            // Validación unique: ignora el email de ESTE usuario (usando id_viajero)
            'email'     => [
                'required', 
                'email', 
                'max:100', 
                Rule::unique('transfer_viajeros')->ignore($viajero->id_viajero, 'id_viajero')
            ],
            'password'  => 'nullable|string|min:8', // Opcional
        ]);

        $viajero->nombre = $data['nombre'];
        $viajero->apellido1 = $data['apellido1'];
        $viajero->apellido2 = $request->apellido2;
        $viajero->email = $data['email'];

        // Solo actualizamos contraseña si escribieron algo
        if ($request->filled('password')) {
            $viajero->password = Hash::make($data['password']);
        }

        $viajero->save();

        return redirect()->route('admin.viajeros.index')
            ->with('success', 'Viajero actualizado correctamente.');
    }

    /**
     * Elimina el viajero.
     */
    public function destroy($id)
    {
        $viajero = TransferViajero::where('id_viajero', $id)->firstOrFail();
        $viajero->delete();

        return redirect()->route('admin.viajeros.index')
            ->with('success', 'Viajero eliminado correctamente.');
    }
}