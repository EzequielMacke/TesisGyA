<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Contrato</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-file-contract"></i> Crear Contrato</h2>
                    <small>Complete los datos para generar un nuevo contrato</small>
                </div>
                <a href="{{ route('contrato.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                </a>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('contrato.store') }}" method="POST" id="contratoForm">
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
                                <label for="presupuesto_servicio_id" class="form-label">Presupuesto</label>
                                <select name="presupuesto_servicio_id" id="presupuesto_servicio_id" class="form-select form-select-sm select2" required disabled>
                                    <option value="">Seleccionar Presupuesto</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Información del Presupuesto --}}
                <div class="card" id="info-section" style="display:none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-info-circle me-2"></i>Información del Presupuesto</span>
                    </div>
                    <div class="card-body">
                        <div class="detail-box">
                            <div class="detail-box-title">Datos del Presupuesto Seleccionado</div>
                            <div id="presupuesto-datos"></div>
                        </div>
                    </div>
                </div>

                {{-- Datos del Contrato --}}
                <div id="contrato-section" style="display:none;">
                    <div class="card">
                        <div class="card-header-section">
                            <span><i class="fas fa-clipboard-list me-2"></i>Datos del Contrato</span>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div>
                                    <label for="plazo_dias" class="form-label">Plazo (días)</label>
                                    <input type="number" name="plazo_dias" id="plazo_dias" class="form-control form-control-sm" min="1" required>
                                </div>
                                <div>
                                    <label for="fecha_firma" class="form-label">Fecha de Firma</label>
                                    <input type="date" name="fecha_firma" id="fecha_firma" class="form-control form-control-sm" required>
                                </div>
                                <div>
                                    <label for="fecha_registro_display" class="form-label">Fecha de Registro</label>
                                    <input type="text" id="fecha_registro_display" class="form-control form-control-sm readonly-field" value="{{ date('d/m/Y') }}" readonly>
                                    <input type="hidden" name="fecha_registro" value="{{ date('Y-m-d') }}">
                                </div>
                                <div>
                                    <label for="garantia_meses" class="form-label">Garantía (meses)</label>
                                    <input type="number" name="garantia_meses" id="garantia_meses" class="form-control form-control-sm" min="0" required>
                                </div>
                                <div>
                                    <label for="ciudad" class="form-label">Ciudad</label>
                                    <input type="text" name="ciudad" id="ciudad" class="form-control form-control-sm" required>
                                </div>
                                <div>
                                    <label for="usuario_display" class="form-label">Usuario</label>
                                    <input type="text" id="usuario_display" class="form-control form-control-sm readonly-field" value="{{ session('user_usuario') }}" readonly>
                                    <input type="hidden" name="usuario_id" value="{{ session('user_id') }}">
                                </div>
                                <div class="span-4">
                                    <label for="observaciones" class="form-label">Observaciones</label>
                                    <textarea name="observaciones" id="observaciones" class="form-control form-control-sm" rows="2" placeholder="Ingrese una observación..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Condiciones de Pago --}}
                    <div class="card">
                        <div class="card-header-section">
                            <span><i class="fas fa-money-bill-wave me-2"></i>Condiciones de Pago</span>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div>
                                    <label for="monto_display" class="form-label">Monto Total</label>
                                    <input type="text" id="monto_display" class="form-control form-control-sm readonly-field" readonly>
                                    <input type="hidden" name="monto" id="monto">
                                </div>
                                <div>
                                    <label for="anticipo_display" class="form-label">Anticipo (%)</label>
                                    <input type="text" id="anticipo_display" class="form-control form-control-sm readonly-field" readonly>
                                    <input type="hidden" name="anticipo" id="anticipo">
                                </div>
                                <div>
                                    <label for="pago_mitad" class="form-label">Pago a la Mitad (%)</label>
                                    <input type="number" name="pago_mitad" id="pago_mitad" class="form-control form-control-sm" min="0" max="100" required>
                                </div>
                                <div>
                                    <label for="pago_final" class="form-label">Pago Final (%)</label>
                                    <input type="number" name="pago_final" id="pago_final" class="form-control form-control-sm readonly-field" readonly>
                                </div>
                            </div>

                            <div class="totals-box">
                                <div class="totals-box-title">Resumen de Pagos</div>
                                <div class="totals-row">
                                    <span>Anticipo</span>
                                    <strong id="montoAnticipo">₲ 0</strong>
                                </div>
                                <div class="totals-row">
                                    <span>Pago a la Mitad</span>
                                    <strong id="montoMitad">₲ 0</strong>
                                </div>
                                <div class="totals-row">
                                    <span>Pago Final</span>
                                    <strong id="montoFinal">₲ 0</strong>
                                </div>
                                <div class="totals-row totals-final">
                                    <span>TOTAL</span>
                                    <strong id="montoTotalResumen">₲ 0</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="estado_id" value="3">

                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Guardar Contrato
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
#contratoForm {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
#contrato-section {
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

/* ── Información del presupuesto ── */
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

