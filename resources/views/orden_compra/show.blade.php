<!-- filepath: c:\laragon\www\TesisGyA\resources\views\orden_compra\show.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Compra #{{ $orden->id }} - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-file-contract"></i> Orden de Compra #{{ $orden->id }}</h2>
                    <small>Detalle de la orden de compra</small>
                </div>
                <div class="header-actions">
                    <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                    <a href="{{ route('orden_compra.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            {{-- Información de la Orden --}}
            <div class="card">
                <div class="card-header-section">
                    <span><i class="fas fa-info-circle me-2"></i>Información de la Orden</span>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label class="form-label">Proveedor</label>
                            <div class="info-value"><i class="fas fa-building"></i>{{ $orden->proveedor->razon_social ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">RUC</label>
                            <div class="info-value"><i class="fas fa-id-card"></i>{{ $orden->proveedor->ruc ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Fecha</label>
                            <div class="info-value">
                                <i class="fas fa-calendar"></i>
                                {{ $orden->fecha ? \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') : 'N/A' }}
                            </div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Condición de Pago</label>
                            <div class="info-value"><i class="fas fa-file-invoice"></i>{{ $orden->condicionPago->descripcion ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Método de Pago</label>
                            <div class="info-value"><i class="fas fa-credit-card"></i>{{ $orden->metodoPago->descripcion ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Monto Total</label>
                            <div class="info-value"><i class="fas fa-coins"></i><span class="amount">₲ {{ number_format($orden->monto, 0, ',', '.') }}</span></div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Cuotas</label>
                            <div class="info-value"><i class="fas fa-list-ol"></i>{{ $orden->cuota ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Intervalo</label>
                            <div class="info-value"><i class="fas fa-clock"></i>{{ $orden->intervalo ? $orden->intervalo . ' días' : 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Estado</label>
                            <div class="info-value">
                                @switch($orden->estado_id)
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
                                        <span class="estado"><i class="estado-dot"></i>{{ $orden->estado->descripcion ?? 'N/A' }}</span>
                                @endswitch
                            </div>
                        </div>
                    </div>

                    @if($orden->observacion)
                        <div class="mt-3">
                            <label class="form-label">Observación</label>
                            <div class="info-value observation-box">{{ $orden->observacion }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Detalle de la Orden --}}
            <div class="card table-card">
                <div class="card-header-section">
                    <span><i class="fas fa-list me-2"></i>Detalle de la Orden</span>
                    <span class="results-count">{{ $orden->detalles->count() }} ítem(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Insumo</th>
                                    <th style="width:100px;" class="text-center">Cantidad</th>
                                    <th style="width:120px;" class="text-end">Precio Unit.</th>
                                    <th style="width:110px;" class="text-center">Impuesto</th>
                                    <th style="width:120px;" class="text-end">Subtotal</th>
                                    <th style="width:130px;" class="text-end">Total Item</th>
                                    <th>Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $subtotalGeneral = 0;
                                    $impuestosGeneral = 0;
                                @endphp
                                @foreach($orden->detalles as $detalle)
                                    @php
                                        $cantidad = $detalle->cantidad;
                                        $precioUnitario = round($detalle->precio_unitario);
                                        $subtotal = $cantidad * $precioUnitario;
                                        $impuesto = ($detalle->impuesto_id !== 1 && $detalle->impuesto && $detalle->impuesto->calculo)
                                            ? round($subtotal / $detalle->impuesto->calculo)
                                            : 0;
                                        $totalItem = $subtotal + $impuesto;
                                        $subtotalGeneral += $subtotal;
                                        $impuestosGeneral += $impuesto;
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $detalle->insumo->descripcion ?? 'N/A' }}</strong><br>
                                            <span class="tag me-1">{{ $detalle->insumo->marca->descripcion ?? 'N/A' }}</span>
                                            <span class="tag tag-secondary">{{ $detalle->insumo->unidadMedida->abreviatura ?? $detalle->insumo->unidadMedida->descripcion ?? 'N/A' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="tag">{{ number_format($cantidad, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="text-end">₲ {{ number_format($precioUnitario, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <span class="tag tag-secondary">{{ $detalle->impuesto->descripcion ?? 'N/A' }}</span>
                                            @if($impuesto > 0)
                                                <br><small class="text-muted">₲ {{ number_format($impuesto, 0, ',', '.') }}</small>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>₲ {{ number_format($subtotal, 0, ',', '.') }}</strong></td>
                                        <td class="text-end"><span class="amount">₲ {{ number_format($totalItem, 0, ',', '.') }}</span></td>
                                        <td>
                                            @if($detalle->observacion)
                                                {{ $detalle->observacion }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Resumen financiero --}}
                    <div class="totals-box">
                        <div class="totals-row">
                            <span>Subtotal</span>
                            <strong>₲ {{ number_format($subtotalGeneral, 0, ',', '.') }}</strong>
                        </div>
                        <div class="totals-row">
                            <span>Impuestos</span>
                            <strong>₲ {{ number_format($impuestosGeneral, 0, ',', '.') }}</strong>
                        </div>
                        <div class="totals-row totals-final">
                            <span>Total</span>
                            <strong>₲ {{ number_format($subtotalGeneral + $impuestosGeneral, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @include('partials.footer')
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
.header-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }

/* ── Cards ── */
.card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: none;
}
.card-header-section {
    padding: 0.65rem 1rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;
    font-weight: 600; font-size: 0.85rem; color: #1e293b;
}
.results-count { font-weight: 400; font-size: 0.78rem; color: #94a3b8; }

/* ── Información general ── */
.info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
}
.info-item .form-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 500;
    color: #94a3b8;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.info-value {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-size: 0.85rem;
    color: #374151;
}
.info-value i { color: #94a3b8; margin-right: 0.5rem; width: 14px; text-align: center; }
.observation-box {
    white-space: pre-wrap;
    line-height: 1.5;
}

@media (max-width: 900px) {
    .info-grid { grid-template-columns: repeat(2, 1fr); }
    .page-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .info-grid { grid-template-columns: 1fr; }
}

/* ── Estado ── */
.estado { display: inline-flex; align-items: center; gap: 0.4rem; }
.estado-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #94a3b8; flex-shrink: 0; }
.estado-pendiente .estado-dot  { background: #f59e0b; }
.estado-confirmado .estado-dot { background: #10b981; }
.estado-anulado .estado-dot    { background: #ef4444; }

/* ── Tabla de detalle ── */
.table-card { display: flex; flex-direction: column; }
.table-container { overflow: auto; }

.data-table {
    width: 100%;
    min-width: 880px;
    border-collapse: collapse;
    table-layout: fixed;
}
.data-table thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 0.6rem 0.65rem;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.data-table tbody td {
    padding: 0.6rem 0.65rem;
    font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
.data-table tbody tr:hover { background: #f8fafc; }
.data-table tbody tr:last-child td { border-bottom: none; }

/* Tags */
.tag {
    display: inline-block;
    padding: 0.2rem 0.55rem;
    border-radius: 4px;
    font-size: 0.72rem;
    font-weight: 600;
    background: #eff6ff;
    color: #2563eb;
}
.tag-secondary { background: #f1f5f9; color: #64748b; }
.amount { font-weight: 700; color: #10b981; }

/* ── Totales ── */
.totals-box {
    margin: 1rem;
    margin-left: auto;
    max-width: 320px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
}
.totals-row { display: flex; justify-content: space-between; font-size: 0.85rem; color: #374151; padding: 0.25rem 0; }
.totals-row.totals-final {
    border-top: 1px solid #e2e8f0;
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
}
.totals-final strong { color: #10b981; }

@media (max-width: 768px) {
    .table-container { font-size: 0.875rem; }
    .totals-box { max-width: 100%; margin: 1rem; }
}

/* ── Impresión ── */
@media print {
    .main-content { margin-left: 0 !important; width: 100% !important; }
    .header-actions { display: none !important; }
    .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
}
</style>
