<?php

namespace Database\Seeders;

use App\Models\MetodoPago;
use Illuminate\Database\Seeder;

class MetodoPagoSeeder extends Seeder
{
    public function run(): void
    {
        $metodos = ['Efectivo', 'Tarjeta', 'Cheque'];

        foreach ($metodos as $descripcion) {
            MetodoPago::firstOrCreate(['descripcion' => $descripcion], ['estado_id' => 1]);
        }
    }
}
