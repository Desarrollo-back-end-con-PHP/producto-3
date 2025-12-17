<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\TransferViajero;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de login.
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Procesa la autenticación y redirige según el dominio.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Si el correo termina en @islatransfers.com, va al panel de administración
            // Esto permite múltiples administradores corporativos
            if (str_ends_with(Auth::user()->email, '@islatransfers.com')) {
                return redirect()->route('admin.dashboard');
            }

            // Para el resto de usuarios, va a su perfil personal
            return redirect()->intended(route('usuario.perfil'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Procesa el registro de un nuevo usuario con bloqueo de dominio corporativo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido1' => 'required|string|max:100',
            // Valida que el email sea único en la tabla transfer_viajeros
            'email' => 'required|email|max:100|unique:transfer_viajeros,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // SEGURIDAD: Evitar que alguien se registre manualmente con el dominio de admin
        if (str_ends_with($request->email, '@islatransfers.com')) {
            return back()->withErrors([
                'email' => 'No está permitido el registro manual con correos corporativos.'
            ])->withInput();
        }

        // Crear el nuevo registro usando el modelo TransferViajero
        $user = TransferViajero::create([
            'nombre' => $request->nombre,
            'apellido1' => $request->apellido1,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'activo',
            'fecha_creacion' => now(),
        ]);

        // Iniciar sesión automáticamente tras el registro
        Auth::login($user);

        return redirect()->route('usuario.perfil');
    }

    /**
     * Cierra la sesión y limpia la sesión del navegador.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}