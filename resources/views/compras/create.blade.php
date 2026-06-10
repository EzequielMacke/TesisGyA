<!-- filepath: c:\laragon\www\TesisGyA\resources\views\compras\create.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Compra (Factura de Proveedor) - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-file-invoice-dollar"></i> Registrar Compra (Factura de Proveedor)</h2>
                    <small>Seleccione la orden de compra confirmada y complete los datos de la factura</small>
                </div>
                <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>

            {{-- Mostrar errores de validación --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h6 class="alert-heading">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Por favor, corrija los siguientes errores:
                    </h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('compras.store') }}" id="compraForm" onsubmit="return validarFechas()">
                @csrf

                {{-- Selección de Orden de Compra Confirmada --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-file-contract me-2"></i>Seleccionar Orden de Compra Confirmada</span>
                    </div>
                    <div class="card-body">
                        <label for="orden_compra_id" class="form-label">Orden de Compra *</label>
                        <select class="form-select form-select-sm" id="orden_compra_id" name="orden_compra_id" required>
                            <option value="">Seleccione una orden confirmada...</option>
                            @foreach($ordenes as $orden)
                                <option value="{{ $orden->id }}" {{ $ordenSeleccionada && $ordenSeleccionada->id == $orden->id ? 'selected' : '' }}>
                                    #{{ $orden->id }} - {{ $orden->proveedor->razon_social }} ({{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Solo se muestran órdenes confirmadas con notas de remisión pendientes</small>
                    </div>
                </div>

                @if($ordenSeleccionada && $datosOrden)

                    {{-- Información de la Orden --}}
                    <div class="card">
                        <div class="card-header-section">
                            <span><i class="fas fa-info-circle me-2"></i>Información de la Orden</span>
                        </div>
                        <div class="card-body">
                            <div class="info-grid">
                                <div class="info-item">
                                    <label class="form-label">Presupuesto Aprobado</label>
                                    <div class="info-value"><i class="fas fa-coins"></i>{{ $datosOrden['presupuesto'] }}</div>
                                </div>
                                <div class="info-item">
                                    <label class="form-label">Condición de Pago</label>
                                    <div class="info-value"><i class="fas fa-file-invoice"></i>{{ $datosOrden['condicion_pago'] }}</div>
                                </div>
                                <div class="info-item">
                                    <label class="form-label">Cantidad de Cuotas</label>
                                    <div class="info-value"><i class="fas fa-list-ol"></i>{{ $datosOrden['cuotas'] }}</div>
                                </div>
                                <div class="info-item">
                                    <label class="form-label">Intervalo</label>
                                    <div class="info-value"><i class="fas fa-clock"></i>{{ $datosOrden['intervalo'] ? $datosOrden['intervalo'] . ' días' : '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Detalle del Presupuesto Aprobado --}}
                    <div class="card table-card">
                        <div class="card-header-section">
                            <span><i class="fas fa-list me-2"></i>Detalle del Presupuesto Aprobado</span>
                            <span class="results-count">{{ $datosOrden['presupuesto_detalles']->count() }} ítem(s)</span>
                        </div>
                        <div class="card-body p-0">
                            @if($datosOrden['presupuesto_detalles']->count() > 0)
                                <div class="table-container">
                                    <table class="data-table">
                                        <thead>
                                            <tr>
                                                <th>Insumo</th>
                                                <th style="width:120px;">Marca</th>
                                                <th style="width:100px;">Unidad</th>
                                                <th style="width:90px;" class="text-center">Cantidad</th>
                                                <th style="width:130px;" class="text-end">Precio Unit.</th>
                                                <th style="width:110px;">Impuesto</th>
                                                <th>Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($datosOrden['presupuesto_detalles'] as $detalle)
                                                <tr>
                                                    <td><strong>{{ $detalle->insumo->descripcion ?? '' }}</strong></td>
                                                    <td><span class="tag tag-secondary">{{ $detalle->insumo->marca->descripcion ?? '' }}</span></td>
                                                    <td><span class="tag tag-secondary">{{ $detalle->insumo->unidadMedida->descripcion ?? '' }}</span></td>
                                                    <td class="text-center">{{ $detalle->cantidad }}</td>
                                                    <td class="text-end">₲ {{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                                                    <td><span class="tag tag-secondary">{{ $detalle->impuesto->descripcion ?? '' }}</span></td>
                                                    <td>
                                                        @if($detalle->observacion)
                                                            {{ $detalle->observacion }}
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="empty-cell">
                                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                    No hay detalles de presupuesto aprobado.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Notas de Remisión Asociadas --}}
                    <div class="card">
                        <div class="card-header-section">
                            <span><i class="fas fa-truck me-2"></i>Notas de Remisión Asociadas</span>
                        </div>
                        <div class="card-body">
                            @if($datosOrden['notas']->count() > 0)
                                <div class="chips-list">
                                    @foreach($datosOrden['notas'] as $nota)
                                        <span class="tag"><i class="fas fa-truck me-1"></i>#{{ $nota->id }} - {{ $nota->nombre }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted mb-0" style="font-size:0.85rem;"><em>No hay notas de remisión asociadas.</em></p>
                            @endif
                        </div>
                    </div>

                    {{-- Artículos de la Compra --}}
                    <div class="card table-card">
                        <div class="card-header-section">
                            <span><i class="fas fa-boxes me-2"></i>Artículos de la Compra</span>
                            <span class="results-count">{{ count($datosOrden['articulos']) }} ítem(s)</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-container">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Insumo</th>
                                            <th style="width:120px;">Marca</th>
                                            <th style="width:100px;">Unidad</th>
                                            <th style="width:110px;" class="text-center">Cant. Total</th>
                                            <th style="width:130px;" class="text-end">Precio Unit.</th>
                                            <th style="width:110px;">Impuesto</th>
                                            <th style="width:130px;" class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($datosOrden['articulos'] as $index => $articulo)
                                            <tr>
                                                <td><strong>{{ $articulo['descripcion'] }}</strong></td>
                                                <td><span class="tag tag-secondary">{{ $articulo['marca'] }}</span></td>
                                                <td><span class="tag tag-secondary">{{ $articulo['unidad'] }}</span></td>
                                                <td class="text-center">{{ $articulo['cantidad_total'] }}</td>
                                                <td class="text-end">₲ {{ number_format($articulo['precio_unitario'], 0, ',', '.') }}</td>
                                                <td><span class="tag tag-secondary">{{ $articulo['impuesto'] }}</span></td>
                                                <td class="text-end"><span class="amount">₲ {{ number_format($articulo['subtotal'], 0, ',', '.') }}</span></td>
                                            </tr>
                                            <input type="hidden" name="detalle[{{ $index }}][insumo_id]" value="{{ $articulo['insumo_id'] }}">
                                            <input type="hidden" name="detalle[{{ $index }}][cantidad_total]" value="{{ $articulo['cantidad_total'] }}">
                                            <input type="hidden" name="detalle[{{ $index }}][precio_unitario]" value="{{ $articulo['precio_unitario'] }}">
                                            <input type="hidden" name="detalle[{{ $index }}][impuesto_id]" value="{{ $articulo['impuesto_id'] }}">
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Resumen Financiero --}}
                    <div class="card">
                        <div class="card-header-section">
                            <span><i class="fas fa-calculator me-2"></i>Resumen Financiero</span>
                        </div>
                        <div class="card-body">
                            <div class="totals-box">
                                <div class="totals-row">
                                    <span>IVA 5%</span>
                                    <strong>₲ {{ number_format($datosOrden['iva5'], 0, ',', '.') }}</strong>
                                </div>
                                <div class="totals-row">
                                    <span>IVA 10%</span>
                                    <strong>₲ {{ number_format($datosOrden['iva10'], 0, ',', '.') }}</strong>
                                </div>
                                <div class="totals-row">
                                    <span>Exento</span>
                                    <strong>₲ {{ number_format($datosOrden['exento'], 0, ',', '.') }}</strong>
                                </div>
                                <div class="totals-row">
                                    <span>Subtotales</span>
                                    <strong>₲ {{ number_format($datosOrden['total_subtotales'], 0, ',', '.') }}</strong>
                                </div>
                                <div class="totals-row">
                                    <span>Total IVA</span>
                                    <strong>₲ {{ number_format($datosOrden['total_impuestos'], 0, ',', '.') }}</strong>
                                </div>
                                <div class="totals-row totals-final">
                                    <span>Total Compra</span>
                                    <strong>₲ {{ number_format($datosOrden['total_compra'], 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Datos de la Factura del Proveedor --}}
                    <div class="card">
                        <div class="card-header-section">
                            <span><i class="fas fa-file-invoice me-2"></i>Datos de la Factura del Proveedor</span>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div>
                                    <label for="nro_factura" class="form-label">N° Factura *</label>
                                    <input type="text" class="form-control form-control-sm" id="nro_factura" name="nro_factura"
                                           required maxlength="15" placeholder="Ej: 001-002-1234567"
                                           title="Formato: 001-002-1234567" pattern="\d{3}-\d{3}-\d{7}">
                                </div>
                                <div>
                                    <label for="nro_timbrado" class="form-label">N° Timbrado *</label>
                                    <input type="text" class="form-control form-control-sm" id="nro_timbrado" name="nro_timbrado"
                                           required pattern="\d{8}" maxlength="8" placeholder="Ej: 12345678"
                                           title="Debe ser un número de 8 dígitos">
                                </div>
                                <div>
                                    <label for="fecha_emision" class="form-label">Fecha de Emisión *</label>
                                    <input type="date" class="form-control form-control-sm" id="fecha_emision" name="fecha_emision" required>
                                </div>
                                <div>
                                    <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                                    <input type="date" class="form-control form-control-sm" id="fecha_vencimiento" name="fecha_vencimiento">
                                </div>

                                <div>
                                    <label class="form-label">Condición de Pago</label>
                                    <input type="text" class="form-control form-control-sm readonly-field" value="{{ $datosOrden['condicion_pago'] }}" readonly>
                                </div>
                                <div>
                                    <label class="form-label">Método de Pago</label>
                                    <input type="text" class="form-control form-control-sm readonly-field" value="{{ $datosOrden['metodo_pago'] }}" readonly>
                                </div>
                                <div class="span-2">
                                    <label for="observacion" class="form-label">Observación</label>
                                    <textarea class="form-control form-control-sm" id="observacion" name="observacion" rows="1" maxlength="300"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Registrar Compra
                            </button>
                        </div>
                    </div>

                @else
                    <div class="alert alert-info mb-0" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        Seleccione una orden de compra para continuar con el registro de la factura.
                    </div>
                @endif
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
#compraForm {
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

.select2-container--bootstrap-5 .select2-selection {
    min-height: calc(1.5em + 0.5rem + 2px);
}

/* ── Información de la orden ── */
.info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
}
.info-item .form-label,
.form-grid .form-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 500;
    color: #94a3b8;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.info-value {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-size: 0.85rem;
    color: #374151;
}
.info-value i { color: #94a3b8; margin-right: 0.5rem; width: 14px; text-align: center; }

/* ── Datos de la factura ── */
.form-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
}
.form-grid .span-2 { grid-column: span 2; }
.readonly-field {
    background-color: #f8fafc !important;
    border-color: #e2e8f0 !important;
    color: #374151;
}

@media (max-width: 900px) {
    .info-grid { grid-template-columns: repeat(2, 1fr); }
    .form-grid { grid-template-columns: repeat(2, 1fr); }
    .page-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .info-grid { grid-template-columns: 1fr; }
    .form-grid { grid-template-columns: 1fr; }
    .form-grid .span-2 { grid-column: span 1; }
}

/* ── Tablas ── */
.table-card { display: flex; flex-direction: column; }
.table-container { overflow: auto; }

.data-table {
    width: 100%;
    min-width: 880px;
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

/* Lista de notas */
.chips-list { display: flex; flex-wrap: wrap; gap: 0.5rem; }

/* ── Totales ── */
.totals-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
    max-width: 360px;
    margin-left: auto;
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
    .totals-box { max-width: 100%; }
}

/* Empty state */
.empty-cell { text-align: center; color: #94a3b8; padding: 2.5rem 1rem; }
.empty-cell i { color: #cbd5e1; }
</style>

<script>
$(document).ready(function() {
    // Inicializar Select2
    $('#orden_compra_id').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Seleccione una orden confirmada...'
    });

    // Redirigir al seleccionar una orden de compra
    $('#orden_compra_id').on('change', function() {
        const id = $(this).val();
        if (id) {
            window.location.href = '{{ route('compras.create') }}/' + id;
        }
    });
});

// Validar fechas antes de enviar el formulario
function validarFechas() {
    const fechaEmision = document.getElementById('fecha_emision');
    const fechaVencimiento = document.getElementById('fecha_vencimiento');
    if (fechaEmision && fechaVencimiento && fechaVencimiento.value && fechaVencimiento.value < fechaEmision.value) {
        alert('La fecha de vencimiento no puede ser menor a la fecha de emisión.');
        return false;
    }
    return true;
}

// Formateo automático del número de factura
window.addEventListener('DOMContentLoaded', function() {
    const facturaInput = document.getElementById('nro_factura');
    if (facturaInput) {
        facturaInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '').slice(0, 13);
            let formatted = '';
            if (value.length > 0) formatted += value.substring(0, 3);
            if (value.length > 3) formatted += '-' + value.substring(3, 6);
            if (value.length > 6) formatted += '-' + value.substring(6, 13);
            e.target.value = formatted;
        });
    }
});
</script>
