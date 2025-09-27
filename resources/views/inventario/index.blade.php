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

    <div class="main-content fade-in">
        <div class="content-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-warehouse me-2"></i>Inventario</h2>
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

            <div class="card mb-3">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <label for="sucursal_filter" class="form-label mb-2">
                                <i class="fas fa-building me-1"></i>Filtrar por Sucursal:
                            </label>
                            <select class="form-select" id="sucursal_filter">
                                <option value="">Todas las sucursales</option>
                                @foreach($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}"
                                            {{ $sucursalSeleccionada == $sucursal->id ? 'selected' : '' }}>
                                        {{ $sucursal->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-2">&nbsp;</label>
                            <div>
                                <button type="button" id="filtrarBtn" class="btn btn-primary">
                                    <i class="fas fa-filter me-2"></i>Filtrar
                                </button>
                                <a href="{{ route('inventario.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Limpiar
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <small class="text-muted">
                                @if($sucursalSeleccionada)
                                    Mostrando: {{ $sucursales->find($sucursalSeleccionada)->descripcion ?? 'Sucursal' }}
                                @else
                                    Mostrando: Todas las sucursales
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body py-3">
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text"
                               class="form-control"
                               id="searchInput"
                               placeholder="Buscar por insumo, marca, depósito, cantidad..."
                               autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        <span id="searchResults">Mostrando <span id="totalRows">{{ $inventarios->count() }}</span> registro(s)</span>
                    </small>
                </div>
            </div>

            <div class="card table-card">
                <div class="card-body p-0">
                    <div class="table-responsive table-container">
                        @if($inventarios->count() > 0)
                            <table class="table table-striped table-hover mb-0" id="inventarioTable">
                                <thead class="table-dark sticky-top">
                                    <tr>
                                        <th style="width: 60px; min-width: 60px;">ID</th>
                                        <th style="width: 120px; min-width: 120px;">Depósito</th>
                                        <th style="min-width: 200px;">Insumo</th>
                                        <th style="width: 120px; min-width: 120px;">Marca</th>
                                        <th style="width: 100px; min-width: 100px;">Unidad</th>
                                        <th style="width: 100px; min-width: 100px;">Cantidad</th>
                                        <th style="width: 80px; min-width: 80px;">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inventarios as $inventario)
                                        <tr class="inventario-row">
                                            <td><strong>{{ $inventario->id }}</strong></td>
                                            <td>
                                                <span class="badge bg-info text-wrap" title="{{ $inventario->deposito->descripcion }}">
                                                    {{ Str::limit($inventario->deposito->descripcion, 15) }}
                                                </span>
                                            </td>
                                            <td title="{{ $inventario->insumo->descripcion }}">
                                                {{ $inventario->insumo->descripcion }}
                                            </td>
                                            <td>
                                                <span class="badge bg-primary text-wrap" title="{{ $inventario->insumo->marca->descripcion }}">
                                                    {{ $inventario->insumo->marca->descripcion }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary text-wrap" title="{{ $inventario->insumo->unidadMedida->descripcion }}">
                                                    {{ $inventario->insumo->unidadMedida->abreviatura ?? $inventario->insumo->unidadMedida->descripcion }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ $inventario->cantidad > 0 ? 'bg-success' : 'bg-warning text-dark' }} fs-6">
                                                    {{ number_format($inventario->cantidad, 2, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $inventario->estado->id == 1 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $inventario->estado->id == 1 ? 'Activo' : 'Inactivo' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!-- Fila para "no hay resultados de búsqueda" - MOVIDA DENTRO DEL TBODY -->
                                    <tr id="noResults" style="display: none;">
                                        <td colspan="7" class="text-center py-5">
                                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No se encontraron resultados para la búsqueda</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
                                <h4 class="text-muted mb-3">No hay inventario disponible</h4>
                                @if($sucursalSeleccionada)
                                    <p class="text-muted mb-4">
                                        No se encontraron productos en el inventario para la sucursal seleccionada.
                                    </p>
                                @else
                                    <p class="text-muted mb-4">
                                        El inventario se actualiza automáticamente con los ingresos y salidas de productos.
                                    </p>
                                @endif
                                @if($sucursalSeleccionada)
                                    <a href="{{ route('inventario.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-eye me-2"></i>Ver Todas las Sucursales
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>

<style>
/* Usar los mismos estilos del index */
.main-content {
    margin-left: 60px;
    width: calc(100vw - 60px);
    min-height: 100vh;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
    overflow-x: hidden;
    box-sizing: border-box;
}

.content-wrapper {
    padding: 20px;
    max-width: 100%;
    box-sizing: border-box;
}

/* Estilos para la card */
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.table-card {
    height: calc(100vh - 280px);
    min-height: 400px;
    max-width: 100%;
    overflow: hidden;
    border-radius: 8px;
}

.table-container {
    height: 100%;
    overflow-y: auto;
    overflow-x: hidden;
    border-radius: 8px;
}

.table {
    table-layout: fixed;
    width: 100%;
    margin-bottom: 0;
}

.table th,
.table td {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    padding: 8px 12px;
    border: none;
    border-bottom: 1px solid #e9ecef;
}

.table th {
    background-color: #343a40 !important;
    color: white;
    font-weight: 600;
    position: sticky;
    top: 0;
    z-index: 10;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.8rem;
    border-radius: 4px;
}

.badge.text-wrap {
    white-space: normal;
    word-wrap: break-word;
    max-width: 100%;
}

/* Estilos para cantidad */
.table td .badge.fs-6 {
    font-size: 0.9rem !important;
    padding: 0.5rem 0.75rem;
    font-weight: 600;
}

/* Estilos para formularios */
.form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 6px;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    transform: translateY(-1px);
}

/* Resaltado de coincidencias */
.highlight {
    background-color: #ffeb3b !important;
    color: #000 !important;
    font-weight: bold;
    padding: 1px 3px;
    border-radius: 2px;
}

/* Animación para filas ocultas */
.inventario-row {
    transition: all 0.3s ease;
}

.inventario-row.hidden {
    display: none;
}

/* Responsive */
@media (max-width: 1200px) {
    .table th,
    .table td {
        padding: 6px 8px;
        font-size: 0.85rem;
    }

    .btn-sm {
        padding: 0.2rem 0.4rem;
        font-size: 0.75rem;
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

    .table-card {
        height: calc(100vh - 260px);
    }

    .table th,
    .table td {
        padding: 4px 6px;
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .main-content {
        margin-left: 45px;
        width: calc(100vw - 45px);
    }

    .content-wrapper {
        padding: 10px;
    }
}

/* Ajuste cuando el menú se expande */
.sidebar-nav:hover ~ .main-content {
    margin-left: 280px;
    width: calc(100vw - 280px);
}

/* Scrollbar personalizado */
.table-container::-webkit-scrollbar {
    width: 6px;
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

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
    transform: scale(1.002);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtro por sucursal
    const sucursalFilter = document.getElementById('sucursal_filter');
    const filtrarBtn = document.getElementById('filtrarBtn');

    if (filtrarBtn && sucursalFilter) {
        filtrarBtn.addEventListener('click', function() {
            const sucursalId = sucursalFilter.value;
            const url = new URL(window.location.href);

            if (sucursalId) {
                url.searchParams.set('sucursal', sucursalId);
            } else {
                url.searchParams.delete('sucursal');
            }

            window.location.href = url.toString();
        });
    }

    // Buscador de inventario
    const searchInput = document.getElementById('searchInput');
    const clearButton = document.getElementById('clearSearch');
    const table = document.getElementById('inventarioTable');

    if (table) {
        const rows = table.querySelectorAll('.inventario-row');
        const noResults = document.getElementById('noResults');
        const totalRowsSpan = document.getElementById('totalRows');
        const totalRows = rows.length;

        function highlightText(text, search) {
            if (!search) return text;
            const regex = new RegExp(`(${search})`, 'gi');
            return text.replace(regex, '<span class="highlight">$1</span>');
        }

        function removeHighlights() {
            const highlights = table.querySelectorAll('.highlight');
            highlights.forEach(highlight => {
                const parent = highlight.parentNode;
                parent.replaceChild(document.createTextNode(highlight.textContent), highlight);
                parent.normalize();
            });
        }

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let visibleCount = 0;

            // Remover resaltados anteriores
            removeHighlights();

            rows.forEach(row => {
                if (!searchTerm) {
                    row.style.display = '';
                    visibleCount++;
                    return;
                }

                const cells = row.querySelectorAll('td');
                let found = false;

                // Buscar en todas las celdas
                for (let i = 0; i < cells.length; i++) {
                    const cellText = cells[i].textContent.toLowerCase();

                    if (cellText.includes(searchTerm)) {
                        found = true;
                        // Resaltar coincidencias (solo en texto plano, no en badges)
                        const originalText = cells[i].innerHTML;
                        if (!originalText.includes('<span class="badge')) {
                            const highlightedText = highlightText(cells[i].textContent, searchTerm);
                            cells[i].innerHTML = highlightedText;
                        }
                    }
                }

                if (found) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Actualizar contador
            totalRowsSpan.textContent = visibleCount;

            // Mostrar/ocultar mensaje de "no resultados" - CORREGIDO
            if (visibleCount === 0 && searchTerm && totalRows > 0) {
                noResults.style.display = 'table-row';
            } else {
                noResults.style.display = 'none';
            }
        }

        // Event listeners para el buscador
        if (searchInput) {
            searchInput.addEventListener('input', filterTable);

            // Limpiar búsqueda
            clearButton.addEventListener('click', function() {
                searchInput.value = '';
                filterTable();
                searchInput.focus();
            });

            // Enfocar el buscador al presionar Ctrl+F
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === 'f') {
                    e.preventDefault();
                    searchInput.focus();
                }
            });

            // Limpiar con Escape
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    filterTable();
                }
            });
        }
    }
});
</script>
