<!-- filepath: c:\laragon\www\TesisGyA\resources\views\pedido_compra\edit.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Pedido de Compra - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-edit"></i> Editar Pedido de Compra #{{ str_pad($pedido->id, 3, '0', STR_PAD_LEFT) }}</h2>
                    <small>Modifique la observación general o los insumos del pedido</small>
                </div>
                <a href="{{ route('pedido_compra.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                </a>
            </div>

            {{-- Alerts --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('pedido_compra.update', $pedido->id) }}" method="POST" id="pedidoForm">
                @csrf
                @method('PUT')

                {{-- Información del Pedido --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-info-circle me-2"></i>Información del Pedido</span>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <label class="form-label">Usuario</label>
                                <div class="info-value"><i class="fas fa-user"></i>{{ $pedido->usuario->usuario }}</div>
                            </div>
                            <div class="info-item">
                                <label class="form-label">Sucursal</label>
                                <div class="info-value"><i class="fas fa-building"></i>{{ $pedido->sucursal->descripcion }}</div>
                            </div>
                            <div class="info-item">
                                <label class="form-label">Depósito</label>
                                <div class="info-value"><i class="fas fa-warehouse"></i>{{ $pedido->deposito->descripcion }}</div>
                            </div>
                            <div class="info-item">
                                <label class="form-label">Fecha</label>
                                <div class="info-value"><i class="fas fa-calendar"></i>{{ $pedido->fecha->format('d/m/Y') }}</div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="observacion" class="form-label">Observación General (opcional)</label>
                            <textarea class="form-control form-control-sm"
                                      id="observacion"
                                      name="observacion"
                                      rows="2"
                                      placeholder="Ingrese observaciones generales del pedido...">{{ old('observacion', $pedido->observacion) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Selección de Insumos --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-plus me-2"></i>Agregar Insumos</span>
                    </div>
                    <div class="card-body">
                        <div class="add-grid">
                            <div>
                                <label for="marca_filter" class="form-label">Filtrar por Marca</label>
                                <select class="form-select form-select-sm" id="marca_filter">
                                    <option value="">Todas las marcas</option>
                                    @foreach($marcas as $marca)
                                        <option value="{{ $marca->id }}">{{ $marca->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="insumo_select" class="form-label">Seleccionar Insumo</label>
                                <select class="form-select form-select-sm" id="insumo_select" disabled>
                                    <option value="">Primero seleccione una marca</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-primary btn-sm w-100" id="agregarInsumo" disabled>
                                    <i class="fas fa-plus me-2"></i>Agregar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabla de Insumos --}}
                <div class="card table-card">
                    <div class="card-header-section">
                        <span><i class="fas fa-list me-2"></i>Insumos del Pedido</span>
                        <span class="results-count" id="totalItems">{{ $pedido->detalles->count() }} items</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-container">
                            <table id="insumosTable">
                                <thead>
                                    <tr>
                                        <th>Insumo</th>
                                        <th style="width:120px;">Marca</th>
                                        <th style="width:90px;">Unidad</th>
                                        <th style="width:130px;">Cantidad</th>
                                        <th>Observación</th>
                                        <th style="width:70px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="insumosBody">
                                    @foreach($pedido->detalles as $index => $detalle)
                                        <tr>
                                            <td>
                                                <i class="fas fa-cube text-muted me-2"></i><strong>{{ $detalle->insumo->descripcion }}</strong>
                                                <input type="hidden" name="insumos[{{ $index }}][insumo_id]" value="{{ $detalle->insumo_id }}">
                                            </td>
                                            <td>
                                                <span class="tag">{{ $detalle->insumo->marca->descripcion }}</span>
                                            </td>
                                            <td>
                                                <span class="tag tag-secondary">{{ $detalle->insumo->unidadMedida->descripcion }}</span>
                                            </td>
                                            <td>
                                                <input type="number"
                                                       class="form-control form-control-sm quantity-input"
                                                       name="insumos[{{ $index }}][cantidad]"
                                                       min="0.01"
                                                       step="0.01"
                                                       value="{{ $detalle->cantidad }}"
                                                       required
                                                       title="Cantidad solicitada">
                                            </td>
                                            <td>
                                                <textarea class="form-control form-control-sm observation-input"
                                                          name="insumos[{{ $index }}][observacion]"
                                                          rows="1"
                                                          placeholder="Observación para este insumo..."
                                                          title="Observación específica para este insumo">{{ $detalle->observacion }}</textarea>
                                            </td>
                                            <td class="text-center">
                                                <button type="button"
                                                        class="btn-icon remove-item"
                                                        data-insumo="{{ $detalle->insumo_id }}"
                                                        title="Eliminar insumo">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr id="emptyRow" style="{{ $pedido->detalles->count() > 0 ? 'display:none;' : '' }}">
                                        <td colspan="6" class="empty-cell">
                                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                            No hay insumos agregados al pedido
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <a href="{{ route('pedido_compra.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-success" id="guardarPedido" {{ $pedido->detalles->isEmpty() ? 'disabled' : '' }}>
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>

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

/* ── Form layout ── */
#pedidoForm {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

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

/* ── Información del pedido ── */
.info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
}
.info-item .form-label,
.add-grid .form-label {
    font-size: 0.7rem;
    font-weight: 500;
    color: #94a3b8;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.info-value {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-size: 0.85rem;
    color: #374151;
}
.info-value i { color: #94a3b8; margin-right: 0.5rem; width: 14px; text-align: center; }

/* ── Agregar insumos ── */
.add-grid {
    display: grid;
    grid-template-columns: 2fr 3fr 1fr;
    gap: 0.75rem;
    align-items: end;
}
.select2-container--bootstrap-5 .select2-selection {
    min-height: calc(1.5em + 0.5rem + 2px);
}

@media (max-width: 900px) {
    .info-grid { grid-template-columns: repeat(2, 1fr); }
    .add-grid { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
    .info-grid { grid-template-columns: 1fr; }
}

/* ── Tabla de insumos ── */
.table-card { display: flex; flex-direction: column; }
.table-container { overflow: auto; }

#insumosTable {
    width: 100%;
    min-width: 760px;
    border-collapse: collapse;
    table-layout: fixed;
}
#insumosTable thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 0.6rem 0.65rem;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
#insumosTable tbody td {
    padding: 0.55rem 0.65rem;
    font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
#insumosTable tbody tr:hover { background: #f8fafc; }
#insumosTable tbody tr:last-child td { border-bottom: none; }

.empty-cell {
    text-align: center;
    color: #94a3b8;
    padding: 2.5rem 1rem;
}
.empty-cell i { color: #cbd5e1; }

/* Tags (marca / unidad) */
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

.quantity-input { width: 100px; text-align: center; }
.observation-input { min-width: 200px; font-size: 0.82rem; }

/* Botón eliminar */
.btn-icon {
    width: 28px; height: 28px;
    display: inline-flex; align-items: center; justify-content: center;
    border: 1px solid #e2e8f0; border-radius: 6px;
    color: #64748b; background: #fff; font-size: 0.78rem;
    cursor: pointer;
}
.btn-icon:hover { background: #fef2f2; color: #dc2626; border-color: #fecaca; }

@media (max-width: 768px) {
    .quantity-input { width: 80px; }
    .observation-input { min-width: 150px; }
    .table-container { font-size: 0.875rem; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
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

    // Insumos ya cargados en el pedido
    let insumosAgregados = @json($pedido->detalles->pluck('insumo_id')->values());
    let insumoCounter = {{ $pedido->detalles->count() }};

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
                    <i class="fas fa-cube text-muted me-2"></i><strong>${insumoNombre}</strong>
                    <input type="hidden" name="insumos[${insumoCounter}][insumo_id]" value="${insumoId}">
                </td>
                <td>
                    <span class="tag">${marca}</span>
                </td>
                <td>
                    <span class="tag tag-secondary">${unidad}</span>
                </td>
                <td>
                    <input type="number"
                           class="form-control form-control-sm quantity-input"
                           name="insumos[${insumoCounter}][cantidad]"
                           min="0.01"
                           step="0.01"
                           value="1.00"
                           required
                           title="Cantidad solicitada">
                </td>
                <td>
                    <textarea class="form-control form-control-sm observation-input"
                              name="insumos[${insumoCounter}][observacion]"
                              rows="1"
                              placeholder="Observación para este insumo..."
                              title="Observación específica para este insumo"></textarea>
                </td>
                <td class="text-center">
                    <button type="button"
                            class="btn-icon remove-item"
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
});
</script>
