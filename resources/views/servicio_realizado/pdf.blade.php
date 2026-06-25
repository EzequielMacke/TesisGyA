<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Servicio Realizado Nro {{ $servicioRealizado->id }}</title>
<style>
    @font-face {
        font-family: 'EB Garamond';
        font-style: normal;
        font-weight: normal;
        src: url('{{ str_replace('\\', '/', resource_path('fonts/EBGaramond-Regular.ttf')) }}') format('truetype');
    }
    @font-face {
        font-family: 'EB Garamond';
        font-style: normal;
        font-weight: bold;
        src: url('{{ str_replace('\\', '/', resource_path('fonts/EBGaramond-Regular.ttf')) }}') format('truetype');
    }
    @font-face {
        font-family: 'EB Garamond';
        font-style: italic;
        font-weight: normal;
        src: url('{{ str_replace('\\', '/', resource_path('fonts/EBGaramond-Italic.ttf')) }}') format('truetype');
    }
    @font-face {
        font-family: 'EB Garamond';
        font-style: italic;
        font-weight: bold;
        src: url('{{ str_replace('\\', '/', resource_path('fonts/EBGaramond-Italic.ttf')) }}') format('truetype');
    }
    @page {
        margin: 80px 55px 60px 55px;
    }
    body {
        font-family: 'EB Garamond', serif;
        font-size: 12.5px;
        color: #000;
        line-height: 1.55;
    }
    .header {
        text-align: center;
        margin-bottom: 4px;
        padding-bottom: 10px;
        border-bottom: 2px solid #000;
    }
    .header .empresa {
        font-size: 17px;
        font-weight: bold;
        color: #000;
        letter-spacing: 0.5px;
    }
    .header .subtitulo {
        font-size: 12px;
        color: #000;
        margin-top: 2px;
    }
    table.meta {
        width: 100%;
        margin: 10px 0 18px 0;
        font-size: 10.5px;
    }
    table.meta td {
        padding: 2px 4px;
        color: #000;
    }
    h2 {
        font-size: 14px;
        color: #000;
        border-bottom: 1px solid #000;
        padding-bottom: 4px;
        margin-top: 20px;
        margin-bottom: 9px;
    }
    p {
        margin: 0 0 9px 0;
        text-align: justify;
        color: #000;
    }
    table.datos {
        width: 100%;
        border-collapse: collapse;
        margin: 6px 0 4px 0;
    }
    table.datos th,
    table.datos td {
        border: 1px solid #000;
        padding: 5px 7px;
        font-size: 10.5px;
        text-align: left;
        vertical-align: top;
        color: #000;
    }
    table.datos th {
        background: #f1f5f9;
        color: #000;
        text-transform: uppercase;
        font-size: 9.5px;
        letter-spacing: 0.3px;
    }
    .text-end { text-align: right; }
    .text-center { text-align: center; }
    .caption {
        text-align: center;
        font-size: 10px;
        font-style: italic;
        color: #000;
        margin: 0 0 16px 0;
    }
    .figure {
        text-align: center;
        margin: 12px 0;
    }
    .figure img {
        max-width: 360px;
        max-height: 250px;
        border: 1px solid #000;
        padding: 3px;
        background: #fff;
    }
    .obs-box {
        border: 1px solid #000;
        background: #f8fafc;
        padding: 10px 12px;
        margin-top: 6px;
        min-height: 16px;
        color: #000;
    }
    .firma {
        margin-top: 50px;
        width: 100%;
    }
    .firma td {
        text-align: center;
        font-size: 10.5px;
        color: #000;
        padding-top: 35px;
        border-top: 1px solid #000;
        width: 50%;
    }
    .cierre {
        margin-top: 22px;
        font-size: 10.5px;
        color: #000;
        text-align: center;
    }
</style>
</head>
<body>

<div class="header">
    <div class="empresa">GAVILAN Y ASOCIADOS S.A</div>
    <div class="subtitulo">Informe de Servicio Realizado</div>
</div>

<table class="meta">
    <tr>
        <td><strong>Servicio Realizado Nro:</strong> {{ $servicioRealizado->id }}</td>
        <td><strong>Estado:</strong> {{ $servicioRealizado->estado->descripcion ?? '-' }}</td>
        <td><strong>Fecha de Registro:</strong> {{ $servicioRealizado->fecha_registro ? $servicioRealizado->fecha_registro->format('d/m/Y') : '-' }}</td>
        <td><strong>Registrado por:</strong> {{ $servicioRealizado->usuario->usuario ?? '-' }}</td>
    </tr>
