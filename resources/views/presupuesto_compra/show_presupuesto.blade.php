<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Presupuesto #{{ $presupuesto->id }} - {{ $presupuesto->nombre }}</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-file-invoice-dollar"></i> Detalle del Presupuesto</h2>
                    <small>{{ $presupuesto->nombre }}</small>
                </div>
                <div class="header-actions">
                    <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                    <a href="{{ route('presupuesto_compra.show_pedido', $presupuesto->pedido_compra_id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver al Pedido
                    </a>
                </div>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Información General --}}
            <div class="card">
                <div class="card-header-section">
                    <span><i class="fas fa-info-circle me-2"></i>Información General</span>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label class="form-label">Presupuesto</label>
                            <div class="info-value"><i class="fas fa-hashtag"></i>{{ $presupuesto->id }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Estado</label>
                            <div class="info-value">
                                @switch($presupuesto->estado_id)
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
                                        <span class="estado"><i class="estado-dot"></i>{{ $presupuesto->estado->descripcion ?? '-' }}</span>
                                @endswitch
                            </div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Fecha Emisión</label>
                            <div class="info-value"><i class="fas fa-calendar"></i>{{ $presupuesto->fecha_emision->format('d/m/Y') }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Fecha Vencimiento</label>
                            <div class="info-value">
                                <i class="fas fa-calendar-times"></i>{{ $presupuesto->fecha_vencimiento->format('d/m/Y') }}
                                @if($presupuesto->fecha_vencimiento->isPast() && $presupuesto->estado_id == 3)
                                    <span class="tag tag-danger ms-1">Vencido</span>
                                @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Validez</label>
                            <div class="info-value"><i class="fas fa-clock"></i>{{ $presupuesto->validez }} días</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Proveedor</label>
                            <div class="info-value"><i class="fas fa-building"></i>{{ $presupuesto->proveedor->razon_social }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">RUC</label>
                            <div class="info-value"><i class="fas fa-id-card"></i>{{ $presupuesto->proveedor->ruc }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Creado por</label>
                            <div class="info-value"><i class="fas fa-user"></i>{{ $presupuesto->usuario->persona->nombre ?? '' }} {{ $presupuesto->usuario->persona->apellido ?? '' }}</div>
                        </div>
                        @if($presupuesto->proveedor->telefono)
                            <div class="info-item">
                                <label class="form-label">Teléfono</label>
                                <div class="info-value"><i class="fas fa-phone"></i>{{ $presupuesto->proveedor->telefono }}</div>
                            </div>
                        @endif
                        @if($presupuesto->proveedor->email)
                            <div class="info-item">
                                <label class="form-label">Email</label>
                                <div class="info-value"><i class="fas fa-envelope"></i>{{ $presupuesto->proveedor->email }}</div>
                            </div>
                        @endif
                    </div>

                    @if($presupuesto->descripcion)
                        <div class="mt-3">
                            <label class="form-label">Descripción</label>
                            <div class="info-value observation-box">{{ $presupuesto->descripcion }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Detalles del Presupuesto --}}
            @php
                $subtotalGeneral = 0;
                $impuestosGeneral = 0;
                $impuestosDetalle = [];
            @endphp

            <div class="card table-card">
                <div class="card-header-section">
                    <span><i class="fas fa-list me-2"></i>Detalles del Presupuesto</span>
                    <span class="results-count">{{ $presupuesto->detalles->count() }} ítem(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table id="detallesTable">
                            <thead>
                                <tr>
                                    <th>Insumo</th>
                                    <th style="width:80px;" class="text-center">Cantidad</th>
                                    <th style="width:110px;" class="text-end">Precio Unit.</th>
                                    <th style="width:120px;">Impuesto</th>
                                    <th style="width:110px;" class="text-end">Subtotal</th>
                                    <th style="width:110px;" class="text-end">Impuesto ₲</th>
                                    <th style="width:120px;" class="text-end">Total</th>
                                    <th style="width:200px;">Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($presupuesto->detalles as $detalle)
                                    @php
                                        $subtotal = $detalle->cantidad * $detalle->precio_unitario;
                                        $impuesto = 0;

                                        if ($detalle->impuesto_id !== 1) {
                                            $impuesto = round($subtotal / $detalle->impuesto->calculo);
                                        }

                                        $total = $subtotal + $impuesto;
                                        $subtotalGeneral += $subtotal;
                                        $impuestosGeneral += $impuesto;

                                        if (!isset($impuestosDetalle[$detalle->impuesto_id])) {
                                            $impuestosDetalle[$detalle->impuesto_id] = [
                                                'nombre' => $detalle->impuesto->descripcion,
                                                'total' => 0
                                            ];
                                        }
                                        $impuestosDetalle[$detalle->impuesto_id]['total'] += $impuesto;
                                    @endphp

                                    <tr>
                                        <td>
                                            <strong class="d-block">{{ $detalle->insumo->descripcion }}</strong>
                                            <span class="tag me-1">{{ $detalle->insumo->marca->descripcion }}</span>
                                            <span class="tag tag-secondary">{{ $detalle->insumo->unidadMedida->abreviatura ?? $detalle->insumo->unidadMedida->descripcion }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="tag">{{ number_format($detalle->cantidad, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="text-end">₲ {{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="tag tag-secondary">{{ $detalle->impuesto->descripcion }}</span>
                                            <small class="d-block text-muted mt-1">{{ $detalle->impuesto->calculo }}%</small>
                                        </td>
                                        <td class="text-end">
                                            <span class="amount">₲ {{ number_format($subtotal, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="text-end">
                                            @if($detalle->impuesto_id === 1)
                                                <span class="text-muted">Exenta</span>
                                            @else
                                                ₲ {{ number_format($impuesto, 0, ',', '.') }}
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <strong>₲ {{ number_format($total, 0, ',', '.') }}</strong>
                                        </td>
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
                </div>
            </div>

            {{-- Resumen de Totales --}}
            <div class="card">
                <div class="card-header-section">
                    <span><i class="fas fa-calculator me-2"></i>Resumen de Totales</span>
                </div>
                <div class="card-body">
                    <div class="totals-grid">
                        <div class="totals-box">
                            <div class="totals-box-title">Detalle por Impuesto</div>
                            @foreach($impuestosDetalle as $impuestoInfo)
                                <div class="totals-row">
                                    <span>{{ $impuestoInfo['nombre'] }}</span>
                                    <strong>
                                        @if($impuestoInfo['total'] > 0)
                                            ₲ {{ number_format($impuestoInfo['total'], 0, ',', '.') }}
                                        @else
                                            Sin impuesto
                                        @endif
                                    </strong>
                                </div>
                            @endforeach
                        </div>
                        <div class="totals-box">
                            <div class="totals-row">
                                <span>Total sin impuestos</span>
                                <strong>₲ {{ number_format($subtotalGeneral, 0, ',', '.') }}</strong>
                            </div>
                            <div class="totals-row">
                                <span>Total impuestos</span>
                                <strong>₲ {{ number_format($impuestosGeneral, 0, ',', '.') }}</strong>
                            </div>
                            <div class="totals-row totals-final">
                                <span>TOTAL FINAL</span>
                                <strong>₲ {{ number_format($subtotalGeneral + $impuestosGeneral, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Información del Pedido Original --}}
            <div class="card">
                <div class="card-header-section">
                    <span><i class="fas fa-shopping-cart me-2"></i>Información del Pedido Original</span>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label class="form-label">Pedido</label>
                            <div class="info-value"><i class="fas fa-hashtag"></i>{{ $presupuesto->pedidoCompra->id }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Fecha del Pedido</label>
                            <div class="info-value"><i class="fas fa-calendar"></i>{{ $presupuesto->pedidoCompra->fecha->format('d/m/Y') }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Sucursal</label>
                            <div class="info-value"><i class="fas fa-building"></i>{{ $presupuesto->pedidoCompra->sucursal->descripcion }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Depósito</label>
                            <div class="info-value"><i class="fas fa-warehouse"></i>{{ $presupuesto->pedidoCompra->deposito->descripcion }}</div>
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
    grid-template-columns: repeat(4, 1fr);
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

/* ── Tablas ── */
.table-card { display: flex; flex-direction: column; }
.table-container { overflow: auto; }

#detallesTable {
    width: 100%;
    min-width: 950px;
    border-collapse: collapse;
    table-layout: fixed;
}
#detallesTable thead th {
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
#detallesTable tbody td {
    padding: 0.6rem 0.65rem;
    font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
#detallesTable tbody tr:hover { background: #f8fafc; }
#detallesTable tbody tr:last-child td { border-bottom: none; }

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
.tag-danger { background: #fef2f2; color: #dc2626; }
.amount { font-weight: 700; color: #10b981; }

/* ── Totales ── */
.totals-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
.totals-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
}
.totals-box-title {
    font-size: 0.7rem;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.5rem;
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
    .totals-grid { grid-template-columns: 1fr; }
}

/* ── Impresión ── */
@media print {
    .main-content { margin-left: 0 !important; width: 100% !important; }
    .header-actions { display: none !important; }
    .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
}
</style>
