<!-- filepath: c:\laragon\www\TesisGyA\resources\views\nota_remision_compra\create.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Nota de Remisión de Compra</title>
    @include('partials.head')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content fade-in">
        <div class="content-wrapper">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 id="titulo-remision">
                        <i class="fas fa-truck me-2 text-primary"></i>
                        Nueva Nota de Remisión de Compra
                    </h2>
                    <p class="text-muted mb-0">Registrar recepción de mercaderías según orden de compra pendiente</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('nota_remision_compra.index') }}" class="btn btn-secondary">
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

            <form action="{{ route('nota_remision_compra.store') }}" method="POST" id="notaRemisionForm">
                @csrf

                <!-- Selector de Orden de Compra -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-search me-2"></i>Seleccionar Orden de Compra Pendiente
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="orden_compra_id" class="form-label">Orden de Compra *</label>
                            <select class="form-control select2" id="orden_compra_id" name="orden_compra_id" required>
                                <option value="">Seleccione una orden pendiente...</option>
                                @foreach($ordenes as $orden)
                                    <option value="{{ $orden->id }}"
                                            data-proveedor="{{ $orden->proveedor->razon_social ?? 'N/A' }}"
                                            data-ruc="{{ $orden->proveedor->ruc ?? 'N/A' }}"
                                            data-fecha="{{ $orden->fecha }}"
                                            data-total="{{ $orden->monto }}">
                                        #{{ $orden->id }} - {{ $orden->proveedor->razon_social ?? 'N/A' }} ({{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Solo se muestran órdenes de compra pendientes</small>
                        </div>
                    </div>
                </div>

                <!-- Información de la Orden -->
                <div class="card mb-4" id="orden_info" style="display: none;">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Información de la Orden
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
                                <label class="form-label text-muted">Fecha Orden:</label>
                                <div class="fw-bold" id="info_fecha">-</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted">Monto Total:</label>
                                <div class="fw-bold" id="info_total">-</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalles de la Orden (AJAX) -->
                <div id="detalles-orden-container"></div>

                <!-- Datos de la Nota de Remisión -->
                <div class="card mb-4" id="datos_remision" style="display: none;">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2"></i>Datos de la Nota de Remisión
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="numero_remision" class="form-label">N° de Remisión *</label>
                                <input type="text" class="form-control" id="numero_remision" name="numero_remision" maxlength="30" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="nombre_remision" class="form-label">Nombre de la Remisión *</label>
                                <input type="text" class="form-control" id="nombre_remision" name="nombre_remision" maxlength="100" required readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="fecha_emision" class="form-label">Fecha de Emisión *</label>
                                <input type="date" class="form-control" id="fecha_emision" name="fecha_emision" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="fecha_recepcion" class="form-label">Fecha de Recepción *</label>
                                <input type="date" class="form-control" id="fecha_recepcion" name="fecha_recepcion" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="recibido_por" class="form-label">Recibido por *</label>
                                <select id="recibido_por" name="recibido_por" class="form-select select2" required>
                                    <option value="">Seleccione funcionario...</option>
                                    @foreach($funcionarios as $funcionario)
                                        <option value="{{ $funcionario->id }}">{{ $funcionario->persona->nombre }} {{ $funcionario->persona->apellido }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="deposito_id" class="form-label">Depósito</label>
                                <input type="text" class="form-control" id="deposito_id" value="{{ $deposito->descripcion ?? '' }}" readonly>
                                <input type="hidden" name="deposito_id" value="{{ $deposito->id ?? '' }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="conductor_nombre" class="form-label">Nombre del Conductor</label>
                                <input type="text" class="form-control" id="conductor_nombre" name="conductor_nombre" maxlength="100">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="conductor_ci" class="form-label">CI del Conductor</label>
                                <input type="text" class="form-control" id="conductor_ci" name="conductor_ci" maxlength="20">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="vehiculo_chapa" class="form-label">Chapa del Vehículo</label>
                                <input type="text" class="form-control" id="vehiculo_chapa" name="vehiculo_chapa" maxlength="20">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="tipo_vehiculo_id" class="form-label">Tipo de Vehículo</label>
                                <select id="tipo_vehiculo_id" name="tipo_vehiculo_id" class="form-select select2">
                                    <option value="">Seleccione...</option>
                                    @foreach($tiposVehiculo as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="origen" class="form-label">Origen</label>
                                <input type="text" class="form-control" id="origen" name="origen" maxlength="100">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="destino" class="form-label">Destino</label>
                                <input type="text" class="form-control" id="destino" name="destino" maxlength="100">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="observacion" class="form-label">Observación</label>
                                <textarea class="form-control" id="observacion" name="observacion" rows="2" maxlength="300"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="text-center" id="botones_accion" style="display: none;">
                    <button type="submit" class="btn btn-success btn-lg me-3">
                        <i class="fas fa-save me-2"></i>Guardar Nota de Remisión
                    </button>
                    <a href="{{ route('nota_remision_compra.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    @include('partials.footer')
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>

<script>
$(document).ready(function() {
    // Inicializar select2 en todos los select con la clase .select2
    $('select').addClass('select2');
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Seleccione...'
    });

    function actualizarNombreRemision() {
        let nro = $('#numero_remision').val();
        let ordenId = $('#orden_compra_id').val();
        if (nro && ordenId) {
            $('#nombre_remision').val(`Remisión #${nro} perteneciente a la orden de compra #${ordenId}`);
        } else {
            $('#nombre_remision').val('');
        }
    }

    $('#orden_compra_id').on('change', function() {
        const selected = $(this).find('option:selected');
        const ordenId = $(this).val();
        if (ordenId) {
            $('#info_proveedor').text(selected.data('proveedor') || '-');
            $('#info_ruc').text(selected.data('ruc') || '-');
            $('#info_fecha').text(selected.data('fecha') ? new Date(selected.data('fecha')).toLocaleDateString('es-PY') : '-');
            $('#info_total').text(selected.data('total') ? '₲ ' + Number(selected.data('total')).toLocaleString('es-PY') : '-');
            $('#orden_info').show();
            $('#datos_remision').show();
            $('#botones_accion').show();

            // Cambiar el título de la remisión
            $('#titulo-remision').html(
                `<i class="fas fa-truck me-2 text-primary"></i>Remisión <span id="remision-numero-span"></span> perteneciente a la orden de compra #${ordenId}`
            );

            // Cargar detalles vía AJAX y renderizar tabla aquí mismo
            fetch(`/api/orden-compra/${ordenId}/detalles-pendientes`)
                .then(res => res.json())
                .then(data => {
                    let html = '';
                    if (data.length > 0) {
                        html += `
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-list me-2"></i>Detalles de la Orden de Compra
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Insumo</th>
                                                <th>Unidad</th>
                                                <th>Cant. Pedida</th>
                                                <th>Cant. Entregada</th>
                                                <th>Cant. Pendiente</th>
                                                <th>Cant. Recibida</th>
                                                <th>Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                        `;
                        data.forEach(function(detalle, i) {
                            const pendiente = detalle.cantidad_pendiente;
                            html += `
                                <tr>
                                    <td>
                                        ${detalle.descripcion}
                                        <input type="hidden" name="detalle[${i}][insumo_id]" value="${detalle.insumo_id}">
                                    </td>
                                    <td>${detalle.unidad}</td>
                                    <td>${detalle.cantidad_pedida}</td>
                                    <td>${detalle.cantidad_entregada}</td>
                                    <td>${pendiente}</td>
                                    <td>
                                        <input type="number" class="form-control" name="detalle[${i}][cantidad_entregada]" min="0" max="${pendiente}" step="0.01" value="${pendiente > 0 ? pendiente : 0}" ${pendiente == 0 ? 'readonly disabled' : ''}>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="detalle[${i}][observacion]" maxlength="200" ${pendiente == 0 ? 'readonly disabled' : ''}>
                                    </td>
                                </tr>
                            `;
                        });
                        html += `
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        `;
                    } else {
                        html = `<div class="alert alert-info">No hay detalles pendientes para esta orden.</div>`;
                    }
                    $('#detalles-orden-container').html(html);
                });
        } else {
            $('#orden_info').hide();
            $('#datos_remision').hide();
            $('#botones_accion').hide();
            $('#detalles-orden-container').html('');
            $('#titulo-remision').html('<i class="fas fa-truck me-2 text-primary"></i>Nueva Nota de Remisión de Compra');
        }
        actualizarNombreRemision();
    });

    // Actualiza el número de remisión en el título y el nombre de la remisión
    $('#numero_remision').on('input', function() {
        let nro = $(this).val();
        if (nro) {
            $('#remision-numero-span').text(`#${nro}`);
        } else {
            $('#remision-numero-span').text('');
        }
        actualizarNombreRemision();
    });

    // También actualizar el nombre si cambia la orden
    $('#orden_compra_id').on('change', actualizarNombreRemision);
});
</script>
