<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Obra;
use Illuminate\Database\Seeder;

class ObraSeeder extends Seeder
{
    public function run(): void
    {
        $obrasPorCliente = [
            '80045678-9' => [
                ['descripcion' => 'Construcción de depósito de materiales', 'ubicacion' => 'Barrio San Pablo, Asunción', 'metros_cuadrados' => 320.00, 'niveles' => 1, 'observacion' => 'Obra en etapa de fundación'],
                ['descripcion' => 'Ampliación de local comercial', 'ubicacion' => 'Av. Mariscal López 1234, Asunción', 'metros_cuadrados' => 95.50, 'niveles' => 1, 'observacion' => 'Pendiente de aprobación de planos'],
                ['descripcion' => 'Remodelación de oficinas administrativas', 'ubicacion' => 'Av. Mariscal López 1234, Asunción', 'metros_cuadrados' => 60.00, 'niveles' => 1, 'observacion' => null],
            ],
            '80098765-4' => [
                ['descripcion' => 'Construcción de edificio de departamentos', 'ubicacion' => 'Barrio Loma Pytã, San Lorenzo', 'metros_cuadrados' => 980.00, 'niveles' => 4, 'observacion' => 'Obra en estructura'],
                ['descripcion' => 'Construcción de vivienda unifamiliar', 'ubicacion' => 'Barrio San Vicente, San Lorenzo', 'metros_cuadrados' => 210.00, 'niveles' => 2, 'observacion' => null],
                ['descripcion' => 'Ampliación de planta de almacenamiento', 'ubicacion' => 'Ruta Mcal. Estigarribia km 12, San Lorenzo', 'metros_cuadrados' => 450.00, 'niveles' => 1, 'observacion' => 'Pendiente inicio de obra'],
            ],
            '80112233-5' => [
                ['descripcion' => 'Construcción de galpón industrial', 'ubicacion' => 'Zona Industrial, Luque', 'metros_cuadrados' => 1200.00, 'niveles' => 1, 'observacion' => 'Obra en etapa de cubierta'],
                ['descripcion' => 'Pavimentación de patio de maniobras', 'ubicacion' => 'Av. Defensores del Chaco 567, Luque', 'metros_cuadrados' => 600.00, 'niveles' => null, 'observacion' => null],
                ['descripcion' => 'Construcción de oficina de ventas', 'ubicacion' => 'Av. Defensores del Chaco 567, Luque', 'metros_cuadrados' => 75.00, 'niveles' => 1, 'observacion' => 'Pendiente de aprobación de planos'],
            ],
            '80223344-6' => [
                ['descripcion' => 'Ampliación de centro de distribución', 'ubicacion' => 'Av. Pioneros del Este 890, Capiatá', 'metros_cuadrados' => 850.00, 'niveles' => 1, 'observacion' => 'Obra en estructura'],
                ['descripcion' => 'Construcción de muro perimetral', 'ubicacion' => 'Av. Pioneros del Este 890, Capiatá', 'metros_cuadrados' => 180.00, 'niveles' => null, 'observacion' => null],
                ['descripcion' => 'Remodelación de área de carga y descarga', 'ubicacion' => 'Av. Pioneros del Este 890, Capiatá', 'metros_cuadrados' => 220.00, 'niveles' => 1, 'observacion' => 'Pendiente inicio de obra'],
            ],
            '80334455-7' => [
                ['descripcion' => 'Construcción de edificio corporativo', 'ubicacion' => 'Av. Luis Alberto de Herrera 234, Fernando de la Mora', 'metros_cuadrados' => 1500.00, 'niveles' => 5, 'observacion' => 'Obra en etapa de fundación'],
                ['descripcion' => 'Construcción de estacionamiento techado', 'ubicacion' => 'Av. Luis Alberto de Herrera 234, Fernando de la Mora', 'metros_cuadrados' => 400.00, 'niveles' => 1, 'observacion' => null],
                ['descripcion' => 'Remodelación de planta baja', 'ubicacion' => 'Av. Luis Alberto de Herrera 234, Fernando de la Mora', 'metros_cuadrados' => 150.00, 'niveles' => 1, 'observacion' => 'Pendiente de aprobación de planos'],
            ],
            '80445566-8' => [
                ['descripcion' => 'Construcción de local comercial', 'ubicacion' => 'Barrio Tablada Nueva, Lambaré', 'metros_cuadrados' => 280.00, 'niveles' => 2, 'observacion' => 'Obra en estructura'],
                ['descripcion' => 'Ampliación de salón de ventas', 'ubicacion' => 'Av. Artigas 456, Lambaré', 'metros_cuadrados' => 130.00, 'niveles' => 1, 'observacion' => null],
                ['descripcion' => 'Construcción de depósito secundario', 'ubicacion' => 'Av. Artigas 456, Lambaré', 'metros_cuadrados' => 200.00, 'niveles' => 1, 'observacion' => 'Pendiente inicio de obra'],
            ],
            '80556677-9' => [
                ['descripcion' => 'Construcción de complejo de departamentos', 'ubicacion' => 'Barrio Santa Librada, Ñemby', 'metros_cuadrados' => 1100.00, 'niveles' => 3, 'observacion' => 'Obra en estructura'],
                ['descripcion' => 'Construcción de vivienda dúplex', 'ubicacion' => 'Barrio San Isidro, Ñemby', 'metros_cuadrados' => 240.00, 'niveles' => 2, 'observacion' => null],
                ['descripcion' => 'Remodelación de fachada e ingreso', 'ubicacion' => 'Av. San Blas 678, Ñemby', 'metros_cuadrados' => 50.00, 'niveles' => 1, 'observacion' => 'Pendiente de aprobación de planos'],
            ],
            '80667788-0' => [
                ['descripcion' => 'Construcción de loteo con casas modelo', 'ubicacion' => 'Compañía Mboi Ka\'e, Itauguá', 'metros_cuadrados' => 2000.00, 'niveles' => 1, 'observacion' => 'Obra en etapa de fundación'],
                ['descripcion' => 'Construcción de vivienda unifamiliar premium', 'ubicacion' => 'Compañía Yuquyry, Itauguá', 'metros_cuadrados' => 350.00, 'niveles' => 2, 'observacion' => null],
                ['descripcion' => 'Ampliación de oficina técnica', 'ubicacion' => 'Ruta 2 km 22, Itauguá', 'metros_cuadrados' => 80.00, 'niveles' => 1, 'observacion' => 'Pendiente inicio de obra'],
            ],
            '80778899-1' => [
                ['descripcion' => 'Construcción de edificio de oficinas', 'ubicacion' => 'Barrio Centro, Limpio', 'metros_cuadrados' => 760.00, 'niveles' => 3, 'observacion' => 'Obra en estructura'],
                ['descripcion' => 'Construcción de salón de eventos', 'ubicacion' => 'Av. Cacique Lambaré 345, Limpio', 'metros_cuadrados' => 500.00, 'niveles' => 1, 'observacion' => null],
                ['descripcion' => 'Remodelación de baños y vestuarios', 'ubicacion' => 'Av. Cacique Lambaré 345, Limpio', 'metros_cuadrados' => 40.00, 'niveles' => 1, 'observacion' => 'Pendiente de aprobación de planos'],
            ],
            '80889900-2' => [
                ['descripcion' => 'Construcción de depósito refrigerado', 'ubicacion' => 'Zona Industrial, Mariano Roque Alonso', 'metros_cuadrados' => 600.00, 'niveles' => 1, 'observacion' => 'Obra en etapa de fundación'],
                ['descripcion' => 'Ampliación de planta de producción', 'ubicacion' => 'Av. Eusebio Ayala 901, Mariano Roque Alonso', 'metros_cuadrados' => 900.00, 'niveles' => 1, 'observacion' => 'Obra en estructura'],
                ['descripcion' => 'Construcción de cerco perimetral y portón', 'ubicacion' => 'Av. Eusebio Ayala 901, Mariano Roque Alonso', 'metros_cuadrados' => 250.00, 'niveles' => null, 'observacion' => null],
            ],
        ];

        foreach ($obrasPorCliente as $ruc => $obras) {
            $cliente = Cliente::where('ruc', $ruc)->first();

            if (!$cliente) {
                $this->command->warn("Cliente no encontrado para RUC: {$ruc}");
                continue;
            }

            foreach ($obras as $datos) {
                Obra::firstOrCreate(
                    ['cliente_id' => $cliente->id, 'descripcion' => $datos['descripcion']],
                    [
                        'ubicacion' => $datos['ubicacion'],
                        'fecha' => now()->toDateString(),
                        'usuario_id' => 1,
                        'estado_id' => 1,
                        'observacion' => $datos['observacion'],
                        'metros_cuadrados' => $datos['metros_cuadrados'],
                        'niveles' => $datos['niveles'],
                    ]
                );
            }
        }
    }
}
