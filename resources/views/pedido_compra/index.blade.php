<!-- filepath: c:\laragon\www\TesisGyA\resources\views\pedido_compra\index.blade.php -->
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-shopping-cart me-2"></i>Pedidos de Compra</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('pedido_compra.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Nuevo Pedido
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            <!-- Filtros -->
            <div class="card mb-3">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label for="estado_filter" class="form-label mb-2">
                                <i class="fas fa-filter me-1"></i>Estado:
                            </label>
                            <select class="form-select" id="estado_filter">
                                <option value="">Todos los estados</option>
                                <option value="3">Pendiente</option>
                                <option value="4">Confirmado</option>
                                <option value="5">Anulado</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="sucursal_filter" class="form-label mb-2">
                                <i class="fas fa-building me-1"></i>Sucursal:
                            </label>
                            <select class="form-select" id="sucursal_filter">
                                <option value="">Todas las sucursales</option>
                                @foreach($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}">{{ $sucursal->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="fecha_filter" class="form-label mb-2">
                                <i class="fas fa-calendar me-1"></i>Fecha desde:
                            </label>
                            <input type="date" class="form-control" id="fecha_filter">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-2">&nbsp;</label>
                            <div>
                                <button type="button" id="filtrarBtn" class="btn btn-primary">
                                    <i class="fas fa-filter me-2"></i>Filtrar
                                </button>
                                <button type="button" id="limpiarBtn" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buscador -->
            <div class="card mb-3">
                <div class="card-body py-3">
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text"
                               class="form-control"
                               id="searchInput"
                               placeholder="Buscar por código, usuario, sucursal, depósito..."
                               autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        <span id="searchResults">Mostrando <span id="totalRows">{{ $pedidos->count() }}</span> pedido(s)</span>
                    </small>
                </div>
            </div>

            <!-- Tabla de Pedidos - Ocupa todo el espacio disponible -->
            <div class="card table-card flex-grow-1">
                <div class="card-body p-0 h-100">
                    <div class="table-responsive table-container h-100">
                        @if($pedidos->count() > 0)
                            <table class="table table-striped table-hover mb-0 h-100" id="pedidosTable">
                                <thead class="table-dark sticky-top">
                                    <tr>
                                        <th style="width: 80px; min-width: 80px;">Código</th>
                                        <th style="width: calc((100% - 80px) / 8);">Pedido</th>
                                        <th style="width: calc((100% - 80px) / 8);">Usuario</th>
                                        <th style="width: calc((100% - 80px) / 8);">Fecha</th>
                                        <th style="width: calc((100% - 80px) / 8);">Sucursal</th>
                                        <th style="width: calc((100% - 80px) / 8);">Depósito</th>
                                        <th style="width: calc((100% - 80px) / 8);">Estado</th>
                                        <th style="width: calc((100% - 80px) / 8);">Observación</th>
                                        <th style="width: calc((100% - 80px) / 8);">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pedidos as $pedido)
                                        <tr class="pedido-row">
                                            <td class="text-center">
                                                <span class="badge bg-dark fs-6">
                                                    #{{ str_pad($pedido->id, 3, '0', STR_PAD_LEFT) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <strong class="text-primary text-truncate" style="max-width: 180px;">
                                                        Pedido #{{ $pedido->id }}
                                                    </strong>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ $pedido->created_at->format('H:i') }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info text-wrap" title="{{ $pedido->usuario->usuario }}">
                                                    {{ Str::limit($pedido->usuario->usuario, 15) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary">
                                                    {{ $pedido->fecha->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary text-wrap" title="{{ $pedido->sucursal->descripcion }}">
                                                    {{ Str::limit($pedido->sucursal->descripcion, 15) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info text-wrap" title="{{ $pedido->deposito->descripcion }}">
                                                    {{ Str::limit($pedido->deposito->descripcion, 15) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge
                                                    @switch($pedido->estado_id)
                                                        @case(3) bg-warning text-dark @break
                                                        @case(4) bg-success @break
                                                        @case(5) bg-danger @break
                                                        @default bg-secondary @break
                                                    @endswitch">
                                                    @switch($pedido->estado_id)
                                                        @case(3) Pendiente @break
                                                        @case(4) Confirmado @break
                                                        @case(5) Anulado @break
                                                        @default {{ $pedido->estado->descripcion }} @break
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td title="{{ $pedido->observacion }}">
                                                @if($pedido->observacion)
                                                    <i class="fas fa-comment text-muted me-1" title="{{ $pedido->observacion }}"></i>
                                                    <span class="text-truncate d-inline-block" style="max-width: 120px;">
                                                        {{ Str::limit($pedido->observacion, 20) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted fst-italic">Sin observación</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('pedido_compra.show', $pedido->id) }}"
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="Ver Detalle">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($pedido->estado_id == 3)
                                                        <a href="{{ route('pedido_compra.edit', $pedido->id) }}"
                                                        class="btn btn-sm btn-outline-success"
                                                        title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-danger"
                                                                title="Anular Pedido"
                                                                onclick="anularPedido({{ $pedido->id }})">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    @endif
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-info"
                                                            title="Imprimir">
                                                        <i class="fas fa-print"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state d-flex flex-column align-items-center justify-content-center h-100">
                                <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                                <h4 class="text-muted mb-3">No hay pedidos de compra</h4>
                                <p class="text-muted mb-4 text-center">
                                    Aún no se han creado pedidos de compra en el sistema.
                                </p>
                                <a href="{{ route('pedido_compra.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Crear Primer Pedido
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Mensaje de "no hay resultados" -->
            <div id="noResults" class="card table-card flex-grow-1" style="display: none;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center h-100">
                    <i class="fas fa-search fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted mb-3">No se encontraron resultados</h4>
                    <p class="text-muted text-center">
                        No se encontraron pedidos que coincidan con los criterios de búsqueda.
                    </p>
                    <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('limpiarBtn').click()">
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
/* Estilos base */
.main-content {
    margin-left: 60px;
    width: calc(100vw - 60px);
    min-height: 100vh;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
    overflow-x: auto;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
}

.content-wrapper {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: calc(100vh - 40px);
    box-sizing: border-box;
}

/* Estilos para cards */
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

/* Tabla que ocupa todo el espacio disponible */
.table-card {
    min-height: 400px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.table-container {
    flex: 1;
    overflow-y: auto;
    overflow-x: auto;
    position: relative;
}

/* Tabla responsiva que se ajusta al contenedor */
.table {
    width: 100%;
    min-width: 1000px;
    margin-bottom: 0;
    table-layout: fixed;
}

.table th,
.table td {
    padding: 12px 8px;
    border: none;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
    overflow: hidden;
    text-overflow: ellipsis;
    word-wrap: break-word;
}

.table th {
    background-color: #343a40 !important;
    color: white;
    font-weight: 600;
    position: sticky;
    top: 0;
    z-index: 10;
    font-size: 0.85rem;
    white-space: nowrap;
}

.table td {
    font-size: 0.8rem;
}

/* Badges optimizados para espacios reducidos */
.badge.text-wrap {
    white-space: normal;
    word-wrap: break-word;
    max-width: 100%;
    line-height: 1.2;
    font-size: 0.7rem;
}

.table td .badge.fs-6 {
    font-size: 0.75rem !important;
    padding: 0.3rem 0.5rem;
    font-weight: 600;
}

/* Empty state centrado */
.empty-state {
    min-height: 400px;
}

/* Estilos para formularios */
.form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 6px;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    transform: translateY(-1px);
}

/* Resaltado de búsqueda */
.highlight {
    background-color: #ffeb3b !important;
    color: #000 !important;
    font-weight: bold;
    padding: 1px 3px;
    border-radius: 2px;
}

/* Botones más compactos */
.btn-group .btn-sm {
    padding: 0.25rem 0.4rem;
    font-size: 0.75rem;
}

/* Responsive optimizado */
@media (max-width: 1400px) {
    .table {
        min-width: 900px;
    }

    .table th,
    .table td {
        padding: 8px 6px;
        font-size: 0.75rem;
    }

    .badge {
        font-size: 0.65rem !important;
    }
}

@media (max-width: 1200px) {
    .table {
        min-width: 800px;
    }

    .table th,
    .table td {
        padding: 6px 4px;
        font-size: 0.7rem;
    }
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 50px;
        width: calc(100vw - 50px);
    }

    .content-wrapper {
        padding: 15px;
    }

    .table {
        min-width: 700px;
    }
}

/* Ajuste del menú lateral */
.sidebar-nav:hover ~ .main-content {
    margin-left: 280px;
    width: calc(100vw - 280px);
}

/* Scrollbar personalizado */
.table-container::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.table-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.table-container::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.table-container::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Efectos hover mejorados */
.table tbody tr {
    transition: all 0.2s ease;
    cursor: pointer;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.08);
    transform: scale(1.002);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Optimización de texto truncado */
.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Estados de carga */
.loading-state {
    opacity: 0.6;
    pointer-events: none;
}

/* Animaciones suaves */
.fade-in {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const searchInput = document.getElementById('searchInput');
    const clearButton = document.getElementById('clearSearch');
    const table = document.getElementById('pedidosTable');
    const filtrarBtn = document.getElementById('filtrarBtn');
    const limpiarBtn = document.getElementById('limpiarBtn');
    const estadoFilter = document.getElementById('estado_filter');
    const sucursalFilter = document.getElementById('sucursal_filter');
    const fechaFilter = document.getElementById('fecha_filter');

    if (table) {
        const rows = table.querySelectorAll('.pedido-row');
        const noResults = document.getElementById('noResults');
        const totalRowsSpan = document.getElementById('totalRows');
        const tableCard = document.querySelector('.table-card');
        const totalRows = rows.length;

        // Función para resaltar texto
        function highlightText(text, search) {
            if (!search) return text;
            const regex = new RegExp(`(${search})`, 'gi');
            return text.replace(regex, '<span class="highlight">$1</span>');
        }

        // Función para remover resaltados
        function removeHighlights() {
            const highlights = table.querySelectorAll('.highlight');
            highlights.forEach(highlight => {
                const parent = highlight.parentNode;
                parent.replaceChild(document.createTextNode(highlight.textContent), highlight);
                parent.normalize();
            });
        }

        // Función para aplicar todos los filtros
        function applyAllFilters() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const estadoSeleccionado = estadoFilter.value;
            const sucursalSeleccionada = sucursalFilter.value;
            const fechaDesde = fechaFilter.value;
            let visibleCount = 0;

            // Agregar clase de carga
            tableCard.classList.add('loading-state');

            removeHighlights();

            rows.forEach(row => {
                let mostrarFila = true;

                // Filtro por búsqueda de texto
                if (searchTerm) {
                    const cells = row.querySelectorAll('td');
                    let found = false;

                    for (let i = 0; i < cells.length - 1; i++) { // Excluir columna de acciones
                        const cellText = cells[i].textContent.toLowerCase();
                        if (cellText.includes(searchTerm)) {
                            found = true;
                            const originalText = cells[i].innerHTML;
                            if (!originalText.includes('<span class="badge') && !originalText.includes('<button')) {
                                const highlightedText = highlightText(cells[i].textContent, searchTerm);
                                cells[i].innerHTML = highlightedText;
                            }
                        }
                    }

                    if (!found) {
                        mostrarFila = false;
                    }
                }

                // Filtro por estado
                if (estadoSeleccionado && mostrarFila) {
                    const estadoBadge = row.querySelector('td:nth-child(7) .badge');
                    if (estadoBadge) {
                        const estadoTexto = estadoBadge.textContent.trim();

                        const estadoMap = {
                            '3': 'Pendiente',
                            '4': 'Confirmado',
                            '5': 'Anulado'
                        };

                        if (estadoTexto !== estadoMap[estadoSeleccionado]) {
                            mostrarFila = false;
                        }
                    }
                }

                // Filtro por sucursal
                if (sucursalSeleccionada && mostrarFila) {
                    const sucursalCell = row.querySelector('td:nth-child(5)');
                    if (sucursalCell) {
                        const sucursalBadge = sucursalCell.querySelector('.badge');
                        if (sucursalBadge) {
                            const sucursalTitle = sucursalBadge.getAttribute('title') || sucursalBadge.textContent;

                            // Buscar la sucursal seleccionada en las opciones del select
                            const sucursalOption = sucursalFilter.querySelector(`option[value="${sucursalSeleccionada}"]`);
                            const sucursalNombre = sucursalOption ? sucursalOption.textContent : '';

                            if (!sucursalTitle.includes(sucursalNombre)) {
                                mostrarFila = false;
                            }
                        }
                    }
                }

                // Filtro por fecha
                if (fechaDesde && mostrarFila) {
                    const fechaCell = row.querySelector('td:nth-child(4) .badge');
                    if (fechaCell) {
                        const fechaTexto = fechaCell.textContent.trim();

                        // Convertir fecha dd/mm/yyyy a yyyy-mm-dd para comparar
                        const [dia, mes, año] = fechaTexto.split('/');
                        if (dia && mes && año) {
                            const fechaPedido = `${año}-${mes.padStart(2, '0')}-${dia.padStart(2, '0')}`;

                            if (fechaPedido < fechaDesde) {
                                mostrarFila = false;
                            }
                        }
                    }
                }

                // Mostrar u ocultar la fila
                if (mostrarFila) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Actualizar contador
            totalRowsSpan.textContent = visibleCount;

            // Manejar mensaje "no hay resultados"
            setTimeout(() => {
                if (visibleCount === 0 && totalRows > 0) {
                    tableCard.style.display = 'none';
                    noResults.style.display = 'flex';
                } else {
                    tableCard.style.display = 'flex';
                    noResults.style.display = 'none';
                }

                // Remover clase de carga
                tableCard.classList.remove('loading-state');
            }, 100);
        }

        // Event listeners para búsqueda en tiempo real
        if (searchInput) {
            // Debounce para mejor performance
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(applyAllFilters, 150);
            });

            clearButton.addEventListener('click', function() {
                searchInput.value = '';
                applyAllFilters();
                searchInput.focus();
            });

            // Atajos de teclado
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === 'f') {
                    e.preventDefault();
                    searchInput.focus();
                }

                if (e.ctrlKey && e.key === 'k') {
                    e.preventDefault();
                    searchInput.focus();
                }
            });

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    applyAllFilters();
                    this.blur();
                }
            });
        }

        // Botón Filtrar
        if (filtrarBtn) {
            filtrarBtn.addEventListener('click', applyAllFilters);
        }

        // Botón Limpiar
        if (limpiarBtn) {
            limpiarBtn.addEventListener('click', function() {
                if (estadoFilter) estadoFilter.value = '';
                if (sucursalFilter) sucursalFilter.value = '';
                if (fechaFilter) fechaFilter.value = '';
                if (searchInput) searchInput.value = '';

                applyAllFilters();

                // Feedback visual
                this.innerHTML = '<i class="fas fa-check me-2"></i>Limpiado';
                this.classList.add('btn-success');
                this.classList.remove('btn-outline-secondary');

                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-times me-2"></i>Limpiar';
                    this.classList.remove('btn-success');
                    this.classList.add('btn-outline-secondary');
                }, 1000);
            });
        }

        // Aplicar filtros automáticamente cuando cambian los selects
        [estadoFilter, sucursalFilter, fechaFilter].forEach(filter => {
            if (filter) {
                filter.addEventListener('change', applyAllFilters);
            }
        });

        // Filtro avanzado por combinación de teclas
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.shiftKey && e.key === 'F') {
                e.preventDefault();
                if (estadoFilter) estadoFilter.focus();
            }
        });

        // Click en filas para ir a detalle
        rows.forEach(row => {
            row.addEventListener('click', function(e) {
                if (e.target.closest('.btn-group')) return; // No activar si se hace click en botones

                const verBtn = this.querySelector('a[title="Ver Detalle"]');
                if (verBtn) {
                    window.location.href = verBtn.href;
                }
            });
        });
    }

    // Función adicional para exportar datos (para futuras implementaciones)
    function exportarDatos() {
        const filasVisibles = Array.from(document.querySelectorAll('.pedido-row')).filter(row => row.style.display !== 'none');
        console.log(`Total de pedidos visibles: ${filasVisibles.length}`);
        // Aquí se puede implementar funcionalidad de exportación
    }

    // Inicialización de tooltips de Bootstrap
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                delay: { show: 500, hide: 100 }
            });
        });
    }

    // Performance monitoring
    console.log('Pedidos de Compra - Index cargado correctamente');

    window.anularPedido = function(pedidoId) {
        // SweetAlert2 si está disponible, sino confirmación nativa
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Anular Pedido?',
                text: 'Esta acción cambiará el estado del pedido a "Anulado" y no se podrá deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, Anular',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    enviarAnulacion(pedidoId);
                }
            });
        } else {
            // Confirmación nativa
            if (confirm('¿Está seguro que desea anular este pedido?\n\nEsta acción cambiará el estado a "Anulado" y no se podrá deshacer.')) {
                enviarAnulacion(pedidoId);
            }
        }
    };

    // Función para enviar la anulación
    function enviarAnulacion(pedidoId) {
        // Crear formulario dinámico para envío
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/pedido_compra/${pedidoId}/anular`;

        // Token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = csrfToken.getAttribute('content');
            form.appendChild(tokenInput);
        }

        // Método PATCH
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PATCH';
        form.appendChild(methodInput);

        // Agregar al DOM y enviar
        document.body.appendChild(form);

        // Mostrar loading en el botón
        const btnAnular = document.querySelector(`[onclick="anularPedido(${pedidoId})"]`);
        if (btnAnular) {
            btnAnular.disabled = true;
            btnAnular.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        }

        form.submit();
    }
});
</script>
