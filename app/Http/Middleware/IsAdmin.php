<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Comprobar si está logueado
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Comprobar si el email termina en @islatransfers.com
        // Usamos str_ends_with para permitir cualquier correo de tu dominio
        if (!str_ends_with(Auth::user()->email, '@islatransfers.com')) {
            
            // Si NO es del dominio oficial, redirigimos a su perfil con aviso
            return redirect()->route('usuario.perfil')
                             ->with('error', 'Acceso denegado. Solo personal de Isla Transfers.');
        }

        // 3. Si es admin (tiene el dominio correcto), dejamos pasar
        return $next($request);
    }
}