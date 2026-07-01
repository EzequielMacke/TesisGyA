<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notas de Compra - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-file-alt"></i> Notas de Compra</h2>
                    <small>Gestión de notas de crédito y débito de proveedores</small>
                </div>
                @if(session('permisos.not_com.agregar'))
                <a href="{{ route('notas_compra.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nueva Nota
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
                    <form method="GET" action="{{ route('notas_compra.index') }}">
                        <div class="toolbar-grid">
                            <div class="toolbar-item">
                                <label class="form-label">Proveedor</label>
                                <select class="form-select form-select-sm" name="proveedor_id">
                                    <option value="">Todos</option>
                                    @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}" {{ request('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                            {{ $proveedor->razon_social }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="toolbar-item">
                                <label class="form-label">Tipo</label>
                                <select class="form-select form-select-sm" name="tipo_documento_id">
                                    <option value="">Todos</option>
                                    @foreach($tiposDocumento as $tipo)
                                        <option value="{{ $tipo->id }}" {{ request('tipo_documento_id') == $tipo->id ? 'selected' : '' }}>
                                            {{ $tipo->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
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
                            <div class="toolbar-item toolbar-actions">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                        <i class="fas fa-search me-1"></i>Buscar
                                    </button>
                                    <a href="{{ route('notas_compra.index') }}" class="btn btn-outline-secondary btn-sm" title="Limpiar filtros">
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
                    <span>Lista de Notas de Compra</span>
                    <span class="results-count">{{ $notas->count() }} nota(s)</span>
                </div>
                <div class="card-body p-0" style="flex:1; display:flex; flex-direction:column;">
                    <div class="table-container">
                        @if($notas->count() > 0)
                            <table id="notasTable">
                                <thead>
                                    <tr>
                                        <th style="width:130px;">N° Nota</th>
                                        <th style="width:120px;">Tipo</th>
                                        <th>Proveedor</th>
                                        <th style="width:110px;">Factura Ref.</th>
                                        <th style="width:90px;">Fecha Emisión</th>
                                        <th style="width:120px;" class="text-end">Monto</th>
                                        <th style="width:100px;">Estado</th>
                                        <th>Usuario</th>
                                        <th style="width:80px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notas as $nota)
                                        <tr>
                                            <td>
                                                <strong>{{ $nota->nro_nota }}</strong>
                                                <br><small class="text-muted">Timbrado: {{ $nota->timbrado }}</small>
                                            </td>
                                            <td>
                                                @if($nota->tipoDocumento)
                                                    @php
                                                        $desc = $nota->tipoDocumento->descripcion;
                                                        $tagClass = str_contains($desc, 'Crédito') ? 'tag-credito' : 'tag-debito';
                                                    @endphp
                                                    <span class="tag {{ $tagClass }}">{{ $desc }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $nota->proveedor->razon_social ?? '-' }}">{{ $nota->proveedor->razon_social ?? '-' }}</span>
                                                <br><small class="text-muted">{{ $nota->proveedor->ruc ?? 'Sin RUC' }}</small>
                                            </td>
                                            <td>
                                                @if($nota->factura)
                                                    <span class="tag tag-secondary">{{ $nota->factura->nro_factura }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($nota->fecha_emision)->format('d/m/Y') }}
                                            </td>
                                            <td class="text-end">
                                                <span class="amount">₲ {{ number_format($nota->monto, 0, ',', '.') }}</span>
                                            </td>
                                            <td>
                                                @switch($nota->estado_id)
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
                                                        <span class="estado"><i class="estado-dot"></i>{{ $nota->estado->descripcion ?? '-' }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <span class="cell-text">{{ $nota->usuario->persona->nombre ?? 'N/A' }} {{ $nota->usuario->persona->apellido ?? '' }}</span>
                                                <br><small class="text-muted">{{ \Carbon\Carbon::parse($nota->created_at)->format('d/m/Y') }}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="#" class="btn-icon" title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($nota->estado_id === 3)
                                                        @if(session('permisos.not_com.editar'))
                                                        <a href="{{ route('notas_compra.edit', $nota->id) }}" class="btn-icon" title="Editar">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
                                                        @endif
                                                        @if(session('permisos.not_com.anular'))
                                                        <form action="{{ route('notas_compra.aprobar', $nota->id) }}" method="POST"
                                                              onsubmit="return confirm('¿Confirmar aprobación de la nota {{ $nota->nro_nota }}?')">
                                                            @csrf
                                                            <button type="submit" class="btn-icon aprobar" title="Aprobar">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('notas_compra.anular', $nota->id) }}" method="POST"
                                                              onsubmit="return confirm('¿Anular la nota {{ $nota->nro_nota }}? Esta acción no se puede deshacer.')">
                                                            @csrf
                                                            <button type="submit" class="btn-icon danger" title="Anular">
                                                                <i class="fas fa-ban"></i>
                                                            </button>
                                                        </form>
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
                                <i class="fas fa-file-alt fa-3x mb-3"></i>
                                <h5 class="text-muted mb-2">No hay notas de compra registradas</h5>
                                <p class="text-muted mb-3" style="font-size:0.85rem;">
                                    No se encontraron notas con los filtros aplicados.
                                </p>
                            </div>
                        @endif
                    </div>
                    @if($notas->hasPages())
                        <div class="pagination-wrapper">
                            {{ $notas->appends(request()->query())->links() }}
                        </div>
                    @endif
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

