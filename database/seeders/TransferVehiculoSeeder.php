<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // para encriptar

class TransferVehiculoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('transfer_vehiculos')->delete();

        DB::table('transfer_vehiculos')->insert([
            [
                'id_vehiculo' => 1,
                'descripcion' => 'Sedan Standard (4 pax)',
                'email_conductor' => 'conductor1@islatransfers.com',
                'password' => Hash::make('password123'), // Encriptado automatico
            ],
            [
                'id_vehiculo' => 2,
                'descripcion' => 'Minivan (8 pax)',
                'email_conductor' => 'conductor2@islatransfers.com',
                'password' => Hash::make('password123'),
            ],
            [
                'id_vehiculo' => 3,
                'descripcion' => 'Vehículo Adaptado (PMR)',
                'email_conductor' => 'conductor3@islatransfers.com',
                'password' => Hash::make('password123'),
            ],
        ]);
    }
}
