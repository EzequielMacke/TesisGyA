<?php

namespace Database\Seeders;

use App\Models\Funcionario;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Database\Seeder;

class FuncionarioSeeder extends Seeder
{
    public function run(): void
    {
        $funcionarios = [
            ['nombre' => 'Pedro', 'apellido' => 'Ramirez', 'usuario' => 'pramirez', 'email' => 'pramirez@gya.com'],
            ['nombre' => 'Lucia', 'apellido' => 'Benitez', 'usuario' => 'lbenitez', 'email' => 'lbenitez@gya.com'],
            ['nombre' => 'Diego', 'apellido' => 'Acosta', 'usuario' => 'dacosta', 'email' => 'dacosta@gya.com'],
            ['nombre' => 'Patricia', 'apellido' => 'Ortiz', 'usuario' => 'portiz', 'email' => 'portiz@gya.com'],
            ['nombre' => 'Roberto', 'apellido' => 'Cabrera', 'usuario' => 'rcabrera', 'email' => 'rcabrera@gya.com'],
        ];

        foreach ($funcionarios as $datos) {
            $persona = Persona::firstOrCreate(
                ['nombre' => $datos['nombre'], 'apellido' => $datos['apellido']],
                ['estado_id' => 1]
            );

            $user = User::firstOrCreate(
                ['email' => $datos['email']],
                [
                    'sucursal_id' => 1,
                    'estado_id' => 1,
                    'verificado' => 1,
                    'acceso_intento' => 0,
                    'persona_id' => $persona->id,
                    'empleado_id' => null,
                    'usuario' => $datos['usuario'],
                    'contraseña' => bcrypt('funcionario123'),
                    'cargo_id' => 4,
                ]
            );

            Funcionario::firstOrCreate(
                ['persona_id' => $persona->id],
                [
                    'fecha_ingreso' => now()->toDateString(),
                    'cargo_id' => 4,
                    'estado_id' => 1,
                    'user_id' => $user->id,
                ]
            );
        }
    }
}
