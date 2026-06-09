<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EstadoSeeder::class,
            DepositoSeeder::class,
            SucursalSeeder::class,
            CargoSeeder::class,
            MarcaSeeder::class,
            InsumoSeeder::class,
        ]);
    }
}
