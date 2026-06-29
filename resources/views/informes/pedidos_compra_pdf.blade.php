<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Informe de Pedidos de Compra</title>
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
    <div class="subtitulo">Informe de Pedidos de Compra</div>
</div>

<table class="meta">
    <tr>
        <td><strong>Depósito:</strong> {{ $filtros['deposito'] ?? 'Todos' }}</td>
        <td><strong>Estado:</strong> {{ $filtros['estado'] ?? 'Todos' }}</td>
        <td><strong>Desde:</strong> {{ $filtros['fecha_desde'] ?? '-' }}</td>
        <td><strong>Hasta:</strong> {{ $filtros['fecha_hasta'] ?? '-' }}</td>
    </tr>
</table>

<table class="datos">
    <thead>
        <tr>
            <th style="width:35px;">Nro</th>
            <th class="text-center" style="width:70px;">Fecha</th>
            <th>Depósito</th>
            <th>Sucursal</th>
            <th>Usuario</th>
            <th style="width:75px;">Estado</th>
            <th class="text-end" style="width:60px;">Ítems</th>
            <th class="text-end" style="width:70px;">Cant. Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pedidos as $pedido)
            <tr>
                <td class="text-center">{{ $pedido->id }}</td>
                <td class="text-center">{{ $pedido->fecha ? $pedido->fecha->format('d/m/Y') : '-' }}</td>
                <td>{{ $pedido->deposito->descripcion ?? '-' }}</td>
                <td>{{ $pedido->sucursal->descripcion ?? '-' }}</td>
                <td>{{ $pedido->usuario->name ?? '-' }}</td>
                <td>{{ $pedido->estado->descripcion ?? '-' }}</td>
                <td class="text-end">{{ $pedido->cant_items }}</td>
                <td class="text-end">{{ number_format((float) $pedido->cant_total, 2, ',', '.') }}</td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center">No se encontraron pedidos con los filtros seleccionados.</td></tr>
        @endforelse
    </tbody>
    @if($pedidos->count() > 0)
        <tfoot>
            <tr class="total-row">
                <td colspan="6" class="text-end">Totales</td>
                <td class="text-end">{{ $totalItems }}</td>
                <td class="text-end">{{ number_format((float) $totalCantidad, 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    @endif
</table>

<div class="cierre">
    Informe generado automáticamente el {{ $generadoEn }} — GAVILAN Y ASOCIADOS S.A
</div>

</body>
</html>
