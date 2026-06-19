<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Visita Previa</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-clipboard-list"></i> Editar Visita Previa</h2>
                    <small>Modifique los datos de la visita previa</small>
                </div>
                <a href="{{ route('visita_previa.index') }}" class="btn btn-secondary">
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

            <form method="POST" action="{{ route('visita_previa.update', $visita->id) }}" enctype="multipart/form-data" id="visitaForm">
                @csrf
                @method('PUT')

                {{-- Datos de la Visita --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-info-circle me-2"></i>Datos de la Visita</span>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div>
                                <label for="cliente_id" class="form-label">Cliente *</label>
                                <select name="cliente_id" id="cliente_id" class="form-select form-select-sm select2">
                                    <option value="">Seleccione un cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ old('cliente_id', $visita->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->razon_social }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="obra_id" class="form-label">Obra *</label>
                                <select name="obra_id" id="obra_id" class="form-select form-select-sm select2" disabled>
                                    <option value="">Cargando obras...</option>
                                </select>
                            </div>
                            <div>
                                <label for="solicitud_servicio_id" class="form-label">Solicitud de Servicio *</label>
                                <select name="solicitud_servicio_id" id="solicitud_servicio_id" class="form-select form-select-sm select2" disabled>
                                    <option value="">Primero seleccione una obra</option>
                                </select>
                            </div>
                            <div>
                                <label for="fecha_visita" class="form-label">Fecha de Visita *</label>
                                <input type="date" name="fecha_visita" id="fecha_visita" class="form-control form-control-sm" value="{{ old('fecha_visita', \Carbon\Carbon::parse($visita->fecha_visita)->format('Y-m-d')) }}">
                            </div>
                            <div>
                                <label for="metros_cuadrados" class="form-label">Metros Cuadrados *</label>
                                <input type="number" min="0" step="0.01" name="metros_cuadrados" id="metros_cuadrados" class="form-control form-control-sm" value="{{ old('metros_cuadrados', $visita->obra->metros_cuadrados ?? '') }}">
                            </div>
                            <div>
                                <label for="niveles" class="form-label">Niveles de la Obra *</label>
                                <input type="text" name="niveles" id="niveles" class="form-control form-control-sm" value="{{ old('niveles', $visita->obra->niveles ?? '') }}" placeholder="Ej: Planta baja, 1er piso">
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="observacion" class="form-label">Observación</label>
                            <textarea name="observacion" id="observacion" class="form-control form-control-sm" rows="2" placeholder="Ingrese una observación...">{{ old('observacion', $visita->observacion) }}</textarea>
                        </div>

                        <div id="info-solicitud" class="detail-box mt-3" style="display:none;"></div>
                    </div>
                </div>

                {{-- Ensayos --}}
                <div class="card" id="ensayos-card" style="display:none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-flask me-2"></i>Ensayos a Realizar</span>
                    </div>
                    <div class="card-body">
                        <div id="ensayos-list"></div>
                    </div>
                </div>

                {{-- Fotos --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-camera me-2"></i>Fotos de la Visita</span>
                        <span class="results-count" id="fotos-count">0 fotos seleccionadas</span>
                    </div>
                    <div class="card-body">
                        @if($visita->fotos->count() > 0)
                            <div class="mb-3">
                                <label class="form-label">Fotos ya cargadas</label>
                                <div class="file-preview-container existing-files">
                                    @foreach($visita->fotos as $foto)
                                        <a href="{{ Storage::disk('public')->url('visitas_previas/fotos/' . $foto->ruta_foto) }}" target="_blank" class="file-preview" title="Ver imagen">
                                            <img src="{{ Storage::disk('public')->url('visitas_previas/fotos/' . $foto->ruta_foto) }}" alt="Foto">
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="file-upload-section" id="fotos-section">
                            <input type="file" name="fotos[]" id="fotos-input" class="file-input-hidden" multiple>
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                            <p>Arrastra y suelta las fotos aquí o haz clic para seleccionar</p>
                            <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('fotos-input').click()">
                                <i class="fas fa-upload me-2"></i>Agregar Fotos
                            </button>
                            <div class="file-preview-container" id="fotos-preview"></div>
                        </div>
                    </div>
                </div>

                {{-- Planos --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-file-alt me-2"></i>Planos de la Obra</span>
                        <span class="results-count" id="planos-count">0 archivos seleccionados</span>
                    </div>
                    <div class="card-body">
                        @if($visita->planos->count() > 0)
                            <div class="mb-3">
                                <label class="form-label">Planos ya cargados</label>
                                <div class="file-preview-container existing-files">
                                    @foreach($visita->planos as $plano)
                                        <a href="{{ Storage::disk('public')->url('visitas_previas/planos/' . $plano->ruta_plano) }}" target="_blank" class="file-preview" title="Ver archivo">
                                            @if(strtolower(pathinfo($plano->ruta_plano, PATHINFO_EXTENSION)) == 'pdf')
                                                <div style="display:flex; align-items:center; justify-content:center; height:100%; background:#f8fafc;">
                                                    <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                                </div>
                                            @else
                                                <img src="{{ Storage::disk('public')->url('visitas_previas/planos/' . $plano->ruta_plano) }}" alt="Plano">
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="file-upload-section" id="planos-section">
                            <input type="file" name="planos[]" id="planos-input" class="file-input-hidden" multiple>
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                            <p>Arrastra y suelta los planos aquí o haz clic para seleccionar</p>
                            <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('planos-input').click()">
                                <i class="fas fa-upload me-2"></i>Agregar Planos
                            </button>
                            <div class="file-preview-container" id="planos-preview"></div>
                        </div>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <a href="{{ route('visita_previa.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
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
#visitaForm {
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

/* ── Datos de la visita ── */
.form-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
}
.form-grid .form-label,
.card-body > .form-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 500;
    color: #94a3b8;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.detail-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-size: 0.8rem;
    color: #374151;
}
.detail-row { display: flex; align-items: flex-start; gap: 0.4rem; margin-bottom: 0.25rem; }
.detail-row:last-child { margin-bottom: 0; }
.detail-row i { color: #94a3b8; width: 14px; text-align: center; margin-top: 0.15rem; }

.select2-container--bootstrap-5 .select2-selection {
    min-height: calc(1.5em + 0.5rem + 2px);
}

@media (max-width: 900px) {
    .form-grid { grid-template-columns: repeat(2, 1fr); }
    .page-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .form-grid { grid-template-columns: 1fr; }
}

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

/* ── Carga de archivos ── */
.file-upload-section {
    border: 2px dashed #e2e8f0;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    background: #f8fafc;
    transition: all 0.2s ease;
}
.file-upload-section:hover,
.file-upload-section.dragover {
    border-color: #2563eb;
    background: #eff6ff;
}
.file-upload-section i.fa-cloud-upload-alt { color: #94a3b8; }
.file-upload-section p { color: #94a3b8; font-size: 0.85rem; margin-bottom: 0.75rem; }
.file-input-hidden {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}
.file-preview-container {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-top: 1rem;
    justify-content: center;
}
.file-preview-container.existing-files {
    margin-top: 0;
    justify-content: flex-start;
}
.file-preview {
    position: relative;
    width: 100px;
    height: 100px;
    border-radius: 6px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    background: #fff;
    display: block;
}
.file-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.file-preview .file-info {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    background: rgba(0, 0, 0, 0.6);
    color: #fff;
    padding: 2px 4px;
    font-size: 0.65rem;
    text-align: center;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.file-preview .remove-file {
    position: absolute;
    top: 4px; right: 4px;
    background: rgba(220, 53, 69, 0.9);
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 20px; height: 20px;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px;
    cursor: pointer;
}
.file-preview .remove-file:hover { background: rgba(220, 53, 69, 1); }
</style>

@php
    $visitaInit = [
        'obra_id' => $visita->obra_id,
        'obra_descripcion' => $visita->obra->descripcion ?? '',
        'solicitud_id' => $visita->solicitud_servicio_id,
        'solicitud_fecha' => optional($visita->solicitudServicio)->fecha,
        'ensayos_seleccionados' => $ensayosSeleccionados,
    ];
@endphp

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('.select2').select2({ theme: 'bootstrap-5', width: '100%' });

    const visitaInit = @json($visitaInit);

    const $clienteSelect = $('#cliente_id');
    const $obraSelect = $('#obra_id');
    const $solicitudSelect = $('#solicitud_servicio_id');
    const $metrosInput = $('#metros_cuadrados');
    const $nivelesInput = $('#niveles');
    const $infoSolicitud = $('#info-solicitud');
    const $ensayosCard = $('#ensayos-card');
    const $ensayosList = $('#ensayos-list');

    // Cargar obras del cliente seleccionado
    function cargarObras(clienteId, targetObraId, targetSolicitudId) {
        $obraSelect.empty().append('<option value="">Cargando obras...</option>').trigger('change');
        if (!targetObraId) {
            $solicitudSelect.empty().append('<option value="">Primero seleccione una obra</option>').prop('disabled', true).trigger('change');
            $metrosInput.val('');
            $nivelesInput.val('');
            $infoSolicitud.hide().html('');
            $ensayosCard.hide();
            $ensayosList.empty();
        }

        $.getJSON(`/ajax/obras/${clienteId}`, function(obras) {
            obras = obras || [];

            if (targetObraId && !obras.some(o => String(o.id) === String(targetObraId))) {
                obras.push({ id: targetObraId, descripcion: visitaInit.obra_descripcion });
            }

            $obraSelect.empty().append('<option value="">Seleccione una obra</option>');
            if (obras.length > 0) {
                $.each(obras, function(i, obra) {
                    $obraSelect.append(`<option value="${obra.id}">${obra.descripcion}</option>`);
                });
                $obraSelect.prop('disabled', false);
            } else {
                $obraSelect.append('<option value="">No hay obras disponibles</option>');
            }

            if (targetObraId) {
                $obraSelect.val(targetObraId).trigger('change.select2');
                cargarSolicitudes(targetObraId, targetSolicitudId);
            } else {
                $obraSelect.trigger('change');
            }
        }).fail(function() {
            $obraSelect.empty().append('<option value="">Error al cargar obras</option>').trigger('change');
        });
    }

    // Cargar solicitudes pendientes de la obra seleccionada
    function cargarSolicitudes(obraId, targetSolicitudId) {
        $solicitudSelect.empty().append('<option value="">Cargando solicitudes...</option>').trigger('change');
        if (!targetSolicitudId) {
            $metrosInput.val('');
            $nivelesInput.val('');
            $infoSolicitud.hide().html('');
            $ensayosCard.hide();
            $ensayosList.empty();
        }

        $.getJSON(`/ajax/solicitudes/${obraId}`, function(solicitudes) {
            solicitudes = solicitudes || [];

            if (targetSolicitudId && !solicitudes.some(s => String(s.id) === String(targetSolicitudId))) {
                solicitudes.push({ id: targetSolicitudId, fecha: visitaInit.solicitud_fecha });
            }

            $solicitudSelect.empty().append('<option value="">Seleccione una solicitud</option>');
            if (solicitudes.length > 0) {
                $.each(solicitudes, function(i, solicitud) {
                    $solicitudSelect.append(`<option value="${solicitud.id}">#${solicitud.id} - ${solicitud.fecha}</option>`);
                });
                $solicitudSelect.prop('disabled', false);
            } else {
                $solicitudSelect.append('<option value="">No hay solicitudes disponibles</option>');
            }

            if (targetSolicitudId) {
                $solicitudSelect.val(targetSolicitudId).trigger('change.select2');
                cargarDetalleSolicitud(targetSolicitudId);
            } else {
                $solicitudSelect.trigger('change');
            }
        }).fail(function() {
            $solicitudSelect.empty().append('<option value="">Error al cargar solicitudes</option>').trigger('change');
        });
    }

    // Cargar el detalle de la solicitud seleccionada
    function cargarDetalleSolicitud(solicitudId) {
        $infoSolicitud.hide().html('');
        $ensayosCard.hide();
        $ensayosList.empty();

        $.getJSON(`/ajax/solicitud/${solicitudId}`, function(data) {
            let detalleHtml = '';
            if (data.detalle && data.detalle.length > 0) {
                detalleHtml = `<div class="detail-row"><i class="fas fa-list"></i><span><strong>Detalle:</strong> ${data.detalle.join(', ')}</span></div>`;
            }

            $infoSolicitud.html(`
                <div class="detail-row"><i class="fas fa-hashtag"></i><span><strong>Solicitud:</strong> #${data.id} - ${data.fecha}</span></div>
                <div class="detail-row"><i class="fas fa-info-circle"></i><span><strong>Estado:</strong> ${data.estado}</span></div>
                <div class="detail-row"><i class="fas fa-sticky-note"></i><span><strong>Observación:</strong> ${data.observacion ? data.observacion : 'No tiene observación'}</span></div>
                ${detalleHtml}
            `).show();

            $metrosInput.val(data.metros_cuadrados || '');
            $nivelesInput.val(data.niveles || '');

            if (data.servicios && data.servicios.length > 0) {
                cargarEnsayos(data.servicios);
            }
        });
    }

    // Cargar ensayos disponibles según los servicios de la solicitud
    function cargarEnsayos(servicios) {
        $ensayosList.empty();

        $.ajax({
            url: '/ajax/ensayos-por-solicitud',
            type: 'POST',
            data: { servicios: servicios },
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    $.each(response, function(i, servicioData) {
                        const checks = servicioData.ensayos.map(ensayo => {
                            const checked = visitaInit.ensayos_seleccionados.includes(ensayo.id) ? 'checked' : '';
                            return `
                                <label class="servicio-check" for="ensayo-${ensayo.id}">
                                    <input type="checkbox" name="ensayos[]" value="${ensayo.id}" id="ensayo-${ensayo.id}" ${checked}>
                                    <span>${ensayo.descripcion}</span>
                                </label>
                            `;
                        }).join('');

                        $ensayosList.append(`
                            <div class="servicio-group">
                                <h6>${servicioData.servicio}</h6>
                                <div class="servicios-grid">${checks}</div>
                            </div>
                        `);
                    });
                    $ensayosList.find('.servicio-check').each(function () {
                        const $label = $(this);
                        const $checkbox = $label.find('input[type="checkbox"]');
                        const sync = () => $label.toggleClass('checked', $checkbox.is(':checked'));
                        sync();
                        $checkbox.on('change', sync);
                    });
                    $ensayosCard.show();
                } else {
                    $ensayosCard.hide();
                }
            },
            error: function() {
                $ensayosCard.hide();
            }
        });
    }

    $clienteSelect.on('change', function() {
        const clienteId = $(this).val();
        if (clienteId) {
            cargarObras(clienteId);
        } else {
            $obraSelect.empty().append('<option value="">Primero seleccione un cliente</option>').prop('disabled', true).trigger('change');
            $solicitudSelect.empty().append('<option value="">Primero seleccione una obra</option>').prop('disabled', true).trigger('change');
            $metrosInput.val('');
            $nivelesInput.val('');
            $infoSolicitud.hide().html('');
            $ensayosCard.hide();
            $ensayosList.empty();
        }
    });

    $obraSelect.on('change', function() {
        const obraId = $(this).val();
        if (obraId) {
            cargarSolicitudes(obraId);
        } else {
            $solicitudSelect.empty().append('<option value="">Primero seleccione una obra</option>').prop('disabled', true).trigger('change');
            $metrosInput.val('');
            $nivelesInput.val('');
            $infoSolicitud.hide().html('');
            $ensayosCard.hide();
            $ensayosList.empty();
        }
    });

    $solicitudSelect.on('change', function() {
        const solicitudId = $(this).val();
        if (solicitudId) {
            cargarDetalleSolicitud(solicitudId);
        } else {
            $infoSolicitud.hide().html('');
            $ensayosCard.hide();
            $ensayosList.empty();
        }
    });

    // Carga inicial: precargar obra y solicitud actuales de la visita
    const clienteInicial = $clienteSelect.val();
    if (clienteInicial) {
        cargarObras(clienteInicial, visitaInit.obra_id, visitaInit.solicitud_id);
    }

    // Manejo de archivos: vista previa, contador y eliminación
    function handleFileSelect(event, type) {
        const files = event.target.files;
        const previewContainer = $(`#${type}-preview`);
        const countElement = $(`#${type}-count`);

        previewContainer.empty();

        const label = type === 'fotos' ? 'fotos' : 'archivos';
        countElement.text(`${files.length} ${label} seleccionado${files.length === 1 ? '' : 's'}`);

        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = $('<div class="file-preview"></div>');
                const removeBtn = $('<button type="button" class="remove-file" title="Remover">&times;</button>');

                removeBtn.on('click', function() {
                    const dt = new DataTransfer();
                    const input = $(`#${type}-input`)[0];
                    const filesArray = Array.from(input.files);
                    filesArray.splice(index, 1);
                    filesArray.forEach(f => dt.items.add(f));
                    input.files = dt.files;
                    handleFileSelect({target: input}, type);
                });

                if (file.type.startsWith('image/')) {
                    previewDiv.append(`<img src="${e.target.result}" alt="${file.name}">`);
                } else {
                    previewDiv.append(`
                        <div style="display: flex; align-items: center; justify-content: center; height: 100%; background: #f8fafc;">
                            <i class="fas fa-file-alt fa-2x text-muted"></i>
                        </div>
                    `);
                }

                previewDiv.append(`<div class="file-info">${file.name}</div>`);
                previewDiv.append(removeBtn);
                previewContainer.append(previewDiv);
            };
            reader.readAsDataURL(file);
        });
    }

    function setupDragAndDrop(sectionId, inputId, type) {
        const section = $(`#${sectionId}`);
        const input = $(`#${inputId}`);

        section.on('dragover dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();
            section.addClass('dragover');
        });

        section.on('dragleave dragend', function(e) {
            e.preventDefault();
            e.stopPropagation();
            section.removeClass('dragover');
        });

        section.on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            section.removeClass('dragover');

            const files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                const dt = new DataTransfer();
                Array.from(input[0].files).forEach(f => dt.items.add(f));
                Array.from(files).forEach(f => dt.items.add(f));
                input[0].files = dt.files;
                handleFileSelect({target: input[0]}, type);
            }
        });
    }

    setupDragAndDrop('fotos-section', 'fotos-input', 'fotos');
    setupDragAndDrop('planos-section', 'planos-input', 'planos');

    $('#fotos-input').on('change', function(e) { handleFileSelect(e, 'fotos'); });
    $('#planos-input').on('change', function(e) { handleFileSelect(e, 'planos'); });
});
</script>
