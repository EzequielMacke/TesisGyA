<?php

namespace Database\Seeders;

use App\Models\Persona;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $persona = Persona::where('nombre', 'Ezequiel')->where('apellido', 'Macke')->first();

        User::firstOrCreate(
            ['email' => 'ezequiel.macke@gmail.com'],
            [
                'sucursal_id' => 1,
                'estado_id' => 1,
                'verificado' => 1,
                'acceso_intento' => 0,
                'persona_id' => $persona?->id,
                'empleado_id' => null,
                'usuario' => 'admin',
                'contraseña' => bcrypt('admin'),
                'cargo_id' => 1,
            ]
        );
    }
}
