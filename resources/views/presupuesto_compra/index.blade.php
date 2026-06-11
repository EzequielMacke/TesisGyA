<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos de Compra - Presupuestos</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-file-invoice-dollar"></i> Pedidos de Compra Pendientes</h2>
                    <small>Crea presupuestos para los pedidos disponibles</small>
                </div>
                @if(session('user_cargo'))
                    <span class="tag tag-secondary"><i class="fas fa-user me-1"></i>{{ session('user_cargo') }}</span>
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

            {{-- Búsqueda y filtros --}}
            <div class="card">
                <div class="card-body py-3 px-3">
                    <div class="toolbar-grid">
                        <div class="toolbar-item search-item">
                            <label class="form-label">Buscar</label>
                            <div class="search-box">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" class="form-control form-control-sm" id="searchInput"
                                       placeholder="Usuario, sucursal, depósito..." autocomplete="off">
                                <button type="button" class="search-clear" id="clearSearch" title="Limpiar búsqueda">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="toolbar-item">
                            <label class="form-label">Presupuestos</label>
                            <select class="form-select form-select-sm" id="presupuestos_filter">
                                <option value="">Todos</option>
                                <option value="sin">Sin presupuestos</option>
                                <option value="con">Con presupuestos</option>
                            </select>
                        </div>
                        <div class="toolbar-item">
                            <label class="form-label">Fecha</label>
                            <input type="date" class="form-control form-control-sm" id="fecha_filter">
                        </div>
                        <div class="toolbar-item toolbar-clear">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" id="limpiarBtn" class="btn btn-outline-secondary btn-sm" title="Limpiar filtros">
                                <i class="fas fa-eraser"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabla --}}
            <div class="card table-card" id="tableCard">
                <div class="card-header-section">
                    <span>Pedidos Pendientes</span>
                    <span class="results-count">
                        Mostrando <strong id="totalRows">{{ $pedidos->count() }}</strong> de {{ $pedidos->count() }}
                    </span>
                </div>
                <div class="card-body p-0" style="flex:1; display:flex; flex-direction:column;">
                    <div class="table-container">
                        @if($pedidos->count() > 0)
                            <table id="pedidosTable">
                                <thead>
                                    <tr>
                                        <th style="width:80px;">Pedido</th>
                                        <th style="width:90px;">Fecha</th>
                                        <th>Usuario</th>
                                        <th>Sucursal / Depósito</th>
                                        <th style="width:80px;" class="text-center">Insumos</th>
                                        <th style="width:130px;" class="text-center">Presupuestos</th>
                                        <th>Observación</th>
                                        <th style="width:90px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pedidos as $pedido)
                                        <tr class="pedido-row"
                                            data-fecha="{{ $pedido->fecha->format('Y-m-d') }}"
                                            data-presupuestos="{{ $pedido->presupuestos_count }}">
                                            <td class="text-center">
                                                <strong>#{{ str_pad($pedido->id, 3, '0', STR_PAD_LEFT) }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $pedido->created_at->format('H:i') }}</small>
                                            </td>
                                            <td class="text-center">
                                                {{ $pedido->fecha->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $pedido->usuario->usuario ?? 'N/A' }}">
                                                    {{ $pedido->usuario->usuario ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="cell-text" title="{{ $pedido->sucursal->descripcion ?? '-' }}">{{ $pedido->sucursal->descripcion ?? '-' }}</span>
                                                <br><small class="text-muted">{{ $pedido->deposito->descripcion ?? '-' }}</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="tag tag-secondary" title="{{ $pedido->detalles->pluck('insumo.descripcion')->filter()->join(', ') }}">
                                                    {{ $pedido->detalles->count() }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($pedido->presupuestos_count > 0)
                                                    <span class="tag">{{ $pedido->presupuestos_count }} presupuesto{{ $pedido->presupuestos_count > 1 ? 's' : '' }}</span>
                                                @else
                                                    <span class="text-muted">Sin presupuestos</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($pedido->observacion)
                                                    <span class="cell-text" title="{{ $pedido->observacion }}">
                                                        {{ Str::limit($pedido->observacion, 28) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('presupuesto_compra.show_pedido', $pedido->id) }}"
                                                       class="btn-icon" title="Ver Detalle">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('presupuesto_compra.create', $pedido->id) }}"
                                                       class="btn-icon" title="{{ $pedido->presupuestos_count > 0 ? 'Nuevo Presupuesto' : 'Crear Primer Presupuesto' }}">
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <h5 class="text-muted mb-2">No hay pedidos pendientes</h5>
                                <p class="text-muted mb-3" style="font-size:0.85rem;">
                                    Por el momento no hay pedidos de compra disponibles para presupuestar.
                                </p>
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
                        No hay pedidos que coincidan con los filtros aplicados.
                    </p>
                    <button type="button" class="btn btn-outline-primary btn-sm"
                            onclick="document.getElementById('limpiarBtn').click()">
                        <i class="fas fa-undo me-2"></i>Limpiar Filtros
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
.toolbar-clear .btn { width: 100%; }

@media (max-width: 900px) {
    .toolbar-grid { grid-template-columns: 1fr 1fr; }
    .toolbar-item.search-item { grid-column: 1 / -1; }
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

#pedidosTable {
    width: 100%;
    min-width: 860px;
    border-collapse: collapse;
    table-layout: fixed;
}
#pedidosTable thead th {
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
#pedidosTable tbody td {
    padding: 0.55rem 0.65rem;
    font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
#pedidosTable tbody tr { cursor: pointer; }
#pedidosTable tbody tr:hover { background: #f8fafc; }
#pedidosTable tbody tr:last-child td { border-bottom: none; }

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
    white-space: nowrap;
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput        = document.getElementById('searchInput');
    const clearButton        = document.getElementById('clearSearch');
    const table               = document.getElementById('pedidosTable');
    const limpiarBtn          = document.getElementById('limpiarBtn');
    const presupuestosFilter  = document.getElementById('presupuestos_filter');
    const fechaFilter         = document.getElementById('fecha_filter');

    if (table) {
        const rows        = table.querySelectorAll('.pedido-row');
        const noResults   = document.getElementById('noResults');
        const tableCard   = document.getElementById('tableCard');
        const totalRowsSp = document.getElementById('totalRows');
        const totalRows   = rows.length;

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

        function applyAllFilters() {
            const term         = searchInput.value.toLowerCase().trim();
            const presupuestos = presupuestosFilter.value;
            const fecha        = fechaFilter.value;
            let visible = 0;

            removeHighlights();

            rows.forEach(row => {
                let show = true;

                if (fecha && row.dataset.fecha !== fecha) show = false;

                const cantPresupuestos = parseInt(row.dataset.presupuestos);
                if (presupuestos === 'sin' && cantPresupuestos > 0) show = false;
                else if (presupuestos === 'con' && cantPresupuestos === 0) show = false;

                if (term && show) {
                    const cells = row.querySelectorAll('td');
                    let found = false;
                    for (let i = 0; i < cells.length - 1; i++) {
                        if (cells[i].textContent.toLowerCase().includes(term)) {
                            found = true;
                            if (!cells[i].querySelector('.tag')) {
                                cells[i].innerHTML = highlightText(cells[i].textContent, term);
                            }
                        }
                    }
                    if (!found) show = false;
                }

                row.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            totalRowsSp.textContent = visible;

            if (visible === 0 && totalRows > 0) {
                tableCard.style.display = 'none';
                noResults.style.display = '';
            } else {
                tableCard.style.display = '';
                noResults.style.display = 'none';
            }
        }

        let debounce;
        searchInput.addEventListener('input', () => { clearTimeout(debounce); debounce = setTimeout(applyAllFilters, 150); });
        clearButton.addEventListener('click', () => { searchInput.value = ''; applyAllFilters(); searchInput.focus(); });
        searchInput.addEventListener('keydown', e => { if (e.key === 'Escape') { searchInput.value = ''; applyAllFilters(); } });

        limpiarBtn.addEventListener('click', function () {
            [presupuestosFilter, fechaFilter].forEach(f => { if (f) f.value = ''; });
            searchInput.value = '';
            applyAllFilters();
        });

        [presupuestosFilter, fechaFilter].forEach(f => f?.addEventListener('change', applyAllFilters));

        rows.forEach(row => {
            row.addEventListener('click', function (e) {
                if (e.target.closest('.btn-group')) return;
                const link = this.querySelector('a[title="Ver Detalle"]');
                if (link) window.location.href = link.href;
            });
        });
    }

    document.addEventListener('keydown', e => {
        if ((e.ctrlKey && e.key === 'f') || (e.ctrlKey && e.key === 'k')) {
            e.preventDefault();
            searchInput?.focus();
        }
    });
});
</script>