.toolbar-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr 1fr 1fr 1fr;
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

@media (max-width: 1024px) {
    .toolbar-grid { grid-template-columns: 1fr 1fr 1fr; }
}
@media (max-width: 600px) {
    .toolbar-grid { grid-template-columns: 1fr; }
    .page-header { flex-direction: column; align-items: flex-start; }
}

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

#notasTable {
    width: 100%;
    min-width: 980px;
    border-collapse: collapse;
    table-layout: fixed;
}
#notasTable thead th {
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
#notasTable tbody td {
    padding: 0.55rem 0.65rem;
    font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
#notasTable tbody tr:hover { background: #f8fafc; }
#notasTable tbody tr:last-child td { border-bottom: none; }

.cell-text {
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.amount { font-weight: 700; color: #10b981; }

.tag {
    display: inline-block;
    padding: 0.2rem 0.55rem;
    border-radius: 4px;
    font-size: 0.72rem;
    font-weight: 600;
}
.tag-secondary { background: #f1f5f9; color: #64748b; }
.tag-credito   { background: #ecfdf5; color: #059669; }
.tag-debito    { background: #fff7ed; color: #ea580c; }

.estado { display: inline-flex; align-items: center; gap: 0.4rem; }
.estado-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #94a3b8; flex-shrink: 0; }
.estado-pendiente .estado-dot  { background: #f59e0b; }
.estado-confirmado .estado-dot { background: #10b981; }
.estado-anulado .estado-dot    { background: #ef4444; }

.btn-group { display: flex; gap: 4px; justify-content: center; }
.btn-icon {
    width: 28px; height: 28px;
    display: inline-flex; align-items: center; justify-content: center;
    border: 1px solid #e2e8f0; border-radius: 6px;
    color: #64748b; background: #fff; font-size: 0.78rem;
    text-decoration: none; cursor: pointer;
}
.btn-icon:hover { background: #f1f5f9; color: #1e293b; }
.btn-icon.aprobar:hover { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
.btn-icon.danger:hover  { background: #fef2f2; color: #dc2626; border-color: #fecaca; }

.empty-state {
    min-height: 320px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 2rem; color: #94a3b8; text-align: center;
}
.empty-state i { color: #cbd5e1; }

.pagination-wrapper {
    padding: 0.75rem 1rem;
    border-top: 1px solid #e2e8f0;
}
.pagination-wrapper .pagination { margin: 0; justify-content: flex-end; }
.pagination-wrapper .page-link { font-size: 0.8rem; color: #64748b; border-color: #e2e8f0; }
.pagination-wrapper .page-item.active .page-link { background-color: #2563eb; border-color: #2563eb; }
.pagination-wrapper .page-link:hover { background-color: #f1f5f9; color: #1e293b; }
</style>
