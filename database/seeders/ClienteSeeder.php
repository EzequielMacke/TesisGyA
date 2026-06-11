<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Persona;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = [
            [
                'nombre' => 'Roberto', 'apellido' => 'Duarte',
                'ruc' => '80045678-9', 'razon_social' => 'Ferretería Duarte S.A.',
                'direccion' => 'Av. Mariscal López 1234, Asunción',
                'telefono' => '0981123456', 'email' => 'contacto@ferreteriaduarte.com.py',
            ],
            [
                'nombre' => 'Claudia', 'apellido' => 'Benítez',
                'ruc' => '80098765-4', 'razon_social' => 'Construcciones Benítez S.R.L.',
                'direccion' => 'Ruta Mcal. Estigarribia km 12, San Lorenzo',
                'telefono' => '0982234567', 'email' => 'ventas@construccionesbenitez.com.py',
            ],
            [
                'nombre' => 'Marcos', 'apellido' => 'Villalba',
                'ruc' => '80112233-5', 'razon_social' => 'Materiales Villalba E.A.S.',
                'direccion' => 'Av. Defensores del Chaco 567, Luque',
                'telefono' => '0983345678', 'email' => 'info@materialesvillalba.com.py',
            ],
            [
                'nombre' => 'Sandra', 'apellido' => 'Ayala',
                'ruc' => '80223344-6', 'razon_social' => 'Distribuidora Ayala S.A.',
                'direccion' => 'Av. Pioneros del Este 890, Capiatá',
                'telefono' => '0984456789', 'email' => 'administracion@distribuidoraayala.com.py',
            ],
            [
                'nombre' => 'Hugo', 'apellido' => 'Cáceres',
                'ruc' => '80334455-7', 'razon_social' => 'Corporación Cáceres S.R.L.',
                'direccion' => 'Av. Luis Alberto de Herrera 234, Fernando de la Mora',
                'telefono' => '0985567890', 'email' => 'gerencia@corporacioncaceres.com.py',
            ],
            [
                'nombre' => 'Gloria', 'apellido' => 'Martínez',
                'ruc' => '80445566-8', 'razon_social' => 'Comercial Martínez S.A.',
                'direccion' => 'Av. Artigas 456, Lambaré',
                'telefono' => '0986678901', 'email' => 'ventas@comercialmartinez.com.py',
            ],
            [
                'nombre' => 'Fernando', 'apellido' => 'Ortiz',
                'ruc' => '80556677-9', 'razon_social' => 'Inversiones Ortiz E.A.S.',
                'direccion' => 'Av. San Blas 678, Ñemby',
                'telefono' => '0987789012', 'email' => 'contacto@inversionesortiz.com.py',
            ],
            [
                'nombre' => 'Patricia', 'apellido' => 'Vera',
                'ruc' => '80667788-0', 'razon_social' => 'Grupo Vera Construcciones S.R.L.',
                'direccion' => 'Ruta 2 km 22, Itauguá',
                'telefono' => '0988890123', 'email' => 'info@grupovera.com.py',
            ],
            [
                'nombre' => 'Ricardo', 'apellido' => 'Ramírez',
                'ruc' => '80778899-1', 'razon_social' => 'Ramírez & Asociados S.A.',
                'direccion' => 'Av. Cacique Lambaré 345, Limpio',
                'telefono' => '0989901234', 'email' => 'administracion@ramirezasociados.com.py',
            ],
            [
                'nombre' => 'Mirta', 'apellido' => 'Gómez',
                'ruc' => '80889900-2', 'razon_social' => 'Gómez Hermanos S.R.L.',
                'direccion' => 'Av. Eusebio Ayala 901, Mariano Roque Alonso',
                'telefono' => '0991012345', 'email' => 'ventas@gomezhermanos.com.py',
            ],
        ];

        foreach ($clientes as $datos) {
            $persona = Persona::firstOrCreate(
                ['nombre' => $datos['nombre'], 'apellido' => $datos['apellido']],
                ['estado_id' => 1]
            );

            Cliente::firstOrCreate(
                ['ruc' => $datos['ruc']],
                [
                    'razon_social' => $datos['razon_social'],
                    'direccion' => $datos['direccion'],
                    'telefono' => $datos['telefono'],
                    'email' => $datos['email'],
                    'fecha' => now()->toDateString(),
                    'usuario_id' => 1,
                    'estado_id' => 1,
                    'persona_id' => $persona->id,
                ]
            );
        }
    }
}
