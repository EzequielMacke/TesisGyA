<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Presupuesto - Pedido #{{ $pedido->id }}</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-file-invoice-dollar"></i> Crear Presupuesto</h2>
                    <small>Para el Pedido de Compra #{{ $pedido->id }}</small>
                </div>
                <a href="{{ route('presupuesto_compra.show_pedido', $pedido->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Pedido
                </a>
            </div>

            {{-- Alerts --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

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

            <form action="{{ route('presupuesto_compra.store') }}" method="POST" id="presupuestoForm">
                @csrf
                <input type="hidden" name="pedido_compra_id" value="{{ $pedido->id }}">
                <input type="hidden" name="proveedor_id" value="{{ $proveedor->id }}">

                {{-- Datos del Presupuesto --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-info-circle me-2"></i>Datos del Presupuesto</span>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div class="span-2">
                                <label for="nombre" class="form-label">Nombre del Presupuesto</label>
                                <input type="text"
                                       class="form-control form-control-sm readonly-field"
                                       id="nombre"
                                       name="nombre"
                                       value="Presupuesto Nro {{ $numeroPresupuesto }} para el Pedido {{ $pedido->id }} del Proveedor {{ $proveedor->razon_social }}"
                                       readonly>
                            </div>
                            <div class="span-2">
                                <label for="proveedor_display" class="form-label">Proveedor</label>
                                <input type="text"
                                       class="form-control form-control-sm readonly-field"
                                       id="proveedor_display"
                                       value="{{ $proveedor->razon_social }}"
                                       readonly>
                            </div>

                            <div>
                                <label for="fecha_emision" class="form-label">Fecha Emisión *</label>
                                <input type="date"
                                       class="form-control form-control-sm"
                                       id="fecha_emision"
                                       name="fecha_emision"
                                       value="{{ old('fecha_emision', date('Y-m-d')) }}"
                                       required>
                            </div>
                            <div>
                                <label for="validez" class="form-label">Validez (días) *</label>
                                <input type="number"
                                       class="form-control form-control-sm"
                                       id="validez"
                                       name="validez"
                                       value="{{ old('validez', '30') }}"
                                       min="1"
                                       max="365"
                                       required>
                            </div>
                            <div>
                                <label for="fecha_vencimiento" class="form-label">Fecha Vencimiento *</label>
                                <input type="date"
                                       class="form-control form-control-sm readonly-field"
                                       id="fecha_vencimiento"
                                       name="fecha_vencimiento"
                                       value="{{ old('fecha_vencimiento') }}"
                                       required
                                       readonly>
                            </div>
                            <div>
                                <label for="impuesto_general" class="form-label">Impuesto para Todos</label>
                                <select class="form-select form-select-sm" id="impuesto_general">
                                    <option value="">Aplicar a todos...</option>
                                    @foreach($impuestos as $impuesto)
                                        <option value="{{ $impuesto->id }}"
                                                data-calculo="{{ $impuesto->calculo }}"
                                                {{ $impuesto->id == 3 ? 'selected' : '' }}>
                                            {{ $impuesto->descripcion }} ({{ $impuesto->calculo }}%)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="span-4">
                                <label for="descripcion" class="form-label">Descripción (opcional)</label>
                                <textarea class="form-control form-control-sm"
                                          id="descripcion"
                                          name="descripcion"
                                          rows="2"
                                          placeholder="Descripción o comentarios del presupuesto...">{{ old('descripcion') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cotización de Insumos --}}
                <div class="card table-card">
                    <div class="card-header-section">
                        <span><i class="fas fa-list me-2"></i>Cotizar Insumos del Pedido</span>
                        <span class="results-count">{{ $pedido->detalles->count() }} ítem(s)</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Insumo</th>
                                        <th style="width:70px;" class="text-center">Cantidad</th>
                                        <th style="width:120px;">Precio Unit.</th>
                                        <th style="width:150px;">Impuesto</th>
                                        <th style="width:120px;" class="text-center">Subtotal</th>
                                        <th style="width:200px;">Observación del Pedido</th>
                                        <th style="width:240px;">Observación del Presupuesto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pedido->detalles as $index => $detalle)
                                        <tr class="insumo-row">
                                            <td>
                                                <strong class="d-block">{{ $detalle->insumo->descripcion }}</strong>
                                                <span class="tag me-1">{{ $detalle->insumo->marca->descripcion }}</span>
                                                <span class="tag tag-secondary">{{ $detalle->insumo->unidadMedida->abreviatura ?? $detalle->insumo->unidadMedida->descripcion }}</span>
                                                <input type="hidden" name="detalles[{{ $index }}][insumo_id]" value="{{ $detalle->insumo_id }}">
                                                <input type="hidden" name="detalles[{{ $index }}][cantidad]" value="{{ $detalle->cantidad }}">
                                            </td>
                                            <td class="text-center">
                                                <span class="tag">{{ number_format($detalle->cantidad, 0, ',', '.') }}</span>
                                            </td>
                                            <td>
                                                <input type="number"
                                                       class="form-control form-control-sm precio-input"
                                                       name="detalles[{{ $index }}][precio_unitario]"
                                                       value="{{ old('detalles.'.$index.'.precio_unitario', '0') }}"
                                                       min="0"
                                                       step="1"
                                                       placeholder="0"
                                                       required>
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm impuesto-select" name="detalles[{{ $index }}][impuesto_id]" required>
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
                                                <span class="amount subtotal-item">₲ 0</span>
                                                <small class="d-block text-muted impuesto-item">+ ₲ 0 imp.</small>
                                            </td>
                                            <td>
                                                @if($detalle->observacion)
                                                    <div class="obs-box">{{ $detalle->observacion }}</div>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <textarea class="form-control form-control-sm observacion-input"
                                                          name="detalles[{{ $index }}][observacion]"
                                                          rows="2"
                                                          placeholder="Observación del proveedor..."
                                                          maxlength="300">{{ old('detalles.'.$index.'.observacion') }}</textarea>
                                                <small class="text-muted char-counter">0/300 caracteres</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Resumen de Totales --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-calculator me-2"></i>Resumen de Totales</span>
                    </div>
                    <div class="card-body">
                        <div class="totals-grid">
                            <div class="totals-box">
                                <div class="totals-box-title">Detalle por Impuesto</div>
                                <div id="resumenImpuestos">
                                    <p class="text-muted mb-0" style="font-size:0.8rem;">Agregue precios para ver el desglose</p>
                                </div>
                            </div>
                            <div class="totals-box">
                                <div class="totals-row">
                                    <span>Total sin impuestos</span>
                                    <strong id="subtotalGeneral">₲ 0</strong>
                                </div>
                                <div class="totals-row">
                                    <span>Total impuestos</span>
                                    <strong id="impuestosGeneral">₲ 0</strong>
                                </div>
                                <div class="totals-row totals-final">
                                    <span>TOTAL FINAL</span>
                                    <strong id="totalGeneral">₲ 0</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <a href="{{ route('presupuesto_compra.show_pedido', $pedido->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-success" id="guardarPresupuesto">
                            <i class="fas fa-save me-2"></i>Guardar Presupuesto
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
.results-count { font-weight: 400; font-size: 0.78rem; color: #94a3b8; }

/* ── Datos del presupuesto ── */
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
.form-grid .span-2 { grid-column: span 2; }
.form-grid .span-4 { grid-column: span 4; }
.readonly-field {
    background-color: #f8fafc !important;
    border-color: #e2e8f0 !important;
    color: #374151;
}

@media (max-width: 900px) {
    .form-grid { grid-template-columns: repeat(2, 1fr); }
    .page-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .form-grid { grid-template-columns: 1fr; }
    .form-grid .span-2,
    .form-grid .span-4 { grid-column: span 1; }
}

/* ── Tablas ── */
.table-card { display: flex; flex-direction: column; }
.table-container { overflow: auto; }

.data-table {
    width: 100%;
    min-width: 1100px;
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
.amount { font-weight: 700; color: #10b981; }

/* Observación del pedido */
.obs-box {
    background: #f8fafc;
    border-left: 3px solid #38bdf8;
    border-radius: 0 6px 6px 0;
    padding: 0.4rem 0.6rem;
    font-size: 0.78rem;
    color: #374151;
}

.precio-input { text-align: center; font-weight: 600; }
.observacion-input { font-size: 0.8rem; resize: vertical; }
.char-counter { font-size: 0.68rem; }

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

@media (max-width: 768px) {
    .table-container { font-size: 0.875rem; }
    .totals-grid { grid-template-columns: 1fr; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aplicar impuesto por defecto a todos los items al cambiar el selector general
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

    // Calcular totales
    function calcularTotales() {
        let subtotalGeneral = 0;
        let impuestosGeneral = 0;
        let impuestosDetalle = {};

        document.querySelectorAll('.insumo-row').forEach(row => {
            const cantidad = parseFloat(row.querySelector('input[name*="[cantidad]"]').value) || 0;
            const precio = Math.round(parseFloat(row.querySelector('.precio-input').value) || 0);
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
                    impuesto = Math.round(subtotal / impuestoCalculo);
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
                if (detalle.total > 0 || detalle.calculo === 0) {
                    html += `
                        <div class="totals-row">
                            <span>${detalle.nombre}</span>
                            <strong>${detalle.total > 0 ? '₲ ' + detalle.total.toLocaleString('es-PY') : 'Sin impuesto'}</strong>
                        </div>
                    `;
                }
            });
            resumenImpuestos.innerHTML = html || '<p class="text-muted mb-0" style="font-size:0.8rem;">Todos los items son exentos</p>';
        } else {
            resumenImpuestos.innerHTML = '<p class="text-muted mb-0" style="font-size:0.8rem;">Agregue precios para ver el desglose</p>';
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
        const counter = textarea.nextElementSibling;
        counter.textContent = `${textarea.value.length}/300 caracteres`;
        textarea.addEventListener('input', function() {
            const maxLength = 300;
            if (this.value.length > maxLength) {
                this.value = this.value.substring(0, maxLength);
            }
            counter.textContent = `${this.value.length}/${maxLength} caracteres`;
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
