<?php

namespace Database\Seeders;

use App\Models\Cargo;
use Illuminate\Database\Seeder;

class CargoSeeder extends Seeder
{
    public function run(): void
    {
        $cargos = ['Administrador', 'Usuario', 'Proveedor', 'Funcionario'];

        foreach ($cargos as $descripcion) {
            Cargo::firstOrCreate(
                ['descripcion' => $descripcion],
                ['estado_id' => 1]
            );
        }
    }
}