</table>

@php
    $fotoNro = 0;
    $planoNro = 0;
    $tablaNro = 0;
    $formatGs = fn ($v) => 'Gs. ' . number_format((float) $v, 0, ',', '.');
    $c = $datosOrden['contrato'];
@endphp

<h2>1. Introducción</h2>
<p>
    En fecha {{ $datosOrden['solicitud_servicio']['fecha'] }}, {{ $datosOrden['solicitud_servicio']['usuario'] }} registró
    la Solicitud de Servicio Nro {{ $datosOrden['solicitud_servicio']['id'] ?? '-' }} en representación de
    <strong>{{ $servicioRealizado->cliente->razon_social ?? '-' }}</strong>, solicitando la realización de los siguientes
    servicios: {{ count($datosOrden['solicitud_servicio']['servicios']) > 0 ? implode(', ', $datosOrden['solicitud_servicio']['servicios']->all()) : 'sin servicios especificados' }},
    para la obra "{{ $servicioRealizado->obra->descripcion ?? '-' }}"{{ ($servicioRealizado->obra->ubicacion ?? null) ? ', ubicada en ' . $servicioRealizado->obra->ubicacion : '' }}.
</p>

<h2>2. Visita Previa</h2>
<p>
    En fecha {{ $datosOrden['visita_previa']['fecha_visita'] }}, {{ $datosOrden['visita_previa']['usuario'] }} realizó la
    visita previa correspondiente (Visita Previa Nro {{ $datosOrden['visita_previa']['id'] ?? '-' }}) a la obra, relevando
    las condiciones del sitio. A continuación se presenta el registro fotográfico y los planos obtenidos durante dicha visita.
</p>

@forelse($datosOrden['visita_previa']['fotos'] as $foto)
    @php $fotoNro++; @endphp
    <div class="figure">
        @if($foto['src'])
            <img src="{{ $foto['src'] }}">
        @endif
    </div>
    <div class="caption">Fotografía {{ $fotoNro }} — Visita Previa ({{ $foto['fecha'] }})</div>
@empty
    <p>No se registraron fotografías durante la visita previa.</p>
@endforelse

@foreach($datosOrden['visita_previa']['planos'] as $plano)
    @php $planoNro++; @endphp
    @if($plano['es_pdf'])
        <div class="caption">Plano {{ $planoNro }} — Visita Previa: documento PDF adjunto (no se incluye vista previa).</div>
    @else
        <div class="figure">
            @if($plano['src'])
                <img src="{{ $plano['src'] }}">
            @endif
        </div>
        <div class="caption">Plano {{ $planoNro }} — Visita Previa ({{ $plano['fecha'] }})</div>
    @endif
@endforeach

<h2>3. Presupuesto de Servicio</h2>
<p>
    El Presupuesto de Servicio Nro {{ $datosOrden['presupuesto']['numero_presupuesto'] }}, de fecha
    {{ $datosOrden['presupuesto']['fecha'] }}, fue elaborado por {{ $datosOrden['presupuesto']['usuario'] }}, con una
    validez de {{ $datosOrden['presupuesto']['validez'] }} días y un anticipo del {{ $datosOrden['presupuesto']['anticipo'] }}%.
    El detalle de los servicios presupuestados se presenta en la Tabla {{ $tablaNro + 1 }}.
</p>

@php $tablaNro++; $tablaPresupuestoNro = $tablaNro; @endphp
<table class="datos">
    <thead>
        <tr>
            <th>Servicio</th>
            <th>Ensayo</th>
            <th class="text-end">Precio Unit.</th>
            <th class="text-center">Cant.</th>
            <th>Impuesto</th>
            <th class="text-end">IVA</th>
            <th class="text-end">Subtotal</th>
        </tr>
    </thead>
    <tbody>
    @forelse($datosOrden['presupuesto']['servicios'] as $servicioData)
        @foreach($servicioData['ensayos'] as $ensayo)
            <tr>
                <td>{{ $servicioData['servicio'] }}</td>
                <td>{{ $ensayo['descripcion'] }}</td>
                <td class="text-end">{{ $formatGs($ensayo['precio_unitario']) }}</td>
                <td class="text-center">{{ $ensayo['cantidad'] }}</td>
                <td>{{ $ensayo['impuesto'] }}</td>
                <td class="text-end">{{ $formatGs($ensayo['iva']) }}</td>
                <td class="text-end">{{ $formatGs($ensayo['subtotal']) }}</td>
            </tr>
        @endforeach
    @empty
        <tr><td colspan="7" class="text-center">Sin detalles de presupuesto.</td></tr>
    @endforelse
    </tbody>
