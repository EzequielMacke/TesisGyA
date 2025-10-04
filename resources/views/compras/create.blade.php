<!-- filepath: c:\laragon\www\TesisGyA\resources\views\compras\create.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Compra (Factura de Proveedor)</title>
    @include('partials.head')
    <script>
        // Validar fechas antes de enviar el formulario
        function validarFechas() {
            const fechaEmision = document.getElementById('fecha_emision').value;
            const fechaVencimiento = document.getElementById('fecha_vencimiento').value;
            if (fechaVencimiento && fechaVencimiento < fechaEmision) {
                alert('La fecha de vencimiento no puede ser menor a la fecha de emisión.');
                return false;
            }
            return true;
        }

        // Formateo automático del número de factura
        window.addEventListener('DOMContentLoaded', function() {
            const facturaInput = document.getElementById('nro_factura');
            facturaInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '').slice(0, 13);
                let formatted = '';
                if (value.length > 0) formatted += value.substring(0, 3);
                if (value.length > 3) formatted += '-' + value.substring(3, 6);
                if (value.length > 6) formatted += '-' + value.substring(6, 13);
                e.target.value = formatted;
            });
        });
    </script>
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content fade-in">
        <div class="content-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>
                        <i class="fas fa-file-invoice-dollar me-2 text-primary"></i>
                        Registrar Compra (Factura de Proveedor)
                    </h2>
                    <p class="text-muted mb-0">Seleccione la orden de compra confirmada y complete los datos de la factura</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('compras.store') }}" onsubmit="return validarFechas()">
                @csrf
                 <!-- Mostrar errores de validación -->
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

                <!-- Mostrar mensaje de éxito -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Mostrar mensaje de error -->
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-times-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <!-- Selección de Orden de Compra Confirmada -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-file-contract me-2"></i>Orden de Compra Confirmada
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="orden_compra_id" class="form-label">Orden de Compra</label>
                                <select class="form-select" id="orden_compra_id" name="orden_compra_id" onchange="window.location.href='{{ route('compras.create') }}/' + this.value" required>
                                    <option value="">Seleccione una orden confirmada...</option>
                                    @foreach($ordenes as $orden)
                                        <option value="{{ $orden->id }}" {{ $ordenSeleccionada && $ordenSeleccionada->id == $orden->id ? 'selected' : '' }}>
                                            #{{ $orden->id }} - {{ $orden->proveedor->razon_social }} ({{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if($ordenSeleccionada)
                                <div class="col-md-6">
                                    <label class="form-label">Presupuesto Aprobado</label>
                                    <div class="fw-bold">
                                        {{ $datosOrden['presupuesto'] }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($ordenSeleccionada && $datosOrden)
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    @if($datosOrden['presupuesto_detalles']->count() > 0)
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Insumo</th>
                                                    <th>Marca</th>
                                                    <th>Unidad</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio Unitario</th>
                                                    <th>Impuesto</th>
                                                    <th>Observación</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($datosOrden['presupuesto_detalles'] as $detalle)
                                                    <tr>
                                                        <td>{{ $detalle->insumo->descripcion ?? '' }}</td>
                                                        <td>{{ $detalle->insumo->marca->descripcion ?? '' }}</td>
                                                        <td>{{ $detalle->insumo->unidadMedida->descripcion ?? '' }}</td>
                                                        <td>{{ $detalle->cantidad }}</td>
                                                        <td>₲ {{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                                                        <td>{{ $detalle->impuesto->descripcion ?? '' }}</td>
                                                        <td>{{ $detalle->observacion ?? '' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <em>No hay detalles de presupuesto aprobado.</em>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Condición de Pago</label>
                                    <input type="text" class="form-control" value="{{ $datosOrden['condicion_pago'] }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Cantidad de Cuotas</label>
                                    <input type="number" class="form-control" value="{{ $datosOrden['cuotas'] }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Intervalo (días)</label>
                                    <input type="number" class="form-control" value="{{ $datosOrden['intervalo'] }}" readonly>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if($ordenSeleccionada && $datosOrden)
                    <!-- Notas de Remisión asociadas -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-truck me-2"></i>Notas de Remisión Asociadas
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($datosOrden['notas']->count() > 0)
                                <ul>
                                    @foreach($datosOrden['notas'] as $nota)
                                        <li><strong>#{{ $nota->id }}</strong> - {{ $nota->nombre }} </li>
                                    @endforeach
                                </ul>
                            @else
                                <em>No hay notas de remisión asociadas.</em>
                            @endif
                        </div>
                    </div>

                    <!-- Artículos sumados de las notas de remisión -->
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-boxes me-2"></i>Artículos de la Compra
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Insumo</th>
                                        <th>Marca</th>
                                        <th>Unidad</th>
                                        <th>Cantidad Total</th>
                                        <th>Precio Unitario</th>
                                        <th>Impuesto</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datosOrden['articulos'] as $index => $articulo)
                                        <tr>
                                            <td>{{ $articulo['descripcion'] }}</td>
                                            <td>{{ $articulo['marca'] }}</td>
                                            <td>{{ $articulo['unidad'] }}</td>
                                            <td>{{ $articulo['cantidad_total'] }}</td>
                                            <td>₲ {{ number_format($articulo['precio_unitario'], 0, ',', '.') }}</td>
                                            <td>{{ $articulo['impuesto'] }}</td>
                                            <td>₲ {{ number_format($articulo['subtotal'], 0, ',', '.') }}</td>
                                        </tr>

                                        <!-- Inputs ocultos para enviar los datos -->
                                        <input type="hidden" name="detalle[{{ $index }}][insumo_id]" value="{{ $articulo['insumo_id'] }}">
                                        <input type="hidden" name="detalle[{{ $index }}][cantidad_total]" value="{{ $articulo['cantidad_total'] }}">
                                        <input type="hidden" name="detalle[{{ $index }}][precio_unitario]" value="{{ $articulo['precio_unitario'] }}">
                                        <input type="hidden" name="detalle[{{ $index }}][impuesto_id]" value="{{ $articulo['impuesto_id'] }}">
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Resumen de impuestos -->
                    <div class="card mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-receipt me-2"></i>Resumen de Impuestos
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul>
                                <li>IVA 5%: ₲ {{ number_format($datosOrden['iva5'], 0, ',', '.') }}</li>
                                <li>IVA 10%: ₲ {{ number_format($datosOrden['iva10'], 0, ',', '.') }}</li>
                                <li>Exento: ₲ {{ number_format($datosOrden['exento'], 0, ',', '.') }}</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Totales -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-calculator me-2"></i>Totales
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="fw-bold fs-5">Total Compra: ₲ {{ number_format($datosOrden['total_compra'], 0, ',', '.') }}</div>
                            <div>Subtotales: ₲ {{ number_format($datosOrden['total_subtotales'], 0, ',', '.') }}</div>
                            <div>Total IVA: ₲ {{ number_format($datosOrden['total_impuestos'], 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <!-- Datos de la factura del proveedor -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-file-invoice me-2"></i>Datos de la Factura del Proveedor
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="nro_factura" class="form-label">N° Factura</label>
                                    <input type="text"
                                        class="form-control"
                                        id="nro_factura"
                                        name="nro_factura"
                                        required
                                        maxlength="15"
                                        placeholder="Ej: 001-002-1234567"
                                        title="Formato: 001-002-1234567"
                                        pattern="\d{3}-\d{3}-\d{7}">
                                </div>
                                <div class="col-md-4">
                                    <label for="nro_timbrado" class="form-label">N° Timbrado</label>
                                    <input type="text"
                                        class="form-control"
                                        id="nro_timbrado"
                                        name="nro_timbrado"
                                        required
                                        pattern="\d{8}"
                                        maxlength="8"
                                        placeholder="Ej: 12345678"
                                        title="Debe ser un número de 8 dígitos">
                                </div>
                                <div class="col-md-4">
                                    <label for="fecha_emision" class="form-label">Fecha de Emisión</label>
                                    <input type="date" class="form-control" id="fecha_emision" name="fecha_emision" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                                    <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento">
                                </div>
                                <div class="col-md-4">
                                    <label for="condicion_pago_id" class="form-label">Condición de Pago</label>
                                    <select class="form-select" id="condicion_pago_id" name="condicion_pago_id" required disabled>
                                        <option value="{{ $ordenSeleccionada->condicion_pago_id }}">{{ $datosOrden['condicion_pago'] }}</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="metodo_pago_id" class="form-label">Método de Pago</label>
                                    <select class="form-select" id="metodo_pago_id" name="metodo_pago_id" required disabled>
                                        <option value="{{ $ordenSeleccionada->metodo_pago_id }}">{{ $datosOrden['metodo_pago'] }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="observacion" class="form-label">Observación</label>
                                    <textarea class="form-control" id="observacion" name="observacion" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mb-5">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i>Registrar Compra
                        </button>
                    </div>
                @else
                    <div class="alert alert-info">
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
.table th {
    font-size: 0.875rem;
    font-weight: 600;
}
.table td {
    vertical-align: middle;
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
