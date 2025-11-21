<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotelAuthController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.hotel-login');
    }

    /**
     * Procesa el login 
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'usuario' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginCorrecto = Auth::guard('hotel')->attempt([
            'usuario' => $credentials['usuario'],
            'password' => $credentials['password'],
            'status' => 'activo'
        ]);

        if ($loginCorrecto) {
            $request->session()->regenerate();

            return redirect()->intended(route('hotel.panel'));
        }

        return back()->withErrors([
            'usuario' => 'Credenciales incorrectas o cuenta inactiva.',
        ])->onlyInput('usuario');
    }

    /**
     * Cerran sesión hotel
     */
    public function logout(Request $request)
    {

        Auth::guard('hotel')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('hotel.login');
    }
}
