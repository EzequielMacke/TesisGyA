<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Reclamo</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-exclamation-circle"></i> Registrar Reclamo</h2>
                    <small>Complete los datos para registrar un nuevo reclamo del cliente</small>
                </div>
                <a href="{{ route('reclamos.index') }}" class="btn btn-secondary">
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
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('reclamos.store') }}" enctype="multipart/form-data" id="reclamoForm">
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
                                <select id="cliente_id" class="form-select form-select-sm select2">
                                    <option value="">Seleccionar Cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->razon_social }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="obra_id" class="form-label">Obra</label>
                                <select id="obra_id" class="form-select form-select-sm select2" disabled>
                                    <option value="">Primero seleccione un cliente</option>
                                </select>
                            </div>
                            <div>
                                <label for="servicio_realizado_id" class="form-label">Servicio Realizado</label>
                                <select name="servicio_realizado_id" id="servicio_realizado_id" class="form-select form-select-sm select2" disabled>
                                    <option value="">Primero seleccione una obra</option>
                                </select>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0" style="font-size:0.78rem;">
                            <i class="fas fa-info-circle me-1"></i>Solo se listan clientes, obras y servicios realizados que cuenten con un Servicio Realizado en estado <strong>Confirmado</strong>.
                        </p>
                    </div>
                </div>

                {{-- Fotos --}}
                <div class="card" id="fotos-card" style="display:none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-camera me-2"></i>Fotografías</span>
                        <span class="results-count" id="fotos-count">0 fotos seleccionadas</span>
                    </div>
                    <div class="card-body">
                        <div class="file-upload-section" id="fotos-section">
                            <input type="file" name="fotos[]" id="fotos-input" class="file-input-hidden" multiple accept="image/*">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                            <p>Arrastra y suelta las fotos aquí o haz clic para seleccionar</p>
                            <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('fotos-input').click()">
                                <i class="fas fa-upload me-2"></i>Seleccionar Fotos
                            </button>
                            <div class="file-preview-container" id="fotos-preview"></div>
                        </div>
                    </div>
                </div>

                {{-- Planos --}}
                <div class="card" id="planos-card" style="display:none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-file-alt me-2"></i>Planos</span>
                        <span class="results-count" id="planos-count">0 archivos seleccionados</span>
                    </div>
                    <div class="card-body">
                        <div class="file-upload-section" id="planos-section">
                            <input type="file" name="planos[]" id="planos-input" class="file-input-hidden" multiple>
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                            <p>Arrastra y suelta los planos aquí o haz clic para seleccionar</p>
                            <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('planos-input').click()">
                                <i class="fas fa-upload me-2"></i>Seleccionar Planos
                            </button>
                            <div class="file-preview-container" id="planos-preview"></div>
                        </div>
                    </div>
                </div>

                {{-- Observación y Acciones --}}
                <div class="card" id="final-card" style="display:none;">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="observacion" class="form-label">Observación</label>
                            <textarea name="observacion" id="observacion" class="form-control form-control-sm" rows="3" placeholder="Describa el reclamo del cliente...">{{ old('observacion') }}</textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('reclamos.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Reclamo
                            </button>
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
#reclamoForm {
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

/* ── Grillas de formulario ── */
.form-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
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

.select2-container--bootstrap-5 .select2-selection {
    min-height: calc(1.5em + 0.5rem + 2px);
}

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
.file-preview {
    position: relative;
    width: 100px;
    height: 100px;
    border-radius: 6px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    background: #fff;
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

@media (max-width: 900px) {
    .form-grid, .form-grid.form-grid-3 { grid-template-columns: repeat(2, 1fr); }
    .page-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .form-grid, .form-grid.form-grid-3 { grid-template-columns: 1fr; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('.select2').select2({ theme: 'bootstrap-5', width: '100%' });

    const $clienteSelect = $('#cliente_id');
    const $obraSelect = $('#obra_id');
    const $servicioSelect = $('#servicio_realizado_id');

    const $fotosCard = $('#fotos-card');
    const $planosCard = $('#planos-card');
    const $finalCard = $('#final-card');

    function ocultarSecciones() {
        $fotosCard.hide();
        $planosCard.hide();
        $finalCard.hide();
    }

    function cargarObras(clienteId) {
        $obraSelect.empty().append('<option value="">Cargando obras...</option>').trigger('change');
        $servicioSelect.empty().append('<option value="">Primero seleccione una obra</option>').prop('disabled', true).trigger('change');
        ocultarSecciones();

        $.getJSON(`/reclamos/obras-por-cliente/${clienteId}`, function(obras) {
            $obraSelect.empty().append('<option value="">Seleccione una obra</option>');
            if (obras && obras.length > 0) {
                $.each(obras, function(i, obra) {
                    $obraSelect.append(`<option value="${obra.id}">${obra.descripcion}</option>`);
                });
                $obraSelect.prop('disabled', false);
            } else {
                $obraSelect.append('<option value="">No hay obras con servicios confirmados</option>');
            }
            $obraSelect.trigger('change');
        }).fail(function() {
            $obraSelect.empty().append('<option value="">Error al cargar obras</option>').trigger('change');
        });
    }

    function cargarServiciosRealizados(obraId) {
        $servicioSelect.empty().append('<option value="">Cargando servicios realizados...</option>').trigger('change');
        ocultarSecciones();

        $.getJSON(`/reclamos/servicios-realizados-por-obra/${obraId}`, function(servicios) {
            $servicioSelect.empty().append('<option value="">Seleccione un servicio realizado</option>');
            if (servicios && servicios.length > 0) {
                $.each(servicios, function(i, servicio) {
                    $servicioSelect.append(`<option value="${servicio.id}">Servicio Realizado #${servicio.id} - ${servicio.fecha_registro}</option>`);
                });
                $servicioSelect.prop('disabled', false);
            } else {
                $servicioSelect.append('<option value="">No hay servicios realizados confirmados</option>');
            }
            $servicioSelect.trigger('change');
        }).fail(function() {
            $servicioSelect.empty().append('<option value="">Error al cargar servicios realizados</option>').trigger('change');
        });
    }

    $clienteSelect.on('change', function() {
        const clienteId = $(this).val();
        if (clienteId) {
            cargarObras(clienteId);
        } else {
            $obraSelect.empty().append('<option value="">Primero seleccione un cliente</option>').prop('disabled', true).trigger('change');
            $servicioSelect.empty().append('<option value="">Primero seleccione una obra</option>').prop('disabled', true).trigger('change');
            ocultarSecciones();
        }
    });

    $obraSelect.on('change', function() {
        const obraId = $(this).val();
        if (obraId) {
            cargarServiciosRealizados(obraId);
        } else {
            $servicioSelect.empty().append('<option value="">Primero seleccione una obra</option>').prop('disabled', true).trigger('change');
            ocultarSecciones();
        }
    });

    $servicioSelect.on('change', function() {
        const servicioId = $(this).val();
        if (servicioId) {
            $fotosCard.show();
            $planosCard.show();
            $finalCard.show();
        } else {
            ocultarSecciones();
        }
    });

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
