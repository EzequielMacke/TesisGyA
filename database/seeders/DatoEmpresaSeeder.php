<?php

namespace Database\Seeders;

use App\Models\DatoEmpresa;
use Illuminate\Database\Seeder;

class DatoEmpresaSeeder extends Seeder
{
    public function run(): void
    {
        DatoEmpresa::firstOrCreate(
            ['ruc' => '1844111-4'],
            [
                'razon_social' => 'Gavilan y Asociados S.A',
                'direccion' => 'Soldado Ovelar casi Asunción',
                'telefono' => '021 510028',
                'email' => 'gavilanyasociados@gavilan.com.py',
                'estado_id' => 1,
            ]
        );
    }
}
