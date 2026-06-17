<?php

namespace Database\Seeders;

use App\Models\TipoDocumento;
use Illuminate\Database\Seeder;

class TipoDocumentoSeeder extends Seeder
{
    public function run(): void
    {
        TipoDocumento::firstOrCreate(['descripcion' => 'Factura'], ['estado_id' => 1]);
        TipoDocumento::firstOrCreate(['descripcion' => 'Nota de Crédito'], ['estado_id' => 1]);
        TipoDocumento::firstOrCreate(['descripcion' => 'Nota de Débito'], ['estado_id' => 1]);
    }
}
