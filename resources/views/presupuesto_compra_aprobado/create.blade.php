<!-- filepath: c:\laragon\www\TesisGyA\resources\views\presupuesto_compra_aprobado\create.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Aprobar Presupuesto</title>
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
                        <i class="fas fa-check-double me-2 text-success"></i>
                        Aprobar Presupuesto
                    </h2>
                    <p class="text-muted mb-0">Seleccione un pedido y apruebe uno de sus presupuestos</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('presupuesto_compra_aprobado.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Debug Info -->
            <div class="alert alert-info">
                <strong>Debug:</strong> Total de pedidos disponibles: {{ count($pedidos) }}
            </div>

            <!-- Selector de Pedido -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-search me-2"></i>Seleccionar Pedido de Compra
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="pedido_select" class="form-label">Pedido de Compra *</label>
                                <select class="form-control" id="pedido_select" name="pedido_id" required>
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
                    </div>
                </div>
            </div>

            <!-- Información del Pedido Seleccionado -->
            <div class="card mb-4" id="pedido_info" style="display: none;">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información del Pedido
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">Pedido ID:</label>
                                <div class="fw-bold" id="pedido_id_display">#</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">Sucursal:</label>
                                <div class="fw-bold" id="pedido_sucursal_display">-</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">Depósito:</label>
                                <div class="fw-bold" id="pedido_deposito_display">-</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">Fecha:</label>
                                <div class="fw-bold" id="pedido_fecha_display">-</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Creado por:</label>
                                <div class="fw-bold" id="pedido_usuario_display">-</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Presupuestos:</label>
                                <div class="fw-bold text-success" id="pedido_presupuestos_display">0</div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="pedido_observacion_row" style="display: none;">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label text-muted">Observación:</label>
                                <div class="bg-light p-3 rounded" id="pedido_observacion_display">-</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalle del Pedido -->
            <div class="card mb-4" id="pedido_detalle" style="display: none;">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Detalle del Pedido
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Insumo</th>
                                    <th>Cantidad Solicitada</th>
                                    <th>Observación</th>
                                </tr>
                            </thead>
                            <tbody id="pedido_detalle_tbody">
                                <!-- Se cargarán dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Presupuestos del Pedido -->
            <div class="card" id="presupuestos_container" style="display: none;">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-file-invoice-dollar me-2"></i>Presupuestos Disponibles
                        </h5>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-light text-dark fs-6" id="presupuesto_counter">1 de 1</span>
                            <div class="btn-group">
                                <button type="button" class="btn btn-light btn-sm" id="btn_prev" onclick="cambiarPresupuesto(-1)">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button type="button" class="btn btn-light btn-sm" id="btn_next" onclick="cambiarPresupuesto(1)">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="presupuesto_viewer">
                        <!-- Aquí se cargarán los presupuestos dinámicamente -->
                    </div>

                    <!-- Botón de Aprobación -->
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

.presupuesto-item {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.presupuesto-item.active {
    border-color: #28a745;
    background-color: rgba(40, 167, 69, 0.05);
}

.table th {
    font-size: 0.875rem;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

.observacion-detalle {
    background-color: #f8f9fa;
    border-left: 4px solid #007bff;
    padding: 8px 12px;
    margin-top: 5px;
    border-radius: 4px;
    font-style: italic;
    font-size: 0.85rem;
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
let presupuestosData = [];
let currentPresupuestoIndex = 0;

// Inicializar cuando el DOM esté listo
$(document).ready(function() {
    console.log('DOM Ready - Inicializando...');

    // Inicializar Select2
    $('#pedido_select').select2({
        placeholder: 'Seleccione un pedido pendiente...',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap-5'
    });

    console.log('Select2 inicializado');

    // Evento change del select
    $('#pedido_select').on('change', function() {
        const pedidoId = $(this).val();
        console.log('Pedido seleccionado:', pedidoId);

        if (pedidoId) {
            mostrarInformacionPedido();
            cargarDetallePedido(pedidoId);
            cargarPresupuestos(pedidoId);
        } else {
            ocultarSecciones();
        }
    });

    // También escuchar el evento select2:select
    $('#pedido_select').on('select2:select', function (e) {
        const pedidoId = e.params.data.id;
        console.log('Select2 select event:', pedidoId);

        if (pedidoId) {
            mostrarInformacionPedido();
            cargarDetallePedido(pedidoId);
            cargarPresupuestos(pedidoId);
        }
    });
});

function mostrarInformacionPedido() {
    console.log('Mostrando información del pedido...');

    const selectedOption = $('#pedido_select option:selected');
    const pedidoId = $('#pedido_select').val();

    console.log('Pedido seleccionado:', pedidoId);
    console.log('Data del option:', {
        sucursal: selectedOption.data('sucursal'),
        deposito: selectedOption.data('deposito'),
        fecha: selectedOption.data('fecha'),
        usuario: selectedOption.data('usuario'),
        observacion: selectedOption.data('observacion'),
        presupuestos: selectedOption.data('presupuestos')
    });

    $('#pedido_id_display').text('#' + pedidoId);
    $('#pedido_sucursal_display').text(selectedOption.data('sucursal') || 'N/A');
    $('#pedido_deposito_display').text(selectedOption.data('deposito') || 'N/A');

    // Formatear fecha
    const fecha = selectedOption.data('fecha');
    if (fecha) {
        $('#pedido_fecha_display').text(new Date(fecha).toLocaleDateString('es-PY'));
    } else {
        $('#pedido_fecha_display').text('N/A');
    }

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
    console.log('Información del pedido mostrada');
}

function cargarDetallePedido(pedidoId) {
    console.log('Cargando detalle del pedido:', pedidoId);

    $.ajax({
        url: `/pedido-compra/detalle/${pedidoId}`,
        method: 'GET',
        success: function(response) {
            console.log('Detalle del pedido cargado:', response);

            if (response.success && response.detalles) {
                mostrarDetallePedido(response.detalles);
            } else {
                $('#pedido_detalle_tbody').html('<tr><td colspan="3" class="text-center">No se pudo cargar el detalle del pedido</td></tr>');
            }
            $('#pedido_detalle').show();
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar detalle del pedido:', error);
            $('#pedido_detalle_tbody').html('<tr><td colspan="3" class="text-center text-danger">Error al cargar el detalle</td></tr>');
            $('#pedido_detalle').show();
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
                    <small class="text-muted">
                        <span class="badge bg-primary me-1">${detalle.insumo?.marca?.descripcion || 'N/A'}</span>
                        <span class="badge bg-secondary">${detalle.insumo?.unidad_medida?.abreviatura || detalle.insumo?.unidad_medida?.descripcion || 'N/A'}</span>
                    </small>
                </td>
                <td class="text-center">
                    <span class="badge bg-info fs-6">${parseInt(detalle.cantidad).toLocaleString('es-PY')}</span>
                </td>
                <td>
                    ${detalle.observacion ? `<div class="observacion-detalle">${detalle.observacion}</div>` : '<span class="text-muted">Sin observación</span>'}
                </td>
            </tr>
        `;
    });

    $('#pedido_detalle_tbody').html(html);
}

function cargarPresupuestos(pedidoId) {
    console.log('Cargando presupuestos para pedido:', pedidoId);

    // Mostrar loading
    $('#presupuesto_viewer').html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin fa-2x"></i><br>Cargando presupuestos...</div>');
    $('#presupuestos_container').show();

    $.ajax({
        url: `/presupuesto-compra-aprobado/presupuestos/${pedidoId}`,
        method: 'GET',
        success: function(response) {
            console.log('Respuesta del servidor:', response);

            if (response.success && response.presupuestos) {
                presupuestosData = response.presupuestos;
                currentPresupuestoIndex = 0;

                if (presupuestosData.length > 0) {
                    mostrarPresupuesto();
                } else {
                    $('#presupuesto_viewer').html('<div class="text-center p-4"><i class="fas fa-exclamation-triangle fa-2x text-warning"></i><br>No hay presupuestos disponibles para este pedido.</div>');
                }
            } else {
                $('#presupuesto_viewer').html('<div class="text-center p-4"><i class="fas fa-exclamation-triangle fa-2x text-danger"></i><br>Error: ' + (response.message || 'No se pudieron cargar los presupuestos') + '</div>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error AJAX:', {
                status: status,
                error: error,
                response: xhr.responseText
            });

            $('#presupuesto_viewer').html('<div class="text-center p-4"><i class="fas fa-exclamation-circle fa-2x text-danger"></i><br>Error al cargar los presupuestos: ' + error + '</div>');
        }
    });
}

function mostrarPresupuesto() {
    if (presupuestosData.length === 0) {
        console.log('No hay presupuestos para mostrar');
        return;
    }

    const presupuesto = presupuestosData[currentPresupuestoIndex];
    const total = presupuestosData.length;

    console.log('Mostrando presupuesto:', presupuesto);

    // Actualizar contador
    $('#presupuesto_counter').text(`${currentPresupuestoIndex + 1} de ${total}`);

    // Actualizar botones de navegación
    $('#btn_prev').prop('disabled', currentPresupuestoIndex === 0);
    $('#btn_next').prop('disabled', currentPresupuestoIndex === total - 1);

    // Generar HTML del presupuesto
    let html = `
        <div class="presupuesto-item active">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5 class="text-primary">${presupuesto.nombre || 'Sin nombre'}</h5>
                    <p class="text-muted mb-1"><strong>Proveedor:</strong> ${presupuesto.proveedor?.razon_social || 'N/A'}</p>
                    <p class="text-muted mb-1"><strong>RUC:</strong> ${presupuesto.proveedor?.ruc || 'N/A'}</p>
                </div>
                <div class="col-md-6 text-end">
                    <div class="mb-2">
                        <span class="badge bg-warning fs-6">${presupuesto.estado?.descripcion || 'Sin estado'}</span>
                    </div>
                    <p class="text-muted mb-1"><strong>Emisión:</strong> ${presupuesto.fecha_emision ? new Date(presupuesto.fecha_emision).toLocaleDateString('es-PY') : 'N/A'}</p>
                    <p class="text-muted mb-1"><strong>Vencimiento:</strong> ${presupuesto.fecha_vencimiento ? new Date(presupuesto.fecha_vencimiento).toLocaleDateString('es-PY') : 'N/A'}</p>
                </div>
            </div>

            ${presupuesto.descripcion ? `
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="bg-light p-3 rounded">
                            <strong>Descripción:</strong> ${presupuesto.descripcion}
                        </div>
                    </div>
                </div>
            ` : ''}

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Insumo</th>
                            <th>Cantidad</th>
                            <th>Precio Unit.</th>
                            <th>Impuesto</th>
                            <th>Total Item</th>
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
                        <small class="text-muted">
                            <span class="badge bg-primary me-1">${detalle.insumo?.marca?.descripcion || 'N/A'}</span>
                            <span class="badge bg-secondary">${detalle.insumo?.unidad_medida?.abreviatura || detalle.insumo?.unidad_medida?.descripcion || 'N/A'}</span>
                        </small>
                        ${detalle.observacion ? `<div class="observacion-detalle mt-2"><i class="fas fa-comment me-1"></i>${detalle.observacion}</div>` : ''}
                    </td>
                    <td class="text-center">
                        <span class="badge bg-info fs-6">${parseInt(detalle.cantidad).toLocaleString('es-PY')}</span>
                    </td>
                    <td class="text-center">
                        <strong>₲ ${parseInt(detalle.precio_unitario).toLocaleString('es-PY')}</strong>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-secondary">${detalle.impuesto?.descripcion || 'N/A'}</span>
                    </td>
                    <td class="text-center">
                        <strong class="text-success">₲ ${totalItem.toLocaleString('es-PY')}</strong>
                    </td>
                </tr>
            `;
        });
    } else {
        html += `
            <tr>
                <td colspan="5" class="text-center">No hay detalles disponibles</td>
            </tr>
        `;
    }

    const totalFinal = subtotalGeneral + impuestosGeneral;

    html += `
                    </tbody>
                </table>
            </div>

            <div class="row mt-3">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <div class="bg-primary text-white p-3 rounded">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong>₲ ${subtotalGeneral.toLocaleString('es-PY')}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Impuestos:</span>
                            <strong>₲ ${impuestosGeneral.toLocaleString('es-PY')}</strong>
                        </div>
                        <hr style="border-color: rgba(255,255,255,0.3);">
                        <div class="d-flex justify-content-between">
                            <span class="h5">TOTAL:</span>
                            <strong class="h4">₲ ${totalFinal.toLocaleString('es-PY')}</strong>
                        </div>
                    </div>
                </div>
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
        // Crear un formulario dinámico para enviar
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("presupuesto_compra_aprobado.store") }}';
        form.style.display = 'none';

        // Token CSRF
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = $('meta[name="csrf-token"]').attr('content');
        form.appendChild(csrfInput);

        // Presupuesto ID
        const presupuestoInput = document.createElement('input');
        presupuestoInput.type = 'hidden';
        presupuestoInput.name = 'presupuesto_id';
        presupuestoInput.value = presupuesto.id;
        form.appendChild(presupuestoInput);

        // Agregar al DOM y enviar
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
