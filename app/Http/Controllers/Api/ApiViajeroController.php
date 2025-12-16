<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ProfileMessageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiViajeroController extends Controller
{
    // IMPORTANTE: Constructor comentado para que no bloquee el acceso Web
    /*
    public function __construct()
    {
        $this->middleware('auth:sanctum'); 
    }
    */

    /**
     * Muestra la vista del perfil WEB
     */
    public function mostrarPerfil()
    {
        $usuario = Auth::user();
        // Carga la vista que está en resources/views/users/perfil.blade.php
        return view('users.perfil', compact('usuario'));
    }

    /**
     * Cambia la contraseña desde el formulario WEB
     */
    public function actualizarContrasenaWeb(Request $request)
    {
        $request->validate([
            'nueva_contrasena' => 'required|string|min:8|confirmed',
        ]);

        $usuario = Auth::user();
        $usuario->password = Hash::make($request->nueva_contrasena);
        $usuario->save();

        // Redirige atrás con mensaje de éxito
        return back()->with('success_pass', 'Contraseña actualizada correctamente.');
    }

    // -------------------------------------------------------------------------
    // MÉTODOS API (Mantienen el retorno JSON por si usas la App móvil o Postman)
    // -------------------------------------------------------------------------

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
                'email'        => 'required|email|unique:transfer_viajeros,email,' . $usuario->id_viajero . ',id_viajero',
            ]);

            $exito = $usuario->update($data);

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