<!-- filepath: c:\laragon\www\TesisGyA\resources\views\presupuesto_compra\create.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Presupuesto - Pedido #{{ $pedido->id }}</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content fade-in">
        <div class="content-wrapper">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>
                        <i class="fas fa-file-invoice-dollar me-2 text-primary"></i>
                        Crear Presupuesto
                    </h2>
                    <p class="text-muted mb-0">Para el Pedido de Compra #{{ $pedido->id }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('presupuesto_compra.show_pedido', $pedido->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver al Pedido
                    </a>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('presupuesto_compra.store') }}" method="POST" id="presupuestoForm">
                @csrf
                <input type="hidden" name="pedido_compra_id" value="{{ $pedido->id }}">
                <input type="hidden" name="proveedor_id" value="{{ $proveedor->id }}">

                <!-- Datos del Presupuesto -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Datos del Presupuesto
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre del Presupuesto</label>
                                    <input type="text"
                                           class="form-control bg-light"
                                           id="nombre"
                                           name="nombre"
                                           value="Presupuesto Nro {{ $numeroPresupuesto }} para el Pedido {{ $pedido->id }} del Proveedor {{ $proveedor->razon_social }}"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="proveedor_display" class="form-label">Proveedor</label>
                                    <input type="text"
                                           class="form-control bg-light"
                                           id="proveedor_display"
                                           value="{{ $proveedor->razon_social }}"
                                           readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="fecha_emision" class="form-label">Fecha Emisión *</label>
                                    <input type="date"
                                           class="form-control"
                                           id="fecha_emision"
                                           name="fecha_emision"
                                           value="{{ old('fecha_emision', date('Y-m-d')) }}"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="validez" class="form-label">Validez (días) *</label>
                                    <input type="number"
                                           class="form-control"
                                           id="validez"
                                           name="validez"
                                           value="{{ old('validez', '30') }}"
                                           min="1"
                                           max="365"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="fecha_vencimiento" class="form-label">Fecha Vencimiento *</label>
                                    <input type="date"
                                           class="form-control"
                                           id="fecha_vencimiento"
                                           name="fecha_vencimiento"
                                           value="{{ old('fecha_vencimiento') }}"
                                           required
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="impuesto_general" class="form-label">Impuesto para Todos</label>
                                    <select class="form-select" id="impuesto_general">
                                        <option value="">Aplicar a todos...</option>
                                        @foreach($impuestos as $impuesto)
                                            <option value="{{ $impuesto->id }}"
                                                    data-calculo="{{ $impuesto->calculo }}"
                                                    {{ $impuesto->id == 3 ? 'selected' : '' }}>
                                                {{ $impuesto->descripcion }} ({{ $impuesto->calculo }}%)
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Aplica el mismo impuesto a todos los items</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción (opcional)</label>
                                    <textarea class="form-control"
                                              id="descripcion"
                                              name="descripcion"
                                              rows="3"
                                              placeholder="Descripción o comentarios del presupuesto...">{{ old('descripcion') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cotización de Insumos -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Cotizar Insumos del Pedido
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="20%">Insumo</th>
                                        <th width="8%">Cantidad</th>
                                        <th width="12%">Precio Unit.</th>
                                        <th width="12%">Impuesto</th>
                                        <th width="10%">Subtotal</th>
                                        <th width="18%">Observación del Pedido</th>
                                        <th width="20%">Observación del Presupuesto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pedido->detalles as $index => $detalle)
                                        <tr class="insumo-row">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2">
                                                        <i class="fas fa-cube text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <strong class="d-block">{{ $detalle->insumo->descripcion }}</strong>
                                                        <small class="text-muted">
                                                            <span class="badge bg-primary me-1">{{ $detalle->insumo->marca->descripcion }}</span>
                                                            <span class="badge bg-secondary">{{ $detalle->insumo->unidadMedida->abreviatura ?? $detalle->insumo->unidadMedida->descripcion }}</span>
                                                        </small>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="detalles[{{ $index }}][insumo_id]" value="{{ $detalle->insumo_id }}">
                                                <input type="hidden" name="detalles[{{ $index }}][cantidad]" value="{{ $detalle->cantidad }}">
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info fs-6">{{ number_format($detalle->cantidad, 0, ',', '.') }}</span>
                                            </td>
                                            <td>
                                                <input type="number"
                                                       class="form-control precio-input"
                                                       name="detalles[{{ $index }}][precio_unitario]"
                                                       value="{{ old('detalles.'.$index.'.precio_unitario', '0') }}"
                                                       min="0"
                                                       step="1"
                                                       placeholder="0"
                                                       required>
                                            </td>
                                            <td>
                                                <select class="form-select impuesto-select" name="detalles[{{ $index }}][impuesto_id]" required>
                                                    <option value="">Seleccionar</option>
                                                    @foreach($impuestos as $impuesto)
                                                        <option value="{{ $impuesto->id }}"
                                                                data-calculo="{{ $impuesto->calculo }}"
                                                                {{ (old('detalles.'.$index.'.impuesto_id') == $impuesto->id || $impuesto->id == 3) ? 'selected' : '' }}>
                                                            {{ $impuesto->descripcion }} ({{ $impuesto->calculo }}%)
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <strong class="subtotal-item text-primary">₲ 0</strong>
                                                <small class="d-block text-muted impuesto-item">+ ₲ 0 imp.</small>
                                            </td>
                                            <td>
                                                @if($detalle->observacion)
                                                    <div class="bg-light p-2 rounded border-start border-3 border-info">
                                                        <small class="text-dark">{{ $detalle->observacion }}</small>
                                                    </div>
                                                @else
                                                    <span class="text-muted small">
                                                        <i class="fas fa-minus me-1"></i>Sin observación
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <textarea class="form-control observacion-input"
                                                          name="detalles[{{ $index }}][observacion]"
                                                          rows="2"
                                                          placeholder="Observación del proveedor..."
                                                          maxlength="300">{{ old('detalles.'.$index.'.observacion') }}</textarea>
                                                <small class="text-muted">Máximo 300 caracteres</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Resumen de Totales -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-calculator me-2"></i>Resumen de Totales
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="bg-light p-4 rounded">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-list-ul me-2"></i>Detalles por Impuesto
                                    </h6>
                                    <div id="resumenImpuestos">
                                        <p class="text-muted">Agregue precios para ver el desglose</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="bg-primary text-white p-4 rounded">
                                    <h6 class="mb-3">
                                        <i class="fas fa-chart-pie me-2"></i>Totales Generales
                                    </h6>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total sin impuestos:</span>
                                        <strong id="subtotalGeneral">₲ 0</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total impuestos:</span>
                                        <strong id="impuestosGeneral">₲ 0</strong>
                                    </div>
                                    <hr class="my-3" style="border-color: rgba(255,255,255,0.3);">
                                    <div class="d-flex justify-content-between">
                                        <span class="h5">TOTAL FINAL:</span>
                                        <strong class="h4" id="totalGeneral">₲ 0</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('presupuesto_compra.show_pedido', $pedido->id) }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-success btn-lg" id="guardarPresupuesto">
                                <i class="fas fa-save me-2"></i>Guardar Presupuesto
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
.main-content {
    margin-left: 60px;
    width: calc(100vw - 60px);
    min-height: 100vh;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
    overflow-x: hidden;
    box-sizing: border-box;
}

