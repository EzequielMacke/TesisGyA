<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Presupuesto #{{ $presupuesto->id }} - {{ $presupuesto->nombre }}</title>
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
                        Detalle del Presupuesto
                    </h2>
                    <p class="text-muted mb-0">{{ $presupuesto->nombre }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('presupuesto_compra.show_pedido', $presupuesto->pedido_compra_id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver al Pedido
                    </a>
                    @if($presupuesto->estado_id == 1)
                        <a href="#" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Editar
                        </a>
                    @endif
                    <button onclick="window.print()" class="btn btn-info">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Información del Presupuesto -->
            <div class="row mb-4">
                <div class="col-lg-8">
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
                                        <label class="form-label text-muted">Presupuesto ID:</label>
                                        <div class="fw-bold">#{{ $presupuesto->id }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Estado:</label>
                                        <div>
                                            @if($presupuesto->estado_id == 1)
                                                <span class="badge bg-warning fs-6">{{ $presupuesto->estado->descripcion }}</span>
                                            @elseif($presupuesto->estado_id == 2)
                                                <span class="badge bg-success fs-6">{{ $presupuesto->estado->descripcion }}</span>
                                            @elseif($presupuesto->estado_id == 4)
                                                <span class="badge bg-danger fs-6">{{ $presupuesto->estado->descripcion }}</span>
                                            @else
                                                <span class="badge bg-secondary fs-6">{{ $presupuesto->estado->descripcion }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Fecha Emisión:</label>
                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($presupuesto->fecha_emision)->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Fecha Vencimiento:</label>
                                        <div class="fw-bold">
                                            {{ \Carbon\Carbon::parse($presupuesto->fecha_vencimiento)->format('d/m/Y') }}
                                            @if(\Carbon\Carbon::parse($presupuesto->fecha_vencimiento)->isPast() && $presupuesto->estado_id == 1)
                                                <span class="badge bg-danger ms-2">Vencido</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Validez:</label>
                                        <div class="fw-bold">{{ $presupuesto->validez }} días</div>
                                    </div>
                                </div>
                            </div>

                            @if($presupuesto->descripcion)
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Descripción:</label>
                                            <div class="bg-light p-3 rounded">{{ $presupuesto->descripcion }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-building me-2"></i>Datos del Proveedor
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted">Razón Social:</label>
                                <div class="fw-bold">{{ $presupuesto->proveedor->razon_social }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">RUC:</label>
                                <div class="fw-bold">{{ $presupuesto->proveedor->ruc }}</div>
                            </div>
                            @if($presupuesto->proveedor->telefono)
                                <div class="mb-3">
                                    <label class="form-label text-muted">Teléfono:</label>
                                    <div class="fw-bold">{{ $presupuesto->proveedor->telefono }}</div>
                                </div>
                            @endif
                            @if($presupuesto->proveedor->email)
                                <div class="mb-3">
                                    <label class="form-label text-muted">Email:</label>
                                    <div class="fw-bold">{{ $presupuesto->proveedor->email }}</div>
                                </div>
                            @endif
                            <div class="mb-0">
                                <label class="form-label text-muted">Creado por:</label>
                                <div class="fw-bold">{{ $presupuesto->usuario->persona->nombre }} {{ $presupuesto->usuario->persona->apellido }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalles del Presupuesto -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Detalles del Presupuesto
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
                                    <th width="12%">Subtotal</th>
                                    <th width="12%">Impuesto ₲</th>
                                    <th width="12%">Total</th>
                                    <th width="12%">Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $subtotalGeneral = 0;
                                    $impuestosGeneral = 0;
                                    $impuestosDetalle = [];
                                @endphp

                                @foreach($presupuesto->detalles as $detalle)
                                    @php
                                        $subtotal = $detalle->cantidad * $detalle->precio_unitario;
                                        $impuesto = 0;

                                        // Calcular impuesto si no es exenta
                                        if ($detalle->impuesto_id !== 1) {
                                            $impuesto = round($subtotal / $detalle->impuesto->calculo);
                                        }

                                        $total = $subtotal + $impuesto;
                                        $subtotalGeneral += $subtotal;
                                        $impuestosGeneral += $impuesto;

                                        // Agrupar impuestos
                                        if (!isset($impuestosDetalle[$detalle->impuesto_id])) {
                                            $impuestosDetalle[$detalle->impuesto_id] = [
                                                'nombre' => $detalle->impuesto->descripcion,
                                                'total' => 0
                                            ];
                                        }
                                        $impuestosDetalle[$detalle->impuesto_id]['total'] += $impuesto;
                                    @endphp

                                    <tr>
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
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info fs-6">{{ number_format($detalle->cantidad, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="text-center">
                                            <strong>₲ {{ number_format($detalle->precio_unitario, 0, ',', '.') }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $detalle->impuesto->descripcion }}</span>
                                            <small class="d-block">({{ $detalle->impuesto->calculo }}%)</small>
                                        </td>
                                        <td class="text-center">
                                            <strong class="text-primary">₲ {{ number_format($subtotal, 0, ',', '.') }}</strong>
                                        </td>
                                        <td class="text-center">
                                            @if($detalle->impuesto_id === 1)
                                                <span class="text-muted">Exenta</span>
                                            @else
                                                <strong class="text-success">₲ {{ number_format($impuesto, 0, ',', '.') }}</strong>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <strong class="text-dark fs-6">₲ {{ number_format($total, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            @if($detalle->observacion)
                                                <div class="bg-light p-2 rounded border-start border-3 border-warning">
                                                    <small class="text-dark">{{ $detalle->observacion }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted small">
                                                    <i class="fas fa-minus me-1"></i>Sin observación
                                                </span>
                                            @endif
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
                <div class="card-header bg-dark text-white">
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
                                @foreach($impuestosDetalle as $impuestoInfo)
                                    <div class="d-flex justify-content-between align-items-center mb-2 p-3 bg-white rounded border-start border-3 border-primary">
                                        <span class="fw-bold text-primary">{{ $impuestoInfo['nombre'] }}</span>
                                        <strong class="text-success">
                                            @if($impuestoInfo['total'] > 0)
                                                ₲ {{ number_format($impuestoInfo['total'], 0, ',', '.') }}
                                            @else
                                                Sin impuesto
                                            @endif
                                        </strong>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="bg-primary text-white p-4 rounded">
                                <h6 class="mb-3">
                                    <i class="fas fa-chart-pie me-2"></i>Totales Generales
                                </h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total sin impuestos:</span>
                                    <strong>₲ {{ number_format($subtotalGeneral, 0, ',', '.') }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total impuestos:</span>
                                    <strong>₲ {{ number_format($impuestosGeneral, 0, ',', '.') }}</strong>
                                </div>
                                <hr class="my-3" style="border-color: rgba(255,255,255,0.3);">
                                <div class="d-flex justify-content-between">
                                    <span class="h5">TOTAL FINAL:</span>
                                    <strong class="h4">₲ {{ number_format($subtotalGeneral + $impuestosGeneral, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Pedido Original -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>Información del Pedido Original
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">Pedido ID:</label>
                                <div class="fw-bold">#{{ $presupuesto->pedidoCompra->id }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">Fecha Pedido:</label>
                                <div class="fw-bold">{{ \Carbon\Carbon::parse($presupuesto->pedidoCompra->fecha_pedido)->format('d/m/Y') }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">Sucursal:</label>
                                <div class="fw-bold">{{ $presupuesto->pedidoCompra->sucursal->descripcion }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-muted">Depósito:</label>
                                <div class="fw-bold">{{ $presupuesto->pedidoCompra->deposito->descripcion }}</div>
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

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
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

/* Estilos para impresión */
@media print {
    .main-content {
        margin-left: 0;
        width: 100%;
    }

    .btn, .alert {
        display: none !important;
    }

    .card {
        box-shadow: none;
        border: 1px solid #dee2e6;
    }
}
</style>
