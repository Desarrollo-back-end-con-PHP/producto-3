<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferZonaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('transfer_zonas')->delete();

        DB::table('transfer_zonas')->insert([
            ['id_zona' => 1, 'descripcion' => 'Palma (Aeropuerto)'],
            ['id_zona' => 2, 'descripcion' => 'Zona Norte (Alcudia, Pollensa)'],
            ['id_zona' => 3, 'descripcion' => 'Zona Este (Cala d\'Or, Cala Millor)'],
            ['id_zona' => 4, 'descripcion' => 'Zona Oeste (Andratx, Paguera)'],
        ]);
    }
}
