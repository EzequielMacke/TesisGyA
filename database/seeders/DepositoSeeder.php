<?php

namespace Database\Seeders;

use App\Models\Deposito;
use Illuminate\Database\Seeder;

class DepositoSeeder extends Seeder
{
    public function run(): void
    {
        Deposito::firstOrCreate(
            ['descripcion' => 'Central'],
            ['estado_id' => 1]
        );
    }
}
