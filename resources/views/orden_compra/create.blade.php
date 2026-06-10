<!-- filepath: c:\laragon\www\TesisGyA\resources\views\orden_compra\create.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Orden de Compra - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-file-contract"></i> Nueva Orden de Compra</h2>
                    <small>Crear orden de compra desde presupuesto aprobado</small>
                </div>
                <a href="{{ route('orden_compra.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>

            {{-- Alerts --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('orden_compra.store') }}" method="POST" id="ordenForm">
                @csrf

                {{-- Selector de Presupuesto --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-search me-2"></i>Seleccionar Presupuesto Aprobado</span>
                    </div>
                    <div class="card-body">
                        <label for="presupuesto_select" class="form-label">Presupuesto Aprobado *</label>
                        <select class="form-select form-select-sm" id="presupuesto_select" name="presupuesto_compra_aprobado_id" required>
                            <option value="">Seleccione un presupuesto aprobado...</option>
                            @foreach($presupuestosAprobados as $presupuesto)
                                <option value="{{ $presupuesto->id }}"
                                        data-proveedor="{{ $presupuesto->proveedor->razon_social ?? 'N/A' }}"
                                        data-ruc="{{ $presupuesto->proveedor->ruc ?? 'N/A' }}"
                                        data-fecha="{{ $presupuesto->fecha_emision }}"
                                        data-sucursal="{{ $presupuesto->pedidoCompra->sucursal->descripcion ?? 'N/A' }}"
                                        data-detalles="{{ $presupuesto->detalles->count() }}">
                                    {{ $presupuesto->nombre }} - {{ $presupuesto->proveedor->razon_social ?? 'N/A' }}
                                    ({{ $presupuesto->detalles->count() }} items)
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Solo se muestran presupuestos aprobados pendientes</small>
                    </div>
                </div>

                {{-- Información del Presupuesto --}}
                <div class="card" id="presupuesto_info" style="display: none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-info-circle me-2"></i>Información del Presupuesto</span>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <label class="form-label">Proveedor</label>
                                <div class="info-value"><i class="fas fa-building"></i><span id="info_proveedor">-</span></div>
                            </div>
                            <div class="info-item">
                                <label class="form-label">RUC</label>
                                <div class="info-value"><i class="fas fa-id-card"></i><span id="info_ruc">-</span></div>
                            </div>
                            <div class="info-item">
                                <label class="form-label">Fecha Emisión</label>
                                <div class="info-value"><i class="fas fa-calendar-plus"></i><span id="info_fecha">-</span></div>
                            </div>
                            <div class="info-item">
                                <label class="form-label">Sucursal</label>
                                <div class="info-value"><i class="fas fa-building"></i><span id="info_sucursal">-</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Datos de la Orden --}}
                <div class="card" id="orden_datos" style="display: none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-edit me-2"></i>Datos de la Orden de Compra</span>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div>
                                <label for="fecha" class="form-label">Fecha de Orden *</label>
                                <input type="date" class="form-control form-control-sm" id="fecha" name="fecha" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div>
                                <label for="condicion_pago_id" class="form-label">Condición de Pago *</label>
                                <select class="form-select form-select-sm" id="condicion_pago_id" name="condicion_pago_id" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($condicionesPago as $condicion)
                                        <option value="{{ $condicion->id }}">{{ $condicion->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="metodo_pago_id" class="form-label">Método de Pago *</label>
                                <select class="form-select form-select-sm" id="metodo_pago_id" name="metodo_pago_id" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($metodosPago as $metodo)
                                        <option value="{{ $metodo->id }}">{{ $metodo->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="monto_display" class="form-label">Monto Total *</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">₲</span>
                                    <input type="text" class="form-control readonly-field" id="monto_display" readonly>
                                    <input type="hidden" id="monto" name="monto" required>
                                </div>
                            </div>

                            <div id="cuota_field">
                                <label for="cuota" class="form-label">Número de Cuotas</label>
                                <input type="number" class="form-control form-control-sm" id="cuota" name="cuota" min="1" value="1">
                                <small class="text-muted">Mínimo 1 cuota</small>
                            </div>
                            <div id="intervalo_field">
                                <label for="intervalo" class="form-label">Intervalo (días)</label>
                                <input type="number" class="form-control form-control-sm" id="intervalo" name="intervalo" min="1" value="30">
                                <small class="text-muted">Días entre cuotas</small>
                            </div>
                            <div class="span-2">
                                <label for="observacion" class="form-label">Observación</label>
                                <textarea class="form-control form-control-sm" id="observacion" name="observacion" rows="2" maxlength="500" placeholder="Observaciones adicionales sobre la orden..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Detalle de la Orden --}}
                <div class="card table-card" id="orden_detalle" style="display: none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-list me-2"></i>Detalle de la Orden</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Insumo</th>
                                        <th style="width:100px;" class="text-center">Cantidad</th>
                                        <th style="width:120px;" class="text-end">Precio Unit.</th>
                                        <th style="width:110px;" class="text-center">Impuesto</th>
                                        <th style="width:120px;" class="text-end">Subtotal</th>
                                        <th style="width:130px;" class="text-end">Total Item</th>
                                        <th style="width:200px;">Observación</th>
                                    </tr>
                                </thead>
                                <tbody id="detalle_tbody">
                                </tbody>
                            </table>
                        </div>

                        {{-- Resumen financiero --}}
                        <div class="totals-box">
                            <div class="totals-row">
                                <span>Subtotal</span>
                                <strong id="subtotal_general">₲ 0</strong>
                            </div>
                            <div class="totals-row">
                                <span>Impuestos</span>
                                <strong id="impuestos_general">₲ 0</strong>
                            </div>
                            <div class="totals-row totals-final">
                                <span>Total</span>
                                <strong id="total_general">₲ 0</strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="card" id="botones_accion" style="display: none;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <a href="{{ route('orden_compra.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Crear Orden de Compra
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
#ordenForm {
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

.select2-container--bootstrap-5 .select2-selection {
    min-height: calc(1.5em + 0.5rem + 2px);
}

/* ── Información del presupuesto ── */
.info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
}
.info-item .form-label,
.form-grid .form-label {
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

/* ── Datos de la orden ── */
.form-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
}
.form-grid .span-2 { grid-column: span 2; }
.readonly-field {
    background-color: #f8fafc !important;
    border-color: #e2e8f0 !important;
    color: #374151;
}

@media (max-width: 900px) {
    .info-grid { grid-template-columns: repeat(2, 1fr); }
    .form-grid { grid-template-columns: repeat(2, 1fr); }
    .form-grid .span-2 { grid-column: span 2; }
    .page-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .info-grid { grid-template-columns: 1fr; }
    .form-grid { grid-template-columns: 1fr; }
    .form-grid .span-2 { grid-column: span 1; }
}

/* ── Tabla de detalle ── */
.table-card { display: flex; flex-direction: column; }
.table-container { overflow: auto; }

.data-table {
    width: 100%;
    min-width: 980px;
    border-collapse: collapse;
    table-layout: fixed;
}
.data-table thead th {
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
.data-table tbody td {
    padding: 0.6rem 0.65rem;
    font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
.data-table tbody tr:hover { background: #f8fafc; }
.data-table tbody tr:last-child td { border-bottom: none; }

.empty-cell {
    text-align: center;
    color: #94a3b8;
    padding: 2.5rem 1rem;
}
.empty-cell i { color: #cbd5e1; }

.quantity-input { width: 90px; text-align: center; }
.observation-input { min-width: 180px; font-size: 0.82rem; }

/* Tags */
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
.amount { font-weight: 700; color: #10b981; }

/* ── Totales ── */
.totals-box {
    margin: 1rem;
    margin-left: auto;
    max-width: 320px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
}
.totals-row { display: flex; justify-content: space-between; font-size: 0.85rem; color: #374151; padding: 0.25rem 0; }
.totals-row.totals-final {
    border-top: 1px solid #e2e8f0;
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
}
.totals-final strong { color: #10b981; }

@media (max-width: 768px) {
    .table-container { font-size: 0.875rem; }
    .totals-box { max-width: 100%; margin: 1rem; }
    .quantity-input { width: 80px; }
    .observation-input { min-width: 140px; }
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
    $('#detalle_tbody').html('<tr><td colspan="7" class="empty-cell"><i class="fas fa-spinner fa-spin fa-2x mb-2"></i><br>Cargando detalle...</td></tr>');
    $('#orden_detalle').show();

    $.ajax({
        url: `/orden-compra/presupuesto-detalle/${presupuestoId}`,
        method: 'GET',
        success: function(response) {
            if (response.success && response.detalles) {
                presupuestoData = response.detalles;
                mostrarDetalleOrden(response.detalles);
                calcularTotales();
            } else {
                $('#detalle_tbody').html('<tr><td colspan="7" class="empty-cell">Error al cargar el detalle del presupuesto</td></tr>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            $('#detalle_tbody').html('<tr><td colspan="7" class="empty-cell text-danger">Error al cargar el detalle del presupuesto</td></tr>');
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
                    <span class="tag me-1">${detalle.insumo?.marca?.descripcion || 'N/A'}</span>
                    <span class="tag tag-secondary">${detalle.insumo?.unidad_medida?.abreviatura || 'N/A'}</span>
                    ${detalle.observacion ? `<div class="text-muted mt-1" style="font-size:0.78rem;"><i class="fas fa-comment me-1"></i>${detalle.observacion}</div>` : ''}

                    <input type="hidden" name="detalles[${index}][insumo_id]" value="${detalle.insumo_id}">
                    <input type="hidden" name="detalles[${index}][impuesto_id]" value="${detalle.impuesto_id}">
                </td>
                <td class="text-center">
                    <input type="number" class="form-control form-control-sm text-center readonly-field quantity-input"
                           name="detalles[${index}][cantidad]"
                           value="${cantidad}"
                           step="0.01" readonly>
                </td>
                <td class="text-end">
                    ₲ ${precioUnitario.toLocaleString('es-PY')}
                    <input type="hidden" name="detalles[${index}][precio_unitario]" value="${precioUnitario}">
                </td>
                <td class="text-center">
                    <span class="tag tag-secondary">${detalle.impuesto?.descripcion || 'N/A'}</span>
                    ${impuesto > 0 ? `<br><small class="text-muted">₲ ${impuesto.toLocaleString('es-PY')}</small>` : ''}
                </td>
                <td class="text-end">
                    <strong>₲ ${subtotal.toLocaleString('es-PY')}</strong>
                </td>
                <td class="text-end">
                    <span class="amount">₲ ${totalItem.toLocaleString('es-PY')}</span>
                </td>
                <td>
                    <textarea class="form-control form-control-sm observation-input" name="detalles[${index}][observacion]"
                              rows="1" maxlength="300" placeholder="Observaciones adicionales...">${detalle.observacion_orden || ''}</textarea>
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

$('#condicion_pago_id').on('change', function() {
    const condicionPagoId = $(this).val();

    if (condicionPagoId === '1') { // Si es contado
        // Deshabilitar y limpiar campos
        $('#cuota').prop('disabled', true).val(1);
        $('#intervalo').prop('disabled', true).val('');

        // Agregar estilo visual de deshabilitado
        $('#cuota').addClass('readonly-field');
        $('#intervalo').addClass('readonly-field');

        // Agregar texto informativo
        if (!$('#info_contado').length) {
            $('#cuota_field').append('<small id="info_contado" class="text-muted d-block">Pago al contado - No aplica cuotas</small>');
        }
    } else {
        // Habilitar campos
        $('#cuota').prop('disabled', false).val(1);
        $('#intervalo').prop('disabled', false).val(30);

        // Quitar estilo visual
        $('#cuota').removeClass('readonly-field');
        $('#intervalo').removeClass('readonly-field');

        // Quitar texto informativo
        $('#info_contado').remove();
    }
});

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

    // Validación adicional para condición de pago
    const condicionPagoId = $('#condicion_pago_id').val();
    if (condicionPagoId === '1') { // Si es contado
        // Forzar valores para contado
        $('#cuota').val(1);
        $('#intervalo').val('');
    } else {
        // Validar que se hayan ingresado cuotas e intervalo
        const cuotas = parseInt($('#cuota').val());
        const intervalo = parseInt($('#intervalo').val());

        if (cuotas <= 0) {
            e.preventDefault();
            alert('Debe ingresar un número válido de cuotas');
            return false;
        }

        if (intervalo <= 0) {
            e.preventDefault();
            alert('Debe ingresar un intervalo válido en días');
            return false;
        }
    }

    // Confirmar creación
    if (!confirm('¿Está seguro de crear esta orden de compra?\n\nEsta acción cambiará el estado del presupuesto aprobado.')) {
        e.preventDefault();
        return false;
    }

    return true;
});
</script>
