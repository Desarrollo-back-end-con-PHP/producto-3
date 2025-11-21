<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * MIDDLEWARE para comprobar en routes/web si el usuario es administrador y puede acceder al panel de admin
 * Modo de comprobación correo: admin@islatransfers.com
 */
class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Comprobar si está logueado (por si acaso falla el otro middleware)
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Comprobar si es el email del admin
        if (Auth::user()->email !== 'admin@islatransfers.com') {

            // Si NO es admin, le prohibimos el paso (Error 403 Forbidden)
            // O lo redirigimos a su perfil con un mensaje
            abort(403, 'Acceso denegado. No eres administrador.');
        }

        // 3. Si es admin, dejamos pasar la petición
        return $next($request);
    }
}
