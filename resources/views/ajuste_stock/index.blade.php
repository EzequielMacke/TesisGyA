<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ajuste de Inventario - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-sliders-h"></i> Ajuste de Inventario</h2>
                    <small>Registro de entradas y salidas manuales de stock</small>
                </div>
                @if(session('permisos.aju_inv.agregar'))
                <a href="{{ route('ajuste_stocks.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo Ajuste
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
                    <form method="GET" action="{{ route('ajuste_stocks.index') }}">
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
                                <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="Obra, depósito, observación...">
                            </div>
                            <div class="toolbar-item toolbar-actions">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                        <i class="fas fa-search me-1"></i>Buscar
                                    </button>
                                    <a href="{{ route('ajuste_stocks.index') }}" class="btn btn-outline-secondary btn-sm" title="Limpiar filtros">
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
                    <span>Lista de Ajustes</span>
                    <span class="results-count">{{ $ajustes->count() }} ajuste(s)</span>
                </div>
                <div class="card-body p-0" style="flex:1; display:flex; flex-direction:column;">
                    <div class="table-container">
                        @if($ajustes->count() > 0)
                            <table id="ajustesTable">
                                <thead>
                                    <tr>
                                        <th style="width:80px;" class="text-center">N°</th>
                                        <th style="width:100px;" class="text-center">Fecha</th>
                                        <th>Obra</th>
                                        <th>Depósito</th>
                                        <th style="width:110px;">Estado</th>
                                        <th>Insumos</th>
                                        <th>Observación</th>
                                        <th>Usuario</th>
                                        <th style="width:80px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ajustes as $ajuste)
                                        <tr>
                                            <td class="text-center">
                                                <span class="tag">#{{ str_pad($ajuste->id, 3, '0', STR_PAD_LEFT) }}</span>
                                            </td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($ajuste->fecha)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                @if($ajuste->obra)
                                                    <span class="tag tag-secondary" title="{{ $ajuste->obra->descripcion }}">
                                                        {{ $ajuste->obra->descripcion }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($ajuste->deposito)
                                                    <span class="tag tag-secondary" title="{{ $ajuste->deposito->descripcion }}">
                                                        {{ $ajuste->deposito->descripcion }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @switch($ajuste->estado_id)
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
                                                        <span class="estado"><i class="estado-dot"></i>{{ $ajuste->estado->descripcion ?? '-' }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($ajuste->detalles && $ajuste->detalles->count())
                                                    <span class="tag tag-secondary" title="{{ $ajuste->detalles->pluck('insumo.descripcion')->filter()->join(', ') }}">
                                                        {{ $ajuste->detalles->count() }} insumo(s)
                                                    </span>
                                                @else
                                                    <span class="text-muted">Sin detalle</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $ajuste->observacion }}">
                                                    {{ $ajuste->observacion ?? '—' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $ajuste->usuario->name ?? '-' }}">
                                                    {{ $ajuste->usuario->name ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn-icon" title="Ver ajuste"
                                                            onclick="abrirVer({{ $ajuste->id }})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if($ajuste->estado_id == 3)
                                                        @if(session('permisos.aju_inv.editar'))
                                                        <a href="{{ route('ajuste_stocks.edit', $ajuste->id) }}" class="btn-icon" title="Editar ajuste">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
                                                        @endif
                                                        @if(session('permisos.aju_inv.anular'))
                                                        <button type="button" class="btn-icon success" title="Confirmar ajuste"
                                                                onclick="abrirConfirmar({{ $ajuste->id }}, '{{ str_pad($ajuste->id, 3, '0', STR_PAD_LEFT) }}')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn-icon danger" title="Anular ajuste"
                                                                onclick="abrirAnular({{ $ajuste->id }}, '{{ str_pad($ajuste->id, 3, '0', STR_PAD_LEFT) }}')">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-sliders-h fa-3x mb-3"></i>
                                <h5 class="text-muted mb-2">No hay ajustes de inventario</h5>
                                <p class="text-muted mb-3" style="font-size:0.85rem;">
                                    No se encontraron ajustes con los filtros aplicados.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal ver resumen --}}
    <div class="modal fade" id="modalVer" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-clipboard-list text-primary me-2"></i>
                        Resumen del Ajuste <span id="verNumero"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    {{-- Info superior --}}
                    <div class="ver-info-grid">
                        <div class="ver-info-item">
                            <span class="ver-label">Estado</span>
                            <span id="verEstado"></span>
                        </div>
                        <div class="ver-info-item">
                            <span class="ver-label">Fecha</span>
                            <span id="verFecha" class="ver-value"></span>
                        </div>
                        <div class="ver-info-item">
                            <span class="ver-label">Destino</span>
                            <span id="verDestino" class="ver-value"></span>
                        </div>
                        <div class="ver-info-item">
                            <span class="ver-label">Usuario</span>
                            <span id="verUsuario" class="ver-value"></span>
                        </div>
                        <div class="ver-info-item ver-info-full">
                            <span class="ver-label">Observación</span>
                            <span id="verObservacion" class="ver-value"></span>
                        </div>
                    </div>
                    {{-- Tabla detalles --}}
                    <div class="ver-detalles-section">
                        <div class="ver-detalles-header">
                            <i class="fas fa-list me-1"></i> Insumos ajustados
                            <span id="verCantDetalles" class="ver-count-badge"></span>
                        </div>
                        <div style="overflow-x:auto;">
                            <table class="ver-table">
                                <thead>
                                    <tr>
                                        <th>Insumo</th>
                                        <th style="width:90px;" class="text-center">Tipo</th>
                                        <th style="width:80px;" class="text-center">Cantidad</th>
                                        <th>Motivo</th>
                                        <th>Observación</th>
                                    </tr>
                                </thead>
                                <tbody id="verTablaDetalles"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal confirmar --}}
    <div class="modal fade" id="modalConfirmar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-check-circle text-success me-2"></i>Confirmar Ajuste</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formConfirmar" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <p class="mb-2">¿Confirma el ajuste de inventario <strong id="confirmarNumero"></strong>?</p>
                        <p class="text-muted mb-0" style="font-size:0.85rem;">
                            Una vez confirmado, el ajuste aplicará los cambios al stock y no podrá anularse.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-2"></i>Confirmar Ajuste
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal anular --}}
    <div class="modal fade" id="modalAnular" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Anular Ajuste</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formAnular" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <p class="mb-2">¿Está seguro que desea anular el ajuste <strong id="anularNumero"></strong>?</p>
                        <p class="text-muted mb-0" style="font-size:0.85rem;">
                            Esta acción no se puede deshacer.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-ban me-2"></i>Anular Ajuste
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>

