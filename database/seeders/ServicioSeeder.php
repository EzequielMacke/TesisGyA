<?php

namespace Database\Seeders;

use App\Models\Servicio;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = ['Evaluación Estructural', 'Reparación Estructural', 'Refuerzo Estructural'];

        foreach ($servicios as $descripcion) {
            Servicio::firstOrCreate(
                ['descripcion' => $descripcion],
                ['estado_id' => 1]
            );
        }
    }
}
