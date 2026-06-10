<!-- filepath: c:\laragon\www\TesisGyA\resources\views\nota_remision_compra\create.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Nota de Remisión de Compra - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2 id="titulo-remision"><i class="fas fa-truck"></i> Nueva Nota de Remisión de Compra</h2>
                    <small>Registrar recepción de mercaderías según orden de compra pendiente</small>
                </div>
                <a href="{{ route('nota_remision_compra.index') }}" class="btn btn-secondary">
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

            <form action="{{ route('nota_remision_compra.store') }}" method="POST" id="notaRemisionForm">
                @csrf

                {{-- Selector de Orden de Compra --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-search me-2"></i>Seleccionar Orden de Compra Pendiente</span>
                    </div>
                    <div class="card-body">
                        <label for="orden_compra_id" class="form-label">Orden de Compra *</label>
                        <select class="form-select form-select-sm" id="orden_compra_id" name="orden_compra_id" required>
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

                {{-- Información de la Orden --}}
                <div class="card" id="orden_info" style="display: none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-info-circle me-2"></i>Información de la Orden</span>
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
                                <label class="form-label">Fecha Orden</label>
                                <div class="info-value"><i class="fas fa-calendar"></i><span id="info_fecha">-</span></div>
                            </div>
                            <div class="info-item">
                                <label class="form-label">Monto Total</label>
                                <div class="info-value"><i class="fas fa-coins"></i><span id="info_total">-</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Detalles de la Orden (AJAX) --}}
                <div id="detalles-orden-container"></div>

                {{-- Datos de la Nota de Remisión --}}
                <div class="card" id="datos_remision" style="display: none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-edit me-2"></i>Datos de la Nota de Remisión</span>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div>
                                <label for="numero_remision" class="form-label">N° de Remisión *</label>
                                <input type="text" class="form-control form-control-sm" id="numero_remision" name="numero_remision" maxlength="30" required>
                            </div>
                            <div>
                                <label for="nombre_remision" class="form-label">Nombre de la Remisión *</label>
                                <input type="text" class="form-control form-control-sm readonly-field" id="nombre_remision" name="nombre_remision" maxlength="100" required readonly>
                            </div>
                            <div>
                                <label for="fecha_emision" class="form-label">Fecha de Emisión *</label>
                                <input type="date" class="form-control form-control-sm" id="fecha_emision" name="fecha_emision" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div>
                                <label for="fecha_recepcion" class="form-label">Fecha de Recepción *</label>
                                <input type="date" class="form-control form-control-sm" id="fecha_recepcion" name="fecha_recepcion" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div>
                                <label for="recibido_por" class="form-label">Recibido por *</label>
                                <select id="recibido_por" name="recibido_por" class="form-select form-select-sm" required>
                                    <option value="">Seleccione funcionario...</option>
                                    @foreach($funcionarios as $funcionario)
                                        <option value="{{ $funcionario->id }}">{{ $funcionario->persona->nombre }} {{ $funcionario->persona->apellido }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="deposito_display" class="form-label">Depósito</label>
                                <input type="text" class="form-control form-control-sm readonly-field" id="deposito_display" value="{{ $deposito->descripcion ?? '' }}" readonly>
                                <input type="hidden" name="deposito_id" value="{{ $deposito->id ?? '' }}">
                            </div>
                            <div>
                                <label for="conductor_nombre" class="form-label">Nombre del Conductor</label>
                                <input type="text" class="form-control form-control-sm" id="conductor_nombre" name="conductor_nombre" maxlength="100">
                            </div>
                            <div>
                                <label for="conductor_ci" class="form-label">CI del Conductor</label>
                                <input type="text" class="form-control form-control-sm" id="conductor_ci" name="conductor_ci" maxlength="20">
                            </div>

                            <div>
                                <label for="vehiculo_chapa" class="form-label">Chapa del Vehículo</label>
                                <input type="text" class="form-control form-control-sm" id="vehiculo_chapa" name="vehiculo_chapa" maxlength="20">
                            </div>
                            <div>
                                <label for="tipo_vehiculo_id" class="form-label">Tipo de Vehículo</label>
                                <select id="tipo_vehiculo_id" name="tipo_vehiculo_id" class="form-select form-select-sm">
                                    <option value="">Seleccione...</option>
                                    @foreach($tiposVehiculo as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="origen" class="form-label">Origen</label>
                                <input type="text" class="form-control form-control-sm" id="origen" name="origen" maxlength="100">
                            </div>
                            <div>
                                <label for="destino" class="form-label">Destino</label>
                                <input type="text" class="form-control form-control-sm" id="destino" name="destino" maxlength="100">
                            </div>

                            <div class="span-4">
                                <label for="observacion" class="form-label">Observación</label>
                                <textarea class="form-control form-control-sm" id="observacion" name="observacion" rows="2" maxlength="300"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="card" id="botones_accion" style="display: none;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <a href="{{ route('nota_remision_compra.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Guardar Nota de Remisión
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
#notaRemisionForm {
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

.select2-container--bootstrap-5 .select2-selection {
    min-height: calc(1.5em + 0.5rem + 2px);
}

/* ── Información de la orden ── */
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

/* ── Datos de la nota de remisión ── */
.form-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
}
.form-grid .span-2 { grid-column: span 2; }
.form-grid .span-4 { grid-column: 1 / -1; }
.readonly-field {
    background-color: #f8fafc !important;
    border-color: #e2e8f0 !important;
    color: #374151;
}

@media (max-width: 900px) {
    .info-grid { grid-template-columns: repeat(2, 1fr); }
    .form-grid { grid-template-columns: repeat(2, 1fr); }
    .page-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .info-grid { grid-template-columns: 1fr; }
    .form-grid { grid-template-columns: 1fr; }
    .form-grid .span-2 { grid-column: span 1; }
}

/* ── Tabla de detalles pendientes ── */
.table-card { display: flex; flex-direction: column; }
.table-container { overflow: auto; }

.data-table {
    width: 100%;
    min-width: 900px;
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

/* Empty state */
.empty-state {
    min-height: 160px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 2rem; color: #94a3b8; text-align: center;
}
.empty-state i { color: #cbd5e1; }

@media (max-width: 768px) {
    .table-container { font-size: 0.875rem; }
}
</style>

<script>
$(document).ready(function() {
    // Inicializar Select2
    $('#orden_compra_id, #recibido_por, #tipo_vehiculo_id').select2({
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
                `<i class="fas fa-truck"></i>Remisión <span id="remision-numero-span"></span> perteneciente a la orden de compra #${ordenId}`
            );

            // Cargar detalles vía AJAX y renderizar tabla aquí mismo
            $('#detalles-orden-container').html('<div class="card table-card"><div class="card-body"><div class="empty-state"><i class="fas fa-spinner fa-spin fa-2x mb-2"></i>Cargando detalles...</div></div></div>');

            fetch(`/api/orden-compra/${ordenId}/detalles-pendientes`)
                .then(res => res.json())
                .then(data => {
                    let html = '';
                    if (data.length > 0) {
                        html += `
                        <div class="card table-card">
                            <div class="card-header-section">
                                <span><i class="fas fa-list me-2"></i>Detalles de la Orden de Compra</span>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-container">
                                    <table class="data-table">
                                        <thead>
                                            <tr>
                                                <th>Insumo</th>
                                                <th style="width:90px;" class="text-center">Unidad</th>
                                                <th style="width:100px;" class="text-center">Cant. Pedida</th>
                                                <th style="width:110px;" class="text-center">Cant. Entregada</th>
                                                <th style="width:110px;" class="text-center">Cant. Pendiente</th>
                                                <th style="width:120px;" class="text-center">Cant. Recibida</th>
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
                                        <strong>${detalle.descripcion}</strong>
                                        <input type="hidden" name="detalle[${i}][insumo_id]" value="${detalle.insumo_id}">
                                    </td>
                                    <td class="text-center"><span class="tag tag-secondary">${detalle.unidad}</span></td>
                                    <td class="text-center">${detalle.cantidad_pedida}</td>
                                    <td class="text-center">${detalle.cantidad_entregada}</td>
                                    <td class="text-center"><span class="tag">${pendiente}</span></td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm text-center" name="detalle[${i}][cantidad_entregada]" min="0" max="${pendiente}" step="0.01" value="${pendiente > 0 ? pendiente : 0}" ${pendiente == 0 ? 'readonly disabled' : ''}>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="detalle[${i}][observacion]" maxlength="200" ${pendiente == 0 ? 'readonly disabled' : ''}>
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
                        html = `
                        <div class="card table-card">
                            <div class="card-body">
                                <div class="empty-state">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    No hay detalles pendientes para esta orden.
                                </div>
                            </div>
                        </div>
                        `;
                    }
                    $('#detalles-orden-container').html(html);
                });
        } else {
            $('#orden_info').hide();
            $('#datos_remision').hide();
            $('#botones_accion').hide();
            $('#detalles-orden-container').html('');
            $('#titulo-remision').html('<i class="fas fa-truck"></i>Nueva Nota de Remisión de Compra');
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
});
</script>
