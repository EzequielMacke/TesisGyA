<!-- filepath: c:\laragon\www\TesisGyA\resources\views\solicitud_servicio\create.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Solicitud de Servicio</title>
    @include('partials.head')
    <style>
        html, body {
            width: 100%;
            min-width: 0;
            overflow-x: hidden;
        }
        .main-content {
            margin-left: 60px;
            min-height: 100vh;
            background-color: #f8f9fa;
            transition: margin-left 0.3s cubic-bezier(.4,2,.6,1);
            overflow-x: auto;
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
        .menu-text {
            opacity: 0;
            transition: opacity 0.2s;
            white-space: nowrap;
        }
        .sidebar-nav:hover .menu-text {
            opacity: 1;
            transition-delay: 0.1s;
        }
        .card-form {
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            border-radius: 1rem;
        }
        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #0d6efd;
            margin-bottom: 1rem;
            letter-spacing: 0.5px;
        }
        .info-card {
            border-left: 4px solid #0d6efd;
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
        }
        .select2-container--default .select2-selection--single {
            border-radius: 0.5rem;
            height: 38px;
            padding-top: 3px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 32px;
        }
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .servicio-check {
            background: #f1f3f6;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            margin-bottom: 0.5rem;
            transition: background 0.2s;
        }
        .servicio-check:hover {
            background: #e2e6ea;
        }
        .btn-primary {
            box-shadow: 0 2px 8px rgba(13,110,253,0.08);
        }
        .btn-secondary {
            box-shadow: 0 2px 8px rgba(108,117,125,0.08);
        }
    </style>
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content fade-in">
        <div class="content-wrapper">
            <div class="d-flex align-items-center mb-4">
                <h2 class="mb-0 section-title">
                    <i class="fas fa-file-alt me-2"></i>Nueva Solicitud de Servicio
                </h2>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger shadow-sm">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('solicitud_servicio.store') }}">
                @csrf

                <div class="card card-form mb-4">
                    <div class="card-body">
                        <div class="row g-4 mb-3">
                            <!-- Cliente -->
                            <div class="col-md-4">
                                <label for="cliente_id" class="form-label fw-bold">
                                    <i class="fas fa-user-tie me-1 text-primary"></i>Cliente
                                </label>
                                <select name="cliente_id" id="cliente_id" class="form-select select2">
                                    <option value="">Seleccione un cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->razon_social }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="info-cliente" class="mt-2"></div>
                            </div>
                            <!-- Obra -->
                            <div class="col-md-4">
                                <label for="obra_id" class="form-label fw-bold">
                                    <i class="fas fa-building me-1 text-primary"></i>Obra
                                </label>
                                <select name="obra_id" id="obra_id" class="form-select select2" disabled>
                                    <option value="">Seleccione una obra</option>
                                </select>
                                <div id="info-obra" class="mt-2"></div>
                            </div>
                            <!-- Fecha -->
                            <div class="col-md-4">
                                <label for="fecha" class="form-label fw-bold">
                                    <i class="fas fa-calendar-alt me-1 text-primary"></i>Fecha
                                </label>
                                <input type="date" name="fecha" id="fecha" class="form-control" value="{{ old('fecha', date('Y-m-d')) }}">
                            </div>
                        </div>

                        <!-- Servicios -->
                        <div class="mb-4">
                            <label class="form-label fw-bold section-title">
                                <i class="fas fa-tools me-2"></i>Servicios
                            </label>
                            <div class="row" id="servicios-list">
                                @foreach($servicios as $servicio)
                                    <div class="col-md-4">
                                        <div class="form-check servicio-check">
                                            <input class="form-check-input" type="checkbox" name="servicios[]" value="{{ $servicio->id }}"
                                                id="servicio_{{ $servicio->id }}"
                                                {{ (is_array(old('servicios')) && in_array($servicio->id, old('servicios'))) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-normal" for="servicio_{{ $servicio->id }}">
                                                <i class="fas fa-cogs me-1 text-secondary"></i>{{ $servicio->descripcion }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Observación -->
                        <div class="mb-4">
                            <label for="observacion" class="form-label fw-bold">
                                <i class="fas fa-comment-dots me-1 text-primary"></i>Observación
                            </label>
                            <textarea name="observacion" id="observacion" class="form-control" rows="2" placeholder="Ingrese una observación...">{{ old('observacion') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('solicitud_servicio.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Guardar Solicitud
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('partials.footer')

    <script>
    $(document).ready(function() {
        $('.select2').select2({ width: '100%' });

        // Al seleccionar cliente, cargar obras activas y mostrar info del cliente
        $('#cliente_id').on('change', function() {
            var clienteId = $(this).val();
            $('#obra_id').prop('disabled', true).empty().append('<option value="">Seleccione una obra</option>');
            $('#info-cliente').html('');
            $('#info-obra').html('');
            if (clienteId) {
                // Cargar obras del cliente
                $.getJSON('{{ url("api/obras") }}/' + clienteId, function(obras) {
                    if (obras.length > 0) {
                        $('#obra_id').append(
                            obras.map(o => `<option value="${o.id}">${o.descripcion}</option>`)
                        );
                        $('#obra_id').prop('disabled', false);
                        @if(old('obra_id'))
                            $('#obra_id').val('{{ old('obra_id') }}').trigger('change');
                        @endif
                    }
                });
                // Mostrar info del cliente (ya que está en el select, puedes buscarlo en el array de PHP o hacer AJAX)
                @php
                    $clientesArr = [];
                    foreach($clientes as $c) {
                        $clientesArr[$c->id] = [
                            'razon_social' => $c->razon_social,
                            'ruc' => $c->ruc,
                            'telefono' => $c->telefono,
                            'email' => $c->email
                        ];
                    }
                @endphp
                var clientesData = @json($clientesArr);
                var data = clientesData[clienteId];
                if(data){
                    $('#info-cliente').html(
                        `<div class="info-card">
                            <div class="fw-bold mb-1"><i class="fas fa-user-tie text-primary me-1"></i>${data.razon_social}</div>
                            <div><i class="fas fa-id-card me-1 text-secondary"></i><strong>RUC:</strong> ${data.ruc}</div>
                            <div><i class="fas fa-phone me-1 text-secondary"></i><strong>Teléfono:</strong> ${data.telefono}</div>
                            <div><i class="fas fa-envelope me-1 text-secondary"></i><strong>Email:</strong> ${data.email}</div>
                        </div>`
                    );
                }
            }
        });

        // Al seleccionar obra, mostrar info de la obra
        $('#obra_id').on('change', function() {
            var obraId = $(this).val();
            $('#info-obra').html('');
            if (obraId) {
                $.getJSON('{{ url("api/obra") }}/' + obraId, function(data) {
                    $('#info-obra').html(
                        `<div class="info-card border-primary">
                            <div class="fw-bold mb-1"><i class="fas fa-building text-primary me-1"></i>${data.descripcion}</div>
                            <div><i class="fas fa-map-marker-alt me-1 text-secondary"></i><strong>Ubicación:</strong> ${data.ubicacion}</div>
                            <div>
                                <i class="fas fa-sticky-note me-1 text-secondary"></i>
                                <strong>Observación:</strong>
                                ${data.observacion ? data.observacion : 'No tiene observación'}
                            </div>
                        </div>`
                    );
                });
            }
        });

        // Si hay valores viejos, dispara los cambios
        @if(old('cliente_id'))
            $('#cliente_id').val('{{ old('cliente_id') }}').trigger('change');
        @endif
        @if(old('obra_id'))
            setTimeout(function() {
                $('#obra_id').val('{{ old('obra_id') }}').trigger('change');
            }, 500);
        @endif
    });
    </script>
</body>
</html>
