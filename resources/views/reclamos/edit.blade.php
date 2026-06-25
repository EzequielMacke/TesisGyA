<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Reclamo</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-exclamation-circle"></i> Editar Reclamo</h2>
                    <small>Modifique la observación y gestione las fotografías y planos del reclamo</small>
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

            <form method="POST" action="{{ route('reclamos.update', $reclamo->id) }}" enctype="multipart/form-data" id="reclamoForm">
                @csrf
                @method('PUT')

                {{-- Datos Generales --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-info-circle me-2"></i>Datos Generales</span>
                    </div>
                    <div class="card-body">
                        <div class="detail-box">
                            <div class="detail-box-title">Identificación</div>
                            <div class="detail-row"><i class="fas fa-hashtag"></i><span><strong>Nro Reclamo:</strong> {{ $reclamo->id }}</span></div>
                            <div class="detail-row"><i class="fas fa-building"></i><span><strong>Cliente:</strong> {{ $reclamo->cliente->razon_social ?? '-' }}</span></div>
                            <div class="detail-row"><i class="fas fa-hard-hat"></i><span><strong>Obra:</strong> {{ $reclamo->obra->descripcion ?? '-' }}</span></div>
                            <div class="detail-row"><i class="fas fa-clipboard-check"></i><span><strong>Servicio Realizado:</strong> {{ $reclamo->servicio_realizado_id ? '#' . $reclamo->servicio_realizado_id : '-' }}</span></div>
                            <div class="detail-row"><i class="fas fa-calendar"></i><span><strong>Fecha de Registro:</strong> {{ $reclamo->fecha_registro ? $reclamo->fecha_registro->format('d/m/Y') : '-' }}</span></div>
                        </div>
                    </div>
                </div>

                {{-- Fotos --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-camera me-2"></i>Fotografías del Reclamo</span>
                        <span class="results-count" id="fotos-count">0 fotos nuevas seleccionadas</span>
                    </div>
                    <div class="card-body">
                        @if($reclamo->fotos->count() > 0)
                            <div class="mb-3">
                                <label class="form-label">Fotos ya cargadas</label>
                                <div class="file-preview-container existing-files" id="fotos-existentes">
                                    @foreach($reclamo->fotos as $foto)
                                        <div class="file-preview" data-existing-id="{{ $foto->id }}">
                                            <a href="{{ Storage::disk('public')->url('reclamos/fotos/' . $foto->nombre_foto) }}" target="_blank" title="Ver imagen">
                                                <img src="{{ Storage::disk('public')->url('reclamos/fotos/' . $foto->nombre_foto) }}" alt="Foto">
                                            </a>
                                            <button type="button" class="remove-file remove-existing" data-type="fotos" data-id="{{ $foto->id }}" title="Quitar foto">&times;</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div id="fotos-eliminar-inputs"></div>
                        <div class="file-upload-section" id="fotos-section">
                            <input type="file" name="fotos[]" id="fotos-input" class="file-input-hidden" multiple accept="image/*">
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
                        <span><i class="fas fa-file-alt me-2"></i>Planos del Reclamo</span>
                        <span class="results-count" id="planos-count">0 archivos nuevos seleccionados</span>
                    </div>
                    <div class="card-body">
                        @if($reclamo->planos->count() > 0)
                            <div class="mb-3">
                                <label class="form-label">Planos ya cargados</label>
                                <div class="file-preview-container existing-files" id="planos-existentes">
                                    @foreach($reclamo->planos as $plano)
                                        <div class="file-preview" data-existing-id="{{ $plano->id }}">
                                            <a href="{{ Storage::disk('public')->url('reclamos/planos/' . $plano->nombre_plano) }}" target="_blank" title="Ver archivo">
                                                @if(strtolower(pathinfo($plano->nombre_plano, PATHINFO_EXTENSION)) == 'pdf')
                                                    <div style="display:flex; align-items:center; justify-content:center; height:100%; background:#f8fafc;">
                                                        <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                                    </div>
                                                @else
                                                    <img src="{{ Storage::disk('public')->url('reclamos/planos/' . $plano->nombre_plano) }}" alt="Plano">
                                                @endif
                                            </a>
                                            <button type="button" class="remove-file remove-existing" data-type="planos" data-id="{{ $plano->id }}" title="Quitar plano">&times;</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div id="planos-eliminar-inputs"></div>
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

                {{-- Observación y Acciones --}}
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="observacion" class="form-label">Observación</label>
                            <textarea name="observacion" id="observacion" class="form-control form-control-sm" rows="3" placeholder="Describa el reclamo del cliente...">{{ old('observacion', $reclamo->observacion) }}</textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('reclamos.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Cambios
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

.form-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 500;
    color: #94a3b8;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

/* ── Datos generales ── */
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
    z-index: 2;
}
.file-preview .remove-file:hover { background: rgba(220, 53, 69, 1); }

@media (max-width: 900px) {
    .page-header { flex-direction: column; align-items: flex-start; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Manejo de archivos nuevos: vista previa, contador y eliminación
    function handleFileSelect(event, type) {
        const files = event.target.files;
        const previewContainer = $(`#${type}-preview`);
        const countElement = $(`#${type}-count`);

        previewContainer.empty();

        const label = type === 'fotos' ? 'fotos nuevas' : 'archivos nuevos';
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

    // Quitar archivos ya cargados (existentes en el servidor)
    $('.remove-existing').on('click', function() {
        const type = $(this).data('type');
        const id = $(this).data('id');
        const $item = $(this).closest('.file-preview');

        $(`#${type}-eliminar-inputs`).append(
            `<input type="hidden" name="${type}_eliminar[]" value="${id}">`
        );
        $item.remove();
    });
});
</script>
