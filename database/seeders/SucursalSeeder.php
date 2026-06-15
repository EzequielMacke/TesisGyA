<?php

namespace Database\Seeders;

use App\Models\Sucursal;
use Illuminate\Database\Seeder;

class SucursalSeeder extends Seeder
{
    public function run(): void
    {
        Sucursal::firstOrCreate(
            ['descripcion' => 'Central'],
            ['estado_id' => 1, 'deposito_id' => 1]
        );
        Sucursal::firstOrCreate(
            ['descripcion' => 'Sucursal'],
            ['estado_id' => 1, 'deposito_id' => 2]
        );
    }
}
