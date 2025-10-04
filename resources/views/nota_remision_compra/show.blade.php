<!-- filepath: c:\laragon\www\TesisGyA\resources\views\nota_remision_compra\show.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Nota de Remisión #{{ $nota->id }}</title>
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
                        <i class="fas fa-truck me-2 text-primary"></i>
                        Detalle Nota de Remisión #{{ $nota->id }}
                    </h2>
                    <p class="text-muted mb-0">Visualización completa de la nota y su orden de compra</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('nota_remision_compra.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <!-- Datos principales de la Nota de Remisión -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>Datos de la Nota de Remisión
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label class="form-label text-muted">N° de Remisión:</label>
                            <div class="fw-bold">{{ $nota->numero_remision ?? $nota->nro }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Nombre de la Remisión:</label>
                            <div class="fw-bold">{{ $nota->nombre_remision ?? $nota->nombre }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Fecha de Emisión:</label>
                            <div class="fw-bold">{{ \Carbon\Carbon::parse($nota->fecha_emision)->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Fecha de Recepción:</label>
                            <div class="fw-bold">{{ \Carbon\Carbon::parse($nota->fecha_recepcion)->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label class="form-label text-muted">Depósito:</label>
                            <div class="fw-bold">{{ $nota->deposito->descripcion ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Recibido por:</label>
                            <div class="fw-bold">{{ $nota->recibidoPor->persona->nombre ?? '-' }} {{ $nota->recibidoPor->persona->apellido ?? '' }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Conductor:</label>
                            <div class="fw-bold">{{ $nota->conductor_nombre ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">CI Conductor:</label>
                            <div class="fw-bold">{{ $nota->conductor_ci ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label class="form-label text-muted">Chapa Vehículo:</label>
                            <div class="fw-bold">{{ $nota->vehiculo_chapa ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Tipo de Vehículo:</label>
                            <div class="fw-bold">{{ $nota->tipoVehiculo->descripcion ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Origen:</label>
                            <div class="fw-bold">{{ $nota->origen ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Destino:</label>
                            <div class="fw-bold">{{ $nota->destino ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label class="form-label text-muted">Proveedor:</label>
                            <div class="fw-bold">{{ $nota->proveedor->razon_social ?? ($orden->proveedor->razon_social ?? '-') }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">RUC Proveedor:</label>
                            <div class="fw-bold">{{ $nota->proveedor->ruc ?? ($orden->proveedor->ruc ?? '-') }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Email Proveedor:</label>
                            <div class="fw-bold">{{ $nota->proveedor->email ?? ($orden->proveedor->email ?? '-') }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Teléfono Proveedor:</label>
                            <div class="fw-bold">{{ $nota->proveedor->telefono ?? ($orden->proveedor->telefono ?? '-') }}</div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label class="form-label text-muted">Observación:</label>
                            <div class="fw-bold">{{ $nota->observacion ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalles de la Nota de Remisión -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Detalles de la Nota de Remisión
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Insumo</th>
                                    <th>Marca</th>
                                    <th>Unidad</th>
                                    <th>Cant. Pedida</th>
                                    <th>Cant. Recibida</th>
                                    <th>Precio Unitario</th>
                                    <th>Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nota->detalles as $detalle)
                                    <tr>
                                        <td>{{ $detalle->insumo->descripcion ?? '-' }}</td>
                                        <td>{{ $detalle->insumo->marca->descripcion ?? '-' }}</td>
                                        <td>{{ $detalle->insumo->unidadMedida->descripcion ?? '-' }}</td>
                                        <td>{{ $detalle->cantidad_pedida }}</td>
                                        <td>{{ $detalle->cantidad_entregada }}</td>
                                        <td>
                                            {{ isset($precios[$detalle->insumo_id]) ? '₲ ' . number_format($precios[$detalle->insumo_id], 0, ',', '.') : '-' }}
                                        </td>
                                        <td>{{ $detalle->observacion ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Información de la Orden de Compra -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-invoice me-2"></i>Orden de Compra Asociada
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label class="form-label text-muted">N° Orden:</label>
                            <div class="fw-bold">#{{ $orden->id }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Proveedor:</label>
                            <div class="fw-bold">{{ $orden->proveedor->razon_social ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">RUC:</label>
                            <div class="fw-bold">{{ $orden->proveedor->ruc ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Fecha Orden:</label>
                            <div class="fw-bold">{{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label class="form-label text-muted">Monto Total:</label>
                            <div class="fw-bold">₲ {{ number_format($orden->monto, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Estado:</label>
                            <div class="fw-bold">{{ $orden->estado->descripcion ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Insumo</th>
                                    <th>Marca</th>
                                    <th>Unidad</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Subtotal</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orden->detalles as $det)
                                    <tr>
                                        <td>{{ $det->insumo->descripcion ?? '-' }}</td>
                                        <td>{{ $det->insumo->marca->descripcion ?? '-' }}</td>
                                        <td>{{ $det->insumo->unidadMedida->descripcion ?? '-' }}</td>
                                        <td>{{ $det->cantidad }}</td>
                                        <td>
                                            {{ isset($precios[$det->insumo_id]) ? '₲ ' . number_format($precios[$det->insumo_id], 0, ',', '.') : '-' }}
                                        </td>
                                        <td>
                                            @if(isset($precios[$det->insumo_id]))
                                                ₲ {{ number_format($det->cantidad * $precios[$det->insumo_id], 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $det->estado->descripcion ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @include('partials.footer')
</body>
</html>
