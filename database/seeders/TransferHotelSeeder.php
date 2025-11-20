<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TransferHotelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('transfer_hotels')->delete();

        DB::table('transfer_hotels')->insert([
            [
                'id_hotel' => 1,
                'id_zona' => 2, // Zona Norte
                'Comision' => 10,
                'usuario' => 'Hotel Iberostar Alcudia',
                'password' => Hash::make('password123'),
                'status' => 'activo'
            ],
            [
                'id_hotel' => 2,
                'id_zona' => 2, // Zona Norte
                'Comision' => 12,
                'usuario' => 'Hotel Meliá Pollensa',
                'password' => Hash::make('password123'),
                'status' => 'activo'
            ],
            [
                'id_hotel' => 3,
                'id_zona' => 3, // Zona Este
                'Comision' => 10,
                'usuario' => 'Hotel Riu Cala d\'Or',
                'password' => Hash::make('password123'),
                'status' => 'activo'
            ],
            [
                'id_hotel' => 4,
                'id_zona' => 4, // Zona Oeste
                'Comision' => 15,
                'usuario' => 'Hotel Hesperia Andratx',
                'password' => Hash::make('password123'),
                'status' => 'activo'
            ],
        ]);
    }
}
