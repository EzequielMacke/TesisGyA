<!-- filepath: c:\laragon\www\TesisGyA\resources\views\orden_compra\create.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Orden de Compra</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content fade-in">
        <div class="content-wrapper">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>
                        <i class="fas fa-file-contract me-2 text-primary"></i>
                        Nueva Orden de Compra
                    </h2>
                    <p class="text-muted mb-0">Crear orden de compra desde presupuesto aprobado</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('orden_compra.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('orden_compra.store') }}" method="POST" id="ordenForm">
                @csrf

                <!-- Selector de Presupuesto -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-search me-2"></i>Seleccionar Presupuesto Aprobado
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="presupuesto_select" class="form-label">Presupuesto Aprobado *</label>
                            <select class="form-control" id="presupuesto_select" name="presupuesto_compra_aprobado_id" required>
                                <option value="">Seleccione un presupuesto aprobado...</option>
                                @foreach($presupuestosAprobados as $presupuesto)
                                    <option value="{{ $presupuesto->id }}"
                                            data-proveedor="{{ $presupuesto->proveedor->razon_social ?? 'N/A' }}"
                                            data-ruc="{{ $presupuesto->proveedor->ruc ?? 'N/A' }}"
                                            data-fecha="{{ $presupuesto->fecha_emision }}"
                                            data-sucursal="{{ $presupuesto->pedidoCompra->sucursal->nombre ?? 'N/A' }}"
                                            data-detalles="{{ $presupuesto->detalles->count() }}">
                                        {{ $presupuesto->nombre }} - {{ $presupuesto->proveedor->razon_social ?? 'N/A' }}
                                        ({{ $presupuesto->detalles->count() }} items)
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Solo se muestran presupuestos aprobados pendientes</small>
                        </div>
                    </div>
                </div>

                <!-- Información del Presupuesto -->
                <div class="card mb-4" id="presupuesto_info" style="display: none;">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Información del Presupuesto
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label text-muted">Proveedor:</label>
                                <div class="fw-bold" id="info_proveedor">-</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted">RUC:</label>
                                <div class="fw-bold" id="info_ruc">-</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted">Fecha Emisión:</label>
                                <div class="fw-bold" id="info_fecha">-</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted">Sucursal:</label>
                                <div class="fw-bold" id="info_sucursal">-</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Datos de la Orden -->
                <div class="card mb-4" id="orden_datos" style="display: none;">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2"></i>Datos de la Orden de Compra
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="fecha" class="form-label">Fecha de Orden *</label>
                                    <input type="date" class="form-control" id="fecha" name="fecha" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="condicion_pago_id" class="form-label">Condición de Pago *</label>
                                    <select class="form-select" id="condicion_pago_id" name="condicion_pago_id" required>
                                        <option value="">Seleccione...</option>
                                        @foreach($condicionesPago as $condicion)
                                            <option value="{{ $condicion->id }}">{{ $condicion->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="metodo_pago_id" class="form-label">Método de Pago *</label>
                                    <select class="form-select" id="metodo_pago_id" name="metodo_pago_id" required>
                                        <option value="">Seleccione...</option>
                                        @foreach($metodosPago as $metodo)
                                            <option value="{{ $metodo->id }}">{{ $metodo->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="monto" class="form-label">Monto Total *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₲</span>
                                        <input type="text" class="form-control" id="monto_display" readonly>
                                        <input type="hidden" id="monto" name="monto" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="cuota" class="form-label">Número de Cuotas</label>
                                    <input type="number" class="form-control" id="cuota" name="cuota" min="1" value="1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="intervalo" class="form-label">Intervalo (días)</label>
                                    <input type="number" class="form-control" id="intervalo" name="intervalo" min="1" value="30">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="observacion" class="form-label">Observación</label>
                                    <textarea class="form-control" id="observacion" name="observacion" rows="3" maxlength="500"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalle de la Orden -->
                <div class="card mb-4" id="orden_detalle" style="display: none;">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Detalle de la Orden
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Insumo</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-center">Precio Unit.</th>
                                        <th class="text-center">Impuesto</th>
                                        <th class="text-center">Subtotal</th>
                                        <th class="text-center">Total Item</th>
                                        <th>Observación</th>
                                    </tr>
                                </thead>
                                <tbody id="detalle_tbody">
                                    <!-- Se cargarán dinámicamente -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Resumen financiero -->
                        <div class="row mt-4">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal:</span>
                                            <strong id="subtotal_general">₲ 0</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Impuestos:</span>
                                            <strong id="impuestos_general">₲ 0</strong>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <span class="h5">TOTAL:</span>
                                            <strong class="h5 text-success" id="total_general">₲ 0</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="text-center" id="botones_accion" style="display: none;">
                    <button type="submit" class="btn btn-success btn-lg me-3">
                        <i class="fas fa-save me-2"></i>Crear Orden de Compra
                    </button>
                    <a href="{{ route('orden_compra.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    @include('partials.footer')
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
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.table th {
    font-size: 0.875rem;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

.observacion-item {
    background-color: #f8f9fa;
    border-left: 4px solid #007bff;
    padding: 8px 12px;
    border-radius: 4px;
    font-style: italic;
    font-size: 0.85rem;
    margin-top: 5px;
}

.readonly-field {
    background-color: #f8f9fa !important;
    border-color: #dee2e6 !important;
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 50px;
        width: calc(100vw - 50px);
    }

    .content-wrapper {
        padding: 15px;
    }
}

.sidebar-nav:hover ~ .main-content {
    margin-left: 280px;
    width: calc(100vw - 280px);
}
</style>

<script>
let presupuestoData = null;

$(document).ready(function() {
    // Inicializar Select2
    $('#presupuesto_select').select2({
        placeholder: 'Seleccione un presupuesto aprobado...',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap-5'
    });

    // Evento change del select
    $('#presupuesto_select').on('change', function() {
        const presupuestoId = $(this).val();

        if (presupuestoId) {
            mostrarInformacionPresupuesto();
            cargarDetallePresupuesto(presupuestoId);
        } else {
            ocultarSecciones();
        }
    });
});

function mostrarInformacionPresupuesto() {
    const selectedOption = $('#presupuesto_select option:selected');

    $('#info_proveedor').text(selectedOption.data('proveedor') || 'N/A');
    $('#info_ruc').text(selectedOption.data('ruc') || 'N/A');

    const fecha = selectedOption.data('fecha');
    $('#info_fecha').text(fecha ? new Date(fecha).toLocaleDateString('es-PY') : 'N/A');

    $('#info_sucursal').text(selectedOption.data('sucursal') || 'N/A');

    $('#presupuesto_info').show();
    $('#orden_datos').show();
}

function cargarDetallePresupuesto(presupuestoId) {
    $.ajax({
        url: `/orden-compra/presupuesto-detalle/${presupuestoId}`,
        method: 'GET',
        success: function(response) {
            if (response.success && response.detalles) {
                presupuestoData = response.detalles;
                mostrarDetalleOrden(response.detalles);
                calcularTotales();
            } else {
                alert('Error al cargar el detalle del presupuesto');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('Error al cargar el detalle del presupuesto');
        }
    });
}

function mostrarDetalleOrden(detalles) {
    let html = '';
    let index = 0;

    detalles.forEach(detalle => {
        const cantidad = parseFloat(detalle.cantidad);
        const precioUnitario = Math.round(parseFloat(detalle.precio_unitario));
        const subtotal = cantidad * precioUnitario;

        // Calcular impuesto
        let impuesto = 0;
        if (detalle.impuesto_id !== 1 && detalle.impuesto && detalle.impuesto.calculo) {
            impuesto = Math.round(subtotal / parseFloat(detalle.impuesto.calculo));
        }

        const totalItem = subtotal + impuesto;

        html += `
            <tr>
                <td>
                    <strong>${detalle.insumo?.descripcion || 'N/A'}</strong><br>
                    <small class="text-muted">
                        <span class="badge bg-primary me-1">${detalle.insumo?.marca?.descripcion || 'N/A'}</span>
                        <span class="badge bg-secondary">${detalle.insumo?.unidad_medida?.abreviatura || 'N/A'}</span>
                    </small>
                    ${detalle.observacion ? `<div class="observacion-item mt-2"><i class="fas fa-comment me-1"></i>${detalle.observacion}</div>` : ''}

                    <!-- Campos ocultos -->
                    <input type="hidden" name="detalles[${index}][insumo_id]" value="${detalle.insumo_id}">
                    <input type="hidden" name="detalles[${index}][impuesto_id]" value="${detalle.impuesto_id}">
                </td>
                <td class="text-center">
                    <input type="number" class="form-control text-center readonly-field"
                           name="detalles[${index}][cantidad]"
                           value="${cantidad}"
                           step="0.01" readonly>
                </td>
                <td class="text-center">
                    <div class="fw-bold">₲ ${precioUnitario.toLocaleString('es-PY')}</div>
                    <input type="hidden" name="detalles[${index}][precio_unitario]" value="${precioUnitario}">
                </td>
                <td class="text-center">
                    <span class="badge bg-secondary">${detalle.impuesto?.descripcion || 'N/A'}</span>
                    ${impuesto > 0 ? `<br><small class="text-muted">₲ ${impuesto.toLocaleString('es-PY')}</small>` : ''}
                </td>
                <td class="text-center">
                    <strong>₲ ${subtotal.toLocaleString('es-PY')}</strong>
                </td>
                <td class="text-center">
                    <strong class="text-success">₲ ${totalItem.toLocaleString('es-PY')}</strong>
                </td>
                <td>
                    <textarea class="form-control" name="detalles[${index}][observacion]"
                              rows="2" maxlength="300" placeholder="Observaciones adicionales...">${detalle.observacion_orden || ''}</textarea>
                </td>
            </tr>
        `;
        index++;
    });

    $('#detalle_tbody').html(html);
    $('#orden_detalle').show();
    $('#botones_accion').show();
}

function calcularTotales() {
    let subtotalGeneral = 0;
    let impuestosGeneral = 0;

    if (presupuestoData) {
        presupuestoData.forEach(detalle => {
            const cantidad = parseFloat(detalle.cantidad);
            const precio = Math.round(parseFloat(detalle.precio_unitario));
            const subtotal = cantidad * precio;

            let impuesto = 0;
            if (detalle.impuesto_id !== 1 && detalle.impuesto && detalle.impuesto.calculo) {
                impuesto = Math.round(subtotal / parseFloat(detalle.impuesto.calculo));
            }

            subtotalGeneral += subtotal;
            impuestosGeneral += impuesto;
        });
    }

    const totalGeneral = subtotalGeneral + impuestosGeneral;

    // Actualizar vista
    $('#subtotal_general').text('₲ ' + subtotalGeneral.toLocaleString('es-PY'));
    $('#impuestos_general').text('₲ ' + impuestosGeneral.toLocaleString('es-PY'));
    $('#total_general').text('₲ ' + totalGeneral.toLocaleString('es-PY'));

    // Actualizar campo monto
    $('#monto_display').val(totalGeneral.toLocaleString('es-PY'));
    $('#monto').val(totalGeneral);
}

function ocultarSecciones() {
    $('#presupuesto_info').hide();
    $('#orden_datos').hide();
    $('#orden_detalle').hide();
    $('#botones_accion').hide();
}

// Validación del formulario
$('#ordenForm').on('submit', function(e) {
    const presupuestoId = $('#presupuesto_select').val();
    if (!presupuestoId) {
        e.preventDefault();
        alert('Debe seleccionar un presupuesto aprobado');
        return false;
    }

    const monto = parseFloat($('#monto').val());
    if (monto <= 0) {
        e.preventDefault();
        alert('El monto total debe ser mayor a 0');
        return false;
    }

    // Confirmar creación
    if (!confirm('¿Está seguro de crear esta orden de compra?\n\nEsta acción cambiará el estado del presupuesto aprobado.')) {
        e.preventDefault();
        return false;
    }

    return true;
});
</script>
