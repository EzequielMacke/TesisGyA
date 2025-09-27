<!-- filepath: c:\laragon\www\TesisGyA\resources\views\marca\index.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Marcas - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content fade-in">
        <div class="content-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-tags me-2"></i>Lista de Marcas</h2>
                <a href="{{ route('marca.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nueva Marca
                </a>
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
                               placeholder="Buscar por ID, descripción, fecha, estado o usuario..."
                               autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        <span id="searchResults">Mostrando <span id="totalRows">{{ $marcas->count() }}</span> registro(s)</span>
                    </small>
                </div>
            </div>

            <div class="card table-card">
                <div class="card-body p-0">
                    <div class="table-responsive table-container">
                        <table class="table table-striped table-hover mb-0" id="marcasTable">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th style="width: 60px; min-width: 60px;">ID</th>
                                    <th style="min-width: 200px;">Descripción</th>
                                    <th style="width: 100px; min-width: 100px;">Fecha</th>
                                    <th style="width: 80px; min-width: 80px;">Estado</th>
                                    <th style="width: 120px; min-width: 120px;">Usuario</th>
                                    <th style="width: 120px; min-width: 120px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($marcas as $marca)
                                    <tr class="marca-row">
                                        <td><strong>{{ $marca->id }}</strong></td>
                                        <td>{{ $marca->descripcion }}</td>
                                        <td>{{ $marca->fecha->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge {{ $marca->estado->id == 1 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $marca->estado->descripcion }}
                                            </span>
                                        </td>
                                        <td>{{ $marca->usuario->usuario }}</td>
                                        <td>
                                            @if($marca->estado->id == 1)
                                                <!-- Botón Editar - Solo si está activo -->
                                                <a href="#" class="btn btn-sm btn-warning me-1" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <!-- Botón Eliminar/Desactivar - Solo si está activo -->
                                                <form action="{{ route('marca.destroy', $marca->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Desactivar" onclick="return confirm('¿Estás seguro de desactivar esta marca?')">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </form>
                                            @elseif($marca->estado->id == 2)
                                                <!-- Botón Activar - Solo si está inactivo -->
                                                <form action="{{ route('marca.activate', $marca->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success" title="Activar" onclick="return confirm('¿Estás seguro de activar esta marca?')">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="noRecords">
                                        <td colspan="6" class="text-center py-5">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No hay marcas registradas</p>
                                        </td>
                                    </tr>
                                @endforelse
                                <tr id="noResults" style="display: none;">
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No se encontraron resultados para la búsqueda</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>

<style>
/* Estilos existentes */
body {
    overflow-x: hidden;
}

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
    padding: 15px;
    max-width: 100%;
    box-sizing: border-box;
    overflow: hidden;
}

.table-card {
    height: calc(100vh - 240px);
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

/* Estilos para el buscador */
#searchInput {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

#searchInput:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
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
.marca-row {
    transition: all 0.3s ease;
}

.marca-row.hidden {
    display: none;
}

/* Responsive */
@media (max-width: 768px) {
    .main-content {
        margin-left: 50px;
        width: calc(100vw - 50px);
    }

    .content-wrapper {
        padding: 10px;
    }

    .table-card {
        height: calc(100vh - 220px);
    }

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

@media (max-width: 576px) {
    .main-content {
        margin-left: 45px;
        width: calc(100vw - 45px);
    }

    .content-wrapper {
        padding: 8px;
    }

    .table-card {
        height: calc(100vh - 200px);
    }

    .table th,
    .table td {
        padding: 4px 6px;
        font-size: 0.8rem;
    }
}

/* Ajuste cuando el menú se expande */
.sidebar-nav:hover ~ .main-content {
    margin-left: 280px;
    width: calc(100vw - 280px);
}

@media (max-width: 768px) {
    .sidebar-nav:hover ~ .main-content {
        margin-left: 250px;
        width: calc(100vw - 250px);
    }
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
    transform: scale(1.005);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearButton = document.getElementById('clearSearch');
    const table = document.getElementById('marcasTable');
    const rows = table.querySelectorAll('.marca-row');
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
            let rowText = '';

            // Buscar en todas las celdas excepto la de acciones
            for (let i = 0; i < cells.length - 1; i++) {
                const cellText = cells[i].textContent.toLowerCase();
                rowText += cellText + ' ';

                if (cellText.includes(searchTerm)) {
                    found = true;
                    // Resaltar coincidencias
                    const originalText = cells[i].innerHTML;
                    const highlightedText = highlightText(cells[i].textContent, searchTerm);

                    // Solo aplicar resaltado si no hay HTML complejo (badges, botones, etc.)
                    if (!originalText.includes('<span class="badge') && !originalText.includes('<form')) {
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

        // Mostrar/ocultar mensaje de "no resultados"
        if (visibleCount === 0 && searchTerm && totalRows > 0) {
            noResults.style.display = '';
        } else {
            noResults.style.display = 'none';
        }
    }

    // Event listeners
    searchInput.addEventListener('input', filterTable);

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
});
</script>
