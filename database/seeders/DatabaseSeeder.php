<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EstadoSeeder::class,
            DatoEmpresaSeeder::class,
            TipoDocumentoSeeder::class,
            CondicionPagoSeeder::class,
            MetodoPagoSeeder::class,
            TipoVehiculoSeeder::class,
            DepositoSeeder::class,
            SucursalSeeder::class,
            CargoSeeder::class,
            PersonaSeeder::class,
            UserSeeder::class,
            EmpleadoSeeder::class,
            FuncionarioSeeder::class,
            MarcaSeeder::class,
            InsumoSeeder::class,
            ImpuestoSeeder::class,
        ]);
    }
}
