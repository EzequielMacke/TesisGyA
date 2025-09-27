<!-- filepath: c:\laragon\www\TesisGyA\resources\views\presupuesto_compra_aprobado\show.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Presupuesto Aprobado #{{ $presupuestoAprobado->id }}</title>
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
                        <i class="fas fa-file-invoice-dollar me-2 text-success"></i>
                        Presupuesto Aprobado #{{ $presupuestoAprobado->id }}
                    </h2>
                    <p class="text-muted mb-0">Detalle completo del presupuesto aprobado</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('presupuesto_compra_aprobado.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                    </a>
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                </div>
            </div>

            <!-- Información General -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Información General
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Nombre:</label>
                                        <div class="fw-bold">{{ $presupuestoAprobado->nombre }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Estado:</label>
                                        <div>
                                            <span class="badge bg-success fs-6">
                                                {{ $presupuestoAprobado->estado->descripcion ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Fecha de Emisión:</label>
                                        <div class="fw-bold">
                                            {{ $presupuestoAprobado->fecha_emision ? \Carbon\Carbon::parse($presupuestoAprobado->fecha_emision)->format('d/m/Y') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Fecha de Vencimiento:</label>
                                        <div class="fw-bold">
                                            {{ $presupuestoAprobado->fecha_vencimiento ? \Carbon\Carbon::parse($presupuestoAprobado->fecha_vencimiento)->format('d/m/Y') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($presupuestoAprobado->descripcion)
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Descripción:</label>
                                            <div class="bg-light p-3 rounded">{{ $presupuestoAprobado->descripcion }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-building me-2"></i>Proveedor
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted">Razón Social:</label>
                                <div class="fw-bold">{{ $presupuestoAprobado->proveedor->razon_social ?? 'N/A' }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">RUC:</label>
                                <div class="fw-bold">{{ $presupuestoAprobado->proveedor->ruc ?? 'N/A' }}</div>
                            </div>
                            @if($presupuestoAprobado->proveedor->telefono)
                                <div class="mb-3">
                                    <label class="form-label text-muted">Teléfono:</label>
                                    <div>{{ $presupuestoAprobado->proveedor->telefono }}</div>
                                </div>
                            @endif
                            @if($presupuestoAprobado->proveedor->email)
                                <div class="mb-3">
                                    <label class="form-label text-muted">Email:</label>
                                    <div>{{ $presupuestoAprobado->proveedor->email }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Pedido -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>Información del Pedido
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">Pedido ID:</label>
                                <div class="fw-bold">#{{ $presupuestoAprobado->pedido_compra_id }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">Sucursal:</label>
                                <div class="fw-bold">{{ $presupuestoAprobado->pedidoCompra->sucursal->nombre ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">Depósito:</label>
                                <div class="fw-bold">{{ $presupuestoAprobado->pedidoCompra->deposito->nombre ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">Fecha Pedido:</label>
                                <div class="fw-bold">
                                    {{ $presupuestoAprobado->pedidoCompra->fecha ? \Carbon\Carbon::parse($presupuestoAprobado->pedidoCompra->fecha)->format('d/m/Y') : 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalles del Presupuesto -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Detalles del Presupuesto
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Insumo</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Precio Unit.</th>
                                    <th class="text-center">Impuesto</th>
                                    <th class="text-center">Subtotal</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $subtotalGeneral = 0;
                                    $impuestosGeneral = 0;
                                @endphp

                                @foreach($presupuestoAprobado->detalles as $detalle)
                                    @php
                                        $subtotal = $detalle->cantidad * $detalle->precio_unitario;
                                        $impuesto = 0;

                                        if ($detalle->impuesto_id !== 1 && $detalle->impuesto && $detalle->impuesto->calculo) {
                                            $impuesto = round($subtotal / $detalle->impuesto->calculo);
                                        }

                                        $totalItem = $subtotal + $impuesto;
                                        $subtotalGeneral += $subtotal;
                                        $impuestosGeneral += $impuesto;
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $detalle->insumo->descripcion ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">
                                                <span class="badge bg-primary me-1">{{ $detalle->insumo->marca->descripcion ?? 'N/A' }}</span>
                                                <span class="badge bg-secondary">{{ $detalle->insumo->unidadMedida->abreviatura ?? $detalle->insumo->unidadMedida->descripcion ?? 'N/A' }}</span>
                                            </small>
                                            @if($detalle->observacion)
                                                <div class="observacion-detalle mt-2">
                                                    <i class="fas fa-comment me-1"></i>{{ $detalle->observacion }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info fs-6">{{ number_format($detalle->cantidad, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="text-center">
                                            <strong>₲ {{ number_format($detalle->precio_unitario, 0, ',', '.') }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $detalle->impuesto->descripcion ?? 'N/A' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <strong>₲ {{ number_format($subtotal, 0, ',', '.') }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <strong class="text-success">₲ {{ number_format($totalItem, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Resumen Financiero -->
            <div class="row mb-4">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-calculator me-2"></i>Resumen Financiero
                            </h5>
                        </div>
                        <div class="card-body bg-light">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <strong>₲ {{ number_format($subtotalGeneral, 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Impuestos:</span>
                                <strong>₲ {{ number_format($impuestosGeneral, 0, ',', '.') }}</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="h5">TOTAL FINAL:</span>
                                <strong class="h4 text-success">₲ {{ number_format($subtotalGeneral + $impuestosGeneral, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de Aprobación -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-check me-2"></i>Información de Aprobación
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Creado por:</label>
                                <div class="fw-bold">
                                    {{ $presupuestoAprobado->usuario->persona->nombre ?? 'N/A' }}
                                    {{ $presupuestoAprobado->usuario->persona->apellido ?? '' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Aprobado por:</label>
                                <div class="fw-bold">
                                    {{ $presupuestoAprobado->aprobadoPor->persona->nombre ?? 'N/A' }}
                                    {{ $presupuestoAprobado->aprobadoPor->persona->apellido ?? '' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Fecha de Aprobación:</label>
                                <div class="fw-bold">
                                    {{ $presupuestoAprobado->fecha_aprobacion ? \Carbon\Carbon::parse($presupuestoAprobado->fecha_aprobacion)->format('d/m/Y H:i') : 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

.observacion-detalle {
    background-color: #f8f9fa;
    border-left: 4px solid #007bff;
    padding: 8px 12px;
    border-radius: 4px;
    font-style: italic;
    font-size: 0.85rem;
}

@media print {
    .btn, .sidebar-nav, .footer {
        display: none !important;
    }

    .main-content {
        margin-left: 0 !important;
        width: 100% !important;
    }

    .card {
        box-shadow: none !important;
        border: 1px solid #dee2e6 !important;
    }
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
