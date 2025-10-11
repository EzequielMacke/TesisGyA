<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Contrato</title>
    @include('partials.head')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
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
            padding: 20px;
            max-width: 100%;
            box-sizing: border-box;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .input-group-text {
            background-color: #e9ecef;
        }
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: 0.375rem;
        }
    </style>
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="text-primary mb-0"><i class="fas fa-file-contract me-3"></i>Crear Contrato</h1>
                    <a href="{{ route('contrato.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Formulario de Contrato</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('contrato.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cliente_id" class="form-label">Cliente</label>
                                    <select name="cliente_id" id="cliente_id" class="form-select" required>
                                        <option value="">Seleccionar Cliente</option>
                                        @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->id }}">{{ $cliente->razon_social }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="obra_id" class="form-label">Obra</label>
                                    <select name="obra_id" id="obra_id" class="form-select" required>
                                        <option value="">Seleccionar Obra</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="presupuesto_servicio_id" class="form-label">Presupuesto</label>
                                    <select name="presupuesto_servicio_id" id="presupuesto_servicio_id" class="form-select" required>
                                        <option value="">Seleccionar Presupuesto</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Usuario</label>
                                    <input type="text" class="form-control" value="{{ session('user_usuario') }}" readonly>
                                    <input type="hidden" name="usuario_id" value="{{ session('user_id') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="plazo_dias" class="form-label">Plazo</label>
                                    <div class="input-group">
                                        <input type="number" name="plazo_dias" id="plazo_dias" class="form-control" min="1" required>
                                        <span class="input-group-text">días</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_firma" class="form-label">Fecha de Firma</label>
                                    <input type="date" name="fecha_firma" id="fecha_firma" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Fecha de Registro</label>
                                    <input type="text" class="form-control" value="{{ date('d/m/Y') }}" readonly>
                                    <input type="hidden" name="fecha_registro" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Monto Total</label>
                                    <input type="text" id="monto" class="form-control" readonly>
                                    <input type="hidden" name="monto" id="monto_hidden">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Anticipo (%)</label>
                                    <input type="text" id="anticipo" class="form-control" readonly>
                                    <input type="hidden" name="anticipo" id="anticipo_hidden">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pago_mitad" class="form-label">Pago a la Mitad (%)</label>
                                    <input type="number" name="pago_mitad" id="pago_mitad" class="form-control" min="0" max="100" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pago_final" class="form-label">Pago Final (%)</label>
                                    <input type="number" name="pago_final" id="pago_final" class="form-control" readonly>
                                    <input type="hidden" name="pago_final_hidden" id="pago_final_hidden">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="garantia_meses" class="form-label">Garantía</label>
                                    <div class="input-group">
                                        <input type="number" name="garantia_meses" id="garantia_meses" class="form-control" min="0" required>
                                        <span class="input-group-text">meses</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ciudad" class="form-label">Ciudad</label>
                                    <input type="text" name="ciudad" id="ciudad" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="observaciones" class="form-label">Observaciones</label>
                                    <textarea name="observaciones" id="observaciones" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="estado_id" value="3">

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Contrato
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#cliente_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'Buscar cliente...',
                allowClear: true
            });
            $('#obra_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'Seleccionar Obra',
                allowClear: true
            });
            $('#presupuesto_servicio_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'Seleccionar Presupuesto',
                allowClear: true
            });

            // Filtrar obras por cliente
            $('#cliente_id').on('change', function() {
                const clienteId = $(this).val();
                if (clienteId) {
                    fetch(`/obras-por-cliente/${clienteId}`)
                        .then(response => response.json())
                        .then(data => {
                            $('#obra_id').empty().append('<option value="">Seleccionar Obra</option>');
                            data.forEach(obra => {
                                $('#obra_id').append(`<option value="${obra.id}">${obra.descripcion}</option>`);
                            });
                            $('#obra_id').trigger('change.select2');
                        });
                } else {
                    $('#obra_id').empty().append('<option value="">Seleccionar Obra</option>');
                    $('#obra_id').trigger('change.select2');
                }
                // Limpiar presupuesto
                $('#presupuesto_servicio_id').empty().append('<option value="">Seleccionar Presupuesto</option>');
                $('#presupuesto_servicio_id').trigger('change.select2');
                $('#monto').val('');
                $('#anticipo').val('');
            });

            // Filtrar presupuestos por obra
            $('#obra_id').on('change', function() {
                const obraId = $(this).val();
                if (obraId) {
                    fetch(`/presupuestos-por-obra/${obraId}`)
                        .then(response => response.json())
                        .then(data => {
                            $('#presupuesto_servicio_id').empty().append('<option value="">Seleccionar Presupuesto</option>');
                            data.forEach(presupuesto => {
                                $('#presupuesto_servicio_id').append(`<option value="${presupuesto.id}" data-monto="${presupuesto.monto}" data-anticipo="${presupuesto.anticipo}">${presupuesto.numero_presupuesto}</option>`);
                            });
                            $('#presupuesto_servicio_id').trigger('change.select2');
                        });
                } else {
                    $('#presupuesto_servicio_id').empty().append('<option value="">Seleccionar Presupuesto</option>');
                    $('#presupuesto_servicio_id').trigger('change.select2');
                }
                $('#monto').val('');
                $('#anticipo').val('');
            });

            // Cargar monto y anticipo al seleccionar presupuesto
            $('#presupuesto_servicio_id').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const monto = selectedOption.data('monto');
                const anticipo = selectedOption.data('anticipo');
                if (monto && anticipo) {
                    $('#monto').val('Gs ' + parseFloat(monto).toLocaleString('es-PY'));
                    $('#monto_hidden').val(monto);
                    $('#anticipo').val(anticipo + '%');
                    $('#anticipo_hidden').val(anticipo);
                    calculatePagoFinal(); // Calcular pago final después de cargar anticipo
                } else {
                    $('#monto').val('');
                    $('#anticipo').val('');
                }
            });
        });

        // Función para calcular pago final
        function calculatePagoFinal() {
            const anticipo = parseFloat(document.getElementById('anticipo_hidden').value) || 0;
            const pagoMitad = parseFloat(document.getElementById('pago_mitad').value) || 0;
            const pagoFinal = 100 - anticipo - pagoMitad;
            if (pagoFinal < 0) {
                alert('El pago a la mitad no puede ser mayor que el restante después del anticipo.');
                document.getElementById('pago_mitad').value = 0;
                document.getElementById('pago_final').value = 100 - anticipo;
                document.getElementById('pago_final_hidden').value = 100 - anticipo;
            } else {
                document.getElementById('pago_final').value = pagoFinal;
                document.getElementById('pago_final_hidden').value = pagoFinal;
            }
        }

        // Evento para pago_mitad
        document.getElementById('pago_mitad').addEventListener('input', calculatePagoFinal);

        function validatePercentages() {
            const anticipo = parseFloat(document.getElementById('anticipo_hidden').value) || 0;
            const pagoMitad = parseFloat(document.getElementById('pago_mitad').value) || 0;
            const pagoFinal = parseFloat(document.getElementById('pago_final_hidden').value) || 0;
            const total = anticipo + pagoMitad + pagoFinal;
            if (total !== 100) {
                alert('La suma de anticipo, pago a la mitad y pago final debe ser exactamente 100%.');
                return false;
            }
            return true;
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            if (!validatePercentages()) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
