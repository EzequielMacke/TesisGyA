<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos de Compra - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content fade-in">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div class="page-title">
                    <div class="page-title-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div>
                        <h2>Pedidos de Compra</h2>
                        <small>Gestión y seguimiento de pedidos</small>
                    </div>
                </div>
                <a href="{{ route('pedido_compra.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Nuevo Pedido
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
            <div class="card filter-card">
                <div class="card-header-section">
                    <i class="fas fa-filter"></i> Filtros
                </div>
                <div class="card-body py-3 px-3">
                    <div class="filter-grid">
                        <div>
                            <label class="form-label">Estado</label>
                            <select class="form-select form-select-sm" id="estado_filter">
                                <option value="">Todos los estados</option>
                                <option value="3">Pendiente</option>
                                <option value="4">Confirmado</option>
                                <option value="5">Anulado</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Sucursal</label>
                            <select class="form-select form-select-sm" id="sucursal_filter">
                                <option value="">Todas</option>
                                @foreach($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}">{{ $sucursal->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Fecha desde</label>
                            <input type="date" class="form-control form-control-sm" id="fecha_filter">
                        </div>
                        <div class="filter-actions">
                            <button type="button" id="filtrarBtn" class="btn btn-primary btn-sm">
                                <i class="fas fa-filter me-1"></i>Filtrar
                            </button>
                            <button type="button" id="limpiarBtn" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times me-1"></i>Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Buscador --}}
            <div class="card search-card">
                <div class="card-body py-2 px-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="background:#1a3461; color:white; border-color:#1a3461;">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" id="searchInput"
                               placeholder="Buscar por código, usuario, sucursal, depósito..."
                               autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <p class="search-meta mb-0">
                        Mostrando <strong id="totalRows">{{ $pedidos->count() }}</strong> pedido(s)
                    </p>
                </div>
            </div>

            {{-- Tabla --}}
            <div class="card table-card" id="tableCard">
                <div class="card-header-section">
                    <i class="fas fa-list"></i> Listado de Pedidos
                </div>
                <div class="card-body p-0" style="flex:1; display:flex; flex-direction:column;">
                    <div class="table-container">
                        @if($pedidos->count() > 0)
                            <table id="pedidosTable">
                                <thead>
                                    <tr>
                                        <th style="width:70px;">Código</th>
                                        <th style="width:120px;">Pedido</th>
                                        <th>Usuario</th>
                                        <th style="width:100px;">Fecha</th>
                                        <th>Sucursal</th>
                                        <th>Depósito</th>
                                        <th style="width:105px;">Estado</th>
                                        <th>Observación</th>
                                        <th style="width:120px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pedidos as $pedido)
                                        <tr class="pedido-row">
                                            <td class="text-center">
                                                <span class="badge-code">#{{ str_pad($pedido->id, 3, '0', STR_PAD_LEFT) }}</span>
                                            </td>
                                            <td>
                                                <strong style="color:#1a3461; font-size:0.82rem;">Pedido #{{ $pedido->id }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>{{ $pedido->created_at->format('H:i') }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info bg-opacity-15 text-info-emphasis text-wrap"
                                                      title="{{ $pedido->usuario->usuario }}">
                                                    {{ Str::limit($pedido->usuario->usuario, 15) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary bg-opacity-15 text-secondary-emphasis">
                                                    {{ $pedido->fecha->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary bg-opacity-15 text-primary text-wrap"
                                                      title="{{ $pedido->sucursal->descripcion }}">
                                                    {{ Str::limit($pedido->sucursal->descripcion, 15) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info bg-opacity-15 text-info-emphasis text-wrap"
                                                      title="{{ $pedido->deposito->descripcion }}">
                                                    {{ Str::limit($pedido->deposito->descripcion, 15) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @switch($pedido->estado_id)
                                                    @case(3)
                                                        <span class="badge badge-pendiente">Pendiente</span>
                                                        @break
                                                    @case(4)
                                                        <span class="badge badge-confirmado">Confirmado</span>
                                                        @break
                                                    @case(5)
                                                        <span class="badge badge-anulado">Anulado</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $pedido->estado->descripcion }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($pedido->observacion)
                                                    <span class="text-truncate d-inline-block" style="max-width:130px;"
                                                          title="{{ $pedido->observacion }}">
                                                        <i class="fas fa-comment text-muted me-1" style="font-size:0.7rem;"></i>
                                                        {{ Str::limit($pedido->observacion, 22) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted fst-italic" style="font-size:0.75rem;">—</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('pedido_compra.show', $pedido->id) }}"
                                                       class="btn btn-sm btn-outline-primary" title="Ver Detalle">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($pedido->estado_id == 3)
                                                        <a href="{{ route('pedido_compra.edit', $pedido->id) }}"
                                                           class="btn btn-sm btn-outline-success" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-danger"
                                                                title="Anular"
                                                                onclick="anularPedido({{ $pedido->id }})">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    @endif
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Imprimir">
                                                        <i class="fas fa-print"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                <h5 class="text-muted mb-2">Sin pedidos de compra</h5>
                                <p class="text-muted mb-3" style="font-size:0.85rem;">
                                    Aún no se han registrado pedidos en el sistema.
                                </p>
                                <a href="{{ route('pedido_compra.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-2"></i>Crear Primer Pedido
                                </a>
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
    border-bottom: 2px solid #e2e8f0;
    margin-bottom: 0.25rem;
}
.page-title { display: flex; align-items: center; gap: 0.75rem; }
.page-title-icon {
    width: 44px; height: 44px;
    background: linear-gradient(135deg, #1a3461, #24508f);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
}
.page-title h2 { margin: 0; font-size: 1.3rem; color: #1a3461; font-weight: 700; line-height: 1.2; }
.page-title small { color: #64748b; font-size: 0.75rem; }

/* ── Cards ── */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.07), 0 4px 14px rgba(0,0,0,0.04);
    background: #fff;
}
.card-header-section {
    padding: 0.7rem 1rem;
    border-bottom: 1px solid #f0f4f8;
    display: flex; align-items: center; gap: 0.5rem;
    font-weight: 600; font-size: 0.82rem; color: #1a3461;
}
.card-header-section i { color: #24508f; }

/* ── Filtros ── */
.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 0.65rem;
    align-items: end;
}
.filter-actions { display: flex; gap: 0.4rem; flex-wrap: wrap; }

/* ── Buscador ── */
.search-meta { font-size: 0.73rem; color: #64748b; margin-top: 0.35rem; }

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
.table-container::-webkit-scrollbar { width: 5px; height: 5px; }
.table-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

#pedidosTable {
    width: 100%;
    min-width: 860px;
    border-collapse: collapse;
    table-layout: fixed;
}
#pedidosTable thead th {
    background: linear-gradient(135deg, #1a3461 0%, #24508f 100%);
    color: white;
    font-size: 0.76rem;
    font-weight: 600;
    padding: 0.7rem 0.65rem;
    position: sticky; top: 0; z-index: 10;
    border: none;
    white-space: nowrap;
    letter-spacing: 0.2px;
    text-transform: uppercase;
}
#pedidosTable tbody td {
    padding: 0.55rem 0.65rem;
    font-size: 0.79rem;
    border: none;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
#pedidosTable tbody tr { cursor: pointer; transition: background 0.12s; }
#pedidosTable tbody tr:hover { background: #f0f6ff; }
#pedidosTable tbody tr:last-child td { border-bottom: none; }

/* Badges */
.badge-code {
    background: #1e293b; color: white;
    font-weight: 700; font-size: 0.72rem;
    padding: 0.28rem 0.5rem; border-radius: 6px;
}
.badge.text-wrap { white-space: normal; word-break: break-word; font-size: 0.68rem; line-height: 1.3; }
.badge-pendiente  { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; font-size: 0.72rem; }
.badge-confirmado { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; font-size: 0.72rem; }
.badge-anulado    { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; font-size: 0.72rem; }

/* Botones de acción */
.btn-group { display: flex; flex-wrap: nowrap; gap: 2px; }
.btn-group .btn-sm {
    padding: 0.25rem 0.42rem;
    font-size: 0.72rem;
    border-radius: 6px !important;
    border-width: 1.5px;
}

/* Búsqueda highlight */
.highlight {
    background: #fef08a; color: #713f12;
    font-weight: 600; padding: 0 2px; border-radius: 2px;
}

/* Empty state */
.empty-state {
    min-height: 320px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 2rem; color: #94a3b8; text-align: center;
}
.empty-state i { color: #cbd5e1; }

/* Loading */
.loading-state { opacity: 0.5; pointer-events: none; }

/* Fade */
.fade-in { animation: fadeIn 0.3s ease; }
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
    .filter-grid { grid-template-columns: 1fr 1fr; }
    .page-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .filter-grid { grid-template-columns: 1fr; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput  = document.getElementById('searchInput');
    const clearButton  = document.getElementById('clearSearch');
    const table        = document.getElementById('pedidosTable');
    const filtrarBtn   = document.getElementById('filtrarBtn');
    const limpiarBtn   = document.getElementById('limpiarBtn');
    const estadoFilter   = document.getElementById('estado_filter');
    const sucursalFilter = document.getElementById('sucursal_filter');
    const fechaFilter    = document.getElementById('fecha_filter');

    if (table) {
        const rows        = table.querySelectorAll('.pedido-row');
        const noResults   = document.getElementById('noResults');
        const tableCard   = document.getElementById('tableCard');
        const totalRowsSp = document.getElementById('totalRows');
        const totalRows   = rows.length;

        function highlightText(text, search) {
            if (!search) return text;
            return text.replace(new RegExp(`(${search})`, 'gi'), '<span class="highlight">$1</span>');
        }

        function removeHighlights() {
            table.querySelectorAll('.highlight').forEach(el => {
                el.parentNode.replaceChild(document.createTextNode(el.textContent), el);
                el.parentNode.normalize();
            });
        }

        function applyAllFilters() {
            const term      = searchInput.value.toLowerCase().trim();
            const estado    = estadoFilter.value;
            const sucursal  = sucursalFilter.value;
            const fecha     = fechaFilter.value;
            let visible = 0;

            tableCard.classList.add('loading-state');
            removeHighlights();

            rows.forEach(row => {
                let show = true;

                if (term) {
                    const cells = row.querySelectorAll('td');
                    let found = false;
                    for (let i = 0; i < cells.length - 1; i++) {
                        if (cells[i].textContent.toLowerCase().includes(term)) {
                            found = true;
                            const html = cells[i].innerHTML;
                            if (!html.includes('<span class="badge') && !html.includes('<button')) {
                                cells[i].innerHTML = highlightText(cells[i].textContent, term);
                            }
                        }
                    }
                    if (!found) show = false;
                }

                if (estado && show) {
                    const badge = row.querySelector('td:nth-child(7) .badge');
                    const map = { '3': 'Pendiente', '4': 'Confirmado', '5': 'Anulado' };
                    if (badge && badge.textContent.trim() !== map[estado]) show = false;
                }

                if (sucursal && show) {
                    const badge = row.querySelector('td:nth-child(5) .badge');
                    const opt   = sucursalFilter.querySelector(`option[value="${sucursal}"]`);
                    if (badge && opt && !badge.getAttribute('title')?.includes(opt.textContent)) show = false;
                }

                if (fecha && show) {
                    const badge = row.querySelector('td:nth-child(4) .badge');
                    if (badge) {
                        const [d, m, y] = badge.textContent.trim().split('/');
                        if (d && m && y) {
                            const fp = `${y}-${m.padStart(2,'0')}-${d.padStart(2,'0')}`;
                            if (fp < fecha) show = false;
                        }
                    }
                }

                row.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            totalRowsSp.textContent = visible;

            setTimeout(() => {
                if (visible === 0 && totalRows > 0) {
                    tableCard.style.display = 'none';
                    noResults.style.display = '';
                } else {
                    tableCard.style.display = '';
                    noResults.style.display = 'none';
                }
                tableCard.classList.remove('loading-state');
            }, 80);
        }

        let debounce;
        searchInput.addEventListener('input', () => { clearTimeout(debounce); debounce = setTimeout(applyAllFilters, 150); });
        clearButton.addEventListener('click', () => { searchInput.value = ''; applyAllFilters(); searchInput.focus(); });
        searchInput.addEventListener('keydown', e => { if (e.key === 'Escape') { searchInput.value = ''; applyAllFilters(); } });

        filtrarBtn.addEventListener('click', applyAllFilters);

        limpiarBtn.addEventListener('click', function () {
            [estadoFilter, sucursalFilter, fechaFilter].forEach(f => { if (f) f.value = ''; });
            searchInput.value = '';
            applyAllFilters();
            this.innerHTML = '<i class="fas fa-check me-1"></i>Limpiado';
            this.classList.replace('btn-outline-secondary', 'btn-success');
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-times me-1"></i>Limpiar';
                this.classList.replace('btn-success', 'btn-outline-secondary');
            }, 1000);
        });

        [estadoFilter, sucursalFilter, fechaFilter].forEach(f => f?.addEventListener('change', applyAllFilters));

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

    window.anularPedido = function (pedidoId) {
        const confirm_ = () => enviarAnulacion(pedidoId);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Anular Pedido?',
                text: 'El pedido pasará a estado "Anulado" y no podrá revertirse.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, Anular',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then(r => { if (r.isConfirmed) confirm_(); });
        } else {
            if (confirm('¿Anular este pedido?')) confirm_();
        }
    };

    function enviarAnulacion(pedidoId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/pedido_compra/${pedidoId}/anular`;
        const csrf = document.querySelector('meta[name="csrf-token"]');
        if (csrf) {
            const t = document.createElement('input');
            t.type = 'hidden'; t.name = '_token'; t.value = csrf.content;
            form.appendChild(t);
        }
        const m = document.createElement('input');
        m.type = 'hidden'; m.name = '_method'; m.value = 'PATCH';
        form.appendChild(m);
        const btn = document.querySelector(`[onclick="anularPedido(${pedidoId})"]`);
        if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; }
        document.body.appendChild(form);
        form.submit();
    }
});
</script>
