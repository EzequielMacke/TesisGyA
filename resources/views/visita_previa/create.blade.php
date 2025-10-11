<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Visita Previa</title>
    @include('partials.head')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .main-content {
            margin-left: 60px;
            min-height: 100vh;
            background-color: #f8f9fa;
            transition: margin-left 0.3s cubic-bezier(.4,2,.6,1);
            overflow-x: hidden;
            box-sizing: border-box;
            width: auto;
            max-width: 100vw;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 50px;
            }
        }
        .sidebar-nav {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 60px;
            transition: width 0.3s cubic-bezier(.4,2,.6,1);
            overflow-x: hidden;
            z-index: 10000;
        }
        .sidebar-nav:hover {
            width: 280px;
            box-shadow: 2px 0 16px rgba(0,0,0,0.07);
        }
        .sidebar-nav:hover ~ .main-content {
            margin-left: 280px;
        }
        @media (max-width: 768px) {
            .sidebar-nav:hover {
                width: 250px;
            }
            .sidebar-nav:hover ~ .main-content {
                margin-left: 250px;
            }
        }
        .content-wrapper {
            padding: 15px;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: auto;
        }
        .info-card {
            border-left: 4px solid #0d6efd;
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
        }
        .select2-container .select2-selection--single,
        .select2-container .select2-selection--multiple {
            min-height: 38px;
            padding: 4px 8px;
        }
        /* Estilos para la sección de archivos */
        .file-upload-section {
            border: 2px dashed #dee2e6;
            border-radius: 0.5rem;
            padding: 2rem;
            text-align: center;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }
        .file-upload-section:hover {
            border-color: #0d6efd;
            background-color: #e7f3ff;
        }
        .file-upload-section.dragover {
            border-color: #0d6efd;
            background-color: #e7f3ff;
        }
        .file-upload-btn {
            background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
        }
        .file-upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
        }
        .file-upload-btn:active {
            transform: translateY(0);
        }
        .file-input-hidden {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
        .file-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }
        .file-preview {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 2px solid #dee2e6;
            background-color: white;
        }
        .file-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .file-preview .file-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 4px 8px;
            font-size: 0.8rem;
            text-align: center;
        }
        .file-preview .remove-file {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        .file-preview .remove-file:hover {
            background: rgba(220, 53, 69, 1);
        }
        .file-count {
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: #6c757d;
        }
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .section-title i {
            color: #0d6efd;
        }
        /* Estilos para ensayos */
        .ensayos-section {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1rem;
            background-color: #f8f9fa;
            margin-bottom: 1.5rem;
        }
        .servicio-group {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
        .servicio-group:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .servicio-group h6 {
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .ensayos-subgroup {
            padding-left: 1rem;
        }
        .ensayo-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .ensayo-item input[type="checkbox"] {
            margin-right: 0.5rem;
        }
        .ensayo-item label {
            margin-bottom: 0;
            cursor: pointer;
        }
    </style>
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content fade-in">
        <div class="content-wrapper">
            <h2 class="mb-4"><i class="fas fa-plus me-2"></i>Nueva Visita Previa</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                        @endforeach>
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('visita_previa.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row mb-3">
                            <!-- Cliente -->
                            <div class="col-md-4">
                                <label for="cliente_id" class="form-label fw-bold">Cliente</label>
                                <select name="cliente_id" id="cliente_id" class="form-select select2" required>
                                    <option value="">Seleccione un cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->razon_social }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Obra -->
                            <div class="col-md-4">
                                <label for="obra_id" class="form-label fw-bold">Obra</label>
                                <select name="obra_id" id="obra_id" class="form-select select2" required>
                                    <option value="">Primero seleccione un cliente</option>
                                </select>
                            </div>
                            <!-- Solicitud -->
                            <div class="col-md-4">
                                <label for="solicitud_servicio_id" class="form-label fw-bold">Solicitud de Servicio</label>
                                <select name="solicitud_servicio_id" id="solicitud_servicio_id" class="form-select select2" required>
                                    <option value="">Primero seleccione una obra</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <!-- Fecha visita -->
                            <div class="col-md-4">
                                <label for="fecha_visita" class="form-label fw-bold">Fecha de Visita</label>
                                <input type="date" name="fecha_visita" id="fecha_visita" class="form-control" value="{{ old('fecha_visita', date('Y-m-d')) }}" required>
                            </div>
                            <!-- Observación -->
                            <div class="col-md-8">
                                <label for="observacion" class="form-label fw-bold">Observación</label>
                                <input type="text" name="observacion" id="observacion" class="form-control" value="{{ old('observacion') }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <!-- Metros cuadrados -->
                            <div class="col-md-4">
                                <label for="metros_cuadrados" class="form-label fw-bold">Metros cuadrados de la obra</label>
                                <input type="number" min="0" step="0.01" name="metros_cuadrados" id="metros_cuadrados" class="form-control" value="{{ old('metros_cuadrados') }}" required>
                            </div>
                            <!-- Niveles -->
                            <div class="col-md-4">
                                <label for="niveles" class="form-label fw-bold">Niveles de la obra</label>
                                <input type="text" name="niveles" id="niveles" class="form-control" value="{{ old('niveles') }}" required placeholder="Ej: Planta baja, 1er piso">
                            </div>
                        </div>
                        <!-- Info de la solicitud seleccionada -->
                        <div id="info-solicitud" class="mb-3"></div>

                        <!-- Ensayos a realizar -->
                        <div id="ensayos-section" class="ensayos-section" style="display: none;">
                            <div class="section-title">
                                <i class="fas fa-flask"></i>
                                Ensayos a Realizar
                            </div>
                            <div id="ensayos-list"></div>
                        </div>

                        <!-- Carga de fotos -->
                        <div class="file-upload-section" id="fotos-section">
                            <div class="section-title">
                                <i class="fas fa-camera"></i>
                                Fotos de la Visita
                            </div>
                            <p class="text-muted mb-3">Arrastra y suelta las fotos aquí o haz clic para seleccionar</p>
                            <input type="file" name="fotos[]" id="fotos-input" class="file-input-hidden" multiple accept="image/*">
                            <button type="button" class="file-upload-btn" onclick="document.getElementById('fotos-input').click()">
                                <i class="fas fa-upload me-2"></i>Seleccionar Fotos
                            </button>
                            <div class="file-count" id="fotos-count">0 fotos seleccionadas</div>
                            <div class="file-preview-container" id="fotos-preview"></div>
                        </div>

                        <!-- Carga de planos -->
                        <div class="file-upload-section" id="planos-section">
                            <div class="section-title">
                                <i class="fas fa-file-alt"></i>
                                Planos de la Obra
                            </div>
                            <p class="text-muted mb-3">Arrastra y suelta los planos aquí o haz clic para seleccionar</p>
                            <input type="file" name="planos[]" id="planos-input" class="file-input-hidden" multiple accept="application/pdf,image/*">
                            <button type="button" class="file-upload-btn" onclick="document.getElementById('planos-input').click()">
                                <i class="fas fa-upload me-2"></i>Seleccionar Planos
                            </button>
                            <div class="file-count" id="planos-count">0 archivos seleccionados</div>
                            <div class="file-preview-container" id="planos-preview"></div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('visita_previa.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Guardar Visita Previa
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('partials.footer')

    <!-- jQuery primero -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 después de jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
    $(document).ready(function() {
        // Inicializar Select2
        $('.select2').select2({
            width: '100%',
            placeholder: 'Seleccione una opción',
            allowClear: true
        });

        // Variables de elementos
        const $clienteSelect = $('#cliente_id');
        const $obraSelect = $('#obra_id');
        const $solicitudSelect = $('#solicitud_servicio_id');
        const $metrosInput = $('#metros_cuadrados');
        const $nivelesInput = $('#niveles');
        const $infoSolicitud = $('#info-solicitud');
        const $ensayosSection = $('#ensayos-section');
        const $ensayosList = $('#ensayos-list');

        // CSRF Token para las peticiones AJAX
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Configurar AJAX con CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        // Función para cargar obras
        function cargarObras(clienteId) {
            // Resetear selects dependientes
            $obraSelect.empty().append('<option value="">Cargando obras...</option>').trigger('change');
            $solicitudSelect.empty().append('<option value="">Primero seleccione una obra</option>').trigger('change');

            // Limpiar campos
            $metrosInput.val('');
            $nivelesInput.val('');
            $infoSolicitud.empty();
            $ensayosSection.hide();
            $ensayosList.empty();

            // Petición AJAX
            $.ajax({
                url: `/ajax/obras/${clienteId}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $obraSelect.empty().append('<option value="">Seleccione una obra</option>');

                    if (response && response.length > 0) {
                        $.each(response, function(index, obra) {
                            $obraSelect.append(`<option value="${obra.id}">${obra.descripcion}</option>`);
                        });
                    } else {
                        $obraSelect.append('<option value="">No hay obras disponibles</option>');
                    }

                    $obraSelect.trigger('change');
                },
                error: function(xhr, status, error) {
                    $obraSelect.empty().append('<option value="">Error al cargar obras</option>').trigger('change');
                }
            });
        }

        // Función para cargar solicitudes
        function cargarSolicitudes(obraId) {
            // Resetear select y campos
            $solicitudSelect.empty().append('<option value="">Cargando solicitudes...</option>').trigger('change');
            $metrosInput.val('');
            $nivelesInput.val('');
            $infoSolicitud.empty();
            $ensayosSection.hide();
            $ensayosList.empty();

            // Petición AJAX
            $.ajax({
                url: `/ajax/solicitudes/${obraId}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $solicitudSelect.empty().append('<option value="">Seleccione una solicitud</option>');

                    if (response && response.length > 0) {
                        $.each(response, function(index, solicitud) {
                            $solicitudSelect.append(`<option value="${solicitud.id}">#${solicitud.id} - ${solicitud.fecha}</option>`);
                        });
                    } else {
                        $solicitudSelect.append('<option value="">No hay solicitudes disponibles</option>');
                    }

                    $solicitudSelect.trigger('change');
                },
                error: function(xhr, status, error) {
                    $solicitudSelect.empty().append('<option value="">Error al cargar solicitudes</option>').trigger('change');
                }
            });
        }

        // Función para cargar detalle de solicitud
        function cargarDetalleSolicitud(solicitudId) {
            $infoSolicitud.empty();
            $ensayosSection.hide();
            $ensayosList.empty();

            // Petición AJAX
            $.ajax({
                url: `/ajax/solicitud/${solicitudId}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let detalleHtml = '';
                    if (data.detalle && data.detalle.length > 0) {
                        detalleHtml = '<div><strong>Detalle:</strong><ul>';
                        $.each(data.detalle, function(index, item) {
                            detalleHtml += `<li>${item}</li>`;
                        });
                        detalleHtml += '</ul></div>';
                    }

                    const infoHtml = `
                        <div class="info-card">
                            <div><strong>ID:</strong> ${data.id}</div>
                            <div><strong>Fecha:</strong> ${data.fecha}</div>
                            <div><strong>Cliente:</strong> ${data.cliente}</div>
                            <div><strong>Obra:</strong> ${data.obra}</div>
                            <div><strong>Estado:</strong> ${data.estado}</div>
                            <div><strong>Observación:</strong> ${data.observacion || 'No tiene observación'}</div>
                            <div><strong>Metros cuadrados:</strong> ${data.metros_cuadrados || 'No especificado'}</div>
                            <div><strong>Niveles:</strong> ${data.niveles || 'No especificado'}</div>
                            ${detalleHtml}
                        </div>
                    `;

                    $infoSolicitud.html(infoHtml);

                    // Llenar campos con valores de la obra
                    $metrosInput.val(data.metros_cuadrados || '');
                    $nivelesInput.val(data.niveles || '');

                    // Cargar ensayos si hay servicios
                    if (data.servicios && data.servicios.length > 0) {
                        cargarEnsayos(data.servicios);
                    }
                },
                error: function(xhr, status, error) {
                    // Error silencioso en producción
                }
            });
        }

        // Función para cargar ensayos
        function cargarEnsayos(servicios) {
            $ensayosList.empty();

            // Petición AJAX para obtener ensayos por servicios
            $.ajax({
                url: '/ajax/ensayos-por-solicitud',
                type: 'POST',
                data: { servicios: servicios },
                dataType: 'json',
                success: function(response) {
                    if (response && response.length > 0) {
                        $.each(response, function(index, servicioData) {
                            const servicioHtml = `
                                <div class="servicio-group mb-3">
                                    <h6 class="text-primary">${servicioData.servicio}</h6>
                                    <div class="ensayos-subgroup">
                                        ${servicioData.ensayos.map(ensayo => `
                                            <div class="ensayo-item">
                                                <input type="checkbox" name="ensayos[]" value="${ensayo.id}" id="ensayo-${ensayo.id}">
                                                <label for="ensayo-${ensayo.id}">${ensayo.descripcion}</label>
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                            `;
                            $ensayosList.append(servicioHtml);
                        });
                        $ensayosSection.show();
                    } else {
                        $ensayosSection.hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al cargar ensayos:', error);
                    $ensayosSection.hide();
                }
            });
        }

        // Event listeners
        $clienteSelect.on('change', function() {
            const clienteId = $(this).val();

            if (clienteId) {
                cargarObras(clienteId);
            } else {
                // Resetear todo si no hay cliente
                $obraSelect.empty().append('<option value="">Primero seleccione un cliente</option>').trigger('change');
                $solicitudSelect.empty().append('<option value="">Primero seleccione una obra</option>').trigger('change');
                $metrosInput.val('');
                $nivelesInput.val('');
                $infoSolicitud.empty();
                $ensayosSection.hide();
                $ensayosList.empty();
            }
        });

        $obraSelect.on('change', function() {
            const obraId = $(this).val();

            if (obraId) {
                cargarSolicitudes(obraId);
            } else {
                // Resetear solicitudes si no hay obra
                $solicitudSelect.empty().append('<option value="">Primero seleccione una obra</option>').trigger('change');
                $metrosInput.val('');
                $nivelesInput.val('');
                $infoSolicitud.empty();
                $ensayosSection.hide();
                $ensayosList.empty();
            }
        });

        $solicitudSelect.on('change', function() {
            const solicitudId = $(this).val();

            if (solicitudId) {
                cargarDetalleSolicitud(solicitudId);
            } else {
                $infoSolicitud.empty();
                $ensayosSection.hide();
                $ensayosList.empty();
            }
        });

        // Funciones para manejar archivos
        function handleFileSelect(event, type) {
            const files = event.target.files;
            const previewContainer = $(`#${type}-preview`);
            const countElement = $(`#${type}-count`);

            previewContainer.empty();

            if (files.length > 0) {
                countElement.text(`${files.length} ${type === 'fotos' ? 'fotos' : 'archivos'} seleccionados`);

                Array.from(files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewDiv = $('<div class="file-preview"></div>');
                        const removeBtn = $('<button type="button" class="remove-file" title="Remover">&times;</button>');

                        removeBtn.on('click', function() {
                            // Remover el archivo del input
                            const dt = new DataTransfer();
                            const input = $(`#${type}-input`)[0];
                            const filesArray = Array.from(input.files);
                            filesArray.splice(index, 1);
                            filesArray.forEach(f => dt.items.add(f));
                            input.files = dt.files;

                            // Actualizar preview
                            handleFileSelect({target: input}, type);
                        });

                        if (file.type.startsWith('image/')) {
                            previewDiv.append(`<img src="${e.target.result}" alt="${file.name}">`);
                        } else {
                            previewDiv.append(`
                                <div style="display: flex; align-items: center; justify-content: center; height: 100%; background: #f8f9fa;">
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
            } else {
                countElement.text(`0 ${type === 'fotos' ? 'fotos' : 'archivos'} seleccionados`);
            }
        }

        // Drag and drop functionality
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
                    // Crear un DataTransfer para agregar archivos
                    const dt = new DataTransfer();
                    Array.from(input[0].files).forEach(f => dt.items.add(f));
                    Array.from(files).forEach(f => dt.items.add(f));
                    input[0].files = dt.files;

                    handleFileSelect({target: input[0]}, type);
                }
            });
        }

        // Configurar drag and drop
        setupDragAndDrop('fotos-section', 'fotos-input', 'fotos');
        setupDragAndDrop('planos-section', 'planos-input', 'planos');

        // Event listeners para inputs de archivo
        $('#fotos-input').on('change', function(e) {
            handleFileSelect(e, 'fotos');
        });

        $('#planos-input').on('change', function(e) {
            handleFileSelect(e, 'planos');
        });
    });
    </script>
</body>
</html>
