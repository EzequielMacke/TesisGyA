<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Orden de Servicio</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-tasks"></i> Editar Orden de Servicio Nro {{ $ordenServicio->nro }}</h2>
                    <small>Solo se pueden modificar los funcionarios asignados y la observación</small>
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

            <form action="{{ route('orden_servicio.update', $ordenServicio->id) }}" method="POST" id="ordenServicioForm">
                @csrf
                @method('PUT')

                {{-- Información del Contrato --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-info-circle me-2"></i>Información del Contrato</span>
                    </div>
                    <div class="card-body">
                        <div class="detail-box">
                            <div class="detail-box-title">Datos del Contrato</div>
                            <div class="detail-row"><i class="fas fa-building"></i><span><strong>Cliente:</strong> {{ $ordenServicio->cliente->razon_social ?? '-' }}</span></div>
                            <div class="detail-row"><i class="fas fa-map-marker-alt"></i><span><strong>Obra:</strong> {{ $ordenServicio->obra->descripcion ?? '-' }}</span></div>
                            <div class="detail-row"><i class="fas fa-hashtag"></i><span><strong>N° Contrato:</strong> {{ str_pad($ordenServicio->contrato_id, 3, '0', STR_PAD_LEFT) }}</span></div>
                            <div class="detail-row"><i class="fas fa-file-invoice-dollar"></i><span><strong>N° Presupuesto:</strong> {{ $ordenServicio->contrato->presupuestoServicio->numero_presupuesto ?? '-' }}</span></div>
                            <div class="detail-row"><i class="fas fa-coins"></i><span><strong>Monto:</strong> ₲ {{ number_format($ordenServicio->contrato->monto ?? 0, 0, ',', '.') }}</span></div>
                            <div class="detail-row"><i class="fas fa-calendar-check"></i><span><strong>Fecha de Firma:</strong> {{ $ordenServicio->contrato->fecha_firma ? $ordenServicio->contrato->fecha_firma->format('d/m/Y') : '-' }}</span></div>
                            <div class="detail-row"><i class="fas fa-clock"></i><span><strong>Plazo:</strong> {{ $ordenServicio->contrato->plazo_dias ?? '-' }} días</span></div>
                        </div>
                    </div>
                </div>

                {{-- Ensayos del Presupuesto --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-flask me-2"></i>Ensayos del Presupuesto</span>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3" style="font-size:0.8rem;">Estos son los ensayos incluidos en el presupuesto y no pueden modificarse desde esta orden de servicio.</p>
                        @foreach($ensayosPorServicio as $servicio)
                            <div class="servicio-group">
                                <h6>{{ $servicio['servicio'] }}</h6>
                                <div class="servicios-grid">
                                    @foreach($servicio['ensayos'] as $ensayo)
                                        <div class="servicio-check servicio-check-readonly">
                                            <i class="fas fa-check-circle text-success"></i>
                                            <span>{{ $ensayo['descripcion'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Funcionarios --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-users me-2"></i>Funcionarios Asignados</span>
                    </div>
                    <div class="card-body">
                        <select name="funcionarios[]" id="funcionarios" class="form-select form-select-sm select2" multiple required>
                            @foreach($funcionarios as $funcionario)
                                <option value="{{ $funcionario->id }}" {{ in_array($funcionario->id, $funcionariosSeleccionados) ? 'selected' : '' }}>
                                    {{ $funcionario->persona->nombre ?? '' }} {{ $funcionario->persona->apellido ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Datos de la Orden de Servicio --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-clipboard-list me-2"></i>Datos de la Orden de Servicio</span>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div>
                                <label for="nro_display" class="form-label">Nro</label>
                                <input type="text" id="nro_display" class="form-control form-control-sm readonly-field" value="{{ $ordenServicio->nro }}" readonly>
                            </div>
                            <div>
                                <label for="fecha_registro_display" class="form-label">Fecha de Registro</label>
                                <input type="text" id="fecha_registro_display" class="form-control form-control-sm readonly-field" value="{{ $ordenServicio->fecha_registro->format('d/m/Y') }}" readonly>
                            </div>
                            <div>
                                <label for="fecha_culminacion_display" class="form-label">Fecha de Culminación Teórica</label>
                                <input type="text" id="fecha_culminacion_display" class="form-control form-control-sm readonly-field" value="{{ $ordenServicio->fecha_culminacion_teorica->format('d/m/Y') }}" readonly>
                            </div>
                            <div>
                                <label for="usuario_display" class="form-label">Usuario</label>
                                <input type="text" id="usuario_display" class="form-control form-control-sm readonly-field" value="{{ $ordenServicio->usuario->usuario ?? '-' }}" readonly>
                            </div>
                            <div class="span-4">
                                <label for="observacion" class="form-label">Observación</label>
                                <textarea name="observacion" id="observacion" class="form-control form-control-sm" rows="2" placeholder="Ingrese una observación...">{{ $ordenServicio->observacion }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Actualizar Orden de Servicio
                    </button>
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
.form-grid .form-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 500;
    color: #94a3b8;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
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
}
.servicio-check-readonly { cursor: default; }
.servicio-check-readonly i { color: #22c55e; }

.select2-container--bootstrap-5 .select2-selection {
    min-height: calc(1.5em + 0.5rem + 2px);
}

@media (max-width: 900px) {
    .form-grid { grid-template-columns: repeat(2, 1fr); }
    .page-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .form-grid { grid-template-columns: 1fr; }
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
});
</script>
