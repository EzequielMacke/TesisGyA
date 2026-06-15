<!-- filepath: c:\laragon\www\TesisGyA\resources\views\inventario\index.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-warehouse"></i> Inventario</h2>
                    <small>Consulta de existencias por depósito y sucursal</small>
                </div>
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

            {{-- Búsqueda y filtros --}}
            <div class="card">
                <div class="card-body py-3 px-3">
                    <div class="toolbar-grid">
                        <div class="toolbar-item search-item">
                            <label class="form-label">Buscar</label>
                            <div class="search-box">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" class="form-control form-control-sm" id="searchInput"
                                       placeholder="Insumo, marca, depósito, cantidad..." autocomplete="off">
                                <button type="button" class="search-clear" id="clearSearch" title="Limpiar búsqueda">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="toolbar-item">
                            <label class="form-label">Sucursal</label>
                            <select class="form-select form-select-sm" id="sucursal_filter">
                                <option value="">Todas las sucursales</option>
                                @foreach($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}"
                                            {{ $sucursalSeleccionada == $sucursal->id ? 'selected' : '' }}>
                                        {{ $sucursal->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="toolbar-item">
                            <label class="form-label">Obra</label>
                            <select class="form-select form-select-sm" id="obra_filter">
                                <option value="">Todas las obras</option>
                                @foreach($obras as $obra)
                                    <option value="{{ $obra->id }}"
                                            {{ $obraSeleccionada == $obra->id ? 'selected' : '' }}>
                                        {{ $obra->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="toolbar-item toolbar-actions">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="button" id="filtrarBtn" class="btn btn-primary btn-sm flex-fill">
                                    <i class="fas fa-filter me-1"></i>Filtrar
                                </button>
                                <a href="{{ route('inventario.index') }}" class="btn btn-outline-secondary btn-sm" title="Limpiar filtros">
                                    <i class="fas fa-eraser"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabla --}}
            <div class="card table-card" id="tableCard">
                <div class="card-header-section">
                    <span>
                        @if($sucursalSeleccionada)
                            Mostrando: {{ $sucursales->find($sucursalSeleccionada)->descripcion ?? 'Sucursal' }}
                        @elseif($obraSeleccionada)
                            Mostrando: Obra - {{ $obras->find($obraSeleccionada)->descripcion ?? 'Obra' }}
                        @else
                            Mostrando: Todas las ubicaciones
                        @endif
                    </span>
                    <span class="results-count">
                        Mostrando <strong id="totalRows">{{ $inventarios->count() }}</strong> de {{ $inventarios->count() }}
                    </span>
                </div>
                <div class="card-body p-0" style="flex:1; display:flex; flex-direction:column;">
                    <div class="table-container">
                        @if($inventarios->count() > 0)
                            <table id="inventarioTable">
                                <thead>
                                    <tr>
                                        <th style="width:60px;">ID</th>
                                        <th style="width:160px;">Ubicación</th>
                                        <th>Insumo</th>
                                        <th style="width:130px;">Marca</th>
                                        <th style="width:90px;">Unidad</th>
                                        <th style="width:110px;" class="text-center">Cantidad</th>
                                        <th style="width:100px;">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inventarios as $inventario)
                                        <tr class="inventario-row">
                                            <td><strong>{{ $inventario->id }}</strong></td>
                                            <td>
                                                @if($inventario->deposito_id)
                                                    <span class="cell-text" title="{{ $inventario->deposito->descripcion }}">
                                                        {{ $inventario->deposito->descripcion }}
                                                    </span>
                                                @else
                                                    <span class="cell-text" title="Obra: {{ $inventario->obra->descripcion ?? '-' }}">
                                                        <span class="tag tag-secondary">Obra</span> {{ $inventario->obra->descripcion ?? '-' }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $inventario->insumo->descripcion }}">
                                                    {{ $inventario->insumo->descripcion }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="tag tag-secondary">{{ $inventario->insumo->marca->descripcion }}</span>
                                            </td>
                                            <td>
                                                <span class="tag tag-secondary">{{ $inventario->insumo->unidadMedida->abreviatura ?? $inventario->insumo->unidadMedida->descripcion }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="tag {{ $inventario->cantidad > 0 ? 'tag-success' : 'tag-warning' }}">
                                                    {{ number_format($inventario->cantidad, 2, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($inventario->estado->id == 1)
                                                    <span class="estado estado-confirmado"><i class="estado-dot"></i>Activo</span>
                                                @else
                                                    <span class="estado estado-anulado"><i class="estado-dot"></i>Inactivo</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-box-open fa-3x mb-3"></i>
                                <h5 class="text-muted mb-2">No hay inventario disponible</h5>
                                @if($sucursalSeleccionada)
                                    <p class="text-muted mb-3" style="font-size:0.85rem;">
                                        No se encontraron productos en el inventario para la sucursal seleccionada.
                                    </p>
                                    <a href="{{ route('inventario.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-eye me-2"></i>Ver Todas las Ubicaciones
                                    </a>
                                @elseif($obraSeleccionada)
                                    <p class="text-muted mb-3" style="font-size:0.85rem;">
                                        No se encontraron productos en el inventario para la obra seleccionada.
                                    </p>
                                    <a href="{{ route('inventario.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-eye me-2"></i>Ver Todas las Ubicaciones
                                    </a>
                                @else
                                    <p class="text-muted mb-0" style="font-size:0.85rem;">
                                        El inventario se actualiza automáticamente con los ingresos y salidas de productos.
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sin resultados de búsqueda --}}
            <div id="noResults" class="card" style="display:none; min-height:280px;">
                <div class="empty-state">
                    <i class="fas fa-search fa-3x mb-3"></i>
                    <h5 class="text-muted mb-2">Sin resultados</h5>
                    <p class="text-muted mb-3" style="font-size:0.85rem;">
                        No hay registros que coincidan con la búsqueda.
                    </p>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="limpiarBusquedaBtn">
                        <i class="fas fa-undo me-2"></i>Limpiar Búsqueda
                    </button>
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

/* ── Toolbar (búsqueda + filtros) ── */
.toolbar-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr auto;
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
.search-box input { padding-left: 2rem; padding-right: 1.8rem; }
.search-clear {
    position: absolute; right: 6px; top: 50%; transform: translateY(-50%);
    border: none; background: none; color: #94a3b8; cursor: pointer;
    font-size: 0.75rem; padding: 4px;
}
.toolbar-actions > div { width: 100%; }

@media (max-width: 900px) {
    .toolbar-grid { grid-template-columns: 1fr 1fr; }
    .toolbar-item.search-item { grid-column: 1 / -1; }
    .page-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .toolbar-grid { grid-template-columns: 1fr; }
    .toolbar-item.search-item { grid-column: auto; }
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

#inventarioTable {
    width: 100%;
    min-width: 860px;
    border-collapse: collapse;
    table-layout: fixed;
}
#inventarioTable thead th {
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
#inventarioTable tbody td {
    padding: 0.55rem 0.65rem;
    font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
#inventarioTable tbody tr:hover { background: #f8fafc; }
#inventarioTable tbody tr:last-child td { border-bottom: none; }

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
.tag-success { background: #dcfce7; color: #16a34a; }
.tag-warning { background: #fef3c7; color: #b45309; }

/* Estado */
.estado { display: inline-flex; align-items: center; gap: 0.4rem; }
.estado-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #94a3b8; flex-shrink: 0; }
.estado-confirmado .estado-dot { background: #10b981; }
.estado-anulado .estado-dot    { background: #ef4444; }

/* Búsqueda highlight */
.highlight { background: #fef08a; padding: 0 1px; }

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
document.addEventListener('DOMContentLoaded', function() {
    // Filtro por sucursal/obra (server-side)
    const sucursalFilter = document.getElementById('sucursal_filter');
    const obraFilter = document.getElementById('obra_filter');
    const filtrarBtn = document.getElementById('filtrarBtn');

    if (filtrarBtn) {
        filtrarBtn.addEventListener('click', function() {
            const sucursalId = sucursalFilter ? sucursalFilter.value : '';
            const obraId = obraFilter ? obraFilter.value : '';
            const url = new URL(window.location.href);

            if (sucursalId) {
                url.searchParams.set('sucursal', sucursalId);
            } else {
                url.searchParams.delete('sucursal');
            }

            if (obraId) {
                url.searchParams.set('obra', obraId);
            } else {
                url.searchParams.delete('obra');
            }

            window.location.href = url.toString();
        });
    }

    // Selección mutuamente excluyente entre sucursal y obra
    if (sucursalFilter && obraFilter) {
        sucursalFilter.addEventListener('change', function() {
            if (sucursalFilter.value) {
                obraFilter.value = '';
            }
        });

        obraFilter.addEventListener('change', function() {
            if (obraFilter.value) {
                sucursalFilter.value = '';
            }
        });
    }

    // Buscador de inventario (client-side)
    const searchInput = document.getElementById('searchInput');
    const clearButton = document.getElementById('clearSearch');
    const table = document.getElementById('inventarioTable');

    if (table) {
        const rows = table.querySelectorAll('.inventario-row');
        const noResults = document.getElementById('noResults');
        const tableCard = document.getElementById('tableCard');
        const totalRowsSpan = document.getElementById('totalRows');
        const totalRows = rows.length;
        const limpiarBusquedaBtn = document.getElementById('limpiarBusquedaBtn');

        function escapeRegExp(text) {
            return text.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }

        function highlightText(text, search) {
            if (!search) return text;
            return text.replace(new RegExp(`(${escapeRegExp(search)})`, 'gi'), '<span class="highlight">$1</span>');
        }

        function removeHighlights() {
            table.querySelectorAll('.highlight').forEach(el => {
                el.parentNode.replaceChild(document.createTextNode(el.textContent), el);
                el.parentNode.normalize();
            });
        }

        function filterTable() {
            const term = searchInput.value.toLowerCase().trim();
            let visible = 0;

            removeHighlights();

            rows.forEach(row => {
                if (!term) {
                    row.style.display = '';
                    visible++;
                    return;
                }

                const cells = row.querySelectorAll('td');
                let found = false;

                cells.forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(term)) {
                        found = true;
                        if (!cell.querySelector('.tag, .estado')) {
                            cell.innerHTML = highlightText(cell.textContent, term);
                        }
                    }
                });

                row.style.display = found ? '' : 'none';
                if (found) visible++;
            });

            totalRowsSpan.textContent = visible;

            if (visible === 0 && totalRows > 0) {
                tableCard.style.display = 'none';
                noResults.style.display = '';
            } else {
                tableCard.style.display = '';
                noResults.style.display = 'none';
            }
        }

        searchInput.addEventListener('input', filterTable);

        clearButton.addEventListener('click', function() {
            searchInput.value = '';
            filterTable();
            searchInput.focus();
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
                filterTable();
            }
        });

        limpiarBusquedaBtn?.addEventListener('click', function() {
            searchInput.value = '';
            filterTable();
        });

        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey && e.key === 'f') || (e.ctrlKey && e.key === 'k')) {
                e.preventDefault();
                searchInput.focus();
            }
        });
    }
});
</script>
