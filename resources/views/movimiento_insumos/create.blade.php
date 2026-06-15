<!-- filepath: c:\laragon\www\TesisGyA\resources\views\movimiento_insumos\create.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Movimiento de Insumos - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-truck"></i> Nuevo Movimiento de Insumos</h2>
                    <small>Registrar traslado de insumos según una solicitud pendiente</small>
                </div>
                <a href="{{ route('movimiento_insumos.index') }}" class="btn btn-secondary">
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
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('movimiento_insumos.store') }}" method="POST" id="movimientoForm">
                @csrf

                {{-- Selector de Solicitud --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-search me-2"></i>Seleccionar Solicitud Pendiente</span>
                    </div>
                    <div class="card-body">
                        <label for="solicitud_material_id" class="form-label">Solicitud *</label>
                        <select class="form-select form-select-sm" id="solicitud_material_id" name="solicitud_material_id" required>
                            <option value="">Seleccione una solicitud pendiente...</option>
                            @foreach($solicitudes as $solicitud)
                                <option value="{{ $solicitud->id }}">
                                    #{{ str_pad($solicitud->id, 3, '0', STR_PAD_LEFT) }} -
                                    {{ \Carbon\Carbon::parse($solicitud->fecha)->format('d/m/Y') }} -
                                    {{ $solicitud->usuario->usuario ?? 'N/A' }} →
                                    @if($solicitud->obra)
                                        Obra: {{ $solicitud->obra->descripcion }}
                                    @elseif($solicitud->deposito)
                                        Depósito: {{ $solicitud->deposito->descripcion }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Solo se muestran solicitudes pendientes</small>
                    </div>
                </div>

                {{-- Información de la Solicitud --}}
                <div class="card" id="solicitud_info" style="display: none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-info-circle me-2"></i>Información de la Solicitud</span>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <label class="form-label">Destino</label>
                                <div class="info-value"><i class="fas fa-map-marker-alt"></i><span id="info_destino">-</span></div>
                            </div>
                            <div class="info-item span-3">
                                <label class="form-label">Observación de la Solicitud</label>
                                <div class="info-value"><i class="fas fa-comment"></i><span id="info_observacion">-</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Datos del Movimiento --}}
                <div class="card" id="datos_movimiento" style="display: none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-edit me-2"></i>Datos del Movimiento</span>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div>
                                <label for="origen_deposito_id" class="form-label">Depósito de Origen *</label>
                                <select id="origen_deposito_id" name="origen_deposito_id" class="form-select form-select-sm" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($depositos as $deposito)
                                        <option value="{{ $deposito->id }}">{{ $deposito->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="nro_remision_preview" class="form-label">N° de Remisión</label>
                                <input type="text" class="form-control form-control-sm readonly-field" id="nro_remision_preview" value="" readonly placeholder="Seleccione el origen...">
                            </div>
                            <div>
                                <label for="fecha" class="form-label">Fecha *</label>
                                <input type="date" class="form-control form-control-sm" id="fecha" name="fecha" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div>
                                <label for="tipo_vehiculo_id" class="form-label">Tipo de Vehículo *</label>
                                <select id="tipo_vehiculo_id" name="tipo_vehiculo_id" class="form-select form-select-sm" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($tiposVehiculo as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="vehiculo_chapa" class="form-label">Chapa del Vehículo *</label>
                                <input type="text" class="form-control form-control-sm" id="vehiculo_chapa" name="vehiculo_chapa" maxlength="20" required>
                            </div>
                            <div>
                                <label for="chofer_nombre" class="form-label">Nombre del Chofer *</label>
                                <input type="text" class="form-control form-control-sm" id="chofer_nombre" name="chofer_nombre" maxlength="255" required>
                            </div>
                            <div>
                                <label for="chofer_ci" class="form-label">CI del Chofer *</label>
                                <input type="text" class="form-control form-control-sm" id="chofer_ci" name="chofer_ci" maxlength="20" required>
                            </div>

                            <div class="span-4">
                                <label for="observacion" class="form-label">Observación</label>
                                <textarea class="form-control form-control-sm" id="observacion" name="observacion" rows="2" maxlength="500"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Detalle de Insumos (AJAX) --}}
                <div id="detalle-insumos-container"></div>

                {{-- Acciones --}}
                <div class="card" id="botones_accion" style="display: none;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <a href="{{ route('movimiento_insumos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Guardar Movimiento
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
#movimientoForm {
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

/* ── Información de la solicitud ── */
.info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
}
.info-grid .span-3 { grid-column: span 3; }
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

/* ── Datos del movimiento ── */
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
    .info-grid .span-3 { grid-column: span 1; }
    .form-grid { grid-template-columns: 1fr; }
    .form-grid .span-2 { grid-column: span 1; }
}

/* ── Tabla de insumos ── */
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

/* Filas sin stock disponible (apagado) */
.data-table tbody tr.row-apagado { background: #f8fafc; color: #94a3b8; }
.data-table tbody tr.row-apagado:hover { background: #f1f5f9; }
.data-table tbody tr.row-apagado td { color: #94a3b8; }

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
.tag-danger { background: #fef2f2; color: #dc2626; }

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
    $('#solicitud_material_id, #origen_deposito_id, #tipo_vehiculo_id').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Seleccione...'
    });

    let solicitudDetalles = null;
    let inventarioOrigen = null;

    function mostrarSeccionesIniciales(mostrar) {
        if (mostrar) {
            $('#solicitud_info, #datos_movimiento').show();
        } else {
            $('#solicitud_info, #datos_movimiento, #botones_accion').hide();
            $('#detalle-insumos-container').html('');
        }
    }

    function renderTablaInsumos() {
        if (!solicitudDetalles) {
            $('#detalle-insumos-container').html('');
            $('#botones_accion').hide();
            return;
        }

        if (solicitudDetalles.length === 0) {
            $('#detalle-insumos-container').html(`
                <div class="card table-card">
                    <div class="card-body">
                        <div class="empty-state">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            No hay insumos pendientes para esta solicitud.
                        </div>
                    </div>
                </div>
            `);
            $('#botones_accion').hide();
            return;
        }

        if (!inventarioOrigen) {
            $('#detalle-insumos-container').html(`
                <div class="card table-card">
                    <div class="card-body">
                        <div class="empty-state">
                            <i class="fas fa-warehouse fa-2x mb-2"></i>
                            Seleccione el depósito de origen para ver el stock disponible.
                        </div>
                    </div>
                </div>
            `);
            $('#botones_accion').hide();
            return;
        }

        let html = `
            <div class="card table-card">
                <div class="card-header-section">
                    <span><i class="fas fa-list me-2"></i>Insumos a Trasladar</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Insumo</th>
                                    <th style="width:90px;" class="text-center">Unidad</th>
                                    <th style="width:100px;" class="text-center">Solicitado</th>
                                    <th style="width:100px;" class="text-center">Pendiente</th>
                                    <th style="width:110px;" class="text-center">Disponible</th>
                                    <th style="width:120px;" class="text-center">A Enviar</th>
                                    <th>Observación</th>
                                </tr>
                            </thead>
                            <tbody>
        `;

        solicitudDetalles.forEach(function(detalle, i) {
            const pendiente = parseFloat(detalle.cantidad_pendiente);
            const disponible = parseFloat(inventarioOrigen[detalle.insumo_id] ?? 0);
            const maxEnviar = Math.max(0, Math.min(pendiente, disponible));
            const apagado = disponible <= 0;

            html += `
                <tr class="${apagado ? 'row-apagado' : ''}">
                    <td>
                        <strong>${detalle.descripcion}</strong>
                        <span class="tag tag-secondary ms-1">${detalle.marca}</span>
                        ${!apagado ? `<input type="hidden" name="detalles[${i}][insumo_id]" value="${detalle.insumo_id}">` : ''}
                    </td>
                    <td class="text-center">${detalle.unidad}</td>
                    <td class="text-center">${Number(detalle.cantidad_solicitada).toFixed(2)}</td>
                    <td class="text-center"><span class="tag">${Number(pendiente).toFixed(2)}</span></td>
                    <td class="text-center">
                        ${apagado
                            ? `<span class="tag tag-danger">Sin stock</span>`
                            : `<span class="tag tag-secondary">${Number(disponible).toFixed(2)}</span>`}
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm text-center" name="detalles[${i}][cantidad]" min="0" max="${maxEnviar}" step="0.01" value="${maxEnviar > 0 ? maxEnviar : 0}" ${apagado ? 'disabled' : ''}>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="detalles[${i}][observacion]" maxlength="200" ${apagado ? 'disabled' : ''}>
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

        $('#detalle-insumos-container').html(html);
        $('#botones_accion').show();
    }

    $('#solicitud_material_id').on('change', function() {
        const solicitudId = $(this).val();

        if (!solicitudId) {
            mostrarSeccionesIniciales(false);
            solicitudDetalles = null;
            return;
        }

        mostrarSeccionesIniciales(true);
        $('#detalle-insumos-container').html(`
            <div class="card table-card">
                <div class="card-body">
                    <div class="empty-state">
                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>Cargando información de la solicitud...
                    </div>
                </div>
            </div>
        `);

        fetch(`/api/movimiento-insumos/solicitud/${solicitudId}`)
            .then(res => res.json())
            .then(data => {
                if (data.destino) {
                    const etiqueta = data.destino.tipo === 'obra' ? 'Obra' : 'Depósito';
                    $('#info_destino').text(`${etiqueta}: ${data.destino.descripcion}`);
                } else {
                    $('#info_destino').text('-');
                }
                $('#info_observacion').text(data.observacion || '-');

                solicitudDetalles = data.detalles;
                renderTablaInsumos();
            });
    });

    $('#origen_deposito_id').on('change', function() {
        const depositoId = $(this).val();

        if (!depositoId) {
            inventarioOrigen = null;
            $('#nro_remision_preview').val('');
            renderTablaInsumos();
            return;
        }

        fetch(`/api/movimiento-insumos/inventario/${depositoId}`)
            .then(res => res.json())
            .then(data => {
                inventarioOrigen = data.inventario || {};
                $('#nro_remision_preview').val(data.nro_remision || '');
                renderTablaInsumos();
            });
    });
});
</script>
