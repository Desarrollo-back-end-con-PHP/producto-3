<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferReservaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('transfer_reservas')->delete();

        DB::table('transfer_reservas')->insert([
            [
                'localizador' => 'IT-ABC123',
                'id_hotel' => null, // Reserva directa
                'id_tipo_reserva' => 1, // Aeropuerto -> Hotel
                'email_cliente' => 'ana.garcia@email.com',
                'fecha_reserva' => '2025-11-01 10:00:00',
                'fecha_modificacion' => '2025-11-01 10:00:00',
                'id_destino' => 1,
                'fecha_entrada' => '2025-11-10',
                'hora_entrada' => '14:30:00',
                'numero_vuelo_entrada' => 'IB3902',
                'origen_vuelo_entrada' => 'Madrid (MAD)',
                'hora_vuelo_salida' => null,
                'fecha_vuelo_salida' => null,
                'num_viajeros' => 2,
                'id_vehiculo' => 1,
                'status' => 'pendiente',
                'numero_vuelo_salida' => null,
                'hora_recogida' => null,
            ],
            [
                'localizador' => 'IT-XYZ789',
                'id_hotel' => 3, // Reserva hecha por el Hotel 3
                'id_tipo_reserva' => 3, // Ida y Vuelta
                'email_cliente' => 'carlos.ruiz@email.com',
                'fecha_reserva' => '2025-11-02 15:00:00',
                'fecha_modificacion' => '2025-11-02 15:00:00',
                'id_destino' => 3,
                'fecha_entrada' => '2025-11-12',
                'hora_entrada' => '09:15:00',
                'numero_vuelo_entrada' => 'RYR1001',
                'origen_vuelo_entrada' => 'Londres (STN)',
                'hora_vuelo_salida' => '11:00:00',
                'fecha_vuelo_salida' => '2025-11-19',
                'num_viajeros' => 5,
                'id_vehiculo' => 2,
                'status' => 'confirmada',
                'numero_vuelo_salida' => 'RYR1002',
                'hora_recogida' => '08:00:00',
            ],
        ]);
    }
}
