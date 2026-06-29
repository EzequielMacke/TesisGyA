<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reclamos del Cliente</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-exclamation-circle"></i> Reclamos del Cliente</h2>
                    <small>Gestión de los reclamos registrados por obra</small>
                </div>
                <a href="{{ route('reclamos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Registrar Reclamo
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
                    <form method="GET" action="{{ route('reclamos.index') }}">
                        <div class="toolbar-grid">
                            <div class="toolbar-item">
                                <label class="form-label">Obra</label>
                                <select name="obra_id" class="form-select form-select-sm">
                                    <option value="">Todas</option>
                                    @foreach($obras as $obra)
                                        <option value="{{ $obra->id }}" {{ request('obra_id') == $obra->id ? 'selected' : '' }}>{{ $obra->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="toolbar-item">
                                <label class="form-label">Estado</label>
                                <select name="estado_id" class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    @foreach($estados as $estado)
                                        <option value="{{ $estado->id }}" {{ request('estado_id') == $estado->id ? 'selected' : '' }}>{{ $estado->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="toolbar-item">
                                <label class="form-label">Fecha Desde</label>
                                <input type="date" class="form-control form-control-sm" name="fecha_desde" value="{{ request('fecha_desde') }}">
                            </div>
                            <div class="toolbar-item">
                                <label class="form-label">Fecha Hasta</label>
                                <input type="date" class="form-control form-control-sm" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
                            </div>
                            <div class="toolbar-item toolbar-actions">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                        <i class="fas fa-search me-1"></i>Buscar
                                    </button>
                                    <a href="{{ route('reclamos.index') }}" class="btn btn-outline-secondary btn-sm" title="Limpiar filtros">
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
                    <span>Lista de Reclamos</span>
                    <span class="results-count">{{ $reclamos->count() }} registro(s)</span>
                </div>
                <div class="card-body p-0" style="flex:1; display:flex; flex-direction:column;">
                    <div class="table-container">
                        @if($reclamos->count() > 0)
                            <table id="reclamosTable">
                                <thead>
                                    <tr>
                                        <th style="width:50px;" class="text-center">ID</th>
                                        <th style="width:180px;">Cliente</th>
                                        <th style="width:180px;">Obra</th>
                                        <th style="width:110px;" class="text-center">Servicio Realizado</th>
                                        <th style="width:100px;">Estado</th>
                                        <th style="width:90px;" class="text-center">Fecha Registro</th>
                                        <th style="width:90px;">Usuario</th>
                                        <th style="width:90px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reclamos as $reclamo)
                                        <tr>
                                            <td class="text-center"><strong>{{ $reclamo->id }}</strong></td>
                                            <td>
                                                <span class="cell-text" title="{{ $reclamo->cliente->razon_social ?? '-' }}">{{ $reclamo->cliente->razon_social ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $reclamo->obra->descripcion ?? '-' }}">{{ $reclamo->obra->descripcion ?? '-' }}</span>
                                            </td>
                                            <td class="text-center">{{ $reclamo->servicio_realizado_id ? '#' . $reclamo->servicio_realizado_id : '-' }}</td>
                                            <td>
                                                @switch($reclamo->estado->descripcion ?? '')
                                                    @case('Pendiente')
                                                        <span class="estado estado-pendiente"><i class="estado-dot"></i>Pendiente</span>
                                                        @break
                                                    @case('Confirmado')
                                                        <span class="estado estado-confirmado"><i class="estado-dot"></i>Confirmado</span>
                                                        @break
                                                    @case('Activo')
                                                        <span class="estado estado-confirmado"><i class="estado-dot"></i>Activo</span>
                                                        @break
                                                    @case('Anulado')
                                                        <span class="estado estado-anulado"><i class="estado-dot"></i>Anulado</span>
                                                        @break
                                                    @case('Inactivo')
                                                        <span class="estado estado-anulado"><i class="estado-dot"></i>Inactivo</span>
                                                        @break
                                                    @default
                                                        <span class="estado"><i class="estado-dot"></i>{{ $reclamo->estado->descripcion ?? '-' }}</span>
                                                @endswitch
                                            </td>
                                            <td class="text-center">
                                                {{ $reclamo->fecha_registro ? $reclamo->fecha_registro->format('d/m/Y') : '-' }}
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $reclamo->usuario->usuario ?? '-' }}">{{ $reclamo->usuario->usuario ?? '-' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="action-buttons">
                                                    <a href="{{ route('reclamos.show', $reclamo->id) }}" class="btn-icon btn-icon-secondary" title="Ver">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($reclamo->estado_id == 3)
                                                        <a href="{{ route('reclamos.edit', $reclamo->id) }}" class="btn-icon btn-icon-secondary" title="Editar">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        <button type="button" class="btn-icon btn-icon-success" title="Confirmar"
                                                                onclick="abrirConfirmar({{ $reclamo->id }})">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn-icon btn-icon-danger" title="Anular"
                                                                onclick="abrirAnular({{ $reclamo->id }})">
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
                                <h5 class="text-muted mb-2">No hay reclamos registrados</h5>
                                <p class="text-muted mb-3" style="font-size:0.85rem;">
                                    No se encontraron registros con los filtros aplicados.
                                </p>
                                <a href="{{ route('reclamos.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-2"></i>Registrar el Primer Reclamo
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal de confirmación --}}
    <div class="modal fade" id="modalConfirmar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-check-circle text-success me-2"></i>Confirmar Reclamo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formConfirmar" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <p class="mb-0">¿Está seguro que desea confirmar el reclamo <strong id="confirmarNumero"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-2"></i>Confirmar Reclamo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal de anulación --}}
    <div class="modal fade" id="modalAnular" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Anular Reclamo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formAnular" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="mb-2">¿Está seguro que desea anular el reclamo <strong id="anularNumero"></strong>?</p>
                        <p class="text-muted mb-0" style="font-size:0.85rem;">
                            Esta acción no se puede deshacer.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-ban me-2"></i>Anular Reclamo
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

#reclamosTable {
    width: 100%;
    min-width: 890px;
    border-collapse: collapse;
    table-layout: fixed;
}
#reclamosTable thead th {
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
#reclamosTable tbody td {
    padding: 0.55rem 0.65rem;
    font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
#reclamosTable tbody tr:hover { background: #f8fafc; }
#reclamosTable tbody tr:last-child td { border-bottom: none; }

.cell-text {
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Estado */
.estado { display: inline-flex; align-items: center; gap: 0.4rem; }
.estado-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #94a3b8; flex-shrink: 0; }
.estado-pendiente .estado-dot  { background: #f59e0b; }
.estado-confirmado .estado-dot { background: #10b981; }
.estado-anulado .estado-dot    { background: #ef4444; }

/* Acciones */
.action-buttons {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 0.25rem;
}
.btn-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 26px;
    height: 26px;
    border-radius: 5px;
    border: 1px solid #e2e8f0;
    background: #fff;
    font-size: 0.75rem;
    flex-shrink: 0;
    transition: background-color .15s, border-color .15s;
}
.btn-icon-secondary { color: #64748b; }
.btn-icon-secondary:hover { background: #f1f5f9; border-color: #cbd5e1; color: #475569; }
.btn-icon-success { color: #64748b; }
.btn-icon-success:hover { background: #f0fdf4; border-color: #bbf7d0; color: #16a34a; }
.btn-icon-danger { color: #64748b; }
.btn-icon-danger:hover { background: #fef2f2; border-color: #fecaca; color: #dc2626; }

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
function abrirConfirmar(id) {
    document.getElementById('formConfirmar').action = `{{ url('reclamos') }}/${id}/confirmar`;
    document.getElementById('confirmarNumero').textContent = '#' + id;
    new bootstrap.Modal(document.getElementById('modalConfirmar')).show();
}

function abrirAnular(id) {
    document.getElementById('formAnular').action = `{{ url('reclamos') }}/${id}/anular`;
    document.getElementById('anularNumero').textContent = '#' + id;
    new bootstrap.Modal(document.getElementById('modalAnular')).show();
}
</script>
