<?php

namespace App\Helpers;

class ProfileMessageHelper
{
    const EXITO_DATOS = 'exito_datos';
    const EXITO_PASS = 'exito_pass';
    const EXITO_RESERVA = 'exito_reserva';
    const EXITO_CANCEL_RESERVA = 'exito_cancel_reserva'; // Nueva constante para reservas
    const EXITO_DELETE = 'exito_delete';
    
    const ERROR_DATOS = 'error_datos';
    const ERROR_BD_PASS = 'error_bd_pass';
    const ERROR_PASS_MISMATCH = 'error_pass_mismatch';
    const ERROR_DESCONOCIDO = 'error_desconocido';
    const ERROR_PASS_SHORT = 'error_pass_short';
    const ERROR_EMAIL = 'error_email';
    const ERROR_DELETE = 'error_delete';
    const ERROR_CAMPOS_VACIOS = 'error_campos_vacios';

    public static function getText(?string $mensaje): string
    {
        return match ($mensaje) {
            self::EXITO_DATOS => '¡Datos personales actualizados con éxito!',
            self::EXITO_PASS => '¡Contraseña actualizada con éxito!',
            self::EXITO_DELETE => 'Tu cuenta ha sido eliminada correctamente.',
            self::EXITO_RESERVA => 'Tu reserva se ha procesado correctamente.',
            self::EXITO_CANCEL_RESERVA => 'Tu reserva ha sido cancelada correctamente.', // Texto solicitado
            self::ERROR_DELETE => 'Error al eliminar la cuenta. Inténtalo de nuevo.',
            self::ERROR_DATOS => 'Error al actualizar los datos personales. Inténtalo de nuevo.',
            self::ERROR_BD_PASS => 'Error al guardar la nueva contraseña en la base de datos.',
            self::ERROR_PASS_MISMATCH => 'Las contraseñas no coinciden o están vacías. Inténtalo de nuevo.',
            self::ERROR_PASS_SHORT => 'Tu contraseña es muy corta. Por favor, usa al menos 8 caracteres.',
            self::ERROR_EMAIL => 'Por favor, introduce un correo electrónico válido.',
            self::ERROR_CAMPOS_VACIOS => 'Todos los campos son obligatorios. Asegúrate de completar todos antes de guardar.',
            'PROFILE_REQUIRED' => 'Tu perfil está incompleto. Por favor, rellena tus datos para continuar con la reserva.',
            default => $mensaje ?? '', // Si no es una constante, muestra el texto tal cual
        };
    }

    public static function getClaseAlerta(?string $mensaje): string
    {
        return match ($mensaje) {
            self::EXITO_DATOS,
            self::EXITO_PASS,
            self::EXITO_DELETE,
            self::EXITO_CANCEL_RESERVA, // Clase success para la cancelación
            self::EXITO_RESERVA => 'alert-success',

            'PROFILE_REQUIRED' => 'alert-warning',

            self::ERROR_DATOS,
            self::ERROR_BD_PASS,
            self::ERROR_CAMPOS_VACIOS,
            self::ERROR_EMAIL,
            self::ERROR_PASS_SHORT,
            self::ERROR_DELETE,
            self::ERROR_PASS_MISMATCH => 'alert-danger',

            default => 'alert-info',
        };
    }
}