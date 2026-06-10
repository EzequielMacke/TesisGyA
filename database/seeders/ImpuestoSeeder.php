<?php

namespace Database\Seeders;

use App\Models\Impuesto;
use Illuminate\Database\Seeder;

class ImpuestoSeeder extends Seeder
{
    public function run(): void
    {
        $impuestos = [
            ['descripcion' => 'Exenta', 'calculo' => 0],
            ['descripcion' => '5%',     'calculo' => 21],
            ['descripcion' => '10%',    'calculo' => 11],
        ];

        foreach ($impuestos as $impuesto) {
            Impuesto::firstOrCreate(
                ['descripcion' => $impuesto['descripcion']],
                [
                    'calculo'   => $impuesto['calculo'],
                    'estado_id' => 1,
                ]
            );
        }
    }
}
