<?php

namespace App\Http\Controllers;

use App\Helpers\ProfileMessageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ViajeroController extends Controller
{
    public function mostrarPerfil()
    {
        $usuario = Auth::user();
        $mensaje = session('mensaje');

        return view('usuario.perfil', [
            'usuario' => $usuario,
            'mensaje' => $mensaje,
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

        if ($exito) {
            return redirect()->route('usuario.perfil')
                             ->with('mensaje', ProfileMessageHelper::EXITO_DATOS);
        } else {
            return redirect()->route('usuario.perfil')
                             ->with('mensaje', ProfileMessageHelper::ERROR_DATOS);
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

        if ($exito) {
            return redirect()->route('usuario.perfil')
                             ->with('mensaje', ProfileMessageHelper::EXITO_PASS);
        } else {
            return redirect()->route('usuario.perfil')
                             ->with('mensaje', ProfileMessageHelper::ERROR_BD_PASS);
        }
    }

    public function eliminarUsuario(Request $request)
    {
        $usuario = Auth::user();
        if (!$usuario) {
            return redirect()->route('login');
        }

        $exito = $usuario->delete();

        if ($exito) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                             ->with('mensaje', ProfileMessageHelper::EXITO_DELETE);
        } else {
            return redirect()->route('usuario.perfil')
                             ->with('mensaje', ProfileMessageHelper::ERROR_DELETE);
        }
    }
}