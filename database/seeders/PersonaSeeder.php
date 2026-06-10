<?php

namespace Database\Seeders;

use App\Models\Persona;
use Illuminate\Database\Seeder;

class PersonaSeeder extends Seeder
{
    public function run(): void
    {
        Persona::firstOrCreate(
            ['nombre' => 'Ezequiel', 'apellido' => 'Macke'],
            [
                'ci' => null,
                'direccion' => null,
                'telefono' => null,
                'fecha_nacimiento' => null,
                'genero' => null,
                'estado_id' => 1,
            ]
        );
    }
}
