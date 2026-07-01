<!-- filepath: c:\laragon\www\TesisGyA\resources\views\presupuesto_compra_aprobado\index.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Presupuestos Aprobados - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-check-circle"></i> Presupuestos Aprobados</h2>
                    <small>Gestión de presupuestos aprobados para compras</small>
                </div>
                @if(session('permisos.pres_apr.agregar'))
                <a href="{{ route('presupuesto_compra_aprobado.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Aprobar Presupuesto
                </a>
                @endif
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

            {{-- Filtros --}}
            <div class="card">
                <div class="card-body py-3 px-3">
                    <form method="GET" action="{{ route('presupuesto_compra_aprobado.index') }}">
                        <div class="toolbar-grid">
                            <div class="toolbar-item search-item">
                                <label class="form-label">Proveedor</label>
                                <div class="search-box">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text" class="form-control form-control-sm" name="proveedor"
                                           value="{{ request('proveedor') }}" placeholder="Buscar por proveedor..." autocomplete="off">
                                </div>
                            </div>
                            <div class="toolbar-item">
                                <label class="form-label">Desde</label>
                                <input type="date" class="form-control form-control-sm" name="fecha_desde" value="{{ request('fecha_desde') }}">
                            </div>
                            <div class="toolbar-item">
                                <label class="form-label">Hasta</label>
                                <input type="date" class="form-control form-control-sm" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
                            </div>
                            <div class="toolbar-item toolbar-actions">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                        <i class="fas fa-search me-1"></i>Buscar
                                    </button>
                                    <a href="{{ route('presupuesto_compra_aprobado.index') }}" class="btn btn-outline-secondary btn-sm" title="Limpiar filtros">
                                        <i class="fas fa-eraser"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabla --}}
            <div class="card table-card">
                <div class="card-header-section">
                    <span>Lista de Presupuestos Aprobados</span>
                    <span class="results-count">
                        @if($presupuestos->total() > 0)
                            Mostrando <strong>{{ $presupuestos->firstItem() }}</strong>-<strong>{{ $presupuestos->lastItem() }}</strong> de {{ $presupuestos->total() }}
                        @else
                            0 registros
                        @endif
                    </span>
                </div>
                <div class="card-body p-0" style="flex:1; display:flex; flex-direction:column;">
                    <div class="table-container">
                        @if($presupuestos->count() > 0)
                            <table id="presupuestosTable">
                                <thead>
                                    <tr>
                                        <th style="width:70px;">ID</th>
                                        <th>Nombre</th>
                                        <th>Proveedor</th>
                                        <th style="width:80px;" class="text-center">Pedido</th>
                                        <th style="width:110px;">Fecha Aprob.</th>
                                        <th>Aprobado Por</th>
                                        <th style="width:130px;" class="text-end">Total</th>
                                        <th style="width:90px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($presupuestos as $presupuesto)
                                        @php
                                            $total = 0;
                                            foreach($presupuesto->detalles as $detalle) {
                                                $subtotal = $detalle->cantidad * $detalle->precio_unitario;
                                                $impuesto = 0;
                                                if ($detalle->impuesto_id !== 1) {
                                                    $impuesto = round($subtotal / $detalle->impuesto->calculo);
                                                }
                                                $total += $subtotal + $impuesto;
                                            }
                                        @endphp
                                        <tr>
                                            <td><strong>#{{ $presupuesto->id }}</strong></td>
                                            <td>
                                                <span class="cell-text" title="{{ $presupuesto->nombre }}">{{ $presupuesto->nombre }}</span>
                                                @if($presupuesto->descripcion)
                                                    <br><small class="text-muted">{{ Str::limit($presupuesto->descripcion, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $presupuesto->proveedor->razon_social }}">{{ $presupuesto->proveedor->razon_social }}</span>
                                                <br><small class="text-muted">RUC: {{ $presupuesto->proveedor->ruc }}</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="tag tag-secondary">#{{ $presupuesto->pedido_compra_id }}</span>
                                            </td>
                                            <td>
                                                {{ $presupuesto->fecha_aprobacion->format('d/m/Y') }}
                                                <br><small class="text-muted">{{ $presupuesto->fecha_aprobacion->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $presupuesto->aprobadoPor->persona->nombre }} {{ $presupuesto->aprobadoPor->persona->apellido }}">
                                                    {{ $presupuesto->aprobadoPor->persona->nombre }} {{ $presupuesto->aprobadoPor->persona->apellido }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="amount">₲ {{ number_format($total, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('presupuesto_compra_aprobado.show', $presupuesto->id) }}"
                                                       class="btn-icon" title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn-icon" onclick="window.print()" title="Imprimir">
                                                        <i class="fas fa-print"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <h5 class="text-muted mb-2">No hay presupuestos aprobados</h5>
                                <p class="text-muted mb-3" style="font-size:0.85rem;">
                                    Aún no se han aprobado presupuestos en el sistema.
                                </p>
                                <a href="{{ route('presupuesto_compra_aprobado.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-2"></i>Aprobar Primer Presupuesto
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                @if($presupuestos->hasPages())
                    <div class="card-footer-section">
                        {{ $presupuestos->links() }}
                    </div>
                @endif
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

/* ── Toolbar (filtros) ── */
.toolbar-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 0.65rem;
    align-items: end;
}
.toolbar-item .form-label {
    font-size: 0.7rem;
    font-weight: 500;
    color: #94a3b8;
    margin-bottom: 0.25rem;
}
.search-box { position: relative; }
.search-box .search-icon {
    position: absolute; left: 10px; top: 50%; transform: translateY(-50%);
    color: #94a3b8; font-size: 0.78rem; pointer-events: none;
}
.search-box input { padding-left: 2rem; }
.toolbar-actions > div { width: 100%; }

@media (max-width: 900px) {
    .toolbar-grid { grid-template-columns: 1fr 1fr; }
    .toolbar-item.search-item { grid-column: 1 / -1; }
    .page-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .toolbar-grid { grid-template-columns: 1fr; }
}

/* ── Tabla ── */
.table-card {
    flex: 1;
    min-height: 400px;
    display: flex;
    flex-direction: column;
}
.table-container {
    flex: 1;
    overflow: auto;
    min-height: 300px;
}

#presupuestosTable {
    width: 100%;
    min-width: 920px;
    border-collapse: collapse;
    table-layout: fixed;
}
#presupuestosTable thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 0.6rem 0.65rem;
    position: sticky; top: 0;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
#presupuestosTable tbody td {
    padding: 0.55rem 0.65rem;
    font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
#presupuestosTable tbody tr:hover { background: #f8fafc; }
#presupuestosTable tbody tr:last-child td { border-bottom: none; }

.cell-text {
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.amount { font-weight: 700; color: #10b981; }

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
    min-height: 320px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 2rem; color: #94a3b8; text-align: center;
}
.empty-state i { color: #cbd5e1; }

/* ── Paginación ── */
.card-footer-section {
    padding: 0.65rem 1rem;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
}
.card-footer-section .pagination {
    margin-bottom: 0;
}

@media (max-width: 768px) {
    .table-container { font-size: 0.875rem; }
}
</style>
