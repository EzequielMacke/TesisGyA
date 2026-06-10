<!-- filepath: c:\laragon\www\TesisGyA\resources\views\presupuesto_compra_aprobado\create.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Aprobar Presupuesto - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-check-double"></i> Aprobar Presupuesto</h2>
                    <small>Seleccione un pedido y apruebe uno de sus presupuestos</small>
                </div>
                <a href="{{ route('presupuesto_compra_aprobado.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
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

            {{-- Selector de Pedido --}}
            <div class="card">
                <div class="card-header-section">
                    <span><i class="fas fa-search me-2"></i>Seleccionar Pedido de Compra</span>
                </div>
                <div class="card-body">
                    <label for="pedido_select" class="form-label">Pedido de Compra *</label>
                    <select class="form-select form-select-sm" id="pedido_select" name="pedido_id" required>
                        <option value="">Seleccione un pedido pendiente...</option>
                        @foreach($pedidos as $pedido)
                            <option value="{{ $pedido->id }}"
                                    data-sucursal="{{ $pedido->sucursal->descripcion ?? 'N/A' }}"
                                    data-deposito="{{ $pedido->deposito->descripcion ?? 'N/A' }}"
                                    data-fecha="{{ $pedido->fecha }}"
                                    data-usuario="{{ ($pedido->usuario->persona->nombre ?? 'N/A') . ' ' . ($pedido->usuario->persona->apellido ?? '') }}"
                                    data-observacion="{{ $pedido->observacion ?? '' }}"
                                    data-presupuestos="{{ $pedido->presupuestos_count }}">
                                Pedido #{{ $pedido->id }} - {{ $pedido->sucursal->descripcion ?? 'N/A' }}
                                ({{ $pedido->presupuestos_count }} presupuestos)
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Solo se muestran pedidos en estado "Pendiente" con presupuestos</small>
                </div>
            </div>

            {{-- Información del Pedido --}}
            <div class="card" id="pedido_info" style="display: none;">
                <div class="card-header-section">
                    <span><i class="fas fa-info-circle me-2"></i>Información del Pedido</span>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label class="form-label">Pedido</label>
                            <div class="info-value"><i class="fas fa-hashtag"></i><span id="pedido_id_display">-</span></div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Sucursal</label>
                            <div class="info-value"><i class="fas fa-building"></i><span id="pedido_sucursal_display">-</span></div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Depósito</label>
                            <div class="info-value"><i class="fas fa-warehouse"></i><span id="pedido_deposito_display">-</span></div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Fecha</label>
                            <div class="info-value"><i class="fas fa-calendar"></i><span id="pedido_fecha_display">-</span></div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Creado por</label>
                            <div class="info-value"><i class="fas fa-user"></i><span id="pedido_usuario_display">-</span></div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Presupuestos</label>
                            <div class="info-value"><i class="fas fa-file-invoice-dollar"></i><span id="pedido_presupuestos_display">0</span></div>
                        </div>
                    </div>
                    <div class="mt-3" id="pedido_observacion_row" style="display: none;">
                        <label class="form-label">Observación</label>
                        <div class="info-value observation-box" id="pedido_observacion_display">-</div>
                    </div>
                </div>
            </div>

            {{-- Detalle del Pedido --}}
            <div class="card table-card" id="pedido_detalle" style="display: none;">
                <div class="card-header-section">
                    <span><i class="fas fa-list me-2"></i>Detalle del Pedido</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Insumo</th>
                                    <th style="width:140px;" class="text-center">Cantidad Solicitada</th>
                                    <th>Observación</th>
                                </tr>
                            </thead>
                            <tbody id="pedido_detalle_tbody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Presupuestos del Pedido --}}
            <div class="card" id="presupuestos_container" style="display: none;">
                <div class="card-header-section">
                    <span><i class="fas fa-file-invoice-dollar me-2"></i>Presupuestos Disponibles</span>
                    <div class="presupuesto-nav">
                        <span class="results-count" id="presupuesto_counter">1 de 1</span>
                        <div class="btn-group">
                            <button type="button" class="btn-icon" id="btn_prev" onclick="cambiarPresupuesto(-1)">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button type="button" class="btn-icon" id="btn_next" onclick="cambiarPresupuesto(1)">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="presupuesto_viewer"></div>

                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-success btn-lg" id="btn_aprobar" onclick="aprobarPresupuesto()">
                            <i class="fas fa-check-double me-2"></i>Aprobar Este Presupuesto
                        </button>
                    </div>
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
    border-bottom: 1px solid #e2e8f0;
}
.page-header h2 { margin: 0; font-size: 1.25rem; font-weight: 600; color: #1e293b; }
.page-header h2 i { color: #94a3b8; margin-right: 0.4rem; }
.page-header small { color: #94a3b8; font-size: 0.8rem; }

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

.select2-container--bootstrap-5 .select2-selection {
    min-height: calc(1.5em + 0.5rem + 2px);
}

/* ── Información del pedido ── */
.info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
}
.info-item .form-label {
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
.observation-box {
    white-space: pre-wrap;
    line-height: 1.5;
}

@media (max-width: 900px) {
    .info-grid { grid-template-columns: repeat(2, 1fr); }
    .page-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .info-grid { grid-template-columns: 1fr; }
}

/* ── Tablas ── */
.table-card { display: flex; flex-direction: column; }
.table-container { overflow: auto; }

.data-table {
    width: 100%;
    min-width: 640px;
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
.tag-warning { background: #fef3c7; color: #b45309; }
.amount { font-weight: 700; color: #10b981; }

/* ── Navegación de presupuestos ── */
.presupuesto-nav { display: flex; align-items: center; gap: 0.75rem; }
.btn-group { display: flex; gap: 4px; }
.btn-icon {
    width: 28px; height: 28px;
    display: inline-flex; align-items: center; justify-content: center;
    border: 1px solid #e2e8f0; border-radius: 6px;
    color: #64748b; background: #fff; font-size: 0.78rem;
    cursor: pointer;
}
.btn-icon:hover { background: #f1f5f9; color: #1e293b; }
.btn-icon:disabled { opacity: 0.4; cursor: not-allowed; }
.btn-icon:disabled:hover { background: #fff; color: #64748b; }

/* ── Estados de carga / error del visor ── */
.viewer-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #94a3b8;
}
.viewer-state i { display: block; margin-bottom: 0.75rem; font-size: 2rem; }

/* ── Encabezado del presupuesto ── */
.presupuesto-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 1rem;
    padding-bottom: 1rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid #e2e8f0;
}
.presupuesto-title { margin: 0 0 0.4rem; font-size: 1.05rem; font-weight: 600; color: #1e293b; }
.presupuesto-meta { display: flex; flex-direction: column; gap: 0.2rem; font-size: 0.82rem; color: #64748b; }
.presupuesto-meta i { width: 14px; color: #94a3b8; margin-right: 0.35rem; }
.presupuesto-meta-right { display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem; }

@media (max-width: 768px) {
    .presupuesto-meta-right { align-items: flex-start; }
}

/* ── Totales ── */
.totals-box {
    margin-top: 1rem;
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
    .totals-box { max-width: 100%; }
}
</style>

<script>
let presupuestosData = [];
let currentPresupuestoIndex = 0;

$(document).ready(function() {
    $('#pedido_select').select2({
        placeholder: 'Seleccione un pedido pendiente...',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap-5'
    });

    $('#pedido_select').on('change', function() {
        const pedidoId = $(this).val();

        if (pedidoId) {
            mostrarInformacionPedido();
            cargarDetallePedido(pedidoId);
            cargarPresupuestos(pedidoId);
        } else {
            ocultarSecciones();
        }
    });
});

function mostrarInformacionPedido() {
    const selectedOption = $('#pedido_select option:selected');
    const pedidoId = $('#pedido_select').val();

    $('#pedido_id_display').text('#' + pedidoId);
    $('#pedido_sucursal_display').text(selectedOption.data('sucursal') || 'N/A');
    $('#pedido_deposito_display').text(selectedOption.data('deposito') || 'N/A');

    const fecha = selectedOption.data('fecha');
    $('#pedido_fecha_display').text(fecha ? new Date(fecha).toLocaleDateString('es-PY') : 'N/A');

    $('#pedido_usuario_display').text(selectedOption.data('usuario') || 'N/A');
    $('#pedido_presupuestos_display').text((selectedOption.data('presupuestos') || 0) + ' presupuestos');

    const observacion = selectedOption.data('observacion');
    if (observacion && observacion.trim() !== '') {
        $('#pedido_observacion_display').text(observacion);
        $('#pedido_observacion_row').show();
    } else {
        $('#pedido_observacion_row').hide();
    }

    $('#pedido_info').show();
}

function cargarDetallePedido(pedidoId) {
    $('#pedido_detalle_tbody').html('<tr><td colspan="3" class="empty-cell"><i class="fas fa-spinner fa-spin fa-2x mb-2"></i><br>Cargando detalle...</td></tr>');
    $('#pedido_detalle').show();

    $.ajax({
        url: `/pedido-compra/detalle/${pedidoId}`,
        method: 'GET',
        success: function(response) {
            if (response.success && response.detalles) {
                mostrarDetallePedido(response.detalles);
            } else {
                $('#pedido_detalle_tbody').html('<tr><td colspan="3" class="empty-cell">No se pudo cargar el detalle del pedido</td></tr>');
            }
        },
        error: function() {
            $('#pedido_detalle_tbody').html('<tr><td colspan="3" class="empty-cell text-danger">Error al cargar el detalle</td></tr>');
        }
    });
}

function mostrarDetallePedido(detalles) {
    let html = '';

    detalles.forEach(detalle => {
        html += `
            <tr>
                <td>
                    <strong>${detalle.insumo?.descripcion || 'N/A'}</strong><br>
                    <span class="tag me-1">${detalle.insumo?.marca?.descripcion || 'N/A'}</span>
                    <span class="tag tag-secondary">${detalle.insumo?.unidad_medida?.abreviatura || detalle.insumo?.unidad_medida?.descripcion || 'N/A'}</span>
                </td>
                <td class="text-center">
                    <span class="tag">${parseInt(detalle.cantidad).toLocaleString('es-PY')}</span>
                </td>
                <td>
                    ${detalle.observacion ? detalle.observacion : '<span class="text-muted">—</span>'}
                </td>
            </tr>
        `;
    });

    $('#pedido_detalle_tbody').html(html);
}

function cargarPresupuestos(pedidoId) {
    $('#presupuesto_viewer').html('<div class="viewer-state"><i class="fas fa-spinner fa-spin"></i>Cargando presupuestos...</div>');
    $('#presupuestos_container').show();

    $.ajax({
        url: `/presupuesto-compra-aprobado/presupuestos/${pedidoId}`,
        method: 'GET',
        success: function(response) {
            if (response.success && response.presupuestos) {
                presupuestosData = response.presupuestos;
                currentPresupuestoIndex = 0;

                if (presupuestosData.length > 0) {
                    mostrarPresupuesto();
                } else {
                    $('#presupuesto_viewer').html('<div class="viewer-state"><i class="fas fa-exclamation-triangle text-warning"></i>No hay presupuestos disponibles para este pedido.</div>');
                }
            } else {
                $('#presupuesto_viewer').html('<div class="viewer-state"><i class="fas fa-exclamation-triangle text-danger"></i>Error: ' + (response.message || 'No se pudieron cargar los presupuestos') + '</div>');
            }
        },
        error: function(xhr, status, error) {
            $('#presupuesto_viewer').html('<div class="viewer-state"><i class="fas fa-exclamation-circle text-danger"></i>Error al cargar los presupuestos: ' + error + '</div>');
        }
    });
}

function mostrarPresupuesto() {
    if (presupuestosData.length === 0) return;

    const presupuesto = presupuestosData[currentPresupuestoIndex];
    const total = presupuestosData.length;

    $('#presupuesto_counter').text(`${currentPresupuestoIndex + 1} de ${total}`);
    $('#btn_prev').prop('disabled', currentPresupuestoIndex === 0);
    $('#btn_next').prop('disabled', currentPresupuestoIndex === total - 1);

    let html = `
        <div class="presupuesto-header">
            <div>
                <h5 class="presupuesto-title">${presupuesto.nombre || 'Sin nombre'}</h5>
                <div class="presupuesto-meta">
                    <span><i class="fas fa-building"></i>${presupuesto.proveedor?.razon_social || 'N/A'}</span>
                    <span><i class="fas fa-id-card"></i>RUC: ${presupuesto.proveedor?.ruc || 'N/A'}</span>
                </div>
            </div>
            <div class="presupuesto-meta-right">
                <span class="tag tag-warning">${presupuesto.estado?.descripcion || 'Sin estado'}</span>
                <div class="presupuesto-meta">
                    <span><i class="fas fa-calendar-plus"></i>Emisión: ${presupuesto.fecha_emision ? new Date(presupuesto.fecha_emision).toLocaleDateString('es-PY') : 'N/A'}</span>
                    <span><i class="fas fa-calendar-times"></i>Vencimiento: ${presupuesto.fecha_vencimiento ? new Date(presupuesto.fecha_vencimiento).toLocaleDateString('es-PY') : 'N/A'}</span>
                </div>
            </div>
        </div>
    `;

    if (presupuesto.descripcion) {
        html += `<div class="info-value observation-box mb-3">${presupuesto.descripcion}</div>`;
    }

    html += `
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Insumo</th>
                        <th style="width:100px;" class="text-center">Cantidad</th>
                        <th style="width:130px;" class="text-end">Precio Unit.</th>
                        <th style="width:110px;" class="text-center">Impuesto</th>
                        <th style="width:140px;" class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
    `;

    let subtotalGeneral = 0;
    let impuestosGeneral = 0;

    if (presupuesto.detalles && presupuesto.detalles.length > 0) {
        presupuesto.detalles.forEach(detalle => {
            const subtotal = parseFloat(detalle.cantidad) * parseFloat(detalle.precio_unitario);
            let impuesto = 0;

            if (detalle.impuesto_id !== 1 && detalle.impuesto?.calculo) {
                impuesto = Math.round(subtotal / parseFloat(detalle.impuesto.calculo));
            }

            const totalItem = subtotal + impuesto;
            subtotalGeneral += subtotal;
            impuestosGeneral += impuesto;

            html += `
                <tr>
                    <td>
                        <strong>${detalle.insumo?.descripcion || 'N/A'}</strong><br>
                        <span class="tag me-1">${detalle.insumo?.marca?.descripcion || 'N/A'}</span>
                        <span class="tag tag-secondary">${detalle.insumo?.unidad_medida?.abreviatura || detalle.insumo?.unidad_medida?.descripcion || 'N/A'}</span>
                        ${detalle.observacion ? `<div class="text-muted mt-1" style="font-size:0.78rem;"><i class="fas fa-comment me-1"></i>${detalle.observacion}</div>` : ''}
                    </td>
                    <td class="text-center">
                        <span class="tag">${parseInt(detalle.cantidad).toLocaleString('es-PY')}</span>
                    </td>
                    <td class="text-end">₲ ${parseInt(detalle.precio_unitario).toLocaleString('es-PY')}</td>
                    <td class="text-center">
                        <span class="tag tag-secondary">${detalle.impuesto?.descripcion || 'N/A'}</span>
                    </td>
                    <td class="text-end">
                        <span class="amount">₲ ${totalItem.toLocaleString('es-PY')}</span>
                    </td>
                </tr>
            `;
        });
    } else {
        html += `<tr><td colspan="5" class="empty-cell">No hay detalles disponibles</td></tr>`;
    }

    const totalFinal = subtotalGeneral + impuestosGeneral;

    html += `
                </tbody>
            </table>
        </div>

        <div class="totals-box">
            <div class="totals-row">
                <span>Subtotal</span>
                <strong>₲ ${subtotalGeneral.toLocaleString('es-PY')}</strong>
            </div>
            <div class="totals-row">
                <span>Impuestos</span>
                <strong>₲ ${impuestosGeneral.toLocaleString('es-PY')}</strong>
            </div>
            <div class="totals-row totals-final">
                <span>Total</span>
                <strong>₲ ${totalFinal.toLocaleString('es-PY')}</strong>
            </div>
        </div>
    `;

    $('#presupuesto_viewer').html(html);
}

function cambiarPresupuesto(direccion) {
    const newIndex = currentPresupuestoIndex + direccion;
    if (newIndex >= 0 && newIndex < presupuestosData.length) {
        currentPresupuestoIndex = newIndex;
        mostrarPresupuesto();
    }
}

function aprobarPresupuesto() {
    if (presupuestosData.length === 0) return;

    const presupuesto = presupuestosData[currentPresupuestoIndex];

    if (confirm(`¿Está seguro de aprobar el presupuesto "${presupuesto.nombre}"?\n\nEsta acción no se puede deshacer.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("presupuesto_compra_aprobado.store") }}';
        form.style.display = 'none';

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = $('meta[name="csrf-token"]').attr('content');
        form.appendChild(csrfInput);

        const presupuestoInput = document.createElement('input');
        presupuestoInput.type = 'hidden';
        presupuestoInput.name = 'presupuesto_id';
        presupuestoInput.value = presupuesto.id;
        form.appendChild(presupuestoInput);

        document.body.appendChild(form);
        form.submit();
    }
}

function ocultarSecciones() {
    $('#pedido_info').hide();
    $('#pedido_detalle').hide();
    $('#presupuestos_container').hide();
}
</script>
