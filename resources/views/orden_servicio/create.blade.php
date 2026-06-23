<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Orden de Servicio</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-tasks"></i> Crear Orden de Servicio</h2>
                    <small>Complete los datos para generar una nueva orden de servicio</small>
                </div>
                <a href="{{ route('orden_servicio.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                </a>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('orden_servicio.store') }}" method="POST" id="ordenServicioForm">
                @csrf

                {{-- Selección de Datos --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-search me-2"></i>Selección de Datos</span>
                    </div>
                    <div class="card-body">
                        <div class="form-grid form-grid-3">
                            <div>
                                <label for="cliente_id" class="form-label">Cliente</label>
                                <select name="cliente_id" id="cliente_id" class="form-select form-select-sm select2" required>
                                    <option value="">Seleccionar Cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->razon_social }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="obra_id" class="form-label">Obra</label>
                                <select name="obra_id" id="obra_id" class="form-select form-select-sm select2" required disabled>
                                    <option value="">Seleccionar Obra</option>
                                </select>
                            </div>
                            <div>
                                <label for="contrato_id" class="form-label">Contrato</label>
                                <select name="contrato_id" id="contrato_id" class="form-select form-select-sm select2" required disabled>
                                    <option value="">Seleccionar Contrato</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Información del Contrato --}}
                <div class="card" id="info-section" style="display:none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-info-circle me-2"></i>Información del Contrato</span>
                    </div>
                    <div class="card-body">
                        <div class="detail-box">
                            <div class="detail-box-title">Datos del Contrato Seleccionado</div>
                            <div id="contrato-datos"></div>
                        </div>
                    </div>
                </div>

                {{-- Ensayos del Presupuesto --}}
                <div class="card" id="ensayos-section" style="display:none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-flask me-2"></i>Ensayos del Presupuesto</span>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3" style="font-size:0.8rem;">Seleccione los ensayos a incluir en esta orden de servicio.</p>
                        <div id="ensayos-por-servicio"></div>
                    </div>
                </div>

                {{-- Funcionarios --}}
                <div class="card" id="funcionarios-section" style="display:none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-users me-2"></i>Funcionarios Asignados</span>
                    </div>
                    <div class="card-body">
                        <select name="funcionarios[]" id="funcionarios" class="form-select form-select-sm select2" multiple required>
                            @foreach($funcionarios as $funcionario)
                                <option value="{{ $funcionario->id }}">{{ $funcionario->persona->nombre ?? '' }} {{ $funcionario->persona->apellido ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Datos de la Orden de Servicio --}}
                <div id="orden-section" style="display:none;">
                    <div class="card">
                        <div class="card-header-section">
                            <span><i class="fas fa-clipboard-list me-2"></i>Datos de la Orden de Servicio</span>
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
                                    <label for="fecha_culminacion_display" class="form-label">Fecha de Culminación Teórica</label>
                                    <input type="text" id="fecha_culminacion_display" class="form-control form-control-sm readonly-field" readonly>
                                </div>
                                <div>
                                    <label for="usuario_display" class="form-label">Usuario</label>
                                    <input type="text" id="usuario_display" class="form-control form-control-sm readonly-field" value="{{ session('user_usuario') }}" readonly>
                                </div>
                                <div class="span-4">
                                    <label for="observacion" class="form-label">Observación</label>
                                    <textarea name="observacion" id="observacion" class="form-control form-control-sm" rows="2" placeholder="Ingrese una observación..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Guardar Orden de Servicio
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

/* ── Formulario ── */
#ordenServicioForm {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
#orden-section {
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

/* ── Grillas de formulario ── */
.form-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
}
.form-grid.form-grid-3 { grid-template-columns: repeat(3, 1fr); }
.form-grid .form-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 500;
    color: #94a3b8;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.form-grid .span-2 { grid-column: span 2; }
.form-grid .span-4 { grid-column: span 4; }
.readonly-field {
    background-color: #f8fafc !important;
    border-color: #e2e8f0 !important;
    color: #374151;
}

/* ── Información del contrato ── */
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

