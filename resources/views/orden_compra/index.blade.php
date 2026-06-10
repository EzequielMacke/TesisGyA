<!-- filepath: c:\laragon\www\TesisGyA\resources\views\orden_compra\index.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Órdenes de Compra - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-file-contract"></i> Órdenes de Compra</h2>
                    <small>Gestión de órdenes de compra generadas desde presupuestos aprobados</small>
                </div>
                <a href="{{ route('orden_compra.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nueva Orden
                </a>
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
                    <form method="GET" action="{{ route('orden_compra.index') }}">
                        <div class="toolbar-grid">
                            <div class="toolbar-item">
                                <label class="form-label">Estado</label>
                                <select class="form-select form-select-sm" name="estado">
                                    <option value="">Todos</option>
                                    <option value="3" {{ request('estado') == '3' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="4" {{ request('estado') == '4' ? 'selected' : '' }}>Confirmado</option>
                                    <option value="5" {{ request('estado') == '5' ? 'selected' : '' }}>Anulado</option>
                                </select>
                            </div>
                            <div class="toolbar-item">
                                <label class="form-label">Proveedor</label>
                                <select class="form-select form-select-sm" name="proveedor">
                                    <option value="">Todos</option>
                                    @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}" {{ request('proveedor') == $proveedor->id ? 'selected' : '' }}>
                                            {{ $proveedor->razon_social }}
                                        </option>
                                    @endforeach
                                </select>
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
                                    <a href="{{ route('orden_compra.index') }}" class="btn btn-outline-secondary btn-sm" title="Limpiar filtros">
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
                    <span>Lista de Órdenes de Compra</span>
                    <span class="results-count">{{ $ordenes->count() }} orden(es)</span>
                </div>
                <div class="card-body p-0" style="flex:1; display:flex; flex-direction:column;">
                    <div class="table-container">
                        @if($ordenes->count() > 0)
                            <table id="ordenesTable">
                                <thead>
                                    <tr>
                                        <th style="width:70px;">ID</th>
                                        <th style="width:90px;">Fecha</th>
                                        <th>Proveedor</th>
                                        <th style="width:130px;" class="text-end">Monto</th>
                                        <th style="width:110px;">Estado</th>
                                        <th>Condición Pago</th>
                                        <th>Usuario</th>
                                        <th style="width:120px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ordenes as $orden)
                                        <tr>
                                            <td><strong>#{{ $orden->id }}</strong></td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}
                                                <br><small class="text-muted">{{ \Carbon\Carbon::parse($orden->created_at)->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $orden->proveedor->razon_social ?? 'N/A' }}">{{ $orden->proveedor->razon_social ?? 'N/A' }}</span>
                                                <br><small class="text-muted">{{ $orden->proveedor->ruc ?? 'Sin RUC' }}</small>
                                            </td>
                                            <td class="text-end">
                                                <span class="amount">₲ {{ number_format($orden->monto, 0, ',', '.') }}</span>
                                                @if($orden->condicionPago && $orden->cuota > 1)
                                                    <br><small class="text-muted">{{ $orden->cuota }} cuotas</small>
                                                @endif
                                            </td>
                                            <td>
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
                                                        <span class="estado"><i class="estado-dot"></i>{{ $orden->estado->descripcion ?? 'Sin estado' }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $orden->condicionPago->descripcion ?? 'N/A' }}">{{ $orden->condicionPago->descripcion ?? 'N/A' }}</span>
                                                <br><small class="text-muted">{{ $orden->metodoPago->descripcion ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $orden->usuario->persona->nombre ?? 'N/A' }} {{ $orden->usuario->persona->apellido ?? '' }}">
                                                    {{ $orden->usuario->persona->nombre ?? 'N/A' }} {{ $orden->usuario->persona->apellido ?? '' }}
                                                </span>
                                                <br><small class="text-muted">{{ \Carbon\Carbon::parse($orden->created_at)->format('d/m/Y') }}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('orden_compra.show', $orden->id) }}"
                                                       class="btn-icon" title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($orden->estado_id == 3)
                                                        <a href="#" class="btn-icon" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn-icon danger" title="Anular"
                                                                onclick="anularOrden({{ $orden->id }})">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    @endif
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
                                <i class="fas fa-file-contract fa-3x mb-3"></i>
                                <h5 class="text-muted mb-2">No hay órdenes de compra</h5>
                                <p class="text-muted mb-3" style="font-size:0.85rem;">
                                    No se encontraron órdenes de compra con los filtros aplicados.
                                </p>
                                <a href="{{ route('orden_compra.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-2"></i>Crear Primera Orden
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
    grid-template-columns: 1fr 1.5fr 1fr 1fr 1fr;
    gap: 0.65rem;
    align-items: end;
}
.toolbar-item .form-label {
    font-size: 0.7rem;
    font-weight: 500;
    color: #94a3b8;
    margin-bottom: 0.25rem;
}
.toolbar-actions > div { width: 100%; }

@media (max-width: 900px) {
    .toolbar-grid { grid-template-columns: 1fr 1fr; }
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

#ordenesTable {
    width: 100%;
    min-width: 920px;
    border-collapse: collapse;
    table-layout: fixed;
}
#ordenesTable thead th {
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
#ordenesTable tbody td {
    padding: 0.55rem 0.65rem;
    font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
#ordenesTable tbody tr:hover { background: #f8fafc; }
#ordenesTable tbody tr:last-child td { border-bottom: none; }

.cell-text {
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.amount { font-weight: 700; color: #10b981; }

/* Estado */
.estado { display: inline-flex; align-items: center; gap: 0.4rem; }
.estado-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #94a3b8; flex-shrink: 0; }
.estado-pendiente .estado-dot  { background: #f59e0b; }
.estado-confirmado .estado-dot { background: #10b981; }
.estado-anulado .estado-dot    { background: #ef4444; }

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
.btn-icon.danger:hover { background: #fef2f2; color: #dc2626; border-color: #fecaca; }

/* Empty state */
.empty-state {
    min-height: 320px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 2rem; color: #94a3b8; text-align: center;
}
.empty-state i { color: #cbd5e1; }

@media (max-width: 768px) {
    .table-container { font-size: 0.875rem; }
}
</style>

<script>
function anularOrden(id) {
    const enviar = () => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/orden-compra/${id}/anular`;

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfInput);

        document.body.appendChild(form);
        form.submit();
    };

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Anular Orden?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, Anular',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then(r => { if (r.isConfirmed) enviar(); });
    } else {
        if (confirm('¿Está seguro de anular esta orden de compra?\n\nEsta acción no se puede deshacer.')) enviar();
    }
}
</script>
