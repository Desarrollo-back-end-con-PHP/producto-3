<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferPrecioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('transfer_precios')->delete();

        // Vehículo 1 (Sedan)
        DB::table('transfer_precios')->insert([
            ['id_vehiculo' => 1, 'id_hotel' => 1, 'Precio' => 50],
            ['id_vehiculo' => 1, 'id_hotel' => 2, 'Precio' => 55],
            ['id_vehiculo' => 1, 'id_hotel' => 3, 'Precio' => 60],
            ['id_vehiculo' => 1, 'id_hotel' => 4, 'Precio' => 45],
        ]);

        // Vehículo 2 (Minivan)
        DB::table('transfer_precios')->insert([
            ['id_vehiculo' => 2, 'id_hotel' => 1, 'Precio' => 80],
            ['id_vehiculo' => 2, 'id_hotel' => 2, 'Precio' => 85],
            ['id_vehiculo' => 2, 'id_hotel' => 3, 'Precio' => 90],
            ['id_vehiculo' => 2, 'id_hotel' => 4, 'Precio' => 75],
        ]);
    }
}
