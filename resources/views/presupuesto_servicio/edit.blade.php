<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Presupuesto de Servicio</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-file-invoice-dollar"></i> Editar Presupuesto de Servicio</h2>
                    <small>Modifique los datos del presupuesto {{ $presupuesto->numero_presupuesto }}</small>
                </div>
                <a href="{{ route('presupuesto_servicio.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                </a>
            </div>

            {{-- Alerts --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('presupuesto_servicio.update', $presupuesto->id) }}" method="POST" id="presupuestoForm">
                @csrf
                @method('PUT')

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
                                    <option value="">Seleccione un cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ old('cliente_id', $presupuesto->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->razon_social }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="obra_id" class="form-label">Obra</label>
                                <select name="obra_id" id="obra_id" class="form-select form-select-sm select2" required disabled>
                                    <option value="">Cargando obras...</option>
                                </select>
                            </div>
                            <div>
                                <label for="visita_previa_id" class="form-label">Visita Previa</label>
                                <select name="visita_previa_id" id="visita_previa_id" class="form-select form-select-sm select2" required disabled>
                                    <option value="">Cargando visitas...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Información de la Visita --}}
                <div class="card" id="info-section" style="display:none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-info-circle me-2"></i>Información de la Visita</span>
                    </div>
                    <div class="card-body">
                        <div class="form-grid form-grid-3 mb-3">
                            <div class="detail-box">
                                <div class="detail-box-title">Datos del Cliente</div>
                                <div id="cliente-datos"></div>
                            </div>
                            <div class="detail-box">
                                <div class="detail-box-title">Datos de la Obra</div>
                                <div id="obra-datos"></div>
                            </div>
                            <div class="detail-box">
                                <div class="detail-box-title">Datos de la Visita Previa</div>
                                <div id="visita-datos"></div>
                            </div>
                        </div>

                        <div class="info-grid-2">
                            <div>
                                <h6 class="subsection-title"><i class="fas fa-camera me-2"></i>Fotos de la Visita</h6>
                                <div class="file-gallery" id="fotos-visita"></div>
                            </div>
                            <div>
                                <h6 class="subsection-title"><i class="fas fa-file-alt me-2"></i>Planos de la Obra</h6>
                                <div class="file-gallery" id="planos-visita"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="presupuesto-section" style="display:none;">

                    {{-- Seleccionar Ensayos --}}
                    <div class="card">
                        <div class="card-header-section">
                            <span><i class="fas fa-flask me-2"></i>Seleccionar Ensayos</span>
                        </div>
                        <div class="card-body">
                            <div class="ensayos-grid">
                                <div>
                                    <h6 class="subsection-title">Ensayos Disponibles</h6>
                                    <div id="ensayos-disponibles"></div>
                                </div>
                                <div>
                                    <h6 class="subsection-title">Ensayos Seleccionados</h6>
                                    <div class="selected-ensayos" id="ensayos-seleccionados">
                                        <span class="text-muted" style="font-size:0.8rem;">Ningún ensayo seleccionado</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Detalles del Presupuesto --}}
                    <div class="card">
                        <div class="card-header-section">
                            <span><i class="fas fa-clipboard-list me-2"></i>Detalles del Presupuesto</span>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div>
                                    <label for="validez" class="form-label">Validez (días)</label>
                                    <input type="number" name="validez" id="validez" class="form-control form-control-sm" min="1" value="{{ old('validez', $presupuesto->validez) }}" required>
                                </div>
                                <div>
                                    <label for="anticipo" class="form-label">Anticipo (%)</label>
                                    <input type="number" name="anticipo" id="anticipo" class="form-control form-control-sm" min="0" max="100" value="{{ old('anticipo', $presupuesto->anticipo) }}" required>
                                </div>
                                <div>
                                    <label for="monto_anticipo" class="form-label">Monto Anticipo</label>
                                    <input type="text" id="monto_anticipo" class="form-control form-control-sm readonly-field" readonly>
                                </div>
                                <div>
                                    <label for="fecha" class="form-label">Fecha</label>
                                    <input type="date" name="fecha" id="fecha" class="form-control form-control-sm readonly-field" value="{{ old('fecha', \Carbon\Carbon::parse($presupuesto->fecha)->format('Y-m-d')) }}" readonly>
                                </div>
                                <div class="span-2">
                                    <label for="numero_presupuesto" class="form-label">N° Presupuesto</label>
                                    <input type="text" id="numero_presupuesto" class="form-control form-control-sm readonly-field" value="{{ $presupuesto->numero_presupuesto }}" readonly>
                                </div>
                                <div class="span-2">
                                    <label for="usuario" class="form-label">Usuario</label>
                                    <input type="text" name="usuario" id="usuario" class="form-control form-control-sm readonly-field" value="{{ $presupuesto->usuario->usuario ?? session('user_usuario') }}" readonly>
                                </div>
                                <div class="span-4">
                                    <label for="observacion" class="form-label">Observación</label>
                                    <textarea name="observacion" id="observacion" class="form-control form-control-sm" rows="2" placeholder="Ingrese una observación...">{{ old('observacion', $presupuesto->observacion) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Precios, Cantidades e Impuestos --}}
                    <div class="card">
                        <div class="card-header-section">
                            <span><i class="fas fa-calculator me-2"></i>Precios, Cantidades e Impuestos</span>
                        </div>
                        <div class="card-body">
                            <div id="precios-cantidades">
                                <p class="text-muted mb-0" style="font-size:0.85rem;">Seleccione los ensayos a presupuestar para configurar precios, cantidades e impuestos.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Resumen --}}
                    <div class="card">
                        <div class="card-header-section">
                            <span><i class="fas fa-chart-line me-2"></i>Resumen del Presupuesto</span>
                        </div>
                        <div class="card-body">
                            <div class="totals-grid">
                                <div class="totals-box">
                                    <div class="totals-box-title">Desglose por Servicio</div>
                                    <div id="resumenServicios">
                                        <p class="text-muted mb-0" style="font-size:0.8rem;">Seleccione ensayos y complete los precios.</p>
                                    </div>
                                </div>
                                <div class="totals-box">
                                    <div class="totals-box-title">Totales</div>
                                    <div id="resumenImpuestos"></div>
                                    <div class="totals-row">
                                        <span>Total Servicios</span>
                                        <strong id="totalEnsayos">₲ 0</strong>
                                    </div>
                                    <div class="totals-row">
                                        <span>Total Impuestos</span>
                                        <strong id="totalImpuestosGeneral">₲ 0</strong>
                                    </div>
                                    <div class="totals-row totals-final">
                                        <span>TOTAL GENERAL</span>
                                        <strong id="totalGeneral">₲ 0</strong>
                                    </div>
                                    <div class="totals-row">
                                        <span id="anticipoLabel">Anticipo (0%)</span>
                                        <strong id="montoAnticipoResumen">₲ 0</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Guardar Cambios
                                </button>
                            </div>
                        </div>
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
#presupuestoForm {
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