</table>
<div class="caption">Tabla {{ $tablaPresupuestoNro }} — Detalle del Presupuesto de Servicio</div>

<p>
    El total general del presupuesto asciende a {{ $formatGs($datosOrden['presupuesto']['total_general']) }}, compuesto
    por {{ $formatGs($datosOrden['presupuesto']['total_servicios']) }} en concepto de servicios y
    {{ $formatGs($datosOrden['presupuesto']['total_impuestos']) }} en impuestos. El monto correspondiente al anticipo del
    {{ $datosOrden['presupuesto']['anticipo'] }}% equivale a {{ $formatGs($datosOrden['presupuesto']['monto_anticipo']) }}.
</p>

<h2>4. Contrato</h2>
@if($c['id'])
    @php
        $tablaNro++;
        $tablaContratoNro = $tablaNro;
        $montoContrato = (float) $c['monto'];
        $montoEtapa = fn ($pct) => $pct !== null ? $formatGs($montoContrato * (float) $pct / 100) : '-';
    @endphp
    <p>
        Con fecha {{ $c['fecha_firma'] }}, las partes suscribieron el Contrato de Prestación de Servicios Nro
        {{ str_pad($c['id'], 3, '0', STR_PAD_LEFT) }}, por un monto total de {{ $formatGs($c['monto']) }}, con un plazo de
        ejecución de {{ $c['plazo_dias'] ?? '-' }} días y una garantía de {{ $c['garantia_meses'] ?? '-' }} meses sobre los
        trabajos realizados. Las condiciones de pago acordadas se detallan en la Tabla {{ $tablaContratoNro }}.
    </p>
    <table class="datos">
        <thead>
            <tr><th>Etapa</th><th class="text-center">Porcentaje</th><th class="text-end">Monto</th></tr>
        </thead>
        <tbody>
            <tr><td>Anticipo</td><td class="text-center">{{ $c['anticipo'] ?? '-' }}%</td><td class="text-end">{{ $montoEtapa($c['anticipo']) }}</td></tr>
            <tr><td>Mitad de Obra</td><td class="text-center">{{ $c['pago_mitad'] ?? '-' }}%</td><td class="text-end">{{ $montoEtapa($c['pago_mitad']) }}</td></tr>
            <tr><td>Pago Final</td><td class="text-center">{{ $c['pago_final'] ?? '-' }}%</td><td class="text-end">{{ $montoEtapa($c['pago_final']) }}</td></tr>
        </tbody>
    </table>
    <div class="caption">Tabla {{ $tablaContratoNro }} — Condiciones de Pago del Contrato</div>
@else
    <p>No se registró un contrato asociado a esta orden de servicio.</p>
@endif

<h2>5. Orden de Servicio</h2>
<p>
    Los trabajos descriptos en el presente informe corresponden a la Orden de Servicio Nro
    {{ $servicioRealizado->ordenServicio->nro ?? '-' }}, emitida para el cliente
    {{ $servicioRealizado->cliente->razon_social ?? '-' }} en la obra "{{ $servicioRealizado->obra->descripcion ?? '-' }}".
</p>

<h2>6. Insumos Utilizados</h2>
@if(count($datosOrden['insumos_utilizados']) > 0)
    @php $tablaNro++; $tablaInsumosNro = $tablaNro; @endphp
    <p>Durante la ejecución del servicio se utilizaron los insumos confirmados que se detallan en la Tabla {{ $tablaInsumosNro }}.</p>
    <table class="datos">
        <thead>
            <tr><th>Nro</th><th>Insumo</th><th>Marca</th><th class="text-center">Cantidad</th><th>Unidad</th><th>Usuario</th></tr>
        </thead>
        <tbody>
        @foreach($datosOrden['insumos_utilizados'] as $insumo)
            @forelse($insumo['detalles'] as $detalle)
                <tr>
                    <td>{{ $insumo['nro'] }}</td>
                    <td>{{ $detalle['descripcion'] }}</td>
                    <td>{{ $detalle['marca'] }}</td>
                    <td class="text-center">{{ $detalle['cantidad'] }}</td>
                    <td>{{ $detalle['unidad'] }}</td>
                    <td>{{ $insumo['usuario'] }}</td>
                </tr>
            @empty
                <tr><td>{{ $insumo['nro'] }}</td><td colspan="5">Sin detalle.</td></tr>
            @endforelse
        @endforeach
        </tbody>
    </table>
    <div class="caption">Tabla {{ $tablaInsumosNro }} — Insumos Utilizados</div>
