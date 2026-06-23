<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Insumos Utilizados</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-boxes"></i> Insumos Utilizados</h2>
                    <small>Gestión de los insumos utilizados en cada orden de servicio</small>
                </div>
                <a href="{{ route('insumos_utilizados.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Cargar Insumos Utilizados
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
                    <form method="GET" action="{{ route('insumos_utilizados.index') }}">
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
                                    <a href="{{ route('insumos_utilizados.index') }}" class="btn btn-outline-secondary btn-sm" title="Limpiar filtros">
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
                    <span>Lista de Insumos Utilizados</span>
                    <span class="results-count">{{ $insumosUtilizados->count() }} registro(s)</span>
                </div>
                <div class="card-body p-0" style="flex:1; display:flex; flex-direction:column;">
                    <div class="table-container">
                        @if($insumosUtilizados->count() > 0)
                            <table id="insumosUtilizadosTable">
                                <thead>
                                    <tr>
                                        <th style="width:90px;" class="text-center">Nro</th>
                                        <th>Obra</th>
                                        <th style="width:100px;" class="text-center">Orden Servicio</th>
                                        <th style="width:110px;">Estado</th>
                                        <th style="width:100px;" class="text-center">Fecha Registro</th>
                                        <th style="width:100px;">Usuario</th>
                                        <th style="width:160px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($insumosUtilizados as $insumoUtilizado)
                                        <tr>
                                            <td class="text-center"><strong>{{ $insumoUtilizado->nro }}</strong></td>
                                            <td>
                                                <span class="cell-text" title="{{ $insumoUtilizado->obra->descripcion ?? '-' }}">{{ $insumoUtilizado->obra->descripcion ?? '-' }}</span>
                                            </td>
                                            <td class="text-center">{{ $insumoUtilizado->ordenServicio->nro ?? '-' }}</td>
                                            <td>
                                                @switch($insumoUtilizado->estado->descripcion ?? '')
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
                                                        <span class="estado"><i class="estado-dot"></i>{{ $insumoUtilizado->estado->descripcion ?? '-' }}</span>
                                                @endswitch
                                            </td>
                                            <td class="text-center">
                                                {{ $insumoUtilizado->fecha_registro ? $insumoUtilizado->fecha_registro->format('d/m/Y') : '-' }}
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $insumoUtilizado->usuario->usuario ?? '-' }}">{{ $insumoUtilizado->usuario->usuario ?? '-' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="action-buttons">
                                                    <button type="button" class="btn-icon btn-icon-secondary" title="Ver" data-bs-toggle="modal" data-bs-target="#verModal{{ $insumoUtilizado->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if($insumoUtilizado->estado_id == 3 && $insumoUtilizado->ordenServicio->estado_id == 3)
                                                        <a href="{{ route('insumos_utilizados.edit', $insumoUtilizado->id) }}" class="btn-icon btn-icon-primary" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
                                                    @if($insumoUtilizado->estado_id == 3 && $insumoUtilizado->ordenServicio->estado_id == 3)
                                                        <button type="button" class="btn-icon btn-icon-success btn-confirmar" title="Confirmar"
                                                                data-bs-toggle="modal" data-bs-target="#confirmarModal"
                                                                data-nro="{{ $insumoUtilizado->nro }}"
                                                                data-url="{{ route('insumos_utilizados.confirmar', $insumoUtilizado->id) }}">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif
                                                    @if(in_array($insumoUtilizado->estado_id, [3, 4]) && $insumoUtilizado->ordenServicio->estado_id == 3)
                                                        <button type="button" class="btn-icon btn-icon-danger btn-anular" title="Anular"
                                                                data-bs-toggle="modal" data-bs-target="#anularModal"
                                                                data-nro="{{ $insumoUtilizado->nro }}"
                                                                data-estado="{{ $insumoUtilizado->estado_id }}"
                                                                data-url="{{ route('insumos_utilizados.anular', $insumoUtilizado->id) }}">
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
                                <h5 class="text-muted mb-2">No hay insumos utilizados registrados</h5>
                                <p class="text-muted mb-3" style="font-size:0.85rem;">
                                    No se encontraron registros con los filtros aplicados.
                                </p>
                                <a href="{{ route('insumos_utilizados.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-2"></i>Cargar el Primer Registro
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Modales de Ver Insumo Utilizado --}}
    @foreach($insumosUtilizados as $insumoUtilizado)
        <div class="modal fade" id="verModal{{ $insumoUtilizado->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-boxes me-2"></i>Insumos Utilizados Nro {{ $insumoUtilizado->nro }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="detail-box mb-3">
                            <div class="detail-box-title">Datos Generales</div>
                            <div class="detail-row"><i class="fas fa-map-marker-alt"></i><span><strong>Obra:</strong> {{ $insumoUtilizado->obra->descripcion ?? '-' }}</span></div>
                            <div class="detail-row"><i class="fas fa-tasks"></i><span><strong>Orden de Servicio:</strong> {{ $insumoUtilizado->ordenServicio->nro ?? '-' }}</span></div>
                            <div class="detail-row"><i class="fas fa-info-circle"></i><span><strong>Estado:</strong> {{ $insumoUtilizado->estado->descripcion ?? '-' }}</span></div>
                            <div class="detail-row"><i class="fas fa-calendar"></i><span><strong>Fecha de Registro:</strong> {{ $insumoUtilizado->fecha_registro ? $insumoUtilizado->fecha_registro->format('d/m/Y') : '-' }}</span></div>
                            <div class="detail-row"><i class="fas fa-user"></i><span><strong>Usuario:</strong> {{ $insumoUtilizado->usuario->usuario ?? '-' }}</span></div>
                            <div class="detail-row"><i class="fas fa-comment"></i><span><strong>Observación:</strong> {{ $insumoUtilizado->observacion ?? '-' }}</span></div>
                        </div>

                        <div class="detail-box">
                            <div class="detail-box-title">Insumos</div>
                            @forelse($insumoUtilizado->detalles as $detalle)
                                <div class="detail-row"><i class="fas fa-box"></i><span>{{ $detalle->insumo->descripcion ?? '-' }} — Cantidad: {{ rtrim(rtrim(number_format($detalle->cantidad, 2, ',', '.'), '0'), ',') }}</span></div>
                            @empty
                                <div class="detail-row"><span class="text-muted">Sin insumos registrados.</span></div>
                            @endforelse
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Modal de confirmación para Confirmar --}}
    <div class="modal fade" id="confirmarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-check-circle text-success me-2"></i>Confirmar Insumos Utilizados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">¿Está seguro que desea confirmar el registro <strong id="confirmarNro"></strong>? Esto descontará las cantidades utilizadas del inventario de la obra.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="confirmarForm" method="POST" action="">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success"><i class="fas fa-check me-2"></i>Confirmar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de confirmación para Anular --}}
    <div class="modal fade" id="anularModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Anular Insumos Utilizados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0" id="anularMensaje"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="anularForm" method="POST" action="">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger"><i class="fas fa-ban me-2"></i>Anular</button>
                    </form>
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

