<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llamamos a los otros seeders en orden lógico, el mismo de creación de las migrations
        $this->call([
            TransferZonaSeeder::class,        // 1. Independiente
            TransferTipoReservaSeeder::class, // 2. Independiente
            TransferVehiculoSeeder::class,    // 3. Independiente
            TransferViajeroSeeder::class,     // 4. Independiente
            TransferHotelSeeder::class,       // 5. Depende de Zonas
            TransferPrecioSeeder::class,      // 6. Depende de Vehículos y Hoteles
            TransferReservaSeeder::class,     // 7. Depende de TODO
        ]);
    }
}
