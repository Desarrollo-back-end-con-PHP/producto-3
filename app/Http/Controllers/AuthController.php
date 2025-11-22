<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AuthController extends Controller
{
    /**
     * Mostar formulario de login
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Procesa el login
     */
    public function authenticate(Request $request)
    {
        //validar que envian el email y la contraseña
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        //comprobamos credenciales y que el usuario tenga estatus activo (no eliminado)
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'status' => 'activo'])) {

            //regeneramos la sesión
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->email === 'admin@islatransfers.com') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/usuario/perfil'); //COMPROBAR QUE ESTA ES LA DIRECCIÓN DEL PERFILE DE USUARIO!!
        }

        return back()->withErrors([
            'email' => 'Las credenciales no cinciden o la cuenta no está activa.',
        ]);
    }

    /**
     * Formulario de registro
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Procesar datos formulario de registro
     */
    public function store(Request $request)
    {

        //validar los campos
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:transfer_viajeros',
            'password' => 'required|string|min:8|confirmed', // confirmed busca un campo 'password_confirmation'
        ]);

        $user = \App\Models\TransferViajero::create([
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'status' => 'activo',
            // Campos obligatorios vacíos 
            'apellido1' => '',
            'apellido2' => '',
            'direccion' => '',
            'codigoPostal' => '',
            'ciudad' => '',
            'pais' => ''
        ]);

        Auth::login($user);

        return redirect('/usuario/perfil'); //REVISAR DIRECCIÓN!!
    }

    /**
     * Cerrar sesión usuario
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); //página de inicio

    }
}