@else
    <p>No se registraron insumos utilizados confirmados para esta orden de servicio.</p>
@endif

<h2>7. Servicios y Ensayos Realizados</h2>
@if(count($datosOrden['servicios']) > 0)
    @php $tablaNro++; $tablaServiciosNro = $tablaNro; @endphp
    <p>Los ensayos efectivamente realizados en el marco de este servicio se presentan en la Tabla {{ $tablaServiciosNro }}.</p>
    <table class="datos">
        <thead><tr><th>Servicio</th><th>Ensayo</th><th class="text-center">Cantidad</th></tr></thead>
        <tbody>
        @foreach($datosOrden['servicios'] as $servicioData)
            @foreach($servicioData['ensayos'] as $ensayo)
                <tr>
                    <td>{{ $servicioData['servicio'] }}</td>
                    <td>{{ $ensayo['descripcion'] }}</td>
                    <td class="text-center">{{ $ensayo['cantidad'] }}</td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
    <div class="caption">Tabla {{ $tablaServiciosNro }} — Servicios y Ensayos Realizados</div>
@else
    <p>No se registraron servicios en el presupuesto asociado.</p>
@endif

<h2>8. Funcionarios Asignados</h2>
@if(count($datosOrden['funcionarios']) > 0)
    @php $tablaNro++; $tablaFuncionariosNro = $tablaNro; @endphp
    <p>La ejecución de los trabajos estuvo a cargo de los funcionarios detallados en la Tabla {{ $tablaFuncionariosNro }}.</p>
    <table class="datos">
        <thead><tr><th>Nombre</th><th>Cargo</th><th>CI</th><th>Teléfono</th><th>Fecha de Ingreso</th></tr></thead>
        <tbody>
        @foreach($datosOrden['funcionarios'] as $f)
            <tr>
                <td>{{ $f['nombre'] }}</td>
                <td>{{ $f['cargo'] }}</td>
                <td>{{ $f['ci'] }}</td>
                <td>{{ $f['telefono'] }}</td>
                <td>{{ $f['fecha_ingreso'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="caption">Tabla {{ $tablaFuncionariosNro }} — Funcionarios Asignados</div>
@else
    <p>No se registraron funcionarios asignados a esta orden de servicio.</p>
@endif

<h2>9. Evidencia Fotográfica del Servicio Realizado</h2>
@if(count($fotosServicio) > 0 || count($planosServicio) > 0)
    <p>Como evidencia de la ejecución del servicio, se registraron las siguientes fotografías y planos.</p>
    @foreach($fotosServicio as $foto)
        @php $fotoNro++; @endphp
        <div class="figure">
            @if($foto['src'])
                <img src="{{ $foto['src'] }}">
            @endif
        </div>
        <div class="caption">Fotografía {{ $fotoNro }} — Servicio Realizado</div>
    @endforeach
    @foreach($planosServicio as $plano)
        @php $planoNro++; @endphp
        @if($plano['es_pdf'])
            <div class="caption">Plano {{ $planoNro }} — Servicio Realizado: documento PDF adjunto ({{ $plano['nombre'] }}).</div>
        @else
            <div class="figure">
                @if($plano['src'])
                    <img src="{{ $plano['src'] }}">
                @endif
            </div>
            <div class="caption">Plano {{ $planoNro }} — Servicio Realizado</div>
        @endif
    @endforeach
@else
    <p>No se registraron fotografías ni planos adicionales para este servicio realizado.</p>
@endif

<h2>10. Observación</h2>
<div class="obs-box">
    {{ $servicioRealizado->observacion ?: 'No se registraron observaciones adicionales.' }}
</div>

<table class="firma">
    <tr>
        <td>Responsable Técnico</td>
        <td>GAVILAN Y ASOCIADOS S.A</td>
    </tr>
</table>

<div class="cierre">
    Informe generado automáticamente el {{ $generadoEn }} — GAVILAN Y ASOCIADOS S.A
</div>

</body>
</html>