@php
$ajustesJson = $ajustes->keyBy('id')->map(fn($a) => [
    'numero'      => '#' . str_pad($a->id, 3, '0', STR_PAD_LEFT),
    'fecha'       => \Carbon\Carbon::parse($a->fecha)->format('d/m/Y'),
    'estado_id'   => $a->estado_id,
    'estado'      => match($a->estado_id) { 3 => 'Pendiente', 4 => 'Confirmado', 5 => 'Anulado', default => '-' },
    'obra'        => $a->obra?->descripcion,
    'deposito'    => $a->deposito?->descripcion,
    'observacion' => $a->observacion ?: '—',
    'usuario'     => $a->usuario?->name ?? '-',
    'detalles'    => $a->detalles->map(fn($d) => [
        'insumo'      => $d->insumo?->descripcion ?? '-',
        'tipo'        => $d->tipo_ajuste == 1 ? 'Entrada' : 'Salida',
        'tipo_class'  => $d->tipo_ajuste == 1 ? 'entrada' : 'salida',
        'cantidad'    => $d->cantidad,
        'motivo'      => $d->motivo ?? '-',
        'observacion' => $d->observacion ?: '-',
    ])->values(),
])->toArray();
@endphp

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

#ajustesTable {
    width: 100%;
    min-width: 1000px;
    border-collapse: collapse;
    table-layout: fixed;
}
#ajustesTable thead th {
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
#ajustesTable tbody td {
    padding: 0.55rem 0.65rem;
    font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
