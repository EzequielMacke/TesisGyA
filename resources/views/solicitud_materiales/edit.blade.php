<!-- filepath: c:\laragon\www\TesisGyA\resources\views\solicitud_materiales\edit.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Solicitud de Insumos - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    @php
        $destinoTipoActual = old('destino_tipo', $solicitud->obra_id ? 'obra' : 'deposito');
        $clienteIdActual = old('cliente_id', $solicitud->obra->cliente_id ?? '');
        $detallesIniciales = $solicitud->detalles->map(function ($detalle) {
            return [
                'insumo_id' => $detalle->insumo_id,
                'descripcion' => $detalle->insumo->descripcion ?? '',
                'marca' => $detalle->insumo->marca->descripcion ?? '-',
                'unidad' => $detalle->insumo->unidadMedida->descripcion ?? '-',
                'cantidad' => (float) $detalle->cantidad_solicitada,
                'observacion' => $detalle->observacion ?? '',
            ];
        })->values();
    @endphp

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-edit"></i> Editar Solicitud de Insumos</h2>
                    <small>Modifique los datos de la solicitud #{{ str_pad($solicitud->id, 3, '0', STR_PAD_LEFT) }}</small>
                </div>
                <a href="{{ route('solicitud_materiales.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                </a>
            </div>

            {{-- Alerts --}}
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

            <form method="POST" action="{{ route('solicitud_materiales.update', $solicitud->id) }}" id="solicitudForm">
                @csrf
                @method('PUT')

                {{-- Datos de la Solicitud --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-info-circle me-2"></i>Datos de la Solicitud</span>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div>
                                <label class="form-label">Solicitante</label>
                                <div class="info-value"><i class="fas fa-user"></i>{{ session('user_usuario') }}</div>
                            </div>
                            <div>
                                <label for="fecha" class="form-label">Fecha *</label>
                                <input type="date" name="fecha" id="fecha" class="form-control form-control-sm" value="{{ old('fecha', $solicitud->fecha->format('Y-m-d')) }}">
                            </div>
                            <div>
                                <label class="form-label">Destino *</label>
                                <div class="destino-toggle">
                                    <label class="destino-check" for="destino_obra">
                                        <input class="form-check-input" type="radio" name="destino_tipo" value="obra" id="destino_obra" {{ $destinoTipoActual == 'obra' ? 'checked' : '' }}>
                                        <span><i class="fas fa-building me-1 text-muted"></i>Obra</span>
                                    </label>
                                    <label class="destino-check" for="destino_deposito">
                                        <input class="form-check-input" type="radio" name="destino_tipo" value="deposito" id="destino_deposito" {{ $destinoTipoActual == 'deposito' ? 'checked' : '' }}>
                                        <span><i class="fas fa-warehouse me-1 text-muted"></i>Depósito</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Destino: Obra --}}
                        <div class="form-grid mt-3" id="seccion-obra" style="display:none;">
                            <div>
                                <label for="cliente_id" class="form-label">Cliente *</label>
                                <select name="cliente_id" id="cliente_id" class="form-select form-select-sm select2">
                                    <option value="">Seleccione un cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ $clienteIdActual == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->razon_social }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="obra_id" class="form-label">Obra *</label>
                                <select name="obra_id" id="obra_id" class="form-select form-select-sm select2" disabled>
                                    <option value="">Seleccione una obra</option>
                                </select>
                            </div>
                        </div>

                        {{-- Destino: Depósito --}}
                        <div class="form-grid mt-3" id="seccion-deposito" style="display:none;">
                            <div>
                                <label for="deposito_id" class="form-label">Depósito *</label>
                                <select name="deposito_id" id="deposito_id" class="form-select form-select-sm select2" disabled>
                                    <option value="">Seleccione un depósito</option>
                                    @foreach($depositos as $deposito)
                                        <option value="{{ $deposito->id }}" {{ old('deposito_id', $solicitud->deposito_id) == $deposito->id ? 'selected' : '' }}>
                                            {{ $deposito->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="observacion" class="form-label">Observación General</label>
                            <textarea name="observacion" id="observacion" class="form-control form-control-sm" rows="2" placeholder="Ingrese una observación general...">{{ old('observacion', $solicitud->observacion) }}</textarea>
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
                                <label for="marca_filter" class="form-label">Marca</label>
                                <select class="form-select form-select-sm select2" id="marca_filter">
                                    <option value="">Seleccione una marca</option>
                                    @foreach($marcas as $marca)
                                        <option value="{{ $marca->id }}">{{ $marca->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="insumo_select" class="form-label">Insumo</label>
                                <select class="form-select form-select-sm select2" id="insumo_select" disabled>
                                    <option value="">Primero seleccione una marca</option>
                                </select>
                            </div>
                            <div>
                                <label for="unidad_display" class="form-label">Unidad de Medida</label>
                                <input type="text" id="unidad_display" class="form-control form-control-sm" readonly placeholder="—">
                            </div>
                            <div>
                                <label for="cantidad_input" class="form-label">Cantidad</label>
                                <input type="number" id="cantidad_input" class="form-control form-control-sm" min="0.01" step="0.01" placeholder="0.00" disabled>
                            </div>
                            <div>
                                <label for="observacion_input" class="form-label">Observación</label>
                                <input type="text" id="observacion_input" class="form-control form-control-sm" placeholder="Observación del insumo..." disabled>
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
                        <span><i class="fas fa-list me-2"></i>Insumos de la Solicitud</span>
                        <span class="results-count" id="totalItems">0 item(s)</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-container">
                            <table id="insumosTable">
                                <thead>
                                    <tr>
                                        <th>Insumo</th>
                                        <th style="width:120px;">Marca</th>
                                        <th style="width:100px;">Unidad</th>
                                        <th style="width:100px;" class="text-center">Cantidad</th>
                                        <th>Observación</th>
                                        <th style="width:70px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="insumosBody">
                                    <tr id="emptyRow">
                                        <td colspan="6" class="empty-cell">
                                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                            No hay insumos agregados a la solicitud
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
                        <a href="{{ route('solicitud_materiales.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-success" id="guardarSolicitud" disabled>
                            <i class="fas fa-save me-2"></i>Actualizar Solicitud
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
#solicitudForm {
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

/* ── Datos de la solicitud ── */
.form-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
}
.form-grid .form-label,
.add-grid .form-label,
.card-body > .form-label {
    display: block;
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

.select2-container--bootstrap-5 .select2-selection {
    min-height: calc(1.5em + 0.5rem + 2px);
}

@media (max-width: 900px) {
    .form-grid { grid-template-columns: repeat(2, 1fr); }
    .page-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .form-grid { grid-template-columns: 1fr; }
}

/* ── Destino (Obra / Depósito) ── */
.destino-toggle {
    display: flex;
    gap: 0.5rem;
}
.destino-check {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    flex: 1;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.45rem 0.75rem;
    font-size: 0.85rem;
    color: #374151;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s;
    margin-bottom: 0;
}
.destino-check:hover { background: #eff6ff; border-color: #bfdbfe; }
.destino-check.checked { background: #eff6ff; border-color: #2563eb; color: #1e293b; }
.destino-check input { margin: 0; cursor: pointer; }

/* ── Agregar insumos ── */
.add-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 0.75rem;
    align-items: end;
}
@media (max-width: 1200px) {
    .add-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 900px) {
    .add-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) {
    .add-grid { grid-template-columns: 1fr; }
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
    .table-container { font-size: 0.875rem; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('.select2').select2({ theme: 'bootstrap-5', width: '100%' });

    const insumosData = @json($insumos);
    const detallesIniciales = @json($detallesIniciales);
    const initialObraId = '{{ old('obra_id', $solicitud->obra_id ?? '') }}';
    const obraDescripcionInicial = @json($solicitud->obra->descripcion ?? '');

    const agregarBtn = document.getElementById('agregarInsumo');
    const insumosBody = document.getElementById('insumosBody');
    const emptyRow = document.getElementById('emptyRow');
    const totalItems = document.getElementById('totalItems');
    const guardarBtn = document.getElementById('guardarSolicitud');
    const cantidadInput = document.getElementById('cantidad_input');
    const observacionInput = document.getElementById('observacion_input');
    const unidadDisplay = document.getElementById('unidad_display');

    let insumosAgregados = [];
    let insumoCounter = 0;

    function escapeHtml(str) {
        return String(str).replace(/[&<>"']/g, function (s) {
            return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[s];
        });
    }

    // ── Toggle Destino (Obra / Depósito) ──
    function toggleDestino() {
        const tipo = $('input[name="destino_tipo"]:checked').val();
        const seccionObra = document.getElementById('seccion-obra');
        const seccionDeposito = document.getElementById('seccion-deposito');

        if (tipo === 'obra') {
            seccionObra.style.display = '';
            seccionDeposito.style.display = 'none';
            $('#cliente_id').prop('disabled', false);
            $('#deposito_id').prop('disabled', true).val('').trigger('change');
        } else if (tipo === 'deposito') {
            seccionObra.style.display = 'none';
            seccionDeposito.style.display = '';
            $('#deposito_id').prop('disabled', false);
            $('#cliente_id').prop('disabled', true).val('').trigger('change');
            $('#obra_id').prop('disabled', true).empty().append('<option value="">Seleccione una obra</option>').trigger('change');
        }

        document.querySelectorAll('.destino-check').forEach(function (label) {
            label.classList.toggle('checked', label.querySelector('input').checked);
        });
    }
    $('input[name="destino_tipo"]').on('change', toggleDestino);

    // ── Cliente -> Obras (AJAX) ──
    $('#cliente_id').on('change', function() {
        var clienteId = $(this).val();
        $('#obra_id').prop('disabled', true).empty().append('<option value="">Seleccione una obra</option>').trigger('change');
        if (clienteId) {
            $.getJSON('{{ url("api/obras") }}/' + clienteId, function(obras) {
                if (obras.length > 0) {
                    $('#obra_id').append(
                        obras.map(o => `<option value="${o.id}">${escapeHtml(o.descripcion)}</option>`)
                    );
                    $('#obra_id').prop('disabled', false);
                }
                if (initialObraId) {
                    if ($(`#obra_id option[value="${initialObraId}"]`).length === 0 && obraDescripcionInicial) {
                        $('#obra_id').append(new Option(obraDescripcionInicial, initialObraId));
                    }
                    $('#obra_id').prop('disabled', false).val(initialObraId).trigger('change');
                } else {
                    $('#obra_id').trigger('change');
                }
            });
        }
    });

    // ── Marca -> Insumos disponibles ──
    $('#marca_filter').on('select2:select select2:clear change', function() {
        const marcaId = $(this).val();

        $('#insumo_select').empty().append('<option value="">Seleccione un insumo</option>');
        resetInsumoInputs();

        if (marcaId) {
            const insumosFiltrados = insumosData.filter(insumo =>
                insumo.marca_id == marcaId && !insumosAgregados.includes(insumo.id)
            );

            insumosFiltrados.forEach(insumo => {
                const option = new Option(insumo.descripcion, insumo.id);
                option.dataset.unidad = insumo.unidad_medida ? insumo.unidad_medida.descripcion : '';
                option.dataset.marca = insumo.marca ? insumo.marca.descripcion : '';
                $('#insumo_select').append(option);
            });

            $('#insumo_select').prop('disabled', false);
        } else {
            $('#insumo_select').prop('disabled', true);
        }

        $('#insumo_select').trigger('change');
    });

    // ── Insumo seleccionado: mostrar unidad y habilitar cantidad/observación ──
    $('#insumo_select').on('select2:select select2:clear change', function() {
        const selected = $(this).val();
        const selectedOption = $(this).find('option:selected')[0];

        if (selected) {
            unidadDisplay.value = (selectedOption && selectedOption.dataset.unidad) || '';
            cantidadInput.disabled = false;
            observacionInput.disabled = false;
            cantidadInput.value = '';
            observacionInput.value = '';
            cantidadInput.focus();
        } else {
            resetInsumoInputs();
        }
        validarAgregar();
    });

    cantidadInput.addEventListener('input', validarAgregar);

    function validarAgregar() {
        const insumoId = $('#insumo_select').val();
        const cantidad = parseFloat(cantidadInput.value);
        agregarBtn.disabled = !insumoId || isNaN(cantidad) || cantidad <= 0;
    }

    function resetInsumoInputs() {
        unidadDisplay.value = '';
        cantidadInput.value = '';
        observacionInput.value = '';
        cantidadInput.disabled = true;
        observacionInput.disabled = true;
        agregarBtn.disabled = true;
    }

    // ── Agregar una fila a la tabla de insumos ──
    function agregarFila(insumoId, insumoNombre, marca, unidad, cantidad, observacion) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <i class="fas fa-cube text-muted me-2"></i><strong>${escapeHtml(insumoNombre)}</strong>
                <input type="hidden" name="insumos[${insumoCounter}][insumo_id]" value="${insumoId}">
                <input type="hidden" name="insumos[${insumoCounter}][cantidad]" value="${cantidad}">
                <input type="hidden" name="insumos[${insumoCounter}][observacion]" value="${escapeHtml(observacion)}">
            </td>
            <td><span class="tag">${escapeHtml(marca)}</span></td>
            <td><span class="tag tag-secondary">${escapeHtml(unidad)}</span></td>
            <td class="text-center">${parseFloat(cantidad).toFixed(2)}</td>
            <td>${observacion ? escapeHtml(observacion) : '<span class="text-muted">—</span>'}</td>
            <td class="text-center">
                <button type="button" class="btn-icon remove-item" data-insumo="${insumoId}" title="Eliminar insumo">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;

        insumosBody.insertBefore(row, emptyRow);
        insumosAgregados.push(insumoId);
        insumoCounter++;
        emptyRow.style.display = 'none';
    }

    // ── Agregar insumo a la lista ──
    agregarBtn.addEventListener('click', function() {
        const insumoId = parseInt($('#insumo_select').val());
        const selectedOption = $('#insumo_select option:selected')[0];
        const cantidad = parseFloat(cantidadInput.value);

        if (!insumoId || isNaN(cantidad) || cantidad <= 0) return;

        const insumoNombre = selectedOption.textContent;
        const marca = selectedOption.dataset.marca || '-';
        const unidad = selectedOption.dataset.unidad || '-';
        const observacion = observacionInput.value.trim();

        agregarFila(insumoId, insumoNombre, marca, unidad, cantidad, observacion);

        $(`#insumo_select option[value="${insumoId}"]`).remove();
        $('#insumo_select').val('').trigger('change');
        resetInsumoInputs();

        updateItemCount();
        guardarBtn.disabled = false;
    });

    // ── Eliminar insumo de la lista ──
    insumosBody.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            const btn = e.target.closest('.remove-item');
            const insumoId = parseInt(btn.dataset.insumo);
            const row = btn.closest('tr');

            if (confirm('¿Está seguro que desea eliminar este insumo de la solicitud?')) {
                const index = insumosAgregados.indexOf(insumoId);
                if (index > -1) insumosAgregados.splice(index, 1);

                row.remove();

                const marcaId = $('#marca_filter').val();
                if (marcaId) {
                    const insumo = insumosData.find(i => i.id == insumoId);
                    if (insumo && insumo.marca_id == marcaId) {
                        const option = new Option(insumo.descripcion, insumo.id);
                        option.dataset.unidad = insumo.unidad_medida ? insumo.unidad_medida.descripcion : '';
                        option.dataset.marca = insumo.marca ? insumo.marca.descripcion : '';
                        $('#insumo_select').append(option);
                    }
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
        totalItems.textContent = `${insumosAgregados.length} item(s)`;
    }

    // ── Validación del formulario ──
    document.getElementById('solicitudForm').addEventListener('submit', function(e) {
        const tipo = $('input[name="destino_tipo"]:checked').val();

        if (!tipo) {
            e.preventDefault();
            alert('Debe seleccionar el destino de la solicitud (Obra o Depósito).');
            return false;
        }
        if (tipo === 'obra' && !$('#obra_id').val()) {
            e.preventDefault();
            alert('Debe seleccionar una obra de destino.');
            return false;
        }
        if (tipo === 'deposito' && !$('#deposito_id').val()) {
            e.preventDefault();
            alert('Debe seleccionar un depósito de destino.');
            return false;
        }
        if (insumosAgregados.length === 0) {
            e.preventDefault();
            alert('Debe agregar al menos un insumo a la solicitud.');
            return false;
        }

        guardarBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
        guardarBtn.disabled = true;
    });

    // ── Precargar datos existentes de la solicitud ──
    toggleDestino();

    @if($clienteIdActual)
        $('#cliente_id').val('{{ $clienteIdActual }}').trigger('change');
    @endif

    detallesIniciales.forEach(function (detalle) {
        agregarFila(detalle.insumo_id, detalle.descripcion, detalle.marca, detalle.unidad, detalle.cantidad, detalle.observacion);
    });
    updateItemCount();
    if (insumosAgregados.length > 0) {
        guardarBtn.disabled = false;
    }
});
</script>
