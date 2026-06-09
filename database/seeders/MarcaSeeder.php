<?php

namespace Database\Seeders;

use App\Models\Marca;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    public function run(): void
    {
        $marcas = [
            'Weber',
            'Sika',
            'Knauf',
            'Bticino',
            'Legrand',
            'Durlock',
            'Saint-Gobain',
            'Isover',
            'Fibratollo',
            'Brimax',
            'Parex',
            'Mapei',
            'Fosroc',
            'Master Builders',
            'Hilti',
            'Bosch',
            'DeWalt',
            'Makita',
            'Stanley',
            'Black & Decker',
            'Pladur',
            'Eternit',
            'Iggam',
            'Aluplast',
            'Veka',
            'Andercol',
            'Corona',
            'Pavco',
            'Tigre',
            'Amanco',
            'Nicoll',
            'Wavin',
            'Gerfor',
            'Tuboplast',
            'Aislante Polipol',
            'Tyvek',
            'Sto',
            'Dryvit',
            'Quimtia',
            'Tecno Fast',
            'Ternium',
            'Gerdau',
            'Acindar',
            'ArcelorMittal',
            'Peri',
            'Doka',
            'Ulma',
            'Alsina',
            'Entrecanales',
            'Leroy Merlin',
        ];

        foreach ($marcas as $descripcion) {
            Marca::firstOrCreate(
                ['descripcion' => $descripcion],
                [
                    'estado_id'  => 1,
                    'usuario_id' => 1,
                    'fecha'      => now()->toDateString(),
                ]
            );
        }
    }
}
