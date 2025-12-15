<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ProfileMessageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiViajeroController extends Controller
{
    // 1. ELIMINAMOS O COMENTAMOS EL CONSTRUCTOR
    // Dejaremos que las rutas (web.php y api.php) se encarguen de la seguridad.
    /*
    public function __construct()
    {
        $this->middleware('auth:sanctum'); 
    }
    */

    /**
     * Muestra la VISTA del perfil (HTML)
     */
    public function mostrarPerfil()
    {
        $usuario = Auth::user();

        // 2. CAMBIO CLAVE: Devolvemos la vista (el archivo blade) en lugar de JSON
        return view('users.perfil', compact('usuario'));
    }

    /**
     * Actualiza los datos (Devuelve JSON porque el formulario lo pide por AJAX o API)
     */
    public function actualizarDatos(Request $request)
    {
        $usuario = Auth::user();

        try {
            $data = $request->validate([
                'nombre'       => 'required|string|max:255',
                'apellido1'    => 'required|string|max:255',
                'apellido2'    => 'nullable|string|max:255',
                'direccion'    => 'nullable|string|max:255',
                'codigoPostal' => 'nullable|string|max:20',
                'ciudad'       => 'nullable|string|max:255',
                'pais'         => 'nullable|string|max:255',
                // Aseguramos que el email sea único, ignorando el id del usuario actual
                'email'        => 'required|email|unique:transfer_viajeros,email,' . $usuario->id_viajero . ',id_viajero',
            ]);

            $exito = $usuario->update($data);

            // Redirigimos de vuelta con un mensaje de éxito (para web)
            // O devolvemos JSON si lo usas desde la App móvil. 
            // Para tu caso Web actual, lo ideal sería un redirect, pero mantengo JSON por si usas JS.
            return response()->json([
                'success' => $exito,
                'mensaje' => $exito ? ProfileMessageHelper::EXITO_DATOS : ProfileMessageHelper::ERROR_DATOS,
                'usuario' => $usuario
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function actualizarContrasena(Request $request)
    {
        $usuario = Auth::user();

        $data = $request->validate([
            'nueva_contrasena' => 'required|string|min:8|confirmed',
        ]);

        $usuario->password = Hash::make($data['nueva_contrasena']);
        $exito = $usuario->save();

        return response()->json([
            'success' => $exito,
            'mensaje' => $exito ? ProfileMessageHelper::EXITO_PASS : ProfileMessageHelper::ERROR_BD_PASS,
        ]);
    }

    public function eliminarUsuario(Request $request)
    {
        $usuario = Auth::user();

        try {
            // Eliminamos tokens si existen
            if ($request->user()->currentAccessToken()) {
                $request->user()->currentAccessToken()->delete();
            }

            $exito = $usuario->delete();
            
            return response()->json([
                'success' => $exito,
                'mensaje' => $exito ? ProfileMessageHelper::EXITO_DELETE : ProfileMessageHelper::ERROR_DELETE,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}