<!-- filepath: c:\laragon\www\TesisGyA\resources\views\solicitud_servicio\index.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitudes de Servicio - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-file-alt"></i> Solicitudes de Servicio</h2>
                    <small>Gestión de solicitudes de servicio de clientes</small>
                </div>
                @if(session('permisos.sol_ser.agregar'))
                <a href="{{ route('solicitud_servicio.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Nueva Solicitud
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
                    <form method="GET" action="{{ route('solicitud_servicio.index') }}">
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
                                <label class="form-label">Cliente</label>
                                <select class="form-select form-select-sm" name="cliente_id" onchange="this.form.submit()">
                                    <option value="">Todos</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->razon_social }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="toolbar-item">
                                <label class="form-label">Desde</label>
                                <input type="date" class="form-control form-control-sm" name="fecha_desde" value="{{ request('fecha_desde') }}" onchange="this.form.submit()">
                            </div>
                            <div class="toolbar-item">
                                <label class="form-label">Buscar</label>
                                <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="Cliente, obra, observación...">
                            </div>
                            <div class="toolbar-item toolbar-actions">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                        <i class="fas fa-search me-1"></i>Buscar
                                    </button>
                                    <a href="{{ route('solicitud_servicio.index') }}" class="btn btn-outline-secondary btn-sm" title="Limpiar filtros">
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
                    <span class="results-count">{{ $solicitudes->total() }} solicitud(es)</span>
                </div>
                <div class="card-body p-0" style="flex:1; display:flex; flex-direction:column;">
                    <div class="table-container">
                        @if($solicitudes->count() > 0)
                            <table id="solicitudesTable">
                                <thead>
                                    <tr>
                                        <th style="width:80px;" class="text-center">N°</th>
                                        <th>Cliente</th>
                                        <th>Obra</th>
                                        <th>Servicios solicitados</th>
                                        <th style="width:130px;">Registrado por</th>
                                        <th style="width:100px;" class="text-center">Fecha</th>
                                        <th style="width:110px;">Estado</th>
                                        <th>Observación</th>
                                        <th style="width:70px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($solicitudes as $solicitud)
                                        <tr>
                                            <td class="text-center">
                                                <span class="tag">#{{ str_pad($solicitud->id, 3, '0', STR_PAD_LEFT) }}</span>
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $solicitud->cliente->razon_social }}">{{ $solicitud->cliente->razon_social }}</span>
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $solicitud->obra->descripcion }}">{{ $solicitud->obra->descripcion }}</span>
                                            </td>
                                            <td>
                                                @if($solicitud->detalles && $solicitud->detalles->count())
                                                    @php $serviciosNombres = $solicitud->detalles->pluck('servicio.descripcion')->filter()->join(', '); @endphp
                                                    <span class="cell-text" title="{{ $serviciosNombres }}">{{ $serviciosNombres }}</span>
                                                @else
                                                    <span class="text-muted">Sin servicios</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $solicitud->usuario->usuario ?? '-' }}">{{ $solicitud->usuario->usuario ?? '-' }}</span>
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
                                                        <span class="estado"><i class="estado-dot"></i>{{ $solicitud->estado->descripcion }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($solicitud->observacion)
                                                    <span class="cell-text" title="{{ $solicitud->observacion }}">{{ Str::limit($solicitud->observacion, 28) }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    @if($solicitud->estado_id == 3)
                                                        @if(session('permisos.sol_ser.editar'))
                                                        <a href="{{ route('solicitud_servicio.edit', $solicitud->id) }}" class="btn-icon" title="Editar">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        @endif
                                                        @if(session('permisos.sol_ser.anular'))
                                                        <button type="button" class="btn-icon danger" title="Anular"
                                                                onclick="abrirAnular({{ $solicitud->id }}, '{{ str_pad($solicitud->id, 3, '0', STR_PAD_LEFT) }}')">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                        @endif
                                                    @endif
                                                    <button type="button" class="btn-icon" title="Ver Detalle"
                                                            onclick="abrirVerDetalle({{ $solicitud->id }})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-file-alt fa-3x mb-3"></i>
                                <h5 class="text-muted mb-2">No hay solicitudes de servicio</h5>
                                <p class="text-muted mb-3" style="font-size:0.85rem;">
                                    No se encontraron solicitudes con los filtros aplicados.
                                </p>
                                <a href="{{ route('solicitud_servicio.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-2"></i>Crear Primera Solicitud
                                </a>
                            </div>
                        @endif
                    </div>
                    @if($solicitudes->hasPages())
                        <div class="pagination-wrapper">
                            {{ $solicitudes->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- Modal de ver detalle --}}
    <div class="modal fade" id="modalVerDetalle" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-file-alt text-primary me-2"></i>Detalle de Solicitud <span id="verNumero"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="detail-row"><i class="fas fa-building"></i><span><strong>Cliente:</strong> <span id="verCliente"></span></span></div>
                    <div class="detail-row"><i class="fas fa-map-marker-alt"></i><span><strong>Obra:</strong> <span id="verObra"></span></span></div>
                    <div class="detail-row"><i class="fas fa-user"></i><span><strong>Registrado por:</strong> <span id="verUsuario"></span></span></div>
                    <div class="detail-row"><i class="fas fa-calendar"></i><span><strong>Fecha:</strong> <span id="verFecha"></span></span></div>
                    <div class="detail-row"><i class="fas fa-flag"></i><span><strong>Estado:</strong> <span id="verEstado"></span></span></div>
                    <div class="detail-row"><i class="fas fa-sticky-note"></i><span><strong>Observación:</strong> <span id="verObservacion"></span></span></div>
                    <hr>
                    <div class="mb-2"><strong><i class="fas fa-tools me-1 text-muted"></i>Servicios solicitados</strong></div>
                    <ul id="verServicios" class="servicios-detalle-list"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

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
                    <div class="modal-body">
                        <p class="mb-2">¿Está seguro que desea anular la solicitud <strong id="anularNumero"></strong>?</p>
                        <p class="text-muted mb-0" style="font-size:0.85rem;">
                            Esta acción no se puede deshacer.
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
    grid-template-columns: 1fr 1.5fr 1fr 1.5fr 1fr;
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
    min-width: 1120px;
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

/* ── Modal Ver Detalle ── */
.detail-row { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; font-size: 0.85rem; color: #374151; }
.detail-row:last-child { margin-bottom: 0; }
.detail-row i { color: #94a3b8; width: 16px; text-align: center; }
.servicios-detalle-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}
.servicios-detalle-list li {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.4rem 0.65rem;
    font-size: 0.82rem;
    color: #374151;
}
</style>

<script>
@php
    $solicitudesData = $solicitudes->mapWithKeys(function ($s) {
        return [$s->id => [
            'numero' => str_pad($s->id, 3, '0', STR_PAD_LEFT),
            'cliente' => $s->cliente->razon_social,
            'obra' => $s->obra->descripcion,
            'usuario' => $s->usuario->usuario ?? '-',
            'fecha' => \Carbon\Carbon::parse($s->fecha)->format('d/m/Y'),
            'estado' => $s->estado->descripcion,
            'observacion' => $s->observacion,
            'servicios' => $s->detalles->pluck('servicio.descripcion')->filter()->values(),
        ]];
    });
@endphp
var solicitudesData = @json($solicitudesData);

function abrirVerDetalle(id) {
    var data = solicitudesData[id];
    if (!data) return;

    document.getElementById('verNumero').textContent = '#' + data.numero;
    document.getElementById('verCliente').textContent = data.cliente;
    document.getElementById('verObra').textContent = data.obra;
    document.getElementById('verUsuario').textContent = data.usuario;
    document.getElementById('verFecha').textContent = data.fecha;
    document.getElementById('verEstado').textContent = data.estado;
    document.getElementById('verObservacion').textContent = data.observacion || 'Sin observación';

    var lista = document.getElementById('verServicios');
    lista.innerHTML = '';
    if (data.servicios.length > 0) {
        data.servicios.forEach(function (servicio) {
            var li = document.createElement('li');
            li.textContent = servicio;
            lista.appendChild(li);
        });
    } else {
        var li = document.createElement('li');
        li.textContent = 'Sin servicios';
        li.classList.add('text-muted');
        lista.appendChild(li);
    }

    new bootstrap.Modal(document.getElementById('modalVerDetalle')).show();
}

function abrirAnular(id, numero) {
    document.getElementById('formAnular').action = `{{ url('solicitud_servicio') }}/${id}/anular`;
    document.getElementById('anularNumero').textContent = '#' + numero;
    new bootstrap.Modal(document.getElementById('modalAnular')).show();
}
</script>
