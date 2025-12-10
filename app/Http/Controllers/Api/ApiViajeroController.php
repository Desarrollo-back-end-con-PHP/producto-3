<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ProfileMessageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiViajeroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // todas las rutas requieren token válido
    }

    // Mostrar perfil del usuario
    public function mostrarPerfil()
    {
        $usuario = Auth::user();
        return response()->json([
            'success' => true,
            'usuario' => $usuario,
        ]);
    }

    // Actualizar datos del usuario
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

    // Actualizar contraseña del usuario
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
        // Revocamos el token actual primero
        $request->user()->currentAccessToken()->delete();

        // Ahora eliminamos el usuario
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
