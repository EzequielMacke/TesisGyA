<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Contrato</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-file-contract"></i> Contrato #{{ $contrato->id }}</h2>
                    <small>{{ $contrato->cliente->razon_social ?? 'Cliente' }} — {{ $contrato->obra->descripcion ?? 'Obra' }}</small>
                </div>
                <div class="header-actions">
                    @switch($contrato->estado_id)
                        @case(3)
                            <span class="estado estado-pendiente"><i class="estado-dot"></i>Pendiente</span>
                            @break
                        @case(4)
                            <span class="estado estado-confirmado"><i class="estado-dot"></i>Confirmado</span>
                            @break
                        @case(5)
                            <span class="estado estado-anulado"><i class="estado-dot"></i>Anulado</span>
                            @break
                        @default
                            <span class="estado"><i class="estado-dot"></i>{{ $contrato->estado->descripcion ?? '-' }}</span>
                    @endswitch
                    <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                    <a href="{{ route('contrato.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <div class="card">
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
                        <p>EL CONTRATANTE abonará a EL PRESTADOR la suma de <span id="monto-letras"></span> (₲ {{ number_format($contrato->monto, 0, ',', '.') }}), en concepto de pago total por los servicios.</p>
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

<style>
.content-wrapper {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* ── Cabecera ── */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e2e8f0;
}
.page-header h2 { margin: 0; font-size: 1.25rem; font-weight: 600; color: #1e293b; }
.page-header h2 i { color: #94a3b8; margin-right: 0.4rem; }
.page-header small { color: #94a3b8; font-size: 0.8rem; }
.header-actions { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

@media (max-width: 900px) {
    .page-header { flex-direction: column; align-items: flex-start; }
}

/* ── Cards ── */
.card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: none;
}

/* ── Estado ── */
.estado { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; color: #374151; }
.estado-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #94a3b8; flex-shrink: 0; }
.estado-pendiente .estado-dot  { background: #f59e0b; }
.estado-confirmado .estado-dot { background: #10b981; }
.estado-anulado .estado-dot    { background: #ef4444; }

/* ── Documento del contrato ── */
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

/* ── Impresión ── */
@media print {
    .main-content { margin-left: 0 !important; width: 100% !important; }
    .header-actions .btn { display: none !important; }
    .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
}
</style>
