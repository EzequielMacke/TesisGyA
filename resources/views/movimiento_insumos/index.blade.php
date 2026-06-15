<!-- filepath: c:\laragon\www\TesisGyA\resources\views\movimiento_insumos\index.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Movimiento de Insumos - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-truck"></i> Movimiento de Insumos</h2>
                    <small>Traslados de insumos entre depósitos y obras</small>
                </div>
                <a href="{{ route('movimiento_insumos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo Movimiento
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
                    <form method="GET" action="{{ route('movimiento_insumos.index') }}">
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
                                <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="Remisión, chapa, CI, depósito, obra...">
                            </div>
                            <div class="toolbar-item toolbar-actions">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                        <i class="fas fa-search me-1"></i>Buscar
                                    </button>
                                    <a href="{{ route('movimiento_insumos.index') }}" class="btn btn-outline-secondary btn-sm" title="Limpiar filtros">
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
                    <span>Lista de Movimientos</span>
                    <span class="results-count">{{ $movimientos->count() }} movimiento(s)</span>
                </div>
                <div class="card-body p-0" style="flex:1; display:flex; flex-direction:column;">
                    <div class="table-container">
                        @if($movimientos->count() > 0)
                            <table id="movimientosTable">
                                <thead>
                                    <tr>
                                        <th style="width:80px;" class="text-center">N°</th>
                                        <th style="width:100px;" class="text-center">Fecha</th>
                                        <th>Remisión</th>
                                        <th>Origen</th>
                                        <th>Destino</th>
                                        <th>Vehículo</th>
                                        <th style="width:110px;">Estado</th>
                                        <th>Insumos</th>
                                        <th style="width:80px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($movimientos as $movimiento)
                                        <tr>
                                            <td class="text-center">
                                                <span class="tag">#{{ str_pad($movimiento->id, 3, '0', STR_PAD_LEFT) }}</span>
                                            </td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $movimiento->nro_remision }}">{{ $movimiento->nro_remision }}</span>
                                            </td>
                                            <td>
                                                <span class="tag tag-secondary" title="{{ $movimiento->origenDeposito->descripcion ?? '-' }}">
                                                    {{ $movimiento->origenDeposito->descripcion ?? '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($movimiento->destinoObra)
                                                    <span class="tag tag-secondary" title="{{ $movimiento->destinoObra->descripcion }}">Obra: {{ $movimiento->destinoObra->descripcion }}</span>
                                                @elseif($movimiento->destinoDeposito)
                                                    <span class="tag tag-secondary" title="{{ $movimiento->destinoDeposito->descripcion }}">Depósito: {{ $movimiento->destinoDeposito->descripcion }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $movimiento->vehiculo_chapa }} - {{ $movimiento->tipoVehiculo->descripcion ?? '-' }}">
                                                    {{ $movimiento->vehiculo_chapa }} <span class="text-muted">({{ $movimiento->tipoVehiculo->descripcion ?? '-' }})</span>
                                                </span>
                                            </td>
                                            <td>
                                                @switch($movimiento->estado_id)
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
                                                        <span class="estado"><i class="estado-dot"></i>{{ $movimiento->estado->descripcion ?? '-' }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($movimiento->detalles && $movimiento->detalles->count())
                                                    <span class="tag tag-secondary" title="{{ $movimiento->detalles->pluck('insumo.descripcion')->filter()->join(', ') }}">
                                                        {{ $movimiento->detalles->count() }} insumo(s)
                                                    </span>
                                                @else
                                                    <span class="text-muted">Sin detalle</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('movimiento_insumos.remision', $movimiento->id) }}" class="btn-icon" title="Ver remisión">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($movimiento->estado_id == 3)
                                                        <button type="button" class="btn-icon success" title="Confirmar llegada"
                                                                onclick="abrirConfirmar({{ $movimiento->id }}, '{{ str_pad($movimiento->id, 3, '0', STR_PAD_LEFT) }}')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn-icon danger" title="Anular movimiento"
                                                                onclick="abrirAnular({{ $movimiento->id }}, '{{ str_pad($movimiento->id, 3, '0', STR_PAD_LEFT) }}')">
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
                                <i class="fas fa-truck fa-3x mb-3"></i>
                                <h5 class="text-muted mb-2">No hay movimientos de insumos</h5>
                                <p class="text-muted mb-3" style="font-size:0.85rem;">
                                    No se encontraron movimientos con los filtros aplicados.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal de confirmación de llegada --}}
    <div class="modal fade" id="modalConfirmar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-check-circle text-success me-2"></i>Confirmar Llegada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formConfirmar" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <p class="mb-2">¿Confirma que el movimiento <strong id="confirmarNumero"></strong> llegó correctamente a destino?</p>
                        <p class="text-muted mb-0" style="font-size:0.85rem;">
                            Una vez confirmado, el movimiento ya no podrá anularse.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-2"></i>Confirmar Llegada
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
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Anular Movimiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formAnular" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <p class="mb-2">¿Está seguro que desea anular el movimiento <strong id="anularNumero"></strong>?</p>
                        <p class="text-muted mb-0" style="font-size:0.85rem;">
                            Esta acción revertirá el inventario movido entre el origen y el destino, y no se puede deshacer.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-ban me-2"></i>Anular Movimiento
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

#movimientosTable {
    width: 100%;
    min-width: 1000px;
    border-collapse: collapse;
    table-layout: fixed;
}
#movimientosTable thead th {
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
#movimientosTable tbody td {
    padding: 0.55rem 0.65rem;
    font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
#movimientosTable tbody tr:hover { background: #f8fafc; }
#movimientosTable tbody tr:last-child td { border-bottom: none; }

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
function abrirConfirmar(id, numero) {
    document.getElementById('formConfirmar').action = `{{ url('movimiento_insumos') }}/${id}/confirmar`;
    document.getElementById('confirmarNumero').textContent = '#' + numero;
    new bootstrap.Modal(document.getElementById('modalConfirmar')).show();
}

function abrirAnular(id, numero) {
    document.getElementById('formAnular').action = `{{ url('movimiento_insumos') }}/${id}/anular`;
    document.getElementById('anularNumero').textContent = '#' + numero;
    new bootstrap.Modal(document.getElementById('modalAnular')).show();
}
</script>
