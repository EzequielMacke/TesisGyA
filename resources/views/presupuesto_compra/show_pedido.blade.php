<!-- filepath: c:\laragon\www\TesisGyA\resources\views\presupuesto_compra\show_pedido.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido #{{ str_pad($pedido->id, 3, '0', STR_PAD_LEFT) }} - Presupuestos</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-shopping-cart"></i> Pedido de Compra #{{ str_pad($pedido->id, 3, '0', STR_PAD_LEFT) }}</h2>
                    <small>Detalle del pedido y presupuestos asociados</small>
                </div>
                <div class="header-actions">
                    <a href="{{ route('presupuesto_compra.create', $pedido->id) }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Crear Presupuesto
                    </a>
                    <a href="{{ route('presupuesto_compra.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver al Listado
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
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
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
                            <label class="form-label">Usuario Solicitante</label>
                            <div class="info-value"><i class="fas fa-user"></i>{{ $pedido->usuario->usuario ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Fecha del Pedido</label>
                            <div class="info-value"><i class="fas fa-calendar"></i>{{ $pedido->fecha->format('d/m/Y') }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Estado</label>
                            <div class="info-value">
                                @switch($pedido->estado_id)
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
                                        <span class="estado"><i class="estado-dot"></i>{{ $pedido->estado->descripcion }}</span>
                                @endswitch
                            </div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Sucursal</label>
                            <div class="info-value"><i class="fas fa-building"></i>{{ $pedido->sucursal->descripcion }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Depósito</label>
                            <div class="info-value"><i class="fas fa-warehouse"></i>{{ $pedido->deposito->descripcion }}</div>
                        </div>
                    </div>

                    @if($pedido->observacion)
                        <div class="mt-3">
                            <label class="form-label">Observación General</label>
                            <div class="info-value observation-box">{{ $pedido->observacion }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Estadísticas --}}
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-icon"><i class="fas fa-boxes"></i></div>
                    <div>
                        <div class="stat-value">{{ $pedido->detalles->count() }}</div>
                        <div class="stat-label">Insumos Solicitados</div>
                    </div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon"><i class="fas fa-sort-numeric-up"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($pedido->detalles->sum('cantidad'), 0, ',', '.') }}</div>
                        <div class="stat-label">Cantidad Total</div>
                    </div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <div>
                        <div class="stat-value">{{ $pedido->presupuestos->count() }}</div>
                        <div class="stat-label">Presupuestos Recibidos</div>
                    </div>
                </div>
            </div>

            {{-- Insumos Solicitados --}}
            <div class="card table-card">
                <div class="card-header-section">
                    <span><i class="fas fa-list me-2"></i>Insumos Solicitados</span>
                    <span class="results-count">{{ $pedido->detalles->count() }} ítem(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        @if($pedido->detalles->count() > 0)
                            <table id="insumosTable">
                                <thead>
                                    <tr>
                                        <th style="width:50px;" class="text-center">#</th>
                                        <th>Insumo</th>
                                        <th style="width:120px;" class="text-center">Cantidad</th>
                                        <th style="width:100px;" class="text-center">Unidad</th>
                                        <th>Observación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pedido->detalles as $index => $detalle)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <i class="fas fa-cube text-muted me-2"></i><strong>{{ $detalle->insumo->descripcion }}</strong>
                                                @if($detalle->insumo->marca->descripcion)
                                                    <br><span class="tag tag-secondary mt-1">{{ $detalle->insumo->marca->descripcion }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="tag">{{ number_format($detalle->cantidad, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="tag tag-secondary">{{ $detalle->insumo->unidadMedida->abreviatura ?? $detalle->insumo->unidadMedida->descripcion }}</span>
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
                                <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-end">Total</th>
                                        <th class="text-center">
                                            <span class="tag">{{ number_format($pedido->detalles->sum('cantidad'), 0, ',', '.') }}</span>
                                        </th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <h5 class="text-muted mb-2">Sin insumos</h5>
                                <p class="text-muted mb-0" style="font-size:0.85rem;">
                                    Este pedido no tiene insumos asociados.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Presupuestos Asociados --}}
            <div class="card table-card">
                <div class="card-header-section">
                    <span><i class="fas fa-file-invoice-dollar me-2"></i>Presupuestos Asociados</span>
                    <span class="results-count">{{ $pedido->presupuestos->count() }} presupuesto(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        @if($pedido->presupuestos->count() > 0)
                            <table id="presupuestosTable">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th style="width:180px;">Proveedor</th>
                                        <th style="width:110px;">Estado</th>
                                        <th style="width:100px;">Emisión</th>
                                        <th style="width:90px;" class="text-center">Validez</th>
                                        <th>Descripción</th>
                                        <th style="width:90px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pedido->presupuestos as $presupuesto)
                                        <tr>
                                            <td>
                                                <span class="cell-text" title="{{ $presupuesto->nombre }}">{{ $presupuesto->nombre }}</span>
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $presupuesto->proveedor->razon_social ?? '-' }}">{{ $presupuesto->proveedor->razon_social ?? '-' }}</span>
                                            </td>
                                            <td>
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
                                            </td>
                                            <td>{{ $presupuesto->fecha_emision->format('d/m/Y') }}</td>
                                            <td class="text-center">
                                                <span class="tag tag-secondary">{{ $presupuesto->validez }} días</span>
                                            </td>
                                            <td>
                                                @if($presupuesto->descripcion)
                                                    <span class="cell-text" title="{{ $presupuesto->descripcion }}">{{ Str::limit($presupuesto->descripcion, 40) }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('presupuesto_compra.show', $presupuesto->id) }}" class="btn-icon" title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-file-invoice fa-3x mb-3"></i>
                                <h5 class="text-muted mb-2">Sin presupuestos</h5>
                                <p class="text-muted mb-3" style="font-size:0.85rem;">
                                    Este pedido aún no tiene presupuestos asociados.
                                </p>
                                <a href="{{ route('presupuesto_compra.create', $pedido->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus me-2"></i>Crear Primer Presupuesto
                                </a>
                            </div>
                        @endif
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

/* ── Estadísticas ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}
.stat-box {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.85rem;
}
.stat-icon {
    width: 42px; height: 42px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 8px;
    background: #eff6ff; color: #2563eb;
    font-size: 1.1rem; flex-shrink: 0;
}
.stat-value { font-size: 1.3rem; font-weight: 700; color: #1e293b; line-height: 1.2; }
.stat-label { font-size: 0.72rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.4px; }

@media (max-width: 768px) {
    .stats-grid { grid-template-columns: 1fr; }
}

/* ── Tablas ── */
.table-card { display: flex; flex-direction: column; }
.table-container { overflow: auto; }

#insumosTable, #presupuestosTable {
    width: 100%;
    min-width: 700px;
    border-collapse: collapse;
    table-layout: fixed;
}
#insumosTable thead th, #presupuestosTable thead th {
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
#insumosTable tbody td, #presupuestosTable tbody td {
    padding: 0.6rem 0.65rem;
    font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
#insumosTable tbody tr:hover, #presupuestosTable tbody tr:hover { background: #f8fafc; }
#insumosTable tbody tr:last-child td, #presupuestosTable tbody tr:last-child td { border-bottom: none; }
#insumosTable tfoot th {
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    padding: 0.6rem 0.65rem;
    font-size: 0.78rem;
    font-weight: 600;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.cell-text {
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

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

/* Acciones */
.btn-group { display: flex; gap: 4px; justify-content: center; }
.btn-icon {
    width: 28px; height: 28px;
    display: inline-flex; align-items: center; justify-content: center;
    border: 1px solid #e2e8f0; border-radius: 6px;
    color: #64748b; background: #fff; font-size: 0.78rem;
    text-decoration: none; cursor: pointer;
}
.btn-icon:hover { background: #f1f5f9; color: #1e293b; }

/* Empty state */
.empty-state {
    min-height: 240px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 2rem; color: #94a3b8; text-align: center;
}
.empty-state i { color: #cbd5e1; }

@media (max-width: 768px) {
    .table-container { font-size: 0.875rem; }
}
</style>
