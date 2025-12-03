<?php

namespace App\Http\Controllers;

use App\Helpers\ProfileMessageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiViajeroController extends Controller
{
    public function mostrarPerfil()
    {
        $usuario = Auth::user();
        
        return response()->json([
        'usuario' => $usuario,
        'mensaje' => session('mensaje') ?? null,

        ]);
    }

    public function actualizarDatos(Request $request)
    {
        $usuario = Auth::user();

        $data = $request->validate([
            'nombre'       => 'required|string|max:255',
            'apellido1'    => 'required|string|max:255',
            'apellido2'    => 'nullable|string|max:255',
            'direccion'    => 'nullable|string|max:255',
            'codigoPostal' => 'nullable|string|max:20',
            'ciudad'       => 'nullable|string|max:255',
            'pais'         => 'nullable|string|max:255',
            'email'        => 'required|email|unique:transfer_viajeros,email,' . $usuario->id_viajero,
        ]);

        $exito = $usuario->update($data);

        return response()->json([
        'success' => $exito,
        'mensaje' => $exito ? ProfileMessageHelper::EXITO_DATOS : ProfileMessageHelper::ERROR_DATOS,
    ]);
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
        'mensaje' => $exito ? ProfileMessageHelper::EXITO_PASS : ProfileMessageHelper::ERROR_BD_PASS,]);
    }

    public function eliminarUsuario(Request $request)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Usuario no autenticado'
            ], 401);
        }

        $exito = $usuario->delete();

        if ($exito) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json([
            'success' => $exito,
            'mensaje' => $exito 
                ? ProfileMessageHelper::EXITO_DELETE 
                : ProfileMessageHelper::ERROR_DELETE,
        ]);
    }
}
