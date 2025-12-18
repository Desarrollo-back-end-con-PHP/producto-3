<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TransferViajeroSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('transfer_viajeros')->delete();

        DB::table('transfer_viajeros')->insert([
            [
                'nombre' => 'Ana',
                'apellido1' => 'García',
                'apellido2' => 'Pérez',
                'direccion' => 'Avenida Principal 45',
                'codigoPostal' => '07005',
                'ciudad' => 'Palma',
                'pais' => 'España',
                'email' => 'ana.garcia@email.com',
                'password' => Hash::make('password123'),
                'status' => 'activo'
            ],
            [
                'nombre' => 'Carlos',
                'apellido1' => 'Ruiz',
                'apellido2' => 'Martínez',
                'direccion' => 'Paseo Marítimo 10',
                'codigoPostal' => '07600',
                'ciudad' => 'El Arenal',
                'pais' => 'España',
                'email' => 'carlos.ruiz@email.com',
                'password' => Hash::make('password123'),
                'status' => 'activo'
            ],
            [
                'nombre' => 'Laura',
                'apellido1' => 'Schmidt',
                'apellido2' => 'Müller',
                'direccion' => 'Hauptstrasse 15',
                'codigoPostal' => '10115',
                'ciudad' => 'Berlín',
                'pais' => 'Alemania',
                'email' => 'laura.schmidt@email.de',
                'password' => Hash::make('password123'),
                'status' => 'activo'
            ]
        ]);
    }
}
