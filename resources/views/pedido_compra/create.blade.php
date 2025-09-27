<!-- filepath: c:\laragon\www\TesisGyA\resources\views\pedido_compra\create.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Pedido de Compra - TesisGyA</title>
    @include('partials.head')

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content fade-in">
        <div class="content-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-shopping-cart me-2"></i>Nuevo Pedido de Compra</h2>
                <a href="{{ route('pedido_compra.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                </a>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pedido_compra.store') }}" method="POST" id="pedidoForm">
                @csrf

                <!-- Información del Pedido -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información del Pedido</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Usuario:</label>
                                <div class="form-control-plaintext bg-light rounded px-3 py-2">
                                    <i class="fas fa-user me-2"></i>{{ session('user_usuario') }}
                                </div>
                                <input type="hidden" name="usuario_id" value="{{ session('user_id') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Sucursal:</label>
                                <div class="form-control-plaintext bg-light rounded px-3 py-2">
                                    <i class="fas fa-building me-2"></i>{{ $sucursal->descripcion }}
                                </div>
                                <input type="hidden" name="sucursal_id" value="{{ $sucursal->id }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Depósito:</label>
                                <div class="form-control-plaintext bg-light rounded px-3 py-2">
                                    <i class="fas fa-warehouse me-2"></i>{{ $deposito->descripcion }}
                                </div>
                                <input type="hidden" name="deposito_id" value="{{ $deposito->id }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha:</label>
                                <div class="form-control-plaintext bg-light rounded px-3 py-2">
                                    <i class="fas fa-calendar me-2"></i>{{ date('d/m/Y') }}
                                </div>
                                <input type="hidden" name="fecha" value="{{ date('Y-m-d') }}">
                                <input type="hidden" name="estado_id" value="3">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <label for="observacion" class="form-label">Observación General (opcional):</label>
                                <textarea class="form-control"
                                          id="observacion"
                                          name="observacion"
                                          rows="2"
                                          placeholder="Ingrese observaciones generales del pedido...">{{ old('observacion') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selección de Insumos -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Agregar Insumos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="marca_filter" class="form-label">Filtrar por Marca:</label>
                                <select class="form-select" id="marca_filter">
                                    <option value="">Todas las marcas</option>
                                    @foreach($marcas as $marca)
                                        <option value="{{ $marca->id }}">{{ $marca->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="insumo_select" class="form-label">Seleccionar Insumo:</label>
                                <select class="form-select" id="insumo_select" disabled>
                                    <option value="">Primero seleccione una marca</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary w-100" id="agregarInsumo" disabled>
                                    <i class="fas fa-plus me-2"></i>Agregar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Insumos -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Insumos del Pedido</h5>
                        <span class="badge bg-light text-dark" id="totalItems">0 items</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="insumosTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="25%">Insumo</th>
                                        <th width="15%">Marca</th>
                                        <th width="10%">Unidad</th>
                                        <th width="15%">Cantidad</th>
                                        <th width="25%">Observación</th>
                                        <th width="10%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="insumosBody">
                                    <tr id="emptyRow">
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                            No hay insumos agregados al pedido
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('pedido_compra.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-success" id="guardarPedido" disabled>
                                <i class="fas fa-save me-2"></i>Guardar Pedido
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('partials.footer')

    <!-- jQuery (necesario para Select2) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>

<style>
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

.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    transform: translateY(-1px);
}

.form-control-plaintext {
    border: 2px solid #e9ecef;
}

.btn {
    border-radius: 6px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.table th {
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
}

.quantity-input {
    width: 100px;
    text-align: center;
}

.observation-input {
    min-width: 200px;
    font-size: 0.9rem;
}

/* Select2 Bootstrap 5 Theme Custom */
.select2-container--bootstrap-5 .select2-selection {
    border: 2px solid #e9ecef !important;
    border-radius: 6px !important;
    transition: all 0.3s ease;
}

.select2-container--bootstrap-5 .select2-selection:focus-within {
    border-color: #007bff !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
    transform: translateY(-1px);
}

.select2-container .select2-search--inline .select2-search__field {
    border: none !important;
    outline: none !important;
}

/* Filas de la tabla mejoradas */
.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.table tbody tr td {
    vertical-align: middle;
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 50px;
        width: calc(100vw - 50px);
    }

    .content-wrapper {
        padding: 15px;
    }

    .table-responsive {
        font-size: 0.875rem;
    }

    .quantity-input {
        width: 80px;
    }

    .observation-input {
        min-width: 150px;
    }
}

.sidebar-nav:hover ~ .main-content {
    margin-left: 280px;
    width: calc(100vw - 280px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Verificar que jQuery y Select2 estén cargados
    if (typeof $ === 'undefined') {
        console.error('jQuery no está cargado');
        return;
    }

    if (typeof $.fn.select2 === 'undefined') {
        console.error('Select2 no está cargado');
        return;
    }

    // Inicializar Select2
    $('#marca_filter').select2({
        theme: 'bootstrap-5',
        placeholder: 'Seleccione una marca',
        allowClear: true,
        width: '100%'
    });

    $('#insumo_select').select2({
        theme: 'bootstrap-5',
        placeholder: 'Primero seleccione una marca',
        allowClear: true,
        width: '100%'
    });

    // Variables globales
    const agregarBtn = document.getElementById('agregarInsumo');
    const insumosBody = document.getElementById('insumosBody');
    const emptyRow = document.getElementById('emptyRow');
    const totalItems = document.getElementById('totalItems');
    const guardarBtn = document.getElementById('guardarPedido');

    let insumosAgregados = [];
    let insumoCounter = 0;

    // Datos de insumos
    const insumosData = @json($insumos);

    // Filtrar insumos por marca
    $('#marca_filter').on('select2:select select2:clear change', function() {
        const marcaId = $(this).val();

        // Limpiar y deshabilitar select de insumos
        $('#insumo_select').empty().append('<option value="">Seleccione un insumo</option>');

        if (marcaId) {
            const insumosFiltrados = insumosData.filter(insumo =>
                insumo.marca_id == marcaId && insumo.estado_id == 1
            );

            insumosFiltrados.forEach(insumo => {
                if (!insumosAgregados.includes(insumo.id)) {
                    const option = new Option(insumo.descripcion, insumo.id);
                    option.dataset.marca = insumo.marca.descripcion;
                    option.dataset.unidad = insumo.unidad_medida.abreviatura || insumo.unidad_medida.descripcion;
                    $('#insumo_select').append(option);
                }
            });

            $('#insumo_select').prop('disabled', false);
        } else {
            $('#insumo_select').prop('disabled', true);
        }

        // Actualizar Select2
        $('#insumo_select').trigger('change');
        agregarBtn.disabled = true;
    });

    // Habilitar botón agregar cuando se selecciona insumo
    $('#insumo_select').on('select2:select select2:clear change', function() {
        agregarBtn.disabled = !$(this).val();
    });

    // Agregar insumo a la tabla
    agregarBtn.addEventListener('click', function() {
        const insumoId = $('#insumo_select').val();
        const selectedOption = $('#insumo_select option:selected')[0];

        if (insumoId && !insumosAgregados.includes(parseInt(insumoId))) {
            const insumoNombre = selectedOption.textContent;
            const marca = selectedOption.dataset.marca;
            const unidad = selectedOption.dataset.unidad;

            // Crear fila
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <div class="d-flex align-items-center">
                        <div class="me-2 text-primary">
                            <i class="fas fa-cube fa-lg"></i>
                        </div>
                        <div>
                            <strong class="text-dark">${insumoNombre}</strong>
                        </div>
                    </div>
                    <input type="hidden" name="insumos[${insumoCounter}][insumo_id]" value="${insumoId}">
                </td>
                <td>
                    <span class="badge bg-primary">${marca}</span>
                </td>
                <td>
                    <span class="badge bg-secondary">${unidad}</span>
                </td>
                <td>
                    <input type="number"
                           class="form-control quantity-input"
                           name="insumos[${insumoCounter}][cantidad]"
                           min="0.01"
                           step="0.01"
                           value="1.00"
                           required
                           title="Cantidad solicitada">
                </td>
                <td>
                    <textarea class="form-control observation-input"
                              name="insumos[${insumoCounter}][observacion]"
                              rows="2"
                              placeholder="Observación para este insumo..."
                              title="Observación específica para este insumo"></textarea>
                </td>
                <td class="text-center">
                    <button type="button"
                            class="btn btn-sm btn-outline-danger remove-item"
                            data-insumo="${insumoId}"
                            title="Eliminar insumo">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            `;

            // Agregar fila antes del emptyRow
            insumosBody.insertBefore(row, emptyRow);

            // Actualizar arrays y contadores
            insumosAgregados.push(parseInt(insumoId));
            insumoCounter++;

            // Remover insumo del select
            $(`#insumo_select option[value="${insumoId}"]`).remove();

            // Ocultar mensaje vacío
            emptyRow.style.display = 'none';

            // Actualizar contador
            updateItemCount();

            // Resetear selects
            $('#insumo_select').val(null).trigger('change');
            agregarBtn.disabled = true;

            // Habilitar botón guardar
            guardarBtn.disabled = false;

            // Agregar efecto de aparición a la nueva fila
            row.style.opacity = '0';
            row.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, 10);
        }
    });

    // Eliminar insumo de la tabla
    insumosBody.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            const btn = e.target.closest('.remove-item');
            const insumoId = parseInt(btn.dataset.insumo);
            const row = btn.closest('tr');

            // Confirmar eliminación
            if (confirm('¿Está seguro que desea eliminar este insumo del pedido?')) {
                // Efecto de desaparición
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '0';
                row.style.transform = 'translateY(-10px)';

                setTimeout(() => {
                    // Remover de array
                    const index = insumosAgregados.indexOf(insumoId);
                    if (index > -1) {
                        insumosAgregados.splice(index, 1);
                    }

                    // Remover fila
                    row.remove();

                    // Volver a agregar al select si la marca está seleccionada
                    const marcaId = $('#marca_filter').val();
                    if (marcaId) {
                        const insumo = insumosData.find(i => i.id == insumoId);
                        if (insumo && insumo.marca_id == marcaId) {
                            const option = new Option(insumo.descripcion, insumo.id);
                            option.dataset.marca = insumo.marca.descripcion;
                            option.dataset.unidad = insumo.unidad_medida.abreviatura || insumo.unidad_medida.descripcion;
                            $('#insumo_select').append(option);
                        }
                    }

                    // Actualizar contador
                    updateItemCount();

                    // Mostrar mensaje vacío si no hay items
                    if (insumosAgregados.length === 0) {
                        emptyRow.style.display = '';
                        guardarBtn.disabled = true;
                    }
                }, 300);
            }
        }
    });

    // Actualizar contador de items
    function updateItemCount() {
        totalItems.textContent = `${insumosAgregados.length} items`;
    }

    // Validación del formulario
    document.getElementById('pedidoForm').addEventListener('submit', function(e) {
        if (insumosAgregados.length === 0) {
            e.preventDefault();
            alert('Debe agregar al menos un insumo al pedido.');
            return false;
        }

        const quantities = this.querySelectorAll('.quantity-input');
        for (let input of quantities) {
            if (!input.value || parseFloat(input.value) <= 0) {
                e.preventDefault();
                alert('Todas las cantidades deben ser mayor a 0.');
                input.focus();
                return false;
            }
        }

        // Mostrar loading en el botón
        guardarBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
        guardarBtn.disabled = true;
    });

    // Auto-resize para textareas de observación
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('observation-input')) {
            e.target.style.height = 'auto';
            e.target.style.height = (e.target.scrollHeight) + 'px';
        }
    });

    console.log('Formulario de pedido de compra cargado correctamente');
});
</script>
