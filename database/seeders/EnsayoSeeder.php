<?php

namespace Database\Seeders;

use App\Models\Ensayo;
use Illuminate\Database\Seeder;

class EnsayoSeeder extends Seeder
{
    public function run(): void
    {
        $ensayos = [
            1 => [
                'Ensayo de Esclerometría (Martillo de Schmidt)',
                'Ensayo de Resistencia a la Compresión (Testigos)',
                'Ensayo de Ultrasonido (Velocidad de Pulso)',
                'Ensayo de Detección de Armaduras (Pacómetro)',
                'Ensayo de Carbonatación del Hormigón',
                'Ensayo de Potencial de Corrosión',
                'Ensayo de Mapeo de Fisuras y Grietas',
                'Ensayo de Humedad',
            ],
            2 => [
                'Reparación de Corrosión en Armaduras',
                'Reparación de Coqueras (Oquedades) en Hormigón',
                'Inyección de Fisuras con Resina Epóxica',
                'Reparación con Mortero de Reparación Estructural',
                'Aplicación de Inhibidor de Corrosión',
                'Recubrimiento Anticorrosivo de Armaduras',
                'Reposición de Recubrimiento de Hormigón',
                'Sellado de Juntas y Grietas',
            ],
            3 => [
                'Refuerzo de Vigas con Fibra de Carbono (FRP)',
                'Refuerzo de Losas con Fibra de Carbono (FRP)',
                'Encamisado de Columnas con Fibra de Carbono',
                'Recrecido de Sección de Hormigón Armado',
                'Encamisado Metálico de Columnas',
                'Refuerzo con Platabandas Metálicas',
                'Anclajes Químicos para Refuerzo Estructural',
                'Apuntalamiento y Refuerzo Provisional',
            ],
        ];

        foreach ($ensayos as $servicioId => $descripciones) {
            foreach ($descripciones as $descripcion) {
                Ensayo::firstOrCreate(
                    ['descripcion' => $descripcion, 'servicio_id' => $servicioId],
                    ['estado_id' => 1]
                );
            }
        }
    }
}
