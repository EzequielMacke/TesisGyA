<?php

namespace Database\Seeders;

use App\Models\Insumo;
use App\Models\Marca;
use App\Models\UnidadMedida;
use Illuminate\Database\Seeder;

class InsumoSeeder extends Seeder
{
    public function run(): void
    {
        // Crear unidades de medida si no existen
        $unidadesDesc = ['kg', 'L', 'm2', 'm', 'unidad', 'bolsa', 'rollo', 'caja', 'm3', 'par'];
        $um = [];
        foreach ($unidadesDesc as $desc) {
            $um[$desc] = UnidadMedida::firstOrCreate(
                ['descripcion' => $desc],
                ['estado_id' => 1, 'usuario_id' => 1, 'fecha' => now()->toDateString()]
            )->id;
        }

        // Obtener IDs de marcas por nombre
        $marcas = Marca::pluck('id', 'descripcion');

        $insumos = [
            'Weber' => [
                ['descripcion' => 'Weber.col Flexible - Adhesivo cerámico flexible', 'unidad' => 'kg'],
                ['descripcion' => 'Weber.prim - Imprimación para morteros',          'unidad' => 'L'],
                ['descripcion' => 'Weber.floor Nivelador autonivelante',             'unidad' => 'kg'],
                ['descripcion' => 'Weber.tec Revoplasc - Revoque plástico',          'unidad' => 'kg'],
            ],
            'Sika' => [
                ['descripcion' => 'SikaFlex-11FC - Sellador poliuretano',            'unidad' => 'L'],
                ['descripcion' => 'Sika-1 - Impermeabilizante integral',             'unidad' => 'kg'],
                ['descripcion' => 'SikaTop-107 - Revestimiento impermeabilizante',  'unidad' => 'kg'],
                ['descripcion' => 'SikaLatex - Aditivo látex para morteros',        'unidad' => 'L'],
            ],
            'Knauf' => [
                ['descripcion' => 'Placa Yeso Estándar 12.5mm',                     'unidad' => 'm2'],
                ['descripcion' => 'Placa Yeso Hidrofugada 12.5mm',                  'unidad' => 'm2'],
                ['descripcion' => 'Perfil Montante Knauf 70mm',                     'unidad' => 'm'],
                ['descripcion' => 'Perfil Solera Knauf 70mm',                       'unidad' => 'm'],
            ],
            'Bticino' => [
                ['descripcion' => 'Toma Schuko 2P+T Bticino',                       'unidad' => 'unidad'],
                ['descripcion' => 'Interruptor Simple Bticino',                     'unidad' => 'unidad'],
                ['descripcion' => 'Interruptor Doble Bticino',                      'unidad' => 'unidad'],
                ['descripcion' => 'Toma USB Doble Bticino',                         'unidad' => 'unidad'],
            ],
            'Legrand' => [
                ['descripcion' => 'Canaleta PVC Legrand 40x25mm',                   'unidad' => 'm'],
                ['descripcion' => 'Tablero Empotrar Legrand 12 Módulos',            'unidad' => 'unidad'],
                ['descripcion' => 'Disyuntor Termomagnético Legrand 20A',           'unidad' => 'unidad'],
                ['descripcion' => 'Caja Empalme Rectangular Legrand',               'unidad' => 'unidad'],
            ],
            'Durlock' => [
                ['descripcion' => 'Placa Durlock Estándar 12.5mm',                  'unidad' => 'm2'],
                ['descripcion' => 'Placa Durlock Hidrofugada 12.5mm',               'unidad' => 'm2'],
                ['descripcion' => 'Perfil Montante DK70',                           'unidad' => 'm'],
                ['descripcion' => 'Masilla Durlock para juntas',                    'unidad' => 'kg'],
            ],
            'Saint-Gobain' => [
                ['descripcion' => 'Vidrio Float 4mm',                               'unidad' => 'm2'],
                ['descripcion' => 'Vidrio Float 6mm',                               'unidad' => 'm2'],
                ['descripcion' => 'Vidrio Laminado 6mm',                            'unidad' => 'm2'],
                ['descripcion' => 'Vidrio Reflectivo 6mm',                          'unidad' => 'm2'],
            ],
            'Isover' => [
                ['descripcion' => 'Lana de Vidrio Rollo 50mm',                      'unidad' => 'm2'],
                ['descripcion' => 'Lana de Vidrio Rollo 80mm',                      'unidad' => 'm2'],
                ['descripcion' => 'Panel Rígido Lana de Vidrio 80mm',               'unidad' => 'm2'],
                ['descripcion' => 'Lana de Vidrio Suelta - Aislante',               'unidad' => 'kg'],
            ],
            'Fibratollo' => [
                ['descripcion' => 'Cañería Fibrocemento 110mm',                     'unidad' => 'm'],
                ['descripcion' => 'Cañería Fibrocemento 75mm',                      'unidad' => 'm'],
                ['descripcion' => 'Codo Fibrocemento 90° 110mm',                   'unidad' => 'unidad'],
                ['descripcion' => 'Ramal Fibrocemento 110/75mm',                   'unidad' => 'unidad'],
            ],
            'Brimax' => [
                ['descripcion' => 'Adhesivo de Contacto Brimax',                    'unidad' => 'L'],
                ['descripcion' => 'Sellador Acrílico Brimax',                       'unidad' => 'L'],
                ['descripcion' => 'Pegamento PVC Brimax',                           'unidad' => 'L'],
                ['descripcion' => 'Masilla Plástica Brimax Universal',              'unidad' => 'kg'],
            ],
            'Parex' => [
                ['descripcion' => 'Parex Revoque Interior Fino',                    'unidad' => 'kg'],
                ['descripcion' => 'Parex Adhesivo Cerámico Estándar',               'unidad' => 'kg'],
                ['descripcion' => 'Parex Pegamento para Porcelanato',               'unidad' => 'kg'],
                ['descripcion' => 'Parex Fratasado Exterior',                       'unidad' => 'kg'],
            ],
            'Mapei' => [
                ['descripcion' => 'Mapei Ultralite S1 - Adhesivo ligero',           'unidad' => 'kg'],
                ['descripcion' => 'Mapei Keracolor FF - Junta cerámica',            'unidad' => 'kg'],
                ['descripcion' => 'Mapei Mapelastic - Impermeabilizante',           'unidad' => 'kg'],
                ['descripcion' => 'Mapei Primer G - Imprimación universal',         'unidad' => 'L'],
            ],
            'Fosroc' => [
                ['descripcion' => 'Fosroc Renderoc Plug - Mortero reparación',      'unidad' => 'kg'],
                ['descripcion' => 'Fosroc Nitobond EP - Adhesivo epóxico',          'unidad' => 'kg'],
                ['descripcion' => 'Fosroc Penacollar - Expansor de juntas',         'unidad' => 'unidad'],
                ['descripcion' => 'Fosroc Conplast SP - Superplastificante',        'unidad' => 'L'],
            ],
            'Master Builders' => [
                ['descripcion' => 'MasterSeal 581 - Sellador poliuretano',          'unidad' => 'L'],
                ['descripcion' => 'MasterEmaco S488 - Mortero de reparación',       'unidad' => 'kg'],
                ['descripcion' => 'MasterFlow 648 - Grout sin retracción',          'unidad' => 'kg'],
                ['descripcion' => 'MasterRoc MP320 - Aditivo para hormigón',        'unidad' => 'L'],
            ],
            'Hilti' => [
                ['descripcion' => 'Clavo HN MX 6/20 Hilti',                        'unidad' => 'caja'],
                ['descripcion' => 'Taco Expansión HSL-3 M12 Hilti',                'unidad' => 'unidad'],
                ['descripcion' => 'Anclaje Químico HIT-RE 500 Hilti',              'unidad' => 'L'],
                ['descripcion' => 'Tornillo S-MD 25 Hilti',                        'unidad' => 'caja'],
            ],
            'Bosch' => [
                ['descripcion' => 'Disco de Corte Bosch A24R 115mm',               'unidad' => 'unidad'],
                ['descripcion' => 'Disco de Desbaste Bosch A24R 115mm',            'unidad' => 'unidad'],
                ['descripcion' => 'Broca SDS-Plus Bosch 12mm Concreto',            'unidad' => 'unidad'],
                ['descripcion' => 'Hoja Sierra Caladora Bosch T144D',              'unidad' => 'unidad'],
            ],
            'DeWalt' => [
                ['descripcion' => 'Disco Diamantado DeWalt 115mm Concreto',        'unidad' => 'unidad'],
                ['descripcion' => 'Broca Percusión DeWalt 10mm Mampostería',       'unidad' => 'unidad'],
                ['descripcion' => 'Papel Lija Orbital DeWalt 80G',                 'unidad' => 'unidad'],
                ['descripcion' => 'Hoja Sierra Circular DeWalt 7 1/4"',            'unidad' => 'unidad'],
            ],
            'Makita' => [
                ['descripcion' => 'Cadena Motosierra Makita 14"',                  'unidad' => 'unidad'],
                ['descripcion' => 'Filtro Aire Makita HR2470',                     'unidad' => 'unidad'],
                ['descripcion' => 'Cepillos de Carbono Makita HR2470',             'unidad' => 'par'],
                ['descripcion' => 'Disco Amoladora Makita 115mm Corte',            'unidad' => 'unidad'],
            ],
            'Stanley' => [
                ['descripcion' => 'Cinta Métrica Stanley 5m',                      'unidad' => 'unidad'],
                ['descripcion' => 'Nivel Aluminio Stanley 60cm',                   'unidad' => 'unidad'],
                ['descripcion' => 'Llave Inglesa Stanley 12"',                     'unidad' => 'unidad'],
                ['descripcion' => 'Martillo Carpintero Stanley 16oz',              'unidad' => 'unidad'],
            ],
            'Black & Decker' => [
                ['descripcion' => 'Hoja Sierra Cinta Black & Decker 345mm',        'unidad' => 'unidad'],
                ['descripcion' => 'Lija de Banda Black & Decker 75x533mm',         'unidad' => 'unidad'],
                ['descripcion' => 'Fresa Ranurado Black & Decker 1/4"',            'unidad' => 'unidad'],
                ['descripcion' => 'Broca Madera Black & Decker 25mm',              'unidad' => 'unidad'],
            ],
            'Pladur' => [
                ['descripcion' => 'Placa Pladur N-13 Estándar',                    'unidad' => 'm2'],
                ['descripcion' => 'Placa Pladur WA-13 Hidrófuga',                  'unidad' => 'm2'],
                ['descripcion' => 'Perfil Montante Pladur M-48',                   'unidad' => 'm'],
                ['descripcion' => 'Pasta Pladur para Juntas',                      'unidad' => 'kg'],
            ],
            'Eternit' => [
                ['descripcion' => 'Placa Eterboard 10mm Cemento',                  'unidad' => 'm2'],
                ['descripcion' => 'Canaleta Pluvial Eternit 3m',                   'unidad' => 'm'],
                ['descripcion' => 'Teja Ondulada Fibrocemento Eternit',            'unidad' => 'unidad'],
                ['descripcion' => 'Bajada Pluvial Eternit D100mm',                 'unidad' => 'm'],
            ],
            'Iggam' => [
                ['descripcion' => 'Membrana Asfáltica Iggam 3mm',                  'unidad' => 'm2'],
                ['descripcion' => 'Membrana Asfáltica Iggam 4mm',                  'unidad' => 'm2'],
                ['descripcion' => 'Membrana con Aluminio Iggam 3mm',               'unidad' => 'm2'],
                ['descripcion' => 'Imprimación Asfáltica Iggam',                   'unidad' => 'L'],
            ],
            'Aluplast' => [
                ['descripcion' => 'Perfil Marco Ventana Aluplast 70mm',            'unidad' => 'm'],
                ['descripcion' => 'Perfil Hoja Ventana Aluplast 70mm',             'unidad' => 'm'],
                ['descripcion' => 'Junta EPDM Aluplast 9mm',                       'unidad' => 'm'],
                ['descripcion' => 'Refuerzo Acero Galvanizado Aluplast 1.5mm',     'unidad' => 'm'],
            ],
            'Veka' => [
                ['descripcion' => 'Perfil Marco Ventana VEKA 70mm',                'unidad' => 'm'],
                ['descripcion' => 'Perfil Batiente VEKA 70mm',                     'unidad' => 'm'],
                ['descripcion' => 'Ángulo de Unión PVC VEKA',                      'unidad' => 'unidad'],
                ['descripcion' => 'Tapajunta Exterior VEKA',                       'unidad' => 'm'],
            ],
            'Andercol' => [
                ['descripcion' => 'Adhesivo Andercol 940 Neopreno',                'unidad' => 'L'],
                ['descripcion' => 'Adhesivo Andercol 850 Vinílico',                'unidad' => 'L'],
                ['descripcion' => 'Silicona Estructural Andercol Neutra',          'unidad' => 'L'],
                ['descripcion' => 'Esmalte Anticorrosivo Andercol',                'unidad' => 'L'],
            ],
            'Corona' => [
                ['descripcion' => 'Porcelanato Beige Corona 60x60cm',              'unidad' => 'm2'],
                ['descripcion' => 'Cerámica Blanca Corona 30x30cm',                'unidad' => 'm2'],
                ['descripcion' => 'Porcelanato Gris Corona 60x60cm',               'unidad' => 'm2'],
                ['descripcion' => 'Azulejo Blanco Corona 20x30cm',                 'unidad' => 'm2'],
            ],
            'Pavco' => [
                ['descripcion' => 'Tubería PVC Sanitaria Pavco 4"',                'unidad' => 'm'],
                ['descripcion' => 'Tubería PVC Sanitaria Pavco 2"',                'unidad' => 'm'],
                ['descripcion' => 'Codo PVC 90° Pavco 4"',                        'unidad' => 'unidad'],
                ['descripcion' => 'Trampa Sifón PVC Pavco 2"',                    'unidad' => 'unidad'],
            ],
            'Tigre' => [
                ['descripcion' => 'Caño PVC Desagüe Tigre 110mm',                  'unidad' => 'm'],
                ['descripcion' => 'Caño PVC Desagüe Tigre 75mm',                   'unidad' => 'm'],
                ['descripcion' => 'Codo PVC 45° Tigre 110mm',                     'unidad' => 'unidad'],
                ['descripcion' => 'Tee PVC Tigre 110/75mm',                       'unidad' => 'unidad'],
            ],
            'Amanco' => [
                ['descripcion' => 'Tubería CPVC Agua Caliente Amanco 1/2"',        'unidad' => 'm'],
                ['descripcion' => 'Tubería PVC Agua Fría Amanco 3/4"',             'unidad' => 'm'],
                ['descripcion' => 'Unión CPVC Amanco 1/2"',                        'unidad' => 'unidad'],
                ['descripcion' => 'Codo CPVC 90° Amanco 1/2"',                    'unidad' => 'unidad'],
            ],
            'Nicoll' => [
                ['descripcion' => 'Canalón PVC Nicoll 125mm',                      'unidad' => 'm'],
                ['descripcion' => 'Bajante PVC Nicoll 80mm',                       'unidad' => 'm'],
                ['descripcion' => 'Conector Canalón Nicoll',                       'unidad' => 'unidad'],
                ['descripcion' => 'Ángulo Interior Canalón Nicoll',                'unidad' => 'unidad'],
            ],
            'Wavin' => [
                ['descripcion' => 'Tubo PVC Presión Wavin 1"',                     'unidad' => 'm'],
                ['descripcion' => 'Tubo PVC Presión Wavin 1 1/2"',                 'unidad' => 'm'],
                ['descripcion' => 'Válvula Check PVC Wavin 1"',                    'unidad' => 'unidad'],
                ['descripcion' => 'Unión Deslizante PVC Wavin 1"',                 'unidad' => 'unidad'],
            ],
            'Gerfor' => [
                ['descripcion' => 'Tubo PVC SDR-13.5 Gerfor 1/2"',                 'unidad' => 'm'],
                ['descripcion' => 'Tubo PVC SDR-13.5 Gerfor 3/4"',                 'unidad' => 'm'],
                ['descripcion' => 'Codo PVC 90° Gerfor 1/2"',                     'unidad' => 'unidad'],
                ['descripcion' => 'Tee PVC Gerfor 3/4"',                          'unidad' => 'unidad'],
            ],
            'Tuboplast' => [
                ['descripcion' => 'Caño Polietileno Tuboplast 20mm',               'unidad' => 'm'],
                ['descripcion' => 'Caño Polietileno Tuboplast 32mm',               'unidad' => 'm'],
                ['descripcion' => 'Conector Compresión Tuboplast 20mm',            'unidad' => 'unidad'],
                ['descripcion' => 'Codo Compresión 90° Tuboplast 20mm',           'unidad' => 'unidad'],
            ],
            'Aislante Polipol' => [
                ['descripcion' => 'Polipol XPS 30mm - Aislante extruido',          'unidad' => 'm2'],
                ['descripcion' => 'Polipol XPS 50mm - Aislante extruido',          'unidad' => 'm2'],
                ['descripcion' => 'Polipol EPS 50mm - Aislante expandido',         'unidad' => 'm2'],
                ['descripcion' => 'Polipol EPS 100mm - Aislante expandido',        'unidad' => 'm2'],
            ],
            'Tyvek' => [
                ['descripcion' => 'Membrana Tyvek HomeWrap - Barrera vapor',       'unidad' => 'm2'],
                ['descripcion' => 'Membrana Tyvek DrainWrap - Drenante',           'unidad' => 'm2'],
                ['descripcion' => 'Membrana Tyvek StuccoWrap',                     'unidad' => 'm2'],
                ['descripcion' => 'Cinta Adhesiva Tyvek - Sellado',                'unidad' => 'rollo'],
            ],
            'Sto' => [
                ['descripcion' => 'StoTherm Classic - Sistema EIFS',               'unidad' => 'm2'],
                ['descripcion' => 'Stodur - Revestimiento duro',                   'unidad' => 'm2'],
                ['descripcion' => 'StoColor Lotusan - Pintura fachada',            'unidad' => 'L'],
                ['descripcion' => 'Stolit K - Revestimiento mineral',              'unidad' => 'kg'],
            ],
            'Dryvit' => [
                ['descripcion' => 'Dryvit Quarzputz - Revestimiento granular',     'unidad' => 'kg'],
                ['descripcion' => 'Dryvit Outsulation - Panel aislante',           'unidad' => 'm2'],
                ['descripcion' => 'Dryvit Primus - Adhesivo base',                 'unidad' => 'kg'],
                ['descripcion' => 'Dryvit Colorex - Pintura elastomérica',         'unidad' => 'L'],
            ],
            'Quimtia' => [
                ['descripcion' => 'Quimtia Adhesivo Cerámico Estándar',            'unidad' => 'kg'],
                ['descripcion' => 'Quimtia Porcelain Fix - Porcelanato',           'unidad' => 'kg'],
                ['descripcion' => 'Quimtia Joint - Junta cerámica',                'unidad' => 'kg'],
                ['descripcion' => 'Quimtia Hidro - Impermeabilizante',             'unidad' => 'kg'],
            ],
            'Tecno Fast' => [
                ['descripcion' => 'Panel SIP Tecno Fast 100mm',                    'unidad' => 'm2'],
                ['descripcion' => 'Panel SIP Tecno Fast 150mm',                    'unidad' => 'm2'],
                ['descripcion' => 'Conector Metálico SIP Tecno Fast',              'unidad' => 'unidad'],
                ['descripcion' => 'Aislante Relleno SIP Tecno Fast 50mm',         'unidad' => 'm2'],
            ],
            'Ternium' => [
                ['descripcion' => 'Chapa Galvanizada Canaleta Ternium',            'unidad' => 'm'],
                ['descripcion' => 'Chapa Ondulada Galvanizada Ternium',            'unidad' => 'm2'],
                ['descripcion' => 'Perfil C 120mm Ternium',                        'unidad' => 'm'],
                ['descripcion' => 'Perfil Z 200mm Ternium - Correa',               'unidad' => 'm'],
            ],
            'Gerdau' => [
                ['descripcion' => 'Barra Acero Construcción Gerdau 12mm',          'unidad' => 'm'],
                ['descripcion' => 'Barra Acero Construcción Gerdau 16mm',          'unidad' => 'm'],
                ['descripcion' => 'Malla Sima Gerdau 15x15 D4.2',                 'unidad' => 'm2'],
                ['descripcion' => 'Acero Pretensado Gerdau 12.5mm',                'unidad' => 'm'],
            ],
            'Acindar' => [
                ['descripcion' => 'Hierro Redondo Liso Acindar 10mm',              'unidad' => 'm'],
                ['descripcion' => 'Hierro Redondo Liso Acindar 12mm',              'unidad' => 'm'],
                ['descripcion' => 'Hierro Cuadrado Acindar 12mm',                  'unidad' => 'm'],
                ['descripcion' => 'Malla Electrosoldada Acindar 15x15',            'unidad' => 'm2'],
            ],
            'ArcelorMittal' => [
                ['descripcion' => 'Chapa Decapada ArcelorMittal 2mm',              'unidad' => 'm2'],
                ['descripcion' => 'Chapa Galvanizada ArcelorMittal 1.5mm',         'unidad' => 'm2'],
                ['descripcion' => 'Perfil IPN 100mm ArcelorMittal',                'unidad' => 'm'],
                ['descripcion' => 'Perfil UPN 80mm ArcelorMittal',                 'unidad' => 'm'],
            ],
            'Peri' => [
                ['descripcion' => 'Panel Encofrado TRIO Peri 72x270',              'unidad' => 'unidad'],
                ['descripcion' => 'Puntal Regulable RS 300 Peri',                  'unidad' => 'unidad'],
                ['descripcion' => 'Viga GT24 Peri 3.9m',                           'unidad' => 'unidad'],
                ['descripcion' => 'Traba TRIO Peri - Accesorio encofrado',         'unidad' => 'unidad'],
            ],
            'Doka' => [
                ['descripcion' => 'Panel Frami Doka 0.75x1.5m',                    'unidad' => 'unidad'],
                ['descripcion' => 'Puntal Aluminio Eurex 20 250 Doka',             'unidad' => 'unidad'],
                ['descripcion' => 'Viga H20 Doka 2.65m',                           'unidad' => 'unidad'],
                ['descripcion' => 'Llave de Encofrado Doka',                       'unidad' => 'unidad'],
            ],
            'Ulma' => [
                ['descripcion' => 'Panel Geopanel Ulma - Encofrado modular',       'unidad' => 'unidad'],
                ['descripcion' => 'Puntal Metálico Regulable Ulma 3m',             'unidad' => 'unidad'],
                ['descripcion' => 'Viga Madera Encofrado Ulma 3m',                 'unidad' => 'unidad'],
                ['descripcion' => 'Esquinero Metálico Ulma',                       'unidad' => 'unidad'],
            ],
            'Alsina' => [
                ['descripcion' => 'Panel Alsina 120x270 - Encofrado',              'unidad' => 'unidad'],
                ['descripcion' => 'Conector Clip Alsina',                          'unidad' => 'unidad'],
                ['descripcion' => 'Puntal Telescópico Alsina',                     'unidad' => 'unidad'],
                ['descripcion' => 'Ménsula Trepante Alsina',                       'unidad' => 'unidad'],
            ],
            'Entrecanales' => [
                ['descripcion' => 'Hormigón Elaborado H-30 Entrecanales',          'unidad' => 'm3'],
                ['descripcion' => 'Mortero Seco M5 Entrecanales',                  'unidad' => 'kg'],
                ['descripcion' => 'Arena Lavada Fina Entrecanales',                'unidad' => 'm3'],
                ['descripcion' => 'Piedra Partida 6/20 Entrecanales',              'unidad' => 'm3'],
            ],
            'Leroy Merlin' => [
                ['descripcion' => 'Pintura Látex Interior Leroy Merlin',           'unidad' => 'L'],
                ['descripcion' => 'Pintura Látex Exterior Leroy Merlin',           'unidad' => 'L'],
                ['descripcion' => 'Barniz Marino Leroy Merlin',                    'unidad' => 'L'],
                ['descripcion' => 'Esmalte Sintético Leroy Merlin',                'unidad' => 'L'],
            ],
        ];

        foreach ($insumos as $marcaNombre => $items) {
            $marcaId = $marcas[$marcaNombre] ?? null;
            if (!$marcaId) {
                $this->command->warn("Marca no encontrada: {$marcaNombre}");
                continue;
            }

            foreach ($items as $item) {
                Insumo::firstOrCreate(
                    ['descripcion' => $item['descripcion']],
                    [
                        'marca_id'         => $marcaId,
                        'unidad_medida_id' => $um[$item['unidad']],
                        'usuario_id'       => 1,
                        'estado_id'        => 1,
                        'fecha'            => now()->toDateString(),
                    ]
                );
            }
        }
    }
}
