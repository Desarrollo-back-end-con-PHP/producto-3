<?php

namespace App\Http\Controllers;

use App\Helpers\ProfileMessageHelper;
use App\Models\TransferReserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ViajeroController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function mostrarPerfil()
    {
        $usuario = Auth::user();
        $mensaje = session('mensaje');

        // 1. Caso ADMINISTRADOR (Cualquier correo que termine en @islatransfers.com)
        if (str_ends_with($usuario->email, '@islatransfers.com')) {
            return view('admin.perfil', [
                'usuario' => $usuario,
                'mensaje' => $mensaje
            ]);
        }

        // 2. Caso USUARIO NORMAL
        $reservas = TransferReserva::where('email_cliente', $usuario->email)
            ->orderBy('fecha_reserva', 'desc')
            ->get();

        return view('users.perfil', [
            'usuario' => $usuario,
            'mensaje' => $mensaje,
            'reservas' => $reservas,
        ]);
    }

    public function actualizarDatos(Request $request)
    {
        $usuario = Auth::user();

        $data = $request->validate([
            'nombre'       => 'required|string|max:100',
            'apellido1'    => 'required|string|max:100',
            'apellido2'    => 'nullable|string|max:100',
            'direccion'    => 'nullable|string|max:100',
            'codigoPostal' => 'nullable|string|max:100',
            'ciudad'       => 'nullable|string|max:100',
            'pais'         => 'nullable|string|max:100',
            'email'        => 'required|email|max:100|unique:transfer_viajeros,email,' . $usuario->id_viajero . ',id_viajero',
        ]);

        $data['apellido2']    = $data['apellido2'] ?? '';
        $data['direccion']    = $data['direccion'] ?? '';
        $data['codigoPostal'] = $data['codigoPostal'] ?? '';
        $data['ciudad']       = $data['ciudad'] ?? '';
        $data['pais']         = $data['pais'] ?? '';

        try {
            $usuario->update($data);

            return redirect()->route('usuario.perfil')
                ->with('mensaje', ProfileMessageHelper::EXITO_DATOS);
        } catch (\Exception $e) {
            return redirect()->route('usuario.perfil')
                ->with('mensaje', 'ERROR SQL: ' . $e->getMessage());
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

        return redirect()->route('usuario.perfil')
            ->with(
                'mensaje',
                $exito
                    ? ProfileMessageHelper::EXITO_PASS
                    : ProfileMessageHelper::ERROR_BD_PASS
            );
    }

    public function eliminarUsuario(Request $request)
    {
        $usuario = Auth::user();
        try {
            Auth::logout();
            $usuario->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('mensaje', ProfileMessageHelper::EXITO_DELETE);
        } catch (\Exception $e) {
            return redirect()->route('usuario.perfil')
                ->with('mensaje', ProfileMessageHelper::ERROR_DELETE);
        }
    }
}
