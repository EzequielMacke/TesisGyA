<?php

namespace Database\Seeders;

use App\Models\Modulo;
use Illuminate\Database\Seeder;

class ModuloSeeder extends Seeder
{
    public function run(): void
    {
        $modulos = [
            ['descripcion' => 'Gestion de Inventario',    'codigo' => 'ges_inv'],
            ['descripcion' => 'Inventario',               'codigo' => 'inv'],
            ['descripcion' => 'Solicitud de Insumos',     'codigo' => 'sol_ins'],
            ['descripcion' => 'Moviemiento de Insumos',   'codigo' => 'mov_ins'],
            ['descripcion' => 'Ajuste de Inventario',     'codigo' => 'aju_inv'],
            ['descripcion' => 'Compra',                   'codigo' => 'com'],
            ['descripcion' => 'Pedidos de Compra',        'codigo' => 'ped_com'],
            ['descripcion' => 'Presupuestos Aprobados',   'codigo' => 'pres_apr'],
            ['descripcion' => 'Ordenes de Compra',        'codigo' => 'ord_com'],
            ['descripcion' => 'Notas de Remision',        'codigo' => 'not_rem'],
            ['descripcion' => 'Compras',                  'codigo' => 'fac_com'],
            ['descripcion' => 'Notas de Compra',          'codigo' => 'not_com'],
            ['descripcion' => 'Servicio',                 'codigo' => 'ser'],
            ['descripcion' => 'Solicitud de Servicio',    'codigo' => 'sol_ser'],
            ['descripcion' => 'Visitas Previas',          'codigo' => 'vis_pre'],
            ['descripcion' => 'Presupuestos de Servicio', 'codigo' => 'pre_ser'],
            ['descripcion' => 'Contratos',                'codigo' => 'cont'],
            ['descripcion' => 'Ordenes de Servicio',      'codigo' => 'ord_ser'],
            ['descripcion' => 'Insumos Utilizados',       'codigo' => 'ins_uti'],
            ['descripcion' => 'Servicios Realizados',     'codigo' => 'ser_rea'],
            ['descripcion' => 'Reclamos del Cliente',     'codigo' => 'rec_cli'],
            ['descripcion' => 'Referenciales',            'codigo' => 'ref'],
            ['descripcion' => 'Marca',                    'codigo' => 'mar'],
            ['descripcion' => 'Insumos',                  'codigo' => 'ins'],
            ['descripcion' => 'Presupuestar Pedidos',     'codigo' => 'pre_ped'],
            ['descripcion' => 'Informes',                 'codigo' => 'inf'],
            ['descripcion' => 'Mi Perfil',                'codigo' => 'mper'],
            ['descripcion' => 'Configuración',            'codigo' => 'conf'],
            ['descripcion' => 'Manual de Usuario',        'codigo' => 'man_usu'],
        ];

        foreach ($modulos as $modulo) {
            Modulo::firstOrCreate(
                ['codigo' => $modulo['codigo']],
                ['descripcion' => $modulo['descripcion']]
            );
        }
    }
}
