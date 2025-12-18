<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferTipoReservaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('transfer_tipo_reservas')->delete();

        DB::table('transfer_tipo_reservas')->insert([
            ['id_tipo_reserva' => 1, 'descripcion' => 'Aeropuerto a Hotel (Llegada)'],
            ['id_tipo_reserva' => 2, 'descripcion' => 'Hotel a Aeropuerto (Salida)'],
            ['id_tipo_reserva' => 3, 'descripcion' => 'Ida y Vuelta (Llegada y Salida)'],
        ]);
    }
}
