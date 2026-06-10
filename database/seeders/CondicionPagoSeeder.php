<?php

namespace Database\Seeders;

use App\Models\CondicionPago;
use Illuminate\Database\Seeder;

class CondicionPagoSeeder extends Seeder
{
    public function run(): void
    {
        $condiciones = ['Contado', 'Credito'];

        foreach ($condiciones as $descripcion) {
            CondicionPago::firstOrCreate(['descripcion' => $descripcion], ['estado_id' => 1]);
        }
    }
}
