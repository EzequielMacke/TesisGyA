<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Contrato</title>
    @include('partials.head')
    <style>
        .main-content {
            margin-left: 60px;
            min-height: 100vh;
            background-color: #f8f9fa;
            transition: margin-left 0.3s cubic-bezier(.4,2,.6,1);
            overflow-x: hidden;
            box-sizing: border-box;
            width: auto;
            max-width: 100vw;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 50px;
            }
        }
        .sidebar-nav {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 60px;
            transition: width 0.3s cubic-bezier(.4,2,.6,1);
            overflow-x: hidden;
            z-index: 10000;
        }
        .sidebar-nav:hover {
            width: 280px;
            box-shadow: 2px 0 16px rgba(0,0,0,0.07);
        }
        .sidebar-nav:hover ~ .main-content {
            margin-left: 280px;
        }
        @media (max-width: 768px) {
            .sidebar-nav:hover {
                width: 250px;
            }
            .sidebar-nav:hover ~ .main-content {
                margin-left: 250px;
            }
        }
        .content-wrapper {
            padding: 20px;
            max-width: 100%;
            box-sizing: border-box;
        }
        .contrato-text {
            font-family: 'Times New Roman', serif;
            line-height: 1.6;
            text-align: justify;
        }
        .contrato-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .contrato-section {
            margin-bottom: 15px;
        }
        .contrato-clause {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary"><i class="fas fa-file-contract me-2"></i>Contrato #{{ $contrato->id }}</h2>
                <div>
                    <a href="{{ route('contrato.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body contrato-text">
                    <div class="contrato-title">
                        CONTRATO DE PRESTACIÓN DE SERVICIOS
                    </div>

                    <p><strong>Entre:</strong></p>
                    <p>{{ $contrato->cliente->razon_social ?? 'Cliente' }}, con domicilio en {{ $contrato->cliente->direccion ?? 'Dirección no especificada' }}, identificado con {{ $contrato->cliente->ruc ?? 'RUC no especificado' }}, en adelante denominado "EL CONTRATANTE".</p>

                    <p><strong>Y:</strong></p>
                    <p>GAVILAN Y ASOCIADOS S.A, con domicilio en Soldado Ovelar Casi Asuncion 1912, identificado con RUC 800.123.456-78, en adelante denominado "EL PRESTADOR".</p>

                    <p><strong>MANIFIESTAN</strong></p>
                    <p>Que EL CONTRATANTE desea contratar los servicios profesionales de EL PRESTADOR para la evaluación, reparación y/o refuerzo de estructuras de hormigón, en adelante denominados “los servicios”, conforme a los términos y condiciones que se detallan a continuación:</p>

                    <hr>

                    <div class="contrato-section">
                        <p class="contrato-clause">CLÁUSULAS</p>
                    </div>

                    <div class="contrato-section">
                        <p class="contrato-clause">PRIMERA – OBJETO</p>
                        <p>EL PRESTADOR se obliga a prestar los servicios de evaluación estructural, diagnóstico de daños, reparación y refuerzo de estructuras de hormigón armado, conforme a las especificaciones técnicas acordadas por ambas partes y según las normas vigentes aplicables.</p>
                    </div>

                    <div class="contrato-section">
                        <p class="contrato-clause">SEGUNDA – ALCANCE DE LOS SERVICIOS</p>
                        <p>Los trabajos podrán incluir, entre otros:</p>
                        <ul>
                            <li>Inspección visual y técnica de estructuras.</li>
                            <li>Ensayos no destructivos (si fueran necesarios).</li>
                            <li>Elaboración de informes técnicos.</li>
                            <li>Diseño de soluciones de reparación o refuerzo.</li>
                            <li>Ejecución de obras menores de reparación estructural.</li>
                            <li>Supervisión técnica de los trabajos realizados.</li>
                        </ul>
                        <p>El alcance final será detallado en un Anexo Técnico firmado por ambas partes, el cual formará parte integral del presente contrato.</p>
                    </div>

                    <div class="contrato-section">
                        <p class="contrato-clause">TERCERA – PLAZO DE EJECUCIÓN</p>
                        <p>Los trabajos tendrán un plazo estimado de ejecución de {{ $contrato->plazo_dias }} días hábiles, a partir de la firma del contrato y/o de la entrega del anticipo, salvo causa de fuerza mayor debidamente justificada.</p>
                    </div>

                    <div class="contrato-section">
                        <p class="contrato-clause">CUARTA – HONORARIOS Y FORMA DE PAGO</p>
                        <p>EL CONTRATANTE abonará a EL PRESTADOR la suma de <span id="monto-letras"></span> (Gs {{ number_format($contrato->monto, 0, ',', '.') }}), en concepto de pago total por los servicios.</p>
                        <p>El pago se realizará de la siguiente forma:</p>
                        <ul>
                            <li>{{ $contrato->anticipo }}% como anticipo al momento de la firma del contrato.</li>
                            <li>{{ $contrato->pago_mitad }}% a la mitad del avance de obra.</li>
                            <li>{{ $contrato->pago_final }}% contra entrega del informe final o finalización de los trabajos.</li>
                        </ul>
                    </div>

                    <div class="contrato-section">
                        <p class="contrato-clause">QUINTA – OBLIGACIONES DEL PRESTADOR</p>
                        <ul>
                            <li>Ejecutar los trabajos con la mayor diligencia, profesionalismo y conforme a las normas técnicas aplicables.</li>
                            <li>Utilizar materiales adecuados y seguros cuando corresponda.</li>
                            <li>Cumplir con las normativas de seguridad laboral vigentes.</li>
                            <li>Informar al contratante sobre cualquier riesgo estructural relevante detectado.</li>
                        </ul>
                    </div>

                    <div class="contrato-section">
                        <p class="contrato-clause">SEXTA – OBLIGACIONES DEL CONTRATANTE</p>
                        <ul>
                            <li>Facilitar el acceso al lugar donde se realizarán los trabajos.</li>
                            <li>Proveer, cuando sea necesario, planos estructurales y datos técnicos del inmueble.</li>
                            <li>Realizar los pagos conforme al cronograma pactado.</li>
                        </ul>
                    </div>

                    <div class="contrato-section">
                        <p class="contrato-clause">SÉPTIMA – RESPONSABILIDAD Y GARANTÍA</p>
                        <p>EL PRESTADOR responderá por la correcta ejecución de los servicios, brindando una garantía de {{ $contrato->garantia_meses }} meses sobre las reparaciones realizadas, contados a partir de la fecha de finalización. Esta garantía no cubre daños ocasionados por terceros o causas externas.</p>
                    </div>

                    <div class="contrato-section">
                        <p class="contrato-clause">OCTAVA – CONFIDENCIALIDAD</p>
                        <p>Ambas partes se comprometen a mantener la confidencialidad de toda la información técnica y comercial intercambiada en virtud del presente contrato.</p>
                    </div>

                    <div class="contrato-section">
                        <p class="contrato-clause">NOVENA – TERMINACIÓN ANTICIPADA</p>
                        <p>El contrato podrá resolverse de forma anticipada por cualquiera de las partes en caso de incumplimiento de las obligaciones por la contraparte, previa notificación escrita con un plazo de 5 días hábiles.</p>
                    </div>

                    <div class="contrato-section">
                        <p class="contrato-clause">DÉCIMA – JURISDICCIÓN Y LEY APLICABLE</p>
                        <p>Para todas las controversias derivadas del presente contrato, las partes se someten a los tribunales ordinarios de la ciudad de {{ $contrato->ciudad }}, renunciando a cualquier otro fuero que pudiera corresponder, y se regirán por las leyes de la República del Paraguay.</p>
                    </div>

                    <p><strong>En fe de lo cual, las partes firman el presente contrato en la ciudad de {{ $contrato->ciudad }}, a los {{ $contrato->fecha_firma ? $contrato->fecha_firma->format('d') : '___' }} días del mes de {{ $contrato->fecha_firma ? $contrato->fecha_firma->format('m') : '___' }} de {{ $contrato->fecha_firma ? $contrato->fecha_firma->format('Y') : '___' }}.</strong></p>

                    <div class="row mt-5">
                        <div class="col-6 text-center">
                            <p>______________________________</p>
                            <p>EL CONTRATANTE</p>
                            <p>{{ $contrato->cliente->razon_social ?? 'Cliente' }}</p>
                        </div>
                        <div class="col-6 text-center">
                            <p>______________________________</p>
                            <p>EL PRESTADOR</p>
                            <p>GAVILAN Y ASOCIADOS S.A</p>
                        </div>
                    </div>

                    @if($contrato->observaciones)
                        <div class="contrato-section">
                            <p class="contrato-clause">OBSERVACIONES</p>
                            <p>{{ $contrato->observaciones }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <script>
        function numToLetras(numero) {
            numero = parseInt(numero);
            const unidades = ['', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve'];
            const decenas = ['', 'diez', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];
            const centenas = ['', 'ciento', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos'];

            if (numero === 0) return 'cero';
            if (numero === 100) return 'cien';

            let letras = '';

            let millones = Math.floor(numero / 1000000);
            numero %= 1000000;

            if (millones > 0) {
                letras += numToLetras(millones) + ' millón' + (millones > 1 ? 'es' : '') + ' ';
            }

            let miles = Math.floor(numero / 1000);
            numero %= 1000;

            if (miles > 0) {
                if (miles === 1) {
                    letras += 'mil ';
                } else {
                    letras += numToLetras(miles) + ' mil ';
                }
            }

            let centena = Math.floor(numero / 100);
            numero %= 100;

            if (centena > 0) {
                letras += centenas[centena] + ' ';
            }

            let decena = Math.floor(numero / 10);
            let unidad = numero % 10;

            if (decena > 0) {
                if (decena === 1) {
                    const especiales = ['', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciseis', 'diecisiete', 'dieciocho', 'diecinueve'];
                    letras += especiales[unidad];
                    return letras.trim();
                } else {
                    letras += decenas[decena];
                    if (unidad > 0) {
                        letras += ' y ' + unidades[unidad];
                    }
                }
            } else {
                if (unidad > 0) {
                    letras += unidades[unidad];
                }
            }

            return letras.trim();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const monto = {{ $contrato->monto }};
            document.getElementById('monto-letras').innerText = numToLetras(monto);
        });
    </script>
</body>
</html>
