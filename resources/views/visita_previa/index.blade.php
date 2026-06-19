<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Visitas Previas</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-clipboard-list"></i> Visitas Previas</h2>
                    <small>Gestión de visitas previas a obras de clientes</small>
                </div>
                <a href="{{ route('visita_previa.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nueva Visita Previa
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
                    <form method="GET" action="{{ route('visita_previa.index') }}">
                        <div class="toolbar-grid">
                            <div class="toolbar-item">
                                <label class="form-label">Cliente</label>
                                <input type="text" class="form-control form-control-sm" name="cliente" value="{{ request('cliente') }}" placeholder="Razón social...">
                            </div>
                            <div class="toolbar-item">
                                <label class="form-label">Obra</label>
                                <input type="text" class="form-control form-control-sm" name="obra" value="{{ request('obra') }}" placeholder="Descripción...">
                            </div>
                            <div class="toolbar-item">
                                <label class="form-label">Estado</label>
                                <select class="form-select form-select-sm" name="estado_id">
                                    <option value="">Todos</option>
                                    @foreach($estados as $estado)
                                        <option value="{{ $estado->id }}" {{ request('estado_id') == $estado->id ? 'selected' : '' }}>
                                            {{ $estado->descripcion }}
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
                            <div class="toolbar-item">
                                <label class="form-label">ID Solicitud</label>
                                <input type="number" class="form-control form-control-sm" name="solicitud_id" value="{{ request('solicitud_id') }}" placeholder="#">
                            </div>
                            <div class="toolbar-item toolbar-actions">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                        <i class="fas fa-search me-1"></i>Buscar
                                    </button>
                                    <a href="{{ route('visita_previa.index') }}" class="btn btn-outline-secondary btn-sm" title="Limpiar filtros">
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
                    <span>Lista de Visitas Previas</span>
                    <span class="results-count">{{ $visitas->total() }} visita(s)</span>
                </div>
                <div class="card-body p-0" style="flex:1; display:flex; flex-direction:column;">
                    <div class="table-container">
                        @if($visitas->count() > 0)
                            <table id="visitasTable">
                                <thead>
                                    <tr>
                                        <th style="width:60px;" class="text-center">ID</th>
                                        <th>Cliente</th>
                                        <th>Obra</th>
                                        <th style="width:90px;" class="text-center">Solicitud</th>
                                        <th style="width:100px;" class="text-center">Fecha Visita</th>
                                        <th style="width:110px;">Estado</th>
                                        <th>Usuario</th>
                                        <th>Observación</th>
                                        <th style="width:70px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($visitas as $visita)
                                        <tr>
                                            <td class="text-center"><strong>{{ $visita->id }}</strong></td>
                                            <td>
                                                <span class="cell-text" title="{{ $visita->cliente->razon_social ?? '-' }}">{{ $visita->cliente->razon_social ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $visita->obra->descripcion ?? '-' }}">{{ $visita->obra->descripcion ?? '-' }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($visita->solicitud_servicio_id)
                                                    <span class="tag tag-secondary">#{{ $visita->solicitud_servicio_id }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($visita->fecha_visita)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                @switch($visita->estado_id)
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
                                                        <span class="estado"><i class="estado-dot"></i>{{ $visita->estado->descripcion ?? '-' }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $visita->usuario->usuario ?? '-' }}">{{ $visita->usuario->usuario ?? '-' }}</span>
                                            </td>
                                            <td>
                                                @if($visita->observacion)
                                                    <span class="cell-text" title="{{ $visita->observacion }}">{{ Str::limit($visita->observacion, 28) }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('visita_previa.show', $visita->id) }}" class="btn-icon" title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($visita->estado_id == 3)
                                                        <a href="{{ route('visita_previa.edit', $visita->id) }}" class="btn-icon" title="Editar">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        <button type="button" class="btn-icon danger" title="Anular"
                                                                onclick="abrirAnular({{ $visita->id }})">
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
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <h5 class="text-muted mb-2">No hay visitas previas registradas</h5>
                                <p class="text-muted mb-3" style="font-size:0.85rem;">
                                    No se encontraron visitas previas con los filtros aplicados.
                                </p>
                                <a href="{{ route('visita_previa.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-2"></i>Crear la Primera Visita Previa
                                </a>
                            </div>
                        @endif
                    </div>
                    @if($visitas->hasPages())
                        <div class="pagination-wrapper">
                            {{ $visitas->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- Modal de anulación --}}
    <div class="modal fade" id="modalAnular" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Anular Visita Previa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formAnular" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="mb-2">¿Está seguro que desea anular la visita previa <strong id="anularNumero"></strong>?</p>
                        <p class="text-muted mb-0" style="font-size:0.85rem;">
                            Esta acción no se puede deshacer.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-ban me-2"></i>Anular Visita
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
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
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
    .page-header { flex-direction: column; align-items: flex-start; }
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

#visitasTable {
    width: 100%;
    min-width: 1000px;
    border-collapse: collapse;
    table-layout: fixed;
}
#visitasTable thead th {
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
#visitasTable tbody td {
    padding: 0.55rem 0.65rem;
    font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
#visitasTable tbody tr:hover { background: #f8fafc; }
#visitasTable tbody tr:last-child td { border-bottom: none; }

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

/* Paginación */
.pagination-wrapper {
    padding: 0.75rem 1rem;
    border-top: 1px solid #e2e8f0;
}
.pagination-wrapper .pagination {
    margin: 0;
    justify-content: flex-end;
}
.pagination-wrapper .page-link {
    font-size: 0.8rem;
    color: #64748b;
    border-color: #e2e8f0;
}
.pagination-wrapper .page-item.active .page-link {
    background-color: #2563eb;
    border-color: #2563eb;
}
.pagination-wrapper .page-link:hover { background-color: #f1f5f9; color: #1e293b; }

@media (max-width: 768px) {
    .table-container { font-size: 0.875rem; }
}
</style>

<script>
function abrirAnular(id) {
    document.getElementById('formAnular').action = `{{ url('visita_previa') }}/${id}/anular`;
    document.getElementById('anularNumero').textContent = '#' + id;
    new bootstrap.Modal(document.getElementById('modalAnular')).show();
}
</script>