/* ── Resumen de pagos ── */
.totals-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1rem;
    max-width: 420px;
}
.totals-box-title {
    font-size: 0.7rem;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.5rem;
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
    .totals-box { max-width: 100%; }
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
            fetch(`/obras-por-cliente/${clienteId}`)
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
        resetPresupuesto();
    });

    // Cargar presupuestos al seleccionar obra
    $('#obra_id').on('change', function() {
        const obraId = $(this).val();
        if (obraId) {
            fetch(`/presupuestos-por-obra/${obraId}`)
                .then(response => response.json())
                .then(data => {
                    $('#presupuesto_servicio_id').html('<option value="">Seleccionar Presupuesto</option>');
                    data.forEach(function(presupuesto) {
                        $('#presupuesto_servicio_id').append(`<option value="${presupuesto.id}" data-numero="${presupuesto.numero_presupuesto}" data-monto="${presupuesto.monto}" data-anticipo="${presupuesto.anticipo}">${presupuesto.numero_presupuesto}</option>`);
                    });
                    $('#presupuesto_servicio_id').prop('disabled', false).trigger('change.select2');
                });
        } else {
            resetPresupuesto();
        }
    });

    // Cargar datos del presupuesto seleccionado
    $('#presupuesto_servicio_id').on('change', function() {
        const selected = $(this).find('option:selected');
        const numero = selected.data('numero');
        const monto = selected.data('monto');
        const anticipo = selected.data('anticipo');

        if (monto !== undefined && anticipo !== undefined) {
            $('#presupuesto-datos').html(`
                <div class="detail-row"><i class="fas fa-hashtag"></i><span><strong>N° Presupuesto:</strong> ${numero}</span></div>
                <div class="detail-row"><i class="fas fa-coins"></i><span><strong>Monto Total:</strong> ₲ ${parseFloat(monto).toLocaleString('es-PY')}</span></div>
                <div class="detail-row"><i class="fas fa-percent"></i><span><strong>Anticipo:</strong> ${anticipo}%</span></div>
            `);

            $('#monto').val(monto);
            $('#monto_display').val('₲ ' + parseFloat(monto).toLocaleString('es-PY'));
            $('#anticipo').val(anticipo);
            $('#anticipo_display').val(anticipo + '%');

            $('#info-section, #contrato-section').show();
            calculatePagoFinal();
        } else {
            resetPresupuestoInfo();
        }
    });

    // Recalcular al modificar el pago a la mitad
    $('#pago_mitad').on('input', function() {
        calculatePagoFinal();
    });

    function calculatePagoFinal() {
        const anticipo = parseFloat($('#anticipo').val()) || 0;
        const pagoMitad = parseFloat($('#pago_mitad').val()) || 0;
        let pagoFinal = 100 - anticipo - pagoMitad;

        if (pagoFinal < 0) {
            alert('El pago a la mitad no puede ser mayor que el restante después del anticipo.');
            $('#pago_mitad').val(0);
            pagoFinal = 100 - anticipo;
        }

        $('#pago_final').val(pagoFinal);
        updateResumenPagos();
    }

    function updateResumenPagos() {
        const monto = parseFloat($('#monto').val()) || 0;
        const anticipo = parseFloat($('#anticipo').val()) || 0;
        const pagoMitad = parseFloat($('#pago_mitad').val()) || 0;
        const pagoFinal = parseFloat($('#pago_final').val()) || 0;

        const montoAnticipo = Math.round(monto * anticipo / 100);
        const montoMitad = Math.round(monto * pagoMitad / 100);
        const montoFinal = Math.round(monto * pagoFinal / 100);

        $('#montoAnticipo').text('₲ ' + montoAnticipo.toLocaleString('es-PY'));
        $('#montoMitad').text('₲ ' + montoMitad.toLocaleString('es-PY'));
        $('#montoFinal').text('₲ ' + montoFinal.toLocaleString('es-PY'));
        $('#montoTotalResumen').text('₲ ' + monto.toLocaleString('es-PY'));
    }

    function resetObra() {
        $('#obra_id').html('<option value="">Seleccionar Obra</option>').prop('disabled', true).trigger('change.select2');
    }

    function resetPresupuesto() {
        $('#presupuesto_servicio_id').html('<option value="">Seleccionar Presupuesto</option>').prop('disabled', true).trigger('change.select2');
        resetPresupuestoInfo();
    }

    function resetPresupuestoInfo() {
        $('#info-section, #contrato-section').hide();
        $('#presupuesto-datos').html('');
        $('#monto, #anticipo').val('');
        $('#monto_display, #anticipo_display').val('');
        $('#montoAnticipo, #montoMitad, #montoFinal, #montoTotalResumen').text('₲ 0');
    }

    function validatePercentages() {
        const anticipo = parseFloat($('#anticipo').val()) || 0;
        const pagoMitad = parseFloat($('#pago_mitad').val()) || 0;
        const pagoFinal = parseFloat($('#pago_final').val()) || 0;
        const total = anticipo + pagoMitad + pagoFinal;
        if (total !== 100) {
            alert('La suma de anticipo, pago a la mitad y pago final debe ser exactamente 100%.');
            return false;
        }
        return true;
    }

    $('#contratoForm').on('submit', function(e) {
        if (!validatePercentages()) {
            e.preventDefault();
        }
    });
});
</script>
