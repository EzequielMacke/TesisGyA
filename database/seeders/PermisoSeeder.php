<?php

namespace Database\Seeders;

use App\Models\Modulo;
use App\Models\Permiso;
use Illuminate\Database\Seeder;

class PermisoSeeder extends Seeder
{
    public function run(): void
    {
        $modulos = Modulo::all();

        foreach ($modulos as $modulo) {
            Permiso::firstOrCreate(
                ['cargo_id' => 1, 'modulo_id' => $modulo->id],
                [
                    'ver'     => 1,
                    'agregar' => 1,
                    'editar'  => 1,
                    'anular'  => 1,
                ]
            );
        }

        Permiso::firstOrCreate(
            ['cargo_id' => 3, 'modulo_id' => 25],
            [
                'ver'     => 1,
                'agregar' => 1,
                'editar'  => 1,
                'anular'  => 1,
            ]
        );
    }
}
