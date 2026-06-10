<!-- filepath: c:\laragon\www\TesisGyA\resources\views\presupuesto_compra_aprobado\show.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Presupuesto Aprobado #{{ $presupuestoAprobado->id }} - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-file-invoice-dollar"></i> Presupuesto Aprobado #{{ $presupuestoAprobado->id }}</h2>
                    <small>Detalle completo del presupuesto aprobado</small>
                </div>
                <div class="header-actions">
                    <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                    <a href="{{ route('presupuesto_compra_aprobado.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            {{-- Información General + Proveedor --}}
            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="card h-100">
                        <div class="card-header-section">
                            <span><i class="fas fa-info-circle me-2"></i>Información General</span>
                            <span class="tag tag-success">{{ $presupuestoAprobado->estado->descripcion ?? 'N/A' }}</span>
                        </div>
                        <div class="card-body">
                            <div class="info-grid">
                                <div class="info-item">
                                    <label class="form-label">Nombre</label>
                                    <div class="info-value"><i class="fas fa-tag"></i>{{ $presupuestoAprobado->nombre }}</div>
                                </div>
                                <div class="info-item">
                                    <label class="form-label">Fecha de Emisión</label>
                                    <div class="info-value">
                                        <i class="fas fa-calendar-plus"></i>
                                        {{ $presupuestoAprobado->fecha_emision ? \Carbon\Carbon::parse($presupuestoAprobado->fecha_emision)->format('d/m/Y') : 'N/A' }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <label class="form-label">Fecha de Vencimiento</label>
                                    <div class="info-value">
                                        <i class="fas fa-calendar-times"></i>
                                        {{ $presupuestoAprobado->fecha_vencimiento ? \Carbon\Carbon::parse($presupuestoAprobado->fecha_vencimiento)->format('d/m/Y') : 'N/A' }}
                                    </div>
                                </div>
                            </div>

                            @if($presupuestoAprobado->descripcion)
                                <div class="mt-3">
                                    <label class="form-label">Descripción</label>
                                    <div class="info-value observation-box">{{ $presupuestoAprobado->descripcion }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header-section">
                            <span><i class="fas fa-building me-2"></i>Proveedor</span>
                        </div>
                        <div class="card-body">
                            <div class="info-grid info-grid-stack">
                                <div class="info-item">
                                    <label class="form-label">Razón Social</label>
                                    <div class="info-value"><i class="fas fa-building"></i>{{ $presupuestoAprobado->proveedor->razon_social ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <label class="form-label">RUC</label>
                                    <div class="info-value"><i class="fas fa-id-card"></i>{{ $presupuestoAprobado->proveedor->ruc ?? 'N/A' }}</div>
                                </div>
                                @if($presupuestoAprobado->proveedor->telefono)
                                    <div class="info-item">
                                        <label class="form-label">Teléfono</label>
                                        <div class="info-value"><i class="fas fa-phone"></i>{{ $presupuestoAprobado->proveedor->telefono }}</div>
                                    </div>
                                @endif
                                @if($presupuestoAprobado->proveedor->email)
                                    <div class="info-item">
                                        <label class="form-label">Email</label>
                                        <div class="info-value"><i class="fas fa-envelope"></i>{{ $presupuestoAprobado->proveedor->email }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Información del Pedido --}}
            <div class="card">
                <div class="card-header-section">
                    <span><i class="fas fa-shopping-cart me-2"></i>Información del Pedido</span>
                    <a href="{{ route('pedido_compra.show', $presupuestoAprobado->pedido_compra_id) }}" class="tag">
                        #{{ $presupuestoAprobado->pedido_compra_id }}
                    </a>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label class="form-label">Pedido</label>
                            <div class="info-value"><i class="fas fa-hashtag"></i>#{{ $presupuestoAprobado->pedido_compra_id }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Sucursal</label>
                            <div class="info-value"><i class="fas fa-building"></i>{{ $presupuestoAprobado->pedidoCompra->sucursal->descripcion ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Depósito</label>
                            <div class="info-value"><i class="fas fa-warehouse"></i>{{ $presupuestoAprobado->pedidoCompra->deposito->descripcion ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Fecha Pedido</label>
                            <div class="info-value">
                                <i class="fas fa-calendar"></i>
                                {{ $presupuestoAprobado->pedidoCompra->fecha ? \Carbon\Carbon::parse($presupuestoAprobado->pedidoCompra->fecha)->format('d/m/Y') : 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detalles del Presupuesto --}}
            <div class="card table-card">
                <div class="card-header-section">
                    <span><i class="fas fa-list me-2"></i>Detalles del Presupuesto</span>
                    <span class="results-count">{{ $presupuestoAprobado->detalles->count() }} ítem(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Insumo</th>
                                    <th style="width:100px;" class="text-center">Cantidad</th>
                                    <th style="width:130px;" class="text-end">Precio Unit.</th>
                                    <th style="width:110px;" class="text-center">Impuesto</th>
                                    <th style="width:130px;" class="text-end">Subtotal</th>
                                    <th style="width:140px;" class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $subtotalGeneral = 0;
                                    $impuestosGeneral = 0;
                                @endphp
                                @foreach($presupuestoAprobado->detalles as $detalle)
                                    @php
                                        $subtotal = $detalle->cantidad * $detalle->precio_unitario;
                                        $impuesto = 0;

                                        if ($detalle->impuesto_id !== 1 && $detalle->impuesto && $detalle->impuesto->calculo) {
                                            $impuesto = round($subtotal / $detalle->impuesto->calculo);
                                        }

                                        $totalItem = $subtotal + $impuesto;
                                        $subtotalGeneral += $subtotal;
                                        $impuestosGeneral += $impuesto;
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $detalle->insumo->descripcion ?? 'N/A' }}</strong><br>
                                            <span class="tag me-1">{{ $detalle->insumo->marca->descripcion ?? 'N/A' }}</span>
                                            <span class="tag tag-secondary">{{ $detalle->insumo->unidadMedida->abreviatura ?? $detalle->insumo->unidadMedida->descripcion ?? 'N/A' }}</span>
                                            @if($detalle->observacion)
                                                <div class="text-muted mt-1" style="font-size:0.78rem;">
                                                    <i class="fas fa-comment me-1"></i>{{ $detalle->observacion }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="tag">{{ number_format($detalle->cantidad, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="text-end">₲ {{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <span class="tag tag-secondary">{{ $detalle->impuesto->descripcion ?? 'N/A' }}</span>
                                        </td>
                                        <td class="text-end">₲ {{ number_format($subtotal, 0, ',', '.') }}</td>
                                        <td class="text-end">
                                            <span class="amount">₲ {{ number_format($totalItem, 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Resumen Financiero --}}
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
                            <span>Total Final</span>
                            <strong>₲ {{ number_format($subtotalGeneral + $impuestosGeneral, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Información de Aprobación --}}
            <div class="card">
                <div class="card-header-section">
                    <span><i class="fas fa-user-check me-2"></i>Información de Aprobación</span>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label class="form-label">Creado por</label>
                            <div class="info-value">
                                <i class="fas fa-user"></i>
                                {{ $presupuestoAprobado->usuario->persona->nombre ?? 'N/A' }}
                                {{ $presupuestoAprobado->usuario->persona->apellido ?? '' }}
                            </div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Aprobado por</label>
                            <div class="info-value">
                                <i class="fas fa-user-check"></i>
                                {{ $presupuestoAprobado->aprobadoPor->persona->nombre ?? 'N/A' }}
                                {{ $presupuestoAprobado->aprobadoPor->persona->apellido ?? '' }}
                            </div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Fecha de Aprobación</label>
                            <div class="info-value">
                                <i class="fas fa-calendar-check"></i>
                                {{ $presupuestoAprobado->fecha_aprobacion ? \Carbon\Carbon::parse($presupuestoAprobado->fecha_aprobacion)->format('d/m/Y H:i') : 'N/A' }}
                            </div>
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

/* ── Información (grids) ── */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 0.75rem;
}
.info-grid-stack { grid-template-columns: 1fr; }
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
    .page-header { flex-direction: column; align-items: flex-start; }
}

/* ── Tags ── */
.tag {
    display: inline-block;
    padding: 0.2rem 0.55rem;
    border-radius: 4px;
    font-size: 0.72rem;
    font-weight: 600;
    background: #eff6ff;
    color: #2563eb;
    text-decoration: none;
}
.tag-secondary { background: #f1f5f9; color: #64748b; }
.tag-success { background: #dcfce7; color: #16a34a; }
.amount { font-weight: 700; color: #10b981; }

/* ── Tabla de detalles ── */
.table-card { display: flex; flex-direction: column; }
.table-container { overflow: auto; }

.data-table {
    width: 100%;
    min-width: 800px;
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
