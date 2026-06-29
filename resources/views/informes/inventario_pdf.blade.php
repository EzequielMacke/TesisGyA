<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Informe de Inventario</title>
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
    <div class="subtitulo">Informe de Inventario</div>
</div>

<table class="meta">
    <tr>
        <td><strong>Depósito:</strong> {{ $filtros['deposito'] ?? 'Todos' }}</td>
        <td><strong>Obra:</strong> {{ $filtros['obra'] ?? 'Todas' }}</td>
    </tr>
</table>

<table class="datos">
    <thead>
        <tr>
            <th style="width:35px;">Nro</th>
            <th>Insumo</th>
            <th style="width:90px;">Marca</th>
            <th>Depósito</th>
            <th>Obra</th>
            <th class="text-end" style="width:70px;">Cantidad</th>
            <th style="width:70px;">Unidad</th>
        </tr>
    </thead>
    <tbody>
        @forelse($inventarios as $inventario)
            <tr>
                <td class="text-center">{{ $inventario->id }}</td>
                <td>{{ $inventario->insumo->descripcion ?? '-' }}</td>
                <td>{{ $inventario->insumo->marca->descripcion ?? '-' }}</td>
                <td>{{ $inventario->deposito->descripcion ?? '-' }}</td>
                <td>{{ $inventario->obra->descripcion ?? 'Stock General' }}</td>
                <td class="text-end">{{ number_format((float) $inventario->cantidad, 2, ',', '.') }}</td>
                <td>{{ $inventario->insumo->unidadMedida->descripcion ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center">No se encontraron registros de inventario con los filtros seleccionados.</td></tr>
        @endforelse
    </tbody>
    @if($inventarios->count() > 0)
        <tfoot>
            <tr class="total-row">
                <td colspan="7">Total de Ítems: {{ $totalItems }}</td>
            </tr>
        </tfoot>
    @endif
</table>

<div class="cierre">
    Informe generado automáticamente el {{ $generadoEn }} — GAVILAN Y ASOCIADOS S.A
</div>

</body>
</html>