/* ── Ensayos por servicio ── */
.servicio-group { margin-bottom: 1rem; }
.servicio-group:last-child { margin-bottom: 0; }
.servicio-group h6 {
    font-size: 0.75rem;
    font-weight: 600;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.5rem;
}
.servicios-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 0.5rem;
}
.servicio-check {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.5rem 0.65rem;
    font-size: 0.8rem;
    color: #374151;
    cursor: pointer;
}
.servicio-check:hover { background: #eff6ff; border-color: #bfdbfe; }
.servicio-check input { margin: 0; cursor: pointer; }

.select2-container--bootstrap-5 .select2-selection {
    min-height: calc(1.5em + 0.5rem + 2px);
}

@media (max-width: 900px) {
    .form-grid { grid-template-columns: repeat(2, 1fr); }
    .form-grid.form-grid-3 { grid-template-columns: repeat(2, 1fr); }
    .page-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .form-grid, .form-grid.form-grid-3 { grid-template-columns: 1fr; }
    .form-grid .span-2,
    .form-grid .span-4 { grid-column: span 1; }
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

    // Cargar obras al seleccionar cliente
    $('#cliente_id').on('change', function() {
        const clienteId = $(this).val();
        if (clienteId) {
            fetch(`/obras-con-contrato/${clienteId}`)
                .then(response => response.json())
                .then(data => {
                    $('#obra_id').html('<option value="">Seleccionar Obra</option>');
                    data.forEach(function(obra) {
                        $('#obra_id').append(`<option value="${obra.id}">${obra.descripcion}</option>`);
                    });
                    $('#obra_id').prop('disabled', false).trigger('change.select2');
                });
        } else {
            resetObra();
        }
        resetContrato();
    });

    // Cargar contratos al seleccionar obra
    $('#obra_id').on('change', function() {
        const obraId = $(this).val();
        if (obraId) {
            fetch(`/contratos-por-obra/${obraId}`)
                .then(response => response.json())
                .then(data => {
                    $('#contrato_id').html('<option value="">Seleccionar Contrato</option>');
                    data.forEach(function(contrato) {
                        $('#contrato_id').append(`<option value="${contrato.id}" data-plazo="${contrato.plazo_dias}" data-firma="${contrato.fecha_firma ?? ''}" data-monto="${contrato.monto}" data-garantia="${contrato.garantia_meses}" data-presupuesto="${contrato.numero_presupuesto ?? ''}" data-presupuesto-id="${contrato.presupuesto_servicio_id}">Contrato #${String(contrato.id).padStart(3, '0')}</option>`);
                    });
                    $('#contrato_id').prop('disabled', false).trigger('change.select2');
                });
        } else {
            resetContrato();
        }
    });

    // Mostrar datos del contrato seleccionado
    $('#contrato_id').on('change', function() {
        const selected = $(this).find('option:selected');
        const plazo = selected.data('plazo');
        const presupuestoId = selected.data('presupuesto-id');

        if (plazo !== undefined) {
            $('#contrato-datos').html(`
                <div class="detail-row"><i class="fas fa-hashtag"></i><span><strong>N° Contrato:</strong> ${String(selected.val()).padStart(3, '0')}</span></div>
                <div class="detail-row"><i class="fas fa-file-invoice-dollar"></i><span><strong>N° Presupuesto:</strong> ${selected.data('presupuesto') || '-'}</span></div>
                <div class="detail-row"><i class="fas fa-coins"></i><span><strong>Monto:</strong> ₲ ${parseFloat(selected.data('monto')).toLocaleString('es-PY')}</span></div>
                <div class="detail-row"><i class="fas fa-calendar-check"></i><span><strong>Fecha de Firma:</strong> ${selected.data('firma') || '-'}</span></div>
                <div class="detail-row"><i class="fas fa-clock"></i><span><strong>Plazo:</strong> ${plazo} días</span></div>
                <div class="detail-row"><i class="fas fa-shield-alt"></i><span><strong>Garantía:</strong> ${selected.data('garantia')} meses</span></div>
            `);

            const hoy = new Date();
            const culminacion = new Date(hoy);
            culminacion.setDate(culminacion.getDate() + parseInt(plazo, 10));
            $('#fecha_culminacion_display').val(culminacion.toLocaleDateString('es-PY'));

            fetch(`/ensayos-por-presupuesto/${presupuestoId}`)
                .then(response => response.json())
                .then(data => {
                    $('#ensayos-por-servicio').html('');
                    data.forEach(function(servicio) {
                        if (!servicio.ensayos || servicio.ensayos.length === 0) return;
                        const checks = servicio.ensayos.map(ensayo => `
                            <label class="servicio-check" for="ensayo-${ensayo.id}">
                                <input type="checkbox" name="ensayos[]" class="ensayo-checkbox" value="${ensayo.id}" id="ensayo-${ensayo.id}" checked>
                                <span>${ensayo.descripcion}</span>
                            </label>
                        `).join('');
                        $('#ensayos-por-servicio').append(`
                            <div class="servicio-group">
                                <h6>${servicio.servicio}</h6>
                                <div class="servicios-grid">${checks}</div>
                            </div>
                        `);
                    });
                    $('#ensayos-section').show();
                });

            $('#info-section, #funcionarios-section, #orden-section').show();
        } else {
            resetContratoInfo();
        }
    });

    function resetObra() {
        $('#obra_id').html('<option value="">Seleccionar Obra</option>').prop('disabled', true).trigger('change.select2');
    }

    function resetContrato() {
        $('#contrato_id').html('<option value="">Seleccionar Contrato</option>').prop('disabled', true).trigger('change.select2');
        resetContratoInfo();
    }

    function resetContratoInfo() {
        $('#info-section, #ensayos-section, #funcionarios-section, #orden-section').hide();
        $('#contrato-datos').html('');
        $('#ensayos-por-servicio').html('');
        $('#fecha_culminacion_display').val('');
    }

    $('#ordenServicioForm').on('submit', function(e) {
        if ($('.ensayo-checkbox:checked').length === 0) {
            alert('Debe seleccionar al menos un ensayo.');
            e.preventDefault();
        }
    });
});
</script>
