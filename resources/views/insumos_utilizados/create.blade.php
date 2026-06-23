<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cargar Insumos Utilizados</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-boxes"></i> Cargar Insumos Utilizados</h2>
                    <small>Registre los insumos utilizados en una orden de servicio</small>
                </div>
                <a href="{{ route('insumos_utilizados.index') }}" class="btn btn-secondary">
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

            <form action="{{ route('insumos_utilizados.store') }}" method="POST" id="insumoUtilizadoForm">
                @csrf

                {{-- Selección de Datos --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-search me-2"></i>Selección de Datos</span>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div>
                                <label for="orden_servicio_id" class="form-label">Orden de Servicio</label>
                                <select name="orden_servicio_id" id="orden_servicio_id" class="form-select form-select-sm select2" required>
                                    <option value="">Seleccionar Orden de Servicio</option>
                                    @foreach($ordenesServicio as $orden)
                                        <option value="{{ $orden->id }}"
                                                data-obra-id="{{ $orden->obra_id }}"
                                                data-obra="{{ $orden->obra->descripcion ?? '-' }}"
                                                data-cliente="{{ $orden->obra->cliente->razon_social ?? '-' }}">
                                            {{ $orden->nro }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Información de la Orden --}}
                <div class="card" id="info-section" style="display:none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-info-circle me-2"></i>Información de la Orden</span>
                    </div>
                    <div class="card-body">
                        <div class="detail-box">
                            <div class="detail-box-title">Datos de la Orden Seleccionada</div>
                            <div id="orden-datos"></div>
                        </div>
                    </div>
                </div>

                {{-- Agregar Insumos --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-plus me-2"></i>Agregar Insumos</span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning py-2 px-3 mb-3" id="sin-inventario-alert" style="display:none; font-size:0.82rem;">
                            <i class="fas fa-exclamation-triangle me-2"></i>La obra de la orden seleccionada no tiene insumos en inventario.
                        </div>
                        <div class="add-grid">
                            <div>
                                <label for="insumo_select" class="form-label">Insumo</label>
                                <select class="form-select form-select-sm" id="insumo_select" disabled>
                                    <option value="">Primero seleccione una orden de servicio</option>
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
                        <span><i class="fas fa-list me-2"></i>Insumos Cargados</span>
                        <span class="results-count" id="totalItems">0 items</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-container">
                            <table id="insumosTable">
                                <thead>
                                    <tr>
                                        <th>Insumo</th>
                                        <th style="width:90px;">Unidad</th>
                                        <th style="width:110px;">Disponible</th>
                                        <th style="width:130px;">Cantidad</th>
                                        <th style="width:70px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="insumosBody">
                                    <tr id="emptyRow">
                                        <td colspan="5" class="empty-cell">
                                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                            No hay insumos agregados
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Datos del Registro --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-clipboard-list me-2"></i>Datos del Registro</span>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div>
                                <label for="nro_display" class="form-label">Nro</label>
                                <input type="text" id="nro_display" class="form-control form-control-sm readonly-field" value="{{ $proximoNro }}" readonly>
                            </div>
                            <div>
                                <label for="fecha_registro_display" class="form-label">Fecha de Registro</label>
                                <input type="text" id="fecha_registro_display" class="form-control form-control-sm readonly-field" value="{{ date('d/m/Y') }}" readonly>
                            </div>
                            <div>
                                <label for="usuario_display" class="form-label">Usuario</label>
                                <input type="text" id="usuario_display" class="form-control form-control-sm readonly-field" value="{{ session('user_usuario') }}" readonly>
                            </div>
                            <div class="span-4">
                                <label for="observacion" class="form-label">Observación</label>
                                <textarea name="observacion" id="observacion" class="form-control form-control-sm" rows="2" placeholder="Ingrese una observación...">{{ old('observacion') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-success" id="guardarInsumoUtilizado" disabled>
                        <i class="fas fa-save me-2"></i>Guardar Insumos Utilizados
                    </button>
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

/* ── Formulario ── */
#insumoUtilizadoForm {
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

/* ── Grillas de formulario ── */
.form-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
}
.form-grid .form-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 500;
    color: #94a3b8;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.form-grid .span-4 { grid-column: span 4; }
.readonly-field {
    background-color: #f8fafc !important;
    border-color: #e2e8f0 !important;
    color: #374151;
}

/* ── Información de la orden ── */
.detail-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.6rem 0.75rem;
    font-size: 0.8rem;
    color: #374151;
}
.detail-box-title {
    font-size: 0.7rem;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.4rem;
}
.detail-row { display: flex; align-items: flex-start; gap: 0.4rem; margin-bottom: 0.25rem; }
.detail-row:last-child { margin-bottom: 0; }
.detail-row i { color: #94a3b8; width: 14px; text-align: center; margin-top: 0.15rem; }

/* ── Agregar insumos ── */
.add-grid {
    display: grid;
    grid-template-columns: 3fr 1fr;
    gap: 0.75rem;
    align-items: end;
}
.select2-container--bootstrap-5 .select2-selection {
    min-height: calc(1.5em + 0.5rem + 2px);
}

/* ── Tabla de insumos ── */
.table-card { display: flex; flex-direction: column; }
.table-container { overflow: auto; }

#insumosTable {
    width: 100%;
    min-width: 600px;
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

.tag {
    display: inline-block;
    padding: 0.2rem 0.55rem;
    border-radius: 4px;
    font-size: 0.72rem;
    font-weight: 600;
    background: #f1f5f9;
    color: #64748b;
}

.quantity-input { width: 110px; text-align: center; }

.btn-icon {
    width: 28px; height: 28px;
    display: inline-flex; align-items: center; justify-content: center;
    border: 1px solid #e2e8f0; border-radius: 6px;
    color: #64748b; background: #fff; font-size: 0.78rem;
    cursor: pointer;
}
.btn-icon:hover { background: #fef2f2; color: #dc2626; border-color: #fecaca; }

@media (max-width: 900px) {
    .form-grid { grid-template-columns: repeat(2, 1fr); }
    .add-grid { grid-template-columns: 1fr; }
    .page-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .form-grid { grid-template-columns: 1fr; }
    .form-grid .span-4 { grid-column: span 1; }
}
@media (max-width: 768px) {
    .quantity-input { width: 90px; }
    .table-container { font-size: 0.875rem; }
}
</style>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Seleccione una opción",
        allowClear: true,
        theme: 'bootstrap-5',
        width: '100%'
    });

    $('#insumo_select').select2({
        placeholder: "Primero seleccione una orden de servicio",
        allowClear: true,
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Variables globales
    const agregarBtn = document.getElementById('agregarInsumo');
    const insumosBody = document.getElementById('insumosBody');
    const emptyRow = document.getElementById('emptyRow');
    const totalItems = document.getElementById('totalItems');
    const guardarBtn = document.getElementById('guardarInsumoUtilizado');
    const sinInventarioAlert = document.getElementById('sin-inventario-alert');

    let insumosAgregados = [];
    let insumoCounter = 0;
    let insumosDisponibles = [];

    function resetTablaInsumos() {
        insumosBody.querySelectorAll('tr:not(#emptyRow)').forEach(tr => tr.remove());
        insumosAgregados = [];
        emptyRow.style.display = '';
        updateItemCount();
        guardarBtn.disabled = true;
    }

    function poblarSelectInsumos(insumos) {
        insumosDisponibles = insumos;
        const $select = $('#insumo_select');
        $select.empty().append('<option value="">Seleccionar Insumo</option>');

        insumos.forEach(insumo => {
            if (!insumosAgregados.includes(insumo.id)) {
                const option = new Option(insumo.descripcion, insumo.id);
                option.dataset.unidad = insumo.unidad;
                option.dataset.cantidad = insumo.cantidad;
                $select.append(option);
            }
        });

        $select.trigger('change');
    }

    // Al cambiar la orden de servicio: mostrar datos de la obra y cargar sus insumos en inventario
    $('#orden_servicio_id').on('change', function() {
        const selected = $(this).find('option:selected');
        const obraId = selected.data('obra-id');
        const obra = selected.data('obra');

        resetTablaInsumos();
        sinInventarioAlert.style.display = 'none';

        if (obraId !== undefined) {
            $('#orden-datos').html(`
                <div class="detail-row"><i class="fas fa-building"></i><span><strong>Cliente:</strong> ${selected.data('cliente') || '-'}</span></div>
                <div class="detail-row"><i class="fas fa-map-marker-alt"></i><span><strong>Obra:</strong> ${obra}</span></div>
            `);
            $('#info-section').show();

            $('#insumo_select').prop('disabled', true).empty().append('<option value="">Cargando insumos...</option>').trigger('change');
            agregarBtn.disabled = true;

            fetch(`/insumos-por-obra/${obraId}`)
                .then(response => response.json())
                .then(insumos => {
                    if (insumos.length === 0) {
                        insumosDisponibles = [];
                        $('#insumo_select').empty().append('<option value="">Sin insumos disponibles</option>').trigger('change');
                        sinInventarioAlert.style.display = '';
                    } else {
                        poblarSelectInsumos(insumos);
                        $('#insumo_select').prop('disabled', false);
                    }
                });
        } else {
            $('#info-section').hide();
            $('#orden-datos').html('');
            $('#insumo_select').prop('disabled', true).empty().append('<option value="">Primero seleccione una orden de servicio</option>').trigger('change');
        }
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
            const unidad = selectedOption.dataset.unidad;
            const disponible = selectedOption.dataset.cantidad;

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <i class="fas fa-cube text-muted me-2"></i><strong>${insumoNombre}</strong>
                    <input type="hidden" name="insumos[${insumoCounter}][insumo_id]" value="${insumoId}">
                </td>
                <td>
                    <span class="tag">${unidad}</span>
                </td>
                <td>${disponible}</td>
                <td>
                    <input type="number"
                           class="form-control form-control-sm quantity-input"
                           name="insumos[${insumoCounter}][cantidad]"
                           min="0.01"
                           max="${disponible}"
                           step="0.01"
                           value="1.00"
                           required
                           title="Cantidad utilizada">
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

            insumosBody.insertBefore(row, emptyRow);

            insumosAgregados.push(parseInt(insumoId));
            insumoCounter++;

            $(`#insumo_select option[value="${insumoId}"]`).remove();

            emptyRow.style.display = 'none';

            updateItemCount();

            $('#insumo_select').val(null).trigger('change');
            agregarBtn.disabled = true;

            guardarBtn.disabled = false;
        }
    });

    // Eliminar insumo de la tabla
    insumosBody.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            const btn = e.target.closest('.remove-item');
            const insumoId = parseInt(btn.dataset.insumo);
            const row = btn.closest('tr');

            if (confirm('¿Está seguro que desea quitar este insumo?')) {
                const index = insumosAgregados.indexOf(insumoId);
                if (index > -1) {
                    insumosAgregados.splice(index, 1);
                }

                row.remove();

                const insumo = insumosDisponibles.find(i => i.id == insumoId);
                if (insumo) {
                    const option = new Option(insumo.descripcion, insumo.id);
                    option.dataset.unidad = insumo.unidad;
                    option.dataset.cantidad = insumo.cantidad;
                    $('#insumo_select').append(option);
                }

                updateItemCount();

                if (insumosAgregados.length === 0) {
                    emptyRow.style.display = '';
                    guardarBtn.disabled = true;
                }
            }
        }
    });

    function updateItemCount() {
        totalItems.textContent = `${insumosAgregados.length} items`;
    }

    // Validación del formulario
    document.getElementById('insumoUtilizadoForm').addEventListener('submit', function(e) {
        if (insumosAgregados.length === 0) {
            e.preventDefault();
            alert('Debe agregar al menos un insumo.');
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

        guardarBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
        guardarBtn.disabled = true;
    });
});
</script>
