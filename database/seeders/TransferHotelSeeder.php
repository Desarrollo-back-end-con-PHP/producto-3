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
                'nombre' => 'Hotel Iberostar Alcudia', 
                'usuario' => 'hotel_iberostar',
                'Comision' => 10,
                'password' => Hash::make('password123'),
                'status' => 'activo'
            ],
            [
                'id_hotel' => 2,
                'id_zona' => 2, // Zona Norte
                'nombre' => 'Hotel Meliá Pollensa',
                'usuario' => 'hotel_melia',
                'Comision' => 12,
                'password' => Hash::make('password123'),
                'status' => 'activo'
            ],
            [
                'id_hotel' => 3,
                'id_zona' => 3, // Zona Este
                'nombre' => 'Hotel Riu Cala d\'Or',
                'usuario' => 'hotel_riu',
                'Comision' => 10,
                'password' => Hash::make('password123'),
                'status' => 'activo'
            ],
            [
                'id_hotel' => 4,
                'id_zona' => 4, // Zona Oeste
                'nombre' => 'Hotel Hesperia Andratx',
                'usuario' => 'hotel_hesperia',
                'Comision' => 15,
                'password' => Hash::make('password123'),
                'status' => 'activo'
            ],
        ]);
    }
}
