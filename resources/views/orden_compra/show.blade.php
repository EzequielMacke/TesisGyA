<!-- filepath: c:\laragon\www\TesisGyA\resources\views\orden_compra\show.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Compra #{{ $orden->id }}</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content fade-in">
        <div class="content-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>
                        <i class="fas fa-file-contract me-2 text-primary"></i>
                        Orden de Compra #{{ $orden->id }}
                    </h2>
                    <p class="text-muted mb-0">Detalle de la orden de compra</p>
                </div>
                <div>
                    <a href="{{ route('orden_compra.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <!-- Información general -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información de la Orden</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Proveedor:</strong> {{ $orden->proveedor->razon_social ?? '-' }}</div>
                        <div class="col-md-4"><strong>RUC:</strong> {{ $orden->proveedor->ruc ?? '-' }}</div>
                        <div class="col-md-4"><strong>Fecha:</strong> {{ $orden->fecha ? \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') : '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Condición de Pago:</strong> {{ $orden->condicionPago->descripcion ?? '-' }}</div>
                        <div class="col-md-4"><strong>Método de Pago:</strong> {{ $orden->metodoPago->descripcion ?? '-' }}</div>
                        <div class="col-md-4"><strong>Monto Total:</strong> ₲ {{ number_format($orden->monto, 0, ',', '.') }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Cuotas:</strong> {{ $orden->cuota ?? '-' }}</div>
                        <div class="col-md-4"><strong>Intervalo:</strong> {{ $orden->intervalo ? $orden->intervalo . ' días' : '-' }}</div>
                        <div class="col-md-4"><strong>Estado:</strong> {{ $orden->estado->descripcion ?? '-' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-12"><strong>Observación:</strong> {{ $orden->observacion ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Detalle de la Orden -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Detalle de la Orden</h5>
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
                                    <th class="text-center">Total Item</th>
                                    <th>Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $subtotalGeneral = 0;
                                    $impuestosGeneral = 0;
                                @endphp
                                @foreach($orden->detalles as $detalle)
                                    @php
                                        $cantidad = $detalle->cantidad;
                                        $precioUnitario = round($detalle->precio_unitario);
                                        $subtotal = $cantidad * $precioUnitario;
                                        $impuesto = ($detalle->impuesto_id !== 1 && $detalle->impuesto && $detalle->impuesto->calculo)
                                            ? round($subtotal / $detalle->impuesto->calculo)
                                            : 0;
                                        $totalItem = $subtotal + $impuesto;
                                        $subtotalGeneral += $subtotal;
                                        $impuestosGeneral += $impuesto;
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $detalle->insumo->descripcion ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">
                                                <span class="badge bg-primary me-1">{{ $detalle->insumo->marca->descripcion ?? 'N/A' }}</span>
                                                <span class="badge bg-secondary">{{ $detalle->insumo->unidadMedida->abreviatura ?? 'N/A' }}</span>
                                            </small>
                                        </td>
                                        <td class="text-center">{{ $cantidad }}</td>
                                        <td class="text-center">₲ {{ number_format($precioUnitario, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $detalle->impuesto->descripcion ?? 'N/A' }}</span>
                                            @if($impuesto > 0)
                                                <br><small class="text-muted">₲ {{ number_format($impuesto, 0, ',', '.') }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center"><strong>₲ {{ number_format($subtotal, 0, ',', '.') }}</strong></td>
                                        <td class="text-center"><strong class="text-success">₲ {{ number_format($totalItem, 0, ',', '.') }}</strong></td>
                                        <td>
                                            @if($detalle->observacion)
                                                <div class="observacion-item mt-2"><i class="fas fa-comment me-1"></i>{{ $detalle->observacion }}</div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Resumen financiero -->
                    <div class="row mt-4">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
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
                                        <span class="h5">TOTAL:</span>
                                        <strong class="h5 text-success">₲ {{ number_format($subtotalGeneral + $impuestosGeneral, 0, ',', '.') }}</strong>
                                    </div>
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

@media (max-width: 768px) {
    .main-content {
        margin-left: 50px;
        width: calc(100vw - 50px);
    }
}

.sidebar-nav:hover ~ .main-content {
    margin-left: 280px;
    width: calc(100vw - 280px);
}
</style>
