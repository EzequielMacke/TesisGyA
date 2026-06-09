<?php

namespace Database\Seeders;

use App\Models\Estado;
use Illuminate\Database\Seeder;

class EstadoSeeder extends Seeder
{
    public function run(): void
    {
        $estados = ['Activo', 'Inactivo', 'Pendiete', 'Confirmado', 'Anulado'];

        foreach ($estados as $descripcion) {
            Estado::firstOrCreate(['descripcion' => $descripcion]);
        }
    }
}
