<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Presupuesto de Servicio</title>
    @include('partials.head')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
        }
        .main-content {
            margin-left: 60px;
            min-height: 100vh;
            background-color: #f4f6f9;
            transition: margin-left 0.3s cubic-bezier(.4,2,.6,1);
            overflow-x: hidden;
            box-sizing: border-box;
            width: auto;
            max-width: 100vw;
            padding: 20px;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 50px;
                padding: 10px;
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
            width: 100%;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 30px;
            overflow-x: auto;
        }
        .page-header {
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .section-card {
            margin-bottom: 30px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .section-header {
            background: #007bff;
            color: white;
            padding: 15px 20px;
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }
        .section-body {
            padding: 25px;
        }
        .ensayos-list, .selected-ensayos {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            background: #fafbfc;
        }
        .file-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .file-item {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            transition: transform 0.2s;
        }
        .file-item:hover {
            transform: scale(1.02);
        }
        .file-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .file-item .file-info {
            padding: 10px;
            font-size: 0.9rem;
        }
        .file-item .file-info a {
            color: #007bff;
            text-decoration: none;
        }
        .file-item .file-info a:hover {
            text-decoration: underline;
        }
        .precios-table, .resumen-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .precios-table th, .precios-table td, .resumen-table th, .resumen-table td {
            border: 1px solid #e9ecef;
            padding: 12px;
        }
        .precios-table th, .resumen-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        .precios-table .ensayo-name {
            text-align: left;
        }
        .resumen-table .concepto {
            text-align: left;
        }
        .resumen-table .monto {
            text-align: right;
        }
        .precios-table input, .precios-table select {
            width: 100%;
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 5px;
            text-align: center;
        }
        .resumen-table .total-row {
            background: #e3f2fd;
            font-weight: bold;
            color: #1976d2;
        }
        .btn-custom {
            background: #28a745;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            transition: transform 0.2s;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            color: white;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        .form-control, .form-select {
            border-radius: 6px;
            border: 1px solid #ced4da;
        }
        .select2-container--default .select2-selection--single {
            border-radius: 6px;
        }
    </style>
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="text-primary mb-0"><i class="fas fa-plus-circle me-3"></i>Crear Presupuesto de Servicio</h1>
                    <a href="{{ route('presupuesto_servicio.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <form action="{{ route('presupuesto_servicio.store') }}" method="POST" id="presupuestoForm">
                @csrf

                <!-- Sección 1: Selección de Datos -->
                <div class="section-card">
                    <h5 class="section-header"><i class="fas fa-search me-2"></i>Selección de Datos</h5>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-lg-4">
                                <label for="cliente_id" class="form-label">Cliente</label>
                                <select name="cliente_id" id="cliente_id" class="form-select select2" required>
                                    <option value="">Seleccione un cliente</option>
                                    @foreach(\App\Models\Cliente::where('estado_id', 1)->get() as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->razon_social }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="obra_id" class="form-label">Obra</label>
                                <select name="obra_id" id="obra_id" class="form-select select2" required disabled>
                                    <option value="">Seleccione una obra</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="visita_previa_id" class="form-label">Visita Previa</label>
                                <select name="visita_previa_id" id="visita_previa_id" class="form-select select2" required disabled>
                                    <option value="">Seleccione una visita previa</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección 2: Información -->
                <div id="info-section" style="display: none;">
                    <div class="section-card">
                        <h5 class="section-header"><i class="fas fa-info-circle me-2"></i>Información</h5>
                        <div class="section-body">
                            <div class="row g-4 mb-4">
                                <div class="col-lg-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-light"><strong>Datos del Cliente</strong></div>
                                        <div class="card-body" id="cliente-datos">
                                            <!-- Datos cargados por AJAX -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-light"><strong>Datos de la Obra</strong></div>
                                        <div class="card-body" id="obra-datos">
                                            <!-- Datos cargados por AJAX -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-light"><strong>Datos de la Visita Previa</strong></div>
                                        <div class="card-body" id="visita-datos">
                                            <!-- Datos cargados por AJAX -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <h6 class="mb-3"><i class="fas fa-camera me-2"></i>Fotos de la Visita</h6>
                                    <div class="file-gallery" id="fotos-visita">
                                        <!-- Fotos cargadas por AJAX -->
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <h6 class="mb-3"><i class="fas fa-file-alt me-2"></i>Planos de la Obra</h6>
                                    <div class="file-gallery" id="planos-visita">
                                        <!-- Planos cargadas por AJAX -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección 3: Presupuesto -->
                <div id="presupuesto-section" style="display: none;">
                    <!-- Seleccionar Ensayos -->
                    <div class="section-card">
                        <h5 class="section-header"><i class="fas fa-flask me-2"></i>Seleccionar Ensayos</h5>
                        <div class="section-body">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <h6 class="text-muted">Ensayos Disponibles</h6>
                                    <div class="ensayos-list" id="ensayos-disponibles">
                                        <!-- Ensayos cargados por AJAX -->
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <h6 class="text-muted">Ensayos Seleccionados</h6>
                                    <div class="selected-ensayos" id="ensayos-seleccionados">
                                        <!-- Lista dinámica -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles del Presupuesto -->
                    <div class="section-card">
                        <h5 class="section-header"><i class="fas fa-list me-2"></i>Detalles del Presupuesto</h5>
                        <div class="section-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="validez" class="form-label">Validez (días)</label>
                                    <input type="number" name="validez" id="validez" class="form-control" min="1" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="anticipo" class="form-label">Anticipo (%)</label>
                                    <input type="number" name="anticipo" id="anticipo" class="form-control" min="0" max="100" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="monto_anticipo" class="form-label">Monto Anticipo</label>
                                    <input type="text" id="monto_anticipo" class="form-control" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="fecha" class="form-label">Fecha</label>
                                    <input type="date" name="fecha" id="fecha" class="form-control" value="{{ date('Y-m-d') }}" readonly>
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label for="numero_presupuesto" class="form-label">Número</label>
                                    <input type="text" name="numero_presupuesto" id="numero_presupuesto" class="form-control" value="PRES-{{ date('Y') }}-{{ str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="usuario" class="form-label">Usuario</label>
                                    <input type="text" name="usuario" id="usuario" class="form-control" value="{{ session('user_usuario') }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <!-- Espacio vacío o ajustar si es necesario -->
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="observacion" class="form-label">Observación</label>
                                <textarea name="observacion" id="observacion" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Precios, Cantidades e Impuesto -->
                    <div class="section-card">
                        <h5 class="section-header"><i class="fas fa-calculator me-2"></i>Precios, Cantidades e Impuesto</h5>
                        <div class="section-body">
                            <div id="precios-cantidades">
                                <!-- Tabla por servicio con encabezados -->
                            </div>
                        </div>
                    </div>

                    <!-- Resumen de Precios -->
                    <div class="section-card">
                        <h5 class="section-header"><i class="fas fa-chart-line me-2"></i>Resumen de Precios</h5>
                        <div class="section-body">
                            <table class="resumen-table" id="resumen-table">
                                <thead>
                                    <tr>
                                        <th>Concepto</th>
                                        <th>Monto</th>
                                    </tr>
                                </thead>
                                <tbody id="resumen-body">
                                    <!-- Detalles -->
                                </tbody>
                            </table>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-custom">
                                    <i class="fas fa-save me-2"></i>Guardar Presupuesto
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializar Select2
            $('.select2').select2({
                placeholder: "Seleccione una opción",
                allowClear: true,
                theme: 'bootstrap-5'
            });

            // Cargar obras al seleccionar cliente
            $('#cliente_id').change(function() {
                var clienteId = $(this).val();
                if (clienteId) {
                    $.get('/ajax/obras/' + clienteId, function(data) {
                        $('#obra_id').html('<option value="">Seleccione una obra</option>');
                        data.forEach(function(obra) {
                            $('#obra_id').append('<option value="' + obra.id + '">' + obra.descripcion + '</option>');
                        });
                        $('#obra_id').prop('disabled', false).trigger('change.select2');
                    });
                } else {
                    resetForm();
                }
            });

            // Cargar visitas previas al seleccionar obra
            $('#obra_id').change(function() {
                var obraId = $(this).val();
                if (obraId) {
                    $.get('/ajax/visitas-previas/' + obraId, function(data) {
                        $('#visita_previa_id').html('<option value="">Seleccione una visita previa</option>');
                        data.forEach(function(visita) {
                            $('#visita_previa_id').append('<option value="' + visita.id + '">' + visita.fecha + ' - ' + visita.estado + '</option>');
                        });
                        $('#visita_previa_id').prop('disabled', false).trigger('change.select2');
                    });
                } else {
                    resetForm();
                }
            });

            // Cargar datos y ensayos al seleccionar visita previa
            $('#visita_previa_id').change(function() {
                var visitaId = $(this).val();
                if (visitaId) {
                    // Cargar datos
                    $.get('/ajax/visita-previa/' + visitaId, function(data) {
                        $('#cliente-datos').html(`
                            <p><strong>Razón Social:</strong> ${data.cliente.razon_social}</p>
                            <p><strong>RUC:</strong> ${data.cliente.ruc}</p>
                            <p><strong>Dirección:</strong> ${data.cliente.direccion}</p>
                        `);
                        $('#obra-datos').html(`
                            <p><strong>Descripción:</strong> ${data.obra.descripcion}</p>
                            <p><strong>Ubicación:</strong> ${data.obra.ubicacion}</p>
                            <p><strong>Metros Cuadrados:</strong> ${data.obra.metros_cuadrados}</p>
                        `);
                        $('#visita-datos').html(`
                            <p><strong>Fecha:</strong> ${data.fecha_visita}</p>
                            <p><strong>Estado:</strong> ${data.estado.descripcion}</p>
                            <p><strong>Observación:</strong> ${data.observacion}</p>
                        `);

                        // Mostrar fotos
                        $('#fotos-visita').html('');
                        if (data.fotos && data.fotos.length > 0) {
                            data.fotos.forEach(function(foto) {
                                $('#fotos-visita').append(`
                                    <div class="file-item">
                                        <img src="/storage/${foto.ruta_foto}" alt="Foto">
                                        <div class="file-info">
                                            <a href="/storage/${foto.ruta_foto}" target="_blank">Ver imagen</a>
                                            <br><small>${foto.fecha}</small>
                                        </div>
                                    </div>
                                `);
                            });
                        } else {
                            $('#fotos-visita').html('<p class="text-muted">No hay fotos disponibles.</p>');
                        }

                        // Mostrar planos
                        $('#planos-visita').html('');
                        if (data.planos && data.planos.length > 0) {
                            data.planos.forEach(function(plano) {
                                var isPdf = plano.ruta_plano.toLowerCase().endsWith('.pdf');
                                $('#planos-visita').append(`
                                    <div class="file-item">
                                        ${isPdf ? `
                                            <div style="height: 150px; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                                                <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                            </div>
                                        ` : `<img src="/storage/${plano.ruta_plano}" alt="Plano">`}
                                        <div class="file-info">
                                            <a href="/storage/${plano.ruta_plano}" target="_blank">Ver archivo</a>
                                            <br><small>${plano.fecha}</small>
                                        </div>
                                    </div>
                                `);
                            });
                        } else {
                            $('#planos-visita').html('<p class="text-muted">No hay planos disponibles.</p>');
                        }

                        $('#info-section').show();
                    });

                    // Cargar ensayos
                    $.get('/ajax/ensayos-por-visita/' + visitaId, function(data) {
                        window.ensayosData = data; // Almacenar datos globalmente
                        $('#ensayos-disponibles').html('');
                        data.forEach(function(servicio) {
                            $('#ensayos-disponibles').append(`
                                <div class="mb-3">
                                    <h6 class="text-primary">${servicio.servicio}</h6>
                                    ${servicio.ensayos.map(ensayo => `
                                        <div class="form-check">
                                            <input class="form-check-input ensayo-checkbox" type="checkbox" value="${ensayo.id}" id="ensayo-${ensayo.id}" ${ensayo.checked ? 'checked' : ''}>
                                            <label class="form-check-label" for="ensayo-${ensayo.id}">
                                                ${ensayo.descripcion}
                                            </label>
                                        </div>
                                    `).join('')}
                                </div>
                            `);
                        });
                        $('#presupuesto-section').show();
                        updateSelectedEnsayos();
                    });
                } else {
                    resetForm();
                }
            });

            // Actualizar lista de ensayos seleccionados
            $(document).on('change', '.ensayo-checkbox', function() {
                updateSelectedEnsayos();
            });

            $(document).on('input', 'input[name^="precios"], input[name^="cantidades"], select[name^="impuestos"]', function() {
                updateTotals();
            });

            $(document).on('input', '#anticipo', function() {
                updateTotals();
            });

            function updateSelectedEnsayos() {
                var selected = [];
                $('.ensayo-checkbox:checked').each(function() {
                    selected.push($(this).val());
                });
                $('#ensayos-seleccionados').html('');
                selected.forEach(function(id) {
                    $('#ensayos-seleccionados').append(`
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="${id}" checked disabled>
                            <label class="form-check-label">
                                ${$('#ensayo-' + id).next().text()}
                            </label>
                        </div>
                    `);
                });

                // Actualizar precios y cantidades por servicio
                updatePreciosCantidades();
            }

            function updatePreciosCantidades() {
                var selected = [];
                $('.ensayo-checkbox:checked').each(function() {
                    selected.push($(this).val());
                });
                $('#precios-cantidades').html('');
                if (typeof window.ensayosData !== 'undefined') {
                    window.ensayosData.forEach(function(servicio) {
                        var selectedEnsayos = servicio.ensayos.filter(ensayo => selected.includes(ensayo.id.toString()));
                        if (selectedEnsayos.length > 0) {
                            $('#precios-cantidades').append(`<h6 class="text-primary mb-3">${servicio.servicio}</h6>`);
                            $('#precios-cantidades').append(`
                                <table class="precios-table">
                                    <thead>
                                        <tr>
                                            <th>Ensayo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Impuesto</th>
                                            <th>IVA Monto</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${selectedEnsayos.map(ensayo => `
                                            <tr class="ensayo-item" data-id="${ensayo.id}">
                                                <td class="ensayo-name">${ensayo.descripcion}</td>
                                                <td><input type="hidden" name="ensayos[]" value="${ensayo.id}"><input type="number" name="precios[${ensayo.id}]" step="0.01" required></td>
                                                <td><input type="number" name="cantidades[${ensayo.id}]" min="1" required></td>
                                                <td>
                                                    <select name="impuestos[${ensayo.id}]" required>
                                                        <option value="">Seleccione</option>
                                                        @foreach(\App\Models\Impuesto::where('estado_id', 1)->get() as $impuesto)
                                                            <option value="{{ $impuesto->id }}" {{ $impuesto->id == 2 ? 'selected' : '' }}>{{ $impuesto->descripcion }} ({{ ($impuesto->calculo * 100) }}%)</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="iva-monto">Gs 0</td>
                                                <td class="subtotal">Gs 0</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            `);
                        }
                    });
                }
                updateTotals();
            }

            function updateTotals() {
                var subtotalesServicio = {};
                var totalEnsayos = 0;
                var totalImpuestos = 0;
                var impuestosPorTipo = {};
                $('.ensayo-item').each(function() {
                    var id = $(this).data('id');
                    var precio = parseFloat($('input[name="precios[' + id + ']"]').val()) || 0;
                    var cantidad = parseInt($('input[name="cantidades[' + id + ']"]').val()) || 0;
                    var subtotal = Math.round(precio * cantidad);
                    var impuestoId = $('select[name="impuestos[' + id + ']"]').val();
                    var impuestoMonto = 0;
                    if (impuestoId == 2) {
                        impuestoMonto = Math.round(subtotal / 11);
                    } else if (impuestoId == 3) {
                        impuestoMonto = Math.round(subtotal / 21);
                    }
                    var tipo = $('select[name="impuestos[' + id + ']"] option:selected').text().split(' (')[0];
                    $(this).find('.iva-monto').text('Gs ' + impuestoMonto.toLocaleString('es-ES'));
                    $(this).find('.subtotal').text('Gs ' + subtotal.toLocaleString('es-ES'));

                    // Encontrar servicio
                    var servicioNombre = '';
                    window.ensayosData.forEach(function(servicio) {
                        servicio.ensayos.forEach(function(ensayo) {
                            if (ensayo.id == id) {
                                servicioNombre = servicio.servicio;
                            }
                        });
                    });
                    if (!subtotalesServicio[servicioNombre]) {
                        subtotalesServicio[servicioNombre] = 0;
                    }
                    subtotalesServicio[servicioNombre] += subtotal;

                    totalEnsayos += subtotal;
                    totalImpuestos += impuestoMonto;

                    if (!impuestosPorTipo[tipo]) {
                        impuestosPorTipo[tipo] = 0;
                    }
                    impuestosPorTipo[tipo] += impuestoMonto;
                });
                var totalGeneral = totalEnsayos + totalImpuestos;

                // Calcular anticipo
                var anticipoPorc = parseFloat($('#anticipo').val()) || 0;
                var montoAnticipo = Math.round(totalGeneral * anticipoPorc / 100);
                $('#monto_anticipo').val('Gs ' + montoAnticipo.toLocaleString('es-ES'));

                // Construir resumen
                var resumenBody = '';
                for (var servicio in subtotalesServicio) {
                    resumenBody += `<tr><td class="concepto">${servicio}</td><td class="monto">Gs ${subtotalesServicio[servicio].toLocaleString('es-ES')}</td></tr>`;
                }
                resumenBody += `<tr class="total-row"><td class="concepto"><strong>Total Servicios</strong></td><td class="monto">Gs ${totalEnsayos.toLocaleString('es-ES')}</td></tr>`;
                for (var tipo in impuestosPorTipo) {
                    if (impuestosPorTipo[tipo] > 0) {
                        resumenBody += `<tr><td class="concepto">${tipo}</td><td class="monto">Gs ${impuestosPorTipo[tipo].toLocaleString('es-ES')}</td></tr>`;
                    }
                }
                resumenBody += `<tr class="total-row"><td class="concepto"><strong>Total General</strong></td><td class="monto">Gs ${totalGeneral.toLocaleString('es-ES')}</td></tr>`;
                resumenBody += `<tr><td class="concepto">Anticipo</td><td class="monto">Gs ${montoAnticipo.toLocaleString('es-ES')}</td></tr>`;
                $('#resumen-body').html(resumenBody);
            }

            function resetForm() {
                $('#obra_id').html('<option value="">Seleccione una obra</option>').prop('disabled', true).trigger('change.select2');
                $('#visita_previa_id').html('<option value="">Seleccione una visita previa</option>').prop('disabled', true).trigger('change.select2');
                $('#info-section, #presupuesto-section').hide();
                $('#cliente-datos, #obra-datos, #visita-datos, #fotos-visita, #planos-visita, #ensayos-disponibles, #ensayos-seleccionados, #precios-cantidades, #resumen-body').html('');
                $('#monto_anticipo').val('');
            }
        });
    </script>

    @include('partials.footer')
</body>
</html>
