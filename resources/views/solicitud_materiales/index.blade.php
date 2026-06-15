<!-- filepath: c:\laragon\www\TesisGyA\resources\views\solicitud_materiales\index.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitudes de Insumos - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-clipboard-list"></i> Solicitudes de Insumos</h2>
                    <small>Gestión de solicitudes de insumos para obras y depósitos</small>
                </div>
                <a href="{{ route('solicitud_materiales.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Nueva Solicitud
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
                    <form method="GET" action="{{ route('solicitud_materiales.index') }}">
                        <div class="toolbar-grid">
                            <div class="toolbar-item">
                                <label class="form-label">Estado</label>
                                <select class="form-select form-select-sm" name="estado_id" onchange="this.form.submit()">
                                    <option value="">Todos</option>
                                    <option value="3" {{ request('estado_id') == 3 ? 'selected' : '' }}>Pendiente</option>
                                    <option value="4" {{ request('estado_id') == 4 ? 'selected' : '' }}>Confirmado</option>
                                    <option value="5" {{ request('estado_id') == 5 ? 'selected' : '' }}>Anulado</option>
                                </select>
                            </div>
                            <div class="toolbar-item">
                                <label class="form-label">Destino</label>
                                <select class="form-select form-select-sm" name="destino" onchange="this.form.submit()">
                                    <option value="">Todos</option>
                                    <option value="obra" {{ request('destino') == 'obra' ? 'selected' : '' }}>Obra</option>
                                    <option value="deposito" {{ request('destino') == 'deposito' ? 'selected' : '' }}>Depósito</option>
                                </select>
                            </div>
                            <div class="toolbar-item">
                                <label class="form-label">Desde</label>
                                <input type="date" class="form-control form-control-sm" name="fecha_desde" value="{{ request('fecha_desde') }}" onchange="this.form.submit()">
                            </div>
                            <div class="toolbar-item">
                                <label class="form-label">Buscar</label>
                                <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="Solicitante, obra, depósito...">
                            </div>
                            <div class="toolbar-item toolbar-actions">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                        <i class="fas fa-search me-1"></i>Buscar
                                    </button>
                                    <a href="{{ route('solicitud_materiales.index') }}" class="btn btn-outline-secondary btn-sm" title="Limpiar filtros">
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
                    <span>Lista de Solicitudes</span>
                    <span class="results-count">{{ $solicitudes->count() }} solicitud(es)</span>
                </div>
                <div class="card-body p-0" style="flex:1; display:flex; flex-direction:column;">
                    <div class="table-container">
                        @if($solicitudes->count() > 0)
                            <table id="solicitudesTable">
                                <thead>
                                    <tr>
                                        <th style="width:80px;" class="text-center">N°</th>
                                        <th>Solicitante</th>
                                        <th>Destino</th>
                                        <th style="width:100px;" class="text-center">Fecha</th>
                                        <th style="width:110px;">Estado</th>
                                        <th>Observación</th>
                                        <th>Insumos</th>
                                        <th style="width:120px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($solicitudes as $solicitud)
                                        <tr>
                                            <td class="text-center">
                                                <span class="tag">#{{ str_pad($solicitud->id, 3, '0', STR_PAD_LEFT) }}</span>
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $solicitud->usuario->usuario ?? '-' }}">{{ $solicitud->usuario->usuario ?? '-' }}</span>
                                            </td>
                                            <td>
                                                @if($solicitud->obra)
                                                    <span class="tag tag-secondary" title="{{ $solicitud->obra->descripcion }}">Obra: {{ $solicitud->obra->descripcion }}</span>
                                                @elseif($solicitud->deposito)
                                                    <span class="tag tag-secondary" title="{{ $solicitud->deposito->descripcion }}">Depósito: {{ $solicitud->deposito->descripcion }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($solicitud->fecha)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                @switch($solicitud->estado_id)
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
                                                        <span class="estado"><i class="estado-dot"></i>{{ $solicitud->estado->descripcion ?? '-' }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($solicitud->observacion)
                                                    <span class="cell-text" title="{{ $solicitud->observacion }}">{{ Str::limit($solicitud->observacion, 28) }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($solicitud->detalles && $solicitud->detalles->count())
                                                    <span class="tag tag-secondary" title="{{ $solicitud->detalles->pluck('insumo.descripcion')->filter()->join(', ') }}">
                                                        {{ $solicitud->detalles->count() }} insumo(s)
                                                    </span>
                                                @else
                                                    <span class="text-muted">Sin detalle</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn-icon" title="Ver detalle" data-bs-toggle="modal" data-bs-target="#detalleModal{{ $solicitud->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if($solicitud->puedeEditarse())
                                                        <a href="{{ route('solicitud_materiales.edit', $solicitud->id) }}" class="btn-icon" title="Editar solicitud">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
                                                    @if($solicitud->estado_id == 3)
                                                        <button type="button" class="btn-icon danger" title="Anular solicitud"
                                                                onclick="abrirAnular({{ $solicitud->id }}, '{{ str_pad($solicitud->id, 3, '0', STR_PAD_LEFT) }}')">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                                <h5 class="text-muted mb-2">No hay solicitudes de insumos</h5>
                                <p class="text-muted mb-3" style="font-size:0.85rem;">
                                    No se encontraron solicitudes con los filtros aplicados.
                                </p>
                                <a href="{{ route('solicitud_materiales.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-2"></i>Crear Primera Solicitud
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Modales de detalle --}}
    @foreach($solicitudes as $solicitud)
        <div class="modal fade" id="detalleModal{{ $solicitud->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-clipboard-list me-2"></i>Solicitud #{{ str_pad($solicitud->id, 3, '0', STR_PAD_LEFT) }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="detalle-grid">
                            <div>
                                <span class="detalle-label">Solicitante</span>
                                <span class="detalle-value">{{ $solicitud->usuario->usuario ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="detalle-label">Fecha</span>
                                <span class="detalle-value">{{ \Carbon\Carbon::parse($solicitud->fecha)->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <span class="detalle-label">Destino</span>
                                <span class="detalle-value">
                                    @if($solicitud->obra)
                                        Obra: {{ $solicitud->obra->descripcion }}
                                    @elseif($solicitud->deposito)
                                        Depósito: {{ $solicitud->deposito->descripcion }}
                                    @else
                                        —
                                    @endif
                                </span>
                            </div>
                            <div>
                                <span class="detalle-label">Estado</span>
                                <span class="detalle-value">
                                    @switch($solicitud->estado_id)
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
                                            <span class="estado"><i class="estado-dot"></i>{{ $solicitud->estado->descripcion ?? '-' }}</span>
                                    @endswitch
                                </span>
                            </div>
                            @if($solicitud->observacion)
                                <div style="grid-column: 1 / -1;">
                                    <span class="detalle-label">Observación General</span>
                                    <span class="detalle-value">{{ $solicitud->observacion }}</span>
                                </div>
                            @endif
                        </div>

                        <table class="detalle-table">
                            <thead>
                                <tr>
                                    <th>Insumo</th>
                                    <th style="width:110px;">Marca</th>
                                    <th style="width:100px;">Unidad</th>
                                    <th style="width:90px;" class="text-center">Cant. Solicitada</th>
                                    <th style="width:90px;" class="text-center">Cant. Entregada</th>
                                    <th>Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($solicitud->detalles as $detalle)
                                    <tr>
                                        <td>{{ $detalle->insumo->descripcion ?? '-' }}</td>
                                        <td><span class="tag">{{ $detalle->insumo->marca->descripcion ?? '-' }}</span></td>
                                        <td><span class="tag tag-secondary">{{ $detalle->insumo->unidadMedida->descripcion ?? '-' }}</span></td>
                                        <td class="text-center">{{ number_format($detalle->cantidad_solicitada, 2, ',', '.') }}</td>
                                        <td class="text-center">
                                            @if(is_null($detalle->cantidad_entregada))
                                                <span class="text-muted">—</span>
                                            @else
                                                {{ number_format($detalle->cantidad_entregada, 2, ',', '.') }}
                                            @endif
                                        </td>
                                        <td>{{ $detalle->observacion ?: '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Sin insumos</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Modal de anulación --}}
    <div class="modal fade" id="modalAnular" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Anular Solicitud</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formAnular" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <p class="mb-2">¿Está seguro que desea anular la solicitud <strong id="anularNumero"></strong>?</p>
                        <p class="text-muted mb-0" style="font-size:0.85rem;">
                            Esta acción no se puede revertir y la solicitud ya no podrá editarse.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-ban me-2"></i>Anular Solicitud
                        </button>
                    </div>
                </form>
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
    grid-template-columns: 1fr 1fr 1fr 1.5fr 1fr;
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

#solicitudesTable {
    width: 100%;
    min-width: 1000px;
    border-collapse: collapse;
    table-layout: fixed;
}
#solicitudesTable thead th {
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
#solicitudesTable tbody td {
    padding: 0.55rem 0.65rem;
    font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
#solicitudesTable tbody tr:hover { background: #f8fafc; }
#solicitudesTable tbody tr:last-child td { border-bottom: none; }

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
.btn-icon.danger:hover { background: #fef2f2; color: #dc2626; border-color: #fecaca; }

/* Modal: detalle de la solicitud */
.detalle-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
    margin-bottom: 1rem;
}
.detalle-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 500;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.2rem;
}
.detalle-value { font-size: 0.85rem; color: #374151; }
.detalle-table { width: 100%; border-collapse: collapse; }
.detalle-table thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 0.5rem 0.6rem;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.detalle-table tbody td {
    padding: 0.5rem 0.6rem;
    font-size: 0.8rem;
    border-bottom: 1px solid #f1f5f9;
    color: #374151;
    vertical-align: middle;
}
.detalle-table tbody tr:last-child td { border-bottom: none; }
@media (max-width: 576px) {
    .detalle-grid { grid-template-columns: 1fr; }
}

/* Estado */
.estado { display: inline-flex; align-items: center; gap: 0.4rem; }
.estado-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #94a3b8; flex-shrink: 0; }
.estado-pendiente .estado-dot  { background: #f59e0b; }
.estado-confirmado .estado-dot { background: #10b981; }
.estado-anulado .estado-dot    { background: #ef4444; }

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
function abrirAnular(id, numero) {
    document.getElementById('formAnular').action = `{{ url('solicitud_materiales') }}/${id}/anular`;
    document.getElementById('anularNumero').textContent = '#' + numero;
    new bootstrap.Modal(document.getElementById('modalAnular')).show();
}
</script>
