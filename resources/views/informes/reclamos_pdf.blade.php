<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Informe de Reclamos</title>
<style>
    @page {
        margin: 70px 45px 60px 45px;
    }
    body {
        font-family: 'Helvetica', sans-serif;
        font-size: 11px;
        color: #000;
        line-height: 1.45;
    }
    .header {
        text-align: center;
        margin-bottom: 4px;
        padding-bottom: 10px;
        border-bottom: 2px solid #000;
    }
    .header .empresa {
        font-size: 16px;
        font-weight: bold;
        letter-spacing: 0.5px;
    }
    .header .subtitulo {
        font-size: 12px;
        margin-top: 2px;
    }
    table.meta {
        width: 100%;
        margin: 10px 0 16px 0;
        font-size: 10px;
    }
    table.meta td {
        padding: 2px 4px;
    }
    table.datos {
        width: 100%;
        border-collapse: collapse;
        margin: 6px 0 4px 0;
    }
    table.datos th,
    table.datos td {
        border: 1px solid #000;
        padding: 5px 6px;
        font-size: 9.5px;
        text-align: left;
        vertical-align: top;
    }
    table.datos th {
        background: #f1f5f9;
        text-transform: uppercase;
        font-size: 8.5px;
        letter-spacing: 0.3px;
    }
    .text-end { text-align: right; }
    .text-center { text-align: center; }
    .total-row td {
        font-weight: bold;
        background: #f8fafc;
    }
    .cierre {
        margin-top: 18px;
        font-size: 9.5px;
        text-align: center;
    }
</style>
</head>
<body>

<div class="header">
    <div class="empresa">GAVILAN Y ASOCIADOS S.A</div>
    <div class="subtitulo">Informe de Reclamos</div>
</div>

<table class="meta">
    <tr>
        <td><strong>Cliente:</strong> {{ $filtros['cliente'] ?? 'Todos' }}</td>
        <td><strong>Estado:</strong> {{ $filtros['estado'] ?? 'Todos' }}</td>
        <td><strong>Desde:</strong> {{ $filtros['fecha_desde'] ?? '-' }}</td>
        <td><strong>Hasta:</strong> {{ $filtros['fecha_hasta'] ?? '-' }}</td>
    </tr>
</table>

<table class="datos">
    <thead>
        <tr>
            <th style="width:30px;">Nro</th>
            <th>Cliente</th>
            <th>Obra</th>
            <th>Usuario</th>
            <th class="text-center" style="width:65px;">F. Registro</th>
            <th style="width:70px;">Estado</th>
            <th>Observación</th>
        </tr>
    </thead>
    <tbody>
        @forelse($reclamos as $reclamo)
            <tr>
                <td class="text-center">{{ $reclamo->id }}</td>
                <td>{{ $reclamo->cliente->razon_social ?? '-' }}</td>
                <td>{{ $reclamo->obra->descripcion ?? '-' }}</td>
                <td>{{ $reclamo->usuario->name ?? '-' }}</td>
                <td class="text-center">{{ $reclamo->fecha_registro ? $reclamo->fecha_registro->format('d/m/Y') : '-' }}</td>
                <td>{{ $reclamo->estado->descripcion ?? '-' }}</td>
                <td>{{ $reclamo->observacion ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center">No se encontraron reclamos con los filtros seleccionados.</td></tr>
        @endforelse
    </tbody>
    @if($reclamos->count() > 0)
        <tfoot>
            <tr class="total-row">
                <td colspan="7">Total de Reclamos: {{ $reclamos->count() }}</td>
            </tr>
        </tfoot>
    @endif
</table>

<div class="cierre">
    Informe generado automáticamente el {{ $generadoEn }} — GAVILAN Y ASOCIADOS S.A
</div>

</body>
</html>
