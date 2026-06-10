<?php

namespace Database\Seeders;

use App\Models\Empleado;
use App\Models\Persona;
use Illuminate\Database\Seeder;

class EmpleadoSeeder extends Seeder
{
    public function run(): void
    {
        $empleados = [
            ['nombre' => 'Carlos', 'apellido' => 'Gonzalez'],
            ['nombre' => 'Maria', 'apellido' => 'Lopez'],
            ['nombre' => 'Juan', 'apellido' => 'Perez'],
            ['nombre' => 'Ana', 'apellido' => 'Rodriguez'],
            ['nombre' => 'Luis', 'apellido' => 'Fernandez'],
        ];

        foreach ($empleados as $datos) {
            $persona = Persona::firstOrCreate(
                ['nombre' => $datos['nombre'], 'apellido' => $datos['apellido']],
                ['estado_id' => 1]
            );

            Empleado::firstOrCreate(
                ['persona_id' => $persona->id],
                [
                    'sucursal_id' => 1,
                    'cargo_id' => 2,
                    'fecha_contratacion' => now()->toDateString(),
                    'estado_id' => 1,
                ]
            );
        }
    }
}