#ajustesTable tbody tr:hover { background: #f8fafc; }
#ajustesTable tbody tr:last-child td { border-bottom: none; }

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
.btn-icon.success:hover { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }

/* Estado */
.estado { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.82rem; }
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

/* ── Modal Ver ── */
.ver-info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
}
.ver-info-full { grid-column: 1 / -1; }
.ver-info-item { padding: 0.3rem 0.5rem; }
.ver-label {
    display: block;
    font-size: 0.68rem;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.2rem;
}
.ver-value { font-size: 0.85rem; color: #1e293b; }

.ver-detalles-section { padding: 0; }
.ver-detalles-header {
    padding: 0.6rem 1.25rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #475569;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.ver-count-badge {
    background: #eff6ff;
    color: #2563eb;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 0.1rem 0.45rem;
    border-radius: 10px;
}
.ver-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.82rem;
}
.ver-table thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    padding: 0.55rem 1rem;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
}
.ver-table tbody td {
    padding: 0.55rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    color: #374151;
    vertical-align: middle;
}
.ver-table tbody tr:last-child td { border-bottom: none; }
.ver-table tbody tr:hover { background: #f8fafc; }
.badge-entrada {
    display: inline-block;
    padding: 0.18rem 0.55rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 700;
    background: #f0fdf4;
    color: #16a34a;
}
.badge-salida {
    display: inline-block;
    padding: 0.18rem 0.55rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 700;
    background: #fef2f2;
    color: #dc2626;
}
@media (max-width: 700px) {
    .ver-info-grid { grid-template-columns: 1fr 1fr; }
}
</style>

<script>
const ajustesData = @json($ajustesJson);

function abrirVer(id) {
    const d = ajustesData[id];
    if (!d) return;

    document.getElementById('verNumero').textContent = d.numero;
    document.getElementById('verFecha').textContent = d.fecha;
    document.getElementById('verUsuario').textContent = d.usuario;
    document.getElementById('verObservacion').textContent = d.observacion;

    // Estado
    const estadoClasses = { 3: 'estado estado-pendiente', 4: 'estado estado-confirmado', 5: 'estado estado-anulado' };
    document.getElementById('verEstado').innerHTML =
        `<span class="${estadoClasses[d.estado_id] || 'estado'}"><i class="estado-dot"></i>${d.estado}</span>`;

    // Destino
    document.getElementById('verDestino').textContent = d.obra || d.deposito || '—';

    // Detalles
    const tbody = document.getElementById('verTablaDetalles');
    document.getElementById('verCantDetalles').textContent = d.detalles.length + ' insumo(s)';
    tbody.innerHTML = d.detalles.map(det => `
        <tr>
            <td>${det.insumo}</td>
            <td class="text-center"><span class="badge-${det.tipo_class}">${det.tipo}</span></td>
            <td class="text-center"><strong>${det.cantidad}</strong></td>
            <td>${det.motivo}</td>
            <td style="color:#94a3b8;">${det.observacion}</td>
        </tr>
    `).join('') || '<tr><td colspan="5" class="text-center text-muted py-3">Sin detalles</td></tr>';

    new bootstrap.Modal(document.getElementById('modalVer')).show();
}

function abrirConfirmar(id, numero) {
    document.getElementById('formConfirmar').action = `{{ url('ajuste_stocks') }}/${id}/confirmar`;
    document.getElementById('confirmarNumero').textContent = '#' + numero;
    new bootstrap.Modal(document.getElementById('modalConfirmar')).show();
}

function abrirAnular(id, numero) {
    document.getElementById('formAnular').action = `{{ url('ajuste_stocks') }}/${id}/anular`;
    document.getElementById('anularNumero').textContent = '#' + numero;
    new bootstrap.Modal(document.getElementById('modalAnular')).show();
}
</script>
