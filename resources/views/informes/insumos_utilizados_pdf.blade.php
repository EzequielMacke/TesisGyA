<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Informe de Insumos Utilizados</title>
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
    <div class="subtitulo">Informe de Insumos Utilizados</div>
</div>

<table class="meta">
    <tr>
        <td><strong>Obra:</strong> {{ $filtros['obra'] ?? 'Todas' }}</td>
        <td><strong>Estado:</strong> {{ $filtros['estado'] ?? 'Todos' }}</td>
        <td><strong>Desde:</strong> {{ $filtros['fecha_desde'] ?? '-' }}</td>
        <td><strong>Hasta:</strong> {{ $filtros['fecha_hasta'] ?? '-' }}</td>
    </tr>
</table>

<table class="datos">
    <thead>
        <tr>
            <th style="width:35px;">Nro</th>
            <th class="text-center" style="width:55px;">Orden Serv.</th>
            <th>Obra</th>
            <th>Insumo</th>
            <th class="text-end" style="width:65px;">Cantidad</th>
            <th style="width:60px;">Unidad</th>
            <th class="text-center" style="width:65px;">F. Registro</th>
            <th style="width:70px;">Estado</th>
        </tr>
    </thead>
    <tbody>
        @forelse($detalles as $detalle)
            <tr>
                <td class="text-center">{{ $detalle->id }}</td>
                <td class="text-center">{{ $detalle->insumoUtilizado->orden_servicio_id ? '#' . $detalle->insumoUtilizado->orden_servicio_id : '-' }}</td>
                <td>{{ $detalle->insumoUtilizado->obra->descripcion ?? '-' }}</td>
                <td>{{ $detalle->insumo->descripcion ?? '-' }}</td>
                <td class="text-end">{{ number_format((float) $detalle->cantidad, 2, ',', '.') }}</td>
                <td>{{ $detalle->insumo->unidadMedida->descripcion ?? '-' }}</td>
                <td class="text-center">{{ $detalle->insumoUtilizado->fecha_registro ? $detalle->insumoUtilizado->fecha_registro->format('d/m/Y') : '-' }}</td>
                <td>{{ $detalle->insumoUtilizado->estado->descripcion ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center">No se encontraron insumos utilizados con los filtros seleccionados.</td></tr>
        @endforelse
    </tbody>
    @if($detalles->count() > 0)
        <tfoot>
            <tr class="total-row">
                <td colspan="8">Total de Ítems: {{ $totalItems }}</td>
            </tr>
        </tfoot>
    @endif
</table>

<div class="cierre">
    Informe generado automáticamente el {{ $generadoEn }} — GAVILAN Y ASOCIADOS S.A
</div>

</body>
</html>
