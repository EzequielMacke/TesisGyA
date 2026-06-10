<?php

namespace Database\Seeders;

use App\Models\TipoVehiculo;
use Illuminate\Database\Seeder;

class TipoVehiculoSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = ['Camión', 'Camioneta', 'Furgón', 'Moto', 'Automóvil'];

        foreach ($tipos as $descripcion) {
            TipoVehiculo::firstOrCreate(['descripcion' => $descripcion], ['estado_id' => 1]);
        }
    }
}