.content-wrapper {
    padding: 20px;
    max-width: 100%;
    box-sizing: border-box;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.precio-input {
    text-align: center;
    font-weight: 600;
}

.observacion-input {
    font-size: 0.875rem;
    resize: vertical;
}

.table th {
    font-size: 0.875rem;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

.insumo-row:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.impuesto-detalle {
    border-left: 4px solid #007bff;
    background-color: #f8f9fa;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    border-radius: 0 6px 6px 0;
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 50px;
        width: calc(100vw - 50px);
    }

    .content-wrapper {
        padding: 15px;
    }
}

.sidebar-nav:hover ~ .main-content {
    margin-left: 280px;
    width: calc(100vw - 280px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aplicar impuesto por defecto (ID 3 - 5%) a todos los items al cargar
    document.getElementById('impuesto_general').addEventListener('change', function() {
        if (this.value) {
            document.querySelectorAll('.impuesto-select').forEach(select => {
                select.value = this.value;
            });
            calcularTotales();
        }
    });

    // Calcular fecha de vencimiento automáticamente
    const fechaEmision = document.getElementById('fecha_emision');
    const validez = document.getElementById('validez');
    const fechaVencimiento = document.getElementById('fecha_vencimiento');

    function calcularFechaVencimiento() {
        if (fechaEmision.value && validez.value) {
            const emision = new Date(fechaEmision.value);
            const dias = parseInt(validez.value);
            const vencimiento = new Date(emision.getTime() + (dias * 24 * 60 * 60 * 1000));
            fechaVencimiento.value = vencimiento.toISOString().split('T')[0];
        }
    }

    fechaEmision.addEventListener('change', calcularFechaVencimiento);
    validez.addEventListener('input', calcularFechaVencimiento);

    // Inicializar cálculo de fecha
    calcularFechaVencimiento();

    // Calcular totales con el nuevo sistema de impuestos
    function calcularTotales() {
        let subtotalGeneral = 0;
        let impuestosGeneral = 0;
        let impuestosDetalle = {};

        document.querySelectorAll('.insumo-row').forEach(row => {
            const cantidad = parseFloat(row.querySelector('input[name*="[cantidad]"]').value) || 0;
            const precio = Math.round(parseFloat(row.querySelector('.precio-input').value) || 0); // Redondear sin decimales
            const impuestoSelect = row.querySelector('.impuesto-select');
            const impuestoId = parseInt(impuestoSelect.value);
            const impuestoCalculo = parseFloat(impuestoSelect.options[impuestoSelect.selectedIndex]?.dataset.calculo) || 0;
            const impuestoNombre = impuestoSelect.options[impuestoSelect.selectedIndex]?.text || '';

            // Actualizar el input con el precio redondeado
            row.querySelector('.precio-input').value = precio;

            if (cantidad > 0 && precio > 0 && impuestoSelect.value) {
                const subtotal = cantidad * precio;
                let impuesto = 0;

                // Si el impuesto es ID 1 (Exentas), no calcular impuesto
                if (impuestoId !== 1) {
                    impuesto = Math.round(subtotal / impuestoCalculo); // División según tu especificación
                }

                subtotalGeneral += subtotal;
                impuestosGeneral += impuesto;

                // Agrupar por tipo de impuesto
                if (!impuestosDetalle[impuestoId]) {
                    impuestosDetalle[impuestoId] = {
                        nombre: impuestoNombre,
                        calculo: impuestoCalculo,
                        total: 0
                    };
                }
                impuestosDetalle[impuestoId].total += impuesto;

                // Actualizar subtotal del item
                row.querySelector('.subtotal-item').textContent = `₲ ${subtotal.toLocaleString('es-PY')}`;

                // Mostrar impuesto solo si no es exenta
                if (impuestoId === 1) {
                    row.querySelector('.impuesto-item').textContent = 'Exenta';
                } else {
                    row.querySelector('.impuesto-item').textContent = `+ ₲ ${impuesto.toLocaleString('es-PY')} imp.`;
                }
            } else {
                row.querySelector('.subtotal-item').textContent = '₲ 0';
                row.querySelector('.impuesto-item').textContent = '+ ₲ 0 imp.';
            }
        });

        // Actualizar resumen por impuesto
        const resumenImpuestos = document.getElementById('resumenImpuestos');
        if (Object.keys(impuestosDetalle).length > 0) {
            let html = '';
            Object.values(impuestosDetalle).forEach(detalle => {
                // Solo mostrar en el resumen si tiene impuesto (no exentas)
                if (detalle.total > 0 || detalle.calculo === 0) {
                    html += `
                        <div class="impuesto-detalle mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold">${detalle.nombre}</span>
                                <strong class="text-success">
                                    ${detalle.total > 0 ? '₲ ' + detalle.total.toLocaleString('es-PY') : 'Sin impuesto'}
                                </strong>
                            </div>
                        </div>
                    `;
                }
            });
            resumenImpuestos.innerHTML = html || '<p class="text-muted">Todos los items son exentos</p>';
        } else {
            resumenImpuestos.innerHTML = '<p class="text-muted">Agregue precios para ver el desglose</p>';
        }

        // Actualizar totales generales
        document.getElementById('subtotalGeneral').textContent = `₲ ${subtotalGeneral.toLocaleString('es-PY')}`;
        document.getElementById('impuestosGeneral').textContent = `₲ ${impuestosGeneral.toLocaleString('es-PY')}`;
        document.getElementById('totalGeneral').textContent = `₲ ${(subtotalGeneral + impuestosGeneral).toLocaleString('es-PY')}`;

        // Habilitar/deshabilitar botón guardar
        document.getElementById('guardarPresupuesto').disabled = subtotalGeneral === 0;
    }

    // Event listeners para cálculos
    document.querySelectorAll('.precio-input, .impuesto-select').forEach(input => {
        input.addEventListener('input', calcularTotales);
        input.addEventListener('change', calcularTotales);
    });

    // Redondear precios al perder el foco
    document.querySelectorAll('.precio-input').forEach(input => {
        input.addEventListener('blur', function() {
            this.value = Math.round(parseFloat(this.value) || 0);
            calcularTotales();
        });
    });

    // Contador de caracteres para observaciones
    document.querySelectorAll('.observacion-input').forEach(textarea => {
        textarea.addEventListener('input', function() {
            const maxLength = 300;
            const currentLength = this.value.length;
            const small = this.nextElementSibling;

            if (currentLength > maxLength) {
                this.value = this.value.substring(0, maxLength);
            }

            small.textContent = `${this.value.length}/${maxLength} caracteres`;
        });
    });

    // Validación del formulario
    document.getElementById('presupuestoForm').addEventListener('submit', function(e) {
        const subtotal = parseFloat(document.getElementById('subtotalGeneral').textContent.replace(/[₲,]/g, '')) || 0;

        if (subtotal === 0) {
            e.preventDefault();
            alert('Debe agregar precios a los insumos antes de guardar el presupuesto.');
            return false;
        }

        // Mostrar loading en el botón
        const btnGuardar = document.getElementById('guardarPresupuesto');
        btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
        btnGuardar.disabled = true;
    });

    // Inicializar cálculos con impuesto por defecto
    setTimeout(() => {
        document.getElementById('impuesto_general').dispatchEvent(new Event('change'));
        calcularTotales();
    }, 100);
});
</script>