.subsection-title {
    font-size: 0.78rem;
    font-weight: 600;
    color: #2563eb;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.5rem;
}

/* ── Información de la visita ── */
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

.info-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
.ensayos-grid {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    gap: 1rem;
}

/* ── Galería de archivos ── */
.file-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 0.6rem;
}
.file-item {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
}
.file-item img,
.file-item .file-placeholder {
    width: 100%;
    height: 100px;
    object-fit: cover;
    display: block;
}
.file-item .file-placeholder {
    display: flex; align-items: center; justify-content: center;
    background: #f8fafc; color: #cbd5e1; font-size: 1.6rem;
}
.file-item .file-info { padding: 0.4rem 0.5rem; font-size: 0.72rem; color: #64748b; }
.file-item .file-info a { color: #2563eb; text-decoration: none; font-weight: 600; }
.file-item .file-info a:hover { text-decoration: underline; }
.file-item .file-info small { display: block; color: #94a3b8; margin-top: 2px; }

/* ── Ensayos ── */
.servicio-group { margin-bottom: 1rem; }
.servicio-group:last-child { margin-bottom: 0; }
.servicio-group h6 {
    font-size: 0.78rem;
    font-weight: 600;
    color: #2563eb;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.5rem;
}
.servicios-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 0.6rem;
}
.servicio-check {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-size: 0.85rem;
    color: #374151;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s;
    margin-bottom: 0;
}
.servicio-check:hover { background: #eff6ff; border-color: #bfdbfe; }
.servicio-check.checked { background: #eff6ff; border-color: #2563eb; color: #1e293b; }
.servicio-check input { margin: 0; cursor: pointer; }

.selected-ensayos {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.6rem 0.75rem;
    min-height: 80px;
    display: flex;
    flex-wrap: wrap;
    align-content: flex-start;
    gap: 0.4rem;
}

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

/* ── Precios y cantidades ── */
.precio-servicio-block { border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; }
.precio-servicio-block + .precio-servicio-block { margin-top: 1rem; }
.precio-servicio-header {
    padding: 0.6rem 1rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    font-weight: 600;
    font-size: 0.8rem;
    color: #2563eb;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
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
.precio-input, .cantidad-input { text-align: center; font-weight: 600; }
.amount { font-weight: 700; color: #10b981; }

/* ── Totales ── */
.totals-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
.totals-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
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
    .ensayos-grid, .info-grid-2 { grid-template-columns: 1fr; }
    .page-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .form-grid, .form-grid.form-grid-3 { grid-template-columns: 1fr; }
    .form-grid .span-2,
    .form-grid .span-4 { grid-column: span 1; }
}
@media (max-width: 768px) {
    .table-container { font-size: 0.875rem; }
    .totals-grid { grid-template-columns: 1fr; }
}
</style>

@php
    $presupuestoInit = [
        'cliente_id' => $presupuesto->cliente_id,
        'obra_id' => $presupuesto->obra_id,
        'obra_descripcion' => $presupuesto->obra->descripcion ?? '',
        'visita_previa_id' => $presupuesto->visita_previa_id,
        'visita_fecha' => optional($presupuesto->visitaPrevia)->fecha_visita,
        'visita_estado' => optional(optional($presupuesto->visitaPrevia)->estado)->descripcion,
        'ensayos_seleccionados' => $ensayosSeleccionados,
        'detalles' => $detallesPresupuesto,
    ];
@endphp

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Seleccione una opción",
        allowClear: true,
        theme: 'bootstrap-5',
        width: '100%'
    });

    const presupuestoInit = @json($presupuestoInit);
    let esVisitaOriginal = true;

    const impuestoOptions = `
        <option value="">Seleccione</option>
        @foreach(\App\Models\Impuesto::where('estado_id', 1)->get() as $impuesto)
            <option value="{{ $impuesto->id }}">{{ $impuesto->descripcion }}</option>
        @endforeach
    `;

    // Cargar obras del cliente seleccionado
    function cargarObras(clienteId, targetObraId) {
        $('#obra_id').html('<option value="">Cargando obras...</option>').trigger('change.select2');

        $.get('/ajax/presupuesto/obras/' + clienteId, function(data) {
            data = data || [];

            if (targetObraId && !data.some(o => String(o.id) === String(targetObraId))) {
                data.push({ id: targetObraId, descripcion: presupuestoInit.obra_descripcion });
            }

            $('#obra_id').html('<option value="">Seleccione una obra</option>');
            data.forEach(function(obra) {
                $('#obra_id').append('<option value="' + obra.id + '">' + obra.descripcion + '</option>');
            });
            $('#obra_id').prop('disabled', false);

            if (targetObraId) {
                $('#obra_id').val(targetObraId).trigger('change.select2');
                cargarVisitas(targetObraId, presupuestoInit.visita_previa_id);
            } else {
                $('#obra_id').trigger('change.select2');
            }
        });
    }

    // Cargar visitas previas pendientes de la obra seleccionada
    function cargarVisitas(obraId, targetVisitaId) {
        $('#visita_previa_id').html('<option value="">Cargando visitas...</option>').trigger('change.select2');

        $.get('/ajax/visitas-previas/' + obraId, function(data) {
            data = data || [];

            if (targetVisitaId && !data.some(v => String(v.id) === String(targetVisitaId))) {
                data.push({ id: targetVisitaId, fecha: presupuestoInit.visita_fecha, estado: presupuestoInit.visita_estado });
            }

            $('#visita_previa_id').html('<option value="">Seleccione una visita previa</option>');
            data.forEach(function(visita) {
                $('#visita_previa_id').append('<option value="' + visita.id + '">' + visita.fecha + ' - ' + visita.estado + '</option>');
            });
            $('#visita_previa_id').prop('disabled', false);

            if (targetVisitaId) {
                esVisitaOriginal = String(targetVisitaId) === String(presupuestoInit.visita_previa_id);
                $('#visita_previa_id').val(targetVisitaId).trigger('change.select2');
                cargarDatosVisita(targetVisitaId);
            } else {
                $('#visita_previa_id').trigger('change.select2');
            }
        });
    }

    // Cargar información, fotos, planos y ensayos disponibles de la visita seleccionada
    function cargarDatosVisita(visitaId) {
        $.get('/ajax/visita-previa/' + visitaId, function(data) {
            $('#cliente-datos').html(`
                <div class="detail-row"><i class="fas fa-building"></i><span><strong>Razón Social:</strong> ${data.cliente.razon_social}</span></div>
                <div class="detail-row"><i class="fas fa-id-card"></i><span><strong>RUC:</strong> ${data.cliente.ruc}</span></div>
                <div class="detail-row"><i class="fas fa-map-marker-alt"></i><span><strong>Dirección:</strong> ${data.cliente.direccion}</span></div>
            `);
            $('#obra-datos').html(`
                <div class="detail-row"><i class="fas fa-building"></i><span><strong>Descripción:</strong> ${data.obra.descripcion}</span></div>
                <div class="detail-row"><i class="fas fa-map-marker-alt"></i><span><strong>Ubicación:</strong> ${data.obra.ubicacion}</span></div>
                <div class="detail-row"><i class="fas fa-ruler-combined"></i><span><strong>Metros Cuadrados:</strong> ${data.obra.metros_cuadrados}</span></div>
            `);
            $('#visita-datos').html(`
                <div class="detail-row"><i class="fas fa-calendar"></i><span><strong>Fecha:</strong> ${data.fecha_visita}</span></div>
                <div class="detail-row"><i class="fas fa-info-circle"></i><span><strong>Estado:</strong> ${data.estado.descripcion}</span></div>
                <div class="detail-row"><i class="fas fa-sticky-note"></i><span><strong>Observación:</strong> ${data.observacion || 'Sin observación'}</span></div>
            `);

            // Fotos
            $('#fotos-visita').html('');
            if (data.fotos && data.fotos.length > 0) {
                data.fotos.forEach(function(foto) {
                    $('#fotos-visita').append(`
                        <div class="file-item">
                            <img src="/storage/visitas_previas/fotos/${foto.ruta_foto}" alt="Foto">
                            <div class="file-info">
                                <a href="/storage/visitas_previas/fotos/${foto.ruta_foto}" target="_blank">Ver imagen</a>
                                <small>${foto.fecha}</small>
                            </div>
                        </div>
                    `);
                });
            } else {
                $('#fotos-visita').html('<p class="text-muted mb-0" style="font-size:0.8rem;">No hay fotos disponibles.</p>');
            }

            // Planos
            $('#planos-visita').html('');
            if (data.planos && data.planos.length > 0) {
                data.planos.forEach(function(plano) {
                    var isPdf = plano.ruta_plano.toLowerCase().endsWith('.pdf');
                    $('#planos-visita').append(`
                        <div class="file-item">
                            ${isPdf
                                ? `<div class="file-placeholder"><i class="fas fa-file-pdf"></i></div>`
                                : `<img src="/storage/visitas_previas/planos/${plano.ruta_plano}" alt="Plano">`}
                            <div class="file-info">
                                <a href="/storage/visitas_previas/planos/${plano.ruta_plano}" target="_blank">Ver archivo</a>
                                <small>${plano.fecha}</small>
                            </div>
                        </div>
                    `);
                });
            } else {
                $('#planos-visita').html('<p class="text-muted mb-0" style="font-size:0.8rem;">No hay planos disponibles.</p>');
            }

            $('#info-section').show();
        });

        // Cargar ensayos disponibles
        $.get('/ajax/ensayos-por-visita/' + visitaId, function(data) {
            window.ensayosData = data;
            $('#ensayos-disponibles').html('');
            data.forEach(function(servicio) {
                if (!servicio.ensayos || servicio.ensayos.length === 0) return;
                const checks = servicio.ensayos.map(ensayo => {
                    const checked = esVisitaOriginal
                        ? presupuestoInit.ensayos_seleccionados.includes(ensayo.id)
                        : ensayo.checked;
                    return `
                        <label class="servicio-check">
                            <input type="checkbox" class="ensayo-checkbox" value="${ensayo.id}" id="ensayo-${ensayo.id}" data-descripcion="${ensayo.descripcion}" ${checked ? 'checked' : ''}>
                            <span>${ensayo.descripcion}</span>
                        </label>
                    `;
                }).join('');
                $('#ensayos-disponibles').append(`
                    <div class="servicio-group">
                        <h6>${servicio.servicio}</h6>
                        <div class="servicios-grid">${checks}</div>
                    </div>
                `);
            });

            $('#ensayos-disponibles .servicio-check').each(function() {
                const $label = $(this);
                const $checkbox = $label.find('input[type="checkbox"]');
                const sync = () => $label.toggleClass('checked', $checkbox.is(':checked'));
                sync();
                $checkbox.on('change', sync);
            });

            $('#presupuesto-section').show();
            updateSelectedEnsayos();
        });
    }

    $('#cliente_id').on('change', function() {
        var clienteId = $(this).val();
        if (clienteId) {
            cargarObras(clienteId);
        } else {
            resetForm();
        }
    });

    $('#obra_id').on('change', function() {
        var obraId = $(this).val();
        if (obraId) {
            cargarVisitas(obraId);
        } else {
            resetForm();
        }
    });

    $('#visita_previa_id').on('change', function() {
        var visitaId = $(this).val();
        if (visitaId) {
            esVisitaOriginal = String(visitaId) === String(presupuestoInit.visita_previa_id);
            cargarDatosVisita(visitaId);
        } else {
            resetForm();
        }
    });

    // Actualizar lista de ensayos seleccionados
    $(document).on('change', '.ensayo-checkbox', function() {
        updateSelectedEnsayos();
    });

    $(document).on('input change', '.precio-input, .cantidad-input, .impuesto-select', function() {
        updateTotals();
    });

    $(document).on('input', '#anticipo', function() {
        updateTotals();
    });

    function updateSelectedEnsayos() {
        var html = '';
        $('.ensayo-checkbox:checked').each(function() {
            html += `<span class="tag tag-secondary">${$(this).data('descripcion')}</span>`;
        });
        $('#ensayos-seleccionados').html(html || '<span class="text-muted" style="font-size:0.8rem;">Ningún ensayo seleccionado</span>');
        updatePreciosCantidades();
    }

    function updatePreciosCantidades() {
        var selected = [];
        $('.ensayo-checkbox:checked').each(function() {
            selected.push($(this).val());
        });

        $('#precios-cantidades').empty();

        if (selected.length === 0) {
            $('#precios-cantidades').html('<p class="text-muted mb-0" style="font-size:0.85rem;">Seleccione los ensayos a presupuestar para configurar precios, cantidades e impuestos.</p>');
            updateTotals();
            return;
        }

        (window.ensayosData || []).forEach(function(servicio) {
            var selectedEnsayos = servicio.ensayos.filter(ensayo => selected.includes(ensayo.id.toString()));
            if (selectedEnsayos.length === 0) return;

            var rows = selectedEnsayos.map(ensayo => {
                var detalle = esVisitaOriginal ? presupuestoInit.detalles[ensayo.id] : null;
                var precioVal = detalle ? detalle.precio_unitario : '';
                var cantidadVal = detalle ? detalle.cantidad : '';
                return `
                    <tr class="ensayo-item" data-id="${ensayo.id}" data-impuesto="${detalle ? detalle.impuesto_id : ''}">
                        <td>${ensayo.descripcion}<input type="hidden" name="ensayos[]" value="${ensayo.id}"></td>
                        <td><input type="number" class="form-control form-control-sm precio-input" name="precios[${ensayo.id}]" step="0.01" min="0" value="${precioVal}" required></td>
                        <td><input type="number" class="form-control form-control-sm cantidad-input" name="cantidades[${ensayo.id}]" min="1" value="${cantidadVal}" required></td>
                        <td><select name="impuestos[${ensayo.id}]" class="form-select form-select-sm impuesto-select" required>${impuestoOptions}</select></td>
                        <td class="text-center"><span class="iva-monto">₲ 0</span></td>
                        <td class="text-center"><span class="amount subtotal">₲ 0</span></td>
                    </tr>
                `;
            }).join('');

            $('#precios-cantidades').append(`
                <div class="precio-servicio-block">
                    <div class="precio-servicio-header">${servicio.servicio}</div>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Ensayo</th>
                                    <th style="width:140px;">Precio</th>
                                    <th style="width:110px;" class="text-center">Cantidad</th>
                                    <th style="width:170px;">Impuesto</th>
                                    <th style="width:120px;" class="text-center">IVA</th>
                                    <th style="width:130px;" class="text-center">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>${rows}</tbody>
                        </table>
                    </div>
                </div>
            `);
        });

        // Preseleccionar el impuesto guardado (o el 10% por defecto) en cada fila
        $('.ensayo-item').each(function() {
            var impuestoGuardado = $(this).data('impuesto');
            var $select = $(this).find('.impuesto-select');
            $select.val(impuestoGuardado ? impuestoGuardado : 2);
        });

        updateTotals();
    }

    function updateTotals() {
        var subtotalesServicio = {};
        var impuestosPorTipo = {};
        var totalEnsayos = 0;
        var totalImpuestos = 0;

        $('.ensayo-item').each(function() {
            var id = $(this).data('id');
            var precio = parseFloat($(this).find('.precio-input').val()) || 0;
            var cantidad = parseInt($(this).find('.cantidad-input').val()) || 0;
            var subtotal = Math.round(precio * cantidad);

            var $impuestoSelect = $(this).find('.impuesto-select');
            var impuestoId = $impuestoSelect.val();
            var impuestoNombre = $impuestoSelect.find('option:selected').text();
            var impuestoMonto = 0;
            if (impuestoId == 2) {
                impuestoMonto = Math.round(subtotal / 11);
            } else if (impuestoId == 3) {
                impuestoMonto = Math.round(subtotal / 21);
            }

            $(this).find('.iva-monto').text('₲ ' + impuestoMonto.toLocaleString('es-PY'));
            $(this).find('.subtotal').text('₲ ' + subtotal.toLocaleString('es-PY'));

            var servicioNombre = '';
            (window.ensayosData || []).forEach(function(servicio) {
                servicio.ensayos.forEach(function(ensayo) {
                    if (ensayo.id == id) servicioNombre = servicio.servicio;
                });
            });

            subtotalesServicio[servicioNombre] = (subtotalesServicio[servicioNombre] || 0) + subtotal;
            totalEnsayos += subtotal;
            totalImpuestos += impuestoMonto;

            if (impuestoMonto > 0) {
                impuestosPorTipo[impuestoNombre] = (impuestosPorTipo[impuestoNombre] || 0) + impuestoMonto;
            }
        });

        var totalGeneral = totalEnsayos + totalImpuestos;
        var anticipoPorc = parseFloat($('#anticipo').val()) || 0;
        var montoAnticipo = Math.round(totalGeneral * anticipoPorc / 100);

        $('#monto_anticipo').val('₲ ' + montoAnticipo.toLocaleString('es-PY'));
        $('#anticipoLabel').text('Anticipo (' + anticipoPorc + '%)');
        $('#montoAnticipoResumen').text('₲ ' + montoAnticipo.toLocaleString('es-PY'));

        var resumenServiciosHtml = '';
        for (var servicio in subtotalesServicio) {
            resumenServiciosHtml += `<div class="totals-row"><span>${servicio}</span><strong>₲ ${subtotalesServicio[servicio].toLocaleString('es-PY')}</strong></div>`;
        }
        $('#resumenServicios').html(resumenServiciosHtml || '<p class="text-muted mb-0" style="font-size:0.8rem;">Seleccione ensayos y complete los precios.</p>');

        var resumenImpuestosHtml = '';
        for (var tipo in impuestosPorTipo) {
            resumenImpuestosHtml += `<div class="totals-row"><span>IVA ${tipo}</span><strong>₲ ${impuestosPorTipo[tipo].toLocaleString('es-PY')}</strong></div>`;
        }
        $('#resumenImpuestos').html(resumenImpuestosHtml);

        $('#totalEnsayos').text('₲ ' + totalEnsayos.toLocaleString('es-PY'));
        $('#totalImpuestosGeneral').text('₲ ' + totalImpuestos.toLocaleString('es-PY'));
        $('#totalGeneral').text('₲ ' + totalGeneral.toLocaleString('es-PY'));
    }

    function resetForm() {
        $('#obra_id').html('<option value="">Seleccione una obra</option>').prop('disabled', true).trigger('change.select2');
        $('#visita_previa_id').html('<option value="">Seleccione una visita previa</option>').prop('disabled', true).trigger('change.select2');
        $('#info-section, #presupuesto-section').hide();
        $('#cliente-datos, #obra-datos, #visita-datos, #fotos-visita, #planos-visita, #ensayos-disponibles').html('');
        $('#ensayos-seleccionados').html('<span class="text-muted" style="font-size:0.8rem;">Ningún ensayo seleccionado</span>');
        $('#precios-cantidades').html('<p class="text-muted mb-0" style="font-size:0.85rem;">Seleccione los ensayos a presupuestar para configurar precios, cantidades e impuestos.</p>');
        $('#resumenServicios').html('<p class="text-muted mb-0" style="font-size:0.8rem;">Seleccione ensayos y complete los precios.</p>');
        $('#resumenImpuestos').html('');
        $('#monto_anticipo').val('');
        $('#anticipoLabel').text('Anticipo (0%)');
        $('#totalEnsayos, #totalImpuestosGeneral, #totalGeneral, #montoAnticipoResumen').text('₲ 0');
    }

    // Carga inicial: precargar cliente, obra, visita y ensayos del presupuesto
    if (presupuestoInit.cliente_id) {
        cargarObras(presupuestoInit.cliente_id, presupuestoInit.obra_id);
    }
});
</script>