#insumosUtilizadosTable {
    width: 100%;
    min-width: 1120px;
    border-collapse: collapse;
    table-layout: fixed;
}
#insumosUtilizadosTable thead th {
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
#insumosUtilizadosTable tbody td {
    padding: 0.55rem 0.65rem;
    font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
#insumosUtilizadosTable tbody tr:hover { background: #f8fafc; }
#insumosUtilizadosTable tbody tr:last-child td { border-bottom: none; }

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
    align-items: center;
    justify-content: center;
    gap: 0.3rem;
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
.btn-icon-primary { color: #2563eb; }
.btn-icon-primary:hover { background: #eff6ff; border-color: #bfdbfe; color: #1d4ed8; }
.btn-icon-success { color: #16a34a; }
.btn-icon-success:hover { background: #f0fdf4; border-color: #bbf7d0; color: #15803d; }
.btn-icon-danger { color: #ef4444; }
.btn-icon-danger:hover { background: #fef2f2; border-color: #fecaca; color: #dc2626; }

/* Empty state */
.empty-state {
    min-height: 320px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 2rem; color: #94a3b8; text-align: center;
}
.empty-state i { color: #cbd5e1; }

/* ── Modal Ver ── */
.detail-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.6rem 0.75rem;
    font-size: 0.8rem;
    color: #374151;
}
.detail-box-title {
    font-size: 0.7rem;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.4rem;
}
.detail-row { display: flex; align-items: flex-start; gap: 0.4rem; margin-bottom: 0.25rem; }
.detail-row:last-child { margin-bottom: 0; }
.detail-row i { color: #94a3b8; width: 14px; text-align: center; margin-top: 0.15rem; }

@media (max-width: 768px) {
    .table-container { font-size: 0.875rem; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-confirmar').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('confirmarNro').textContent = this.dataset.nro;
            document.getElementById('confirmarForm').action = this.dataset.url;
        });
    });

    document.querySelectorAll('.btn-anular').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('anularForm').action = this.dataset.url;

            const mensaje = this.dataset.estado == '4'
                ? `El registro ${this.dataset.nro} está Confirmado: al anularlo volverá a estado Pendiente y se restituirán las cantidades descontadas del inventario de la obra.`
                : `¿Está seguro que desea anular el registro ${this.dataset.nro}? Esta acción no se puede deshacer.`;

            document.getElementById('anularMensaje').textContent = mensaje;
        });
    });
});
</script>
