<!-- filepath: c:\laragon\www\TesisGyA\resources\views\orden_compra\index.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Órdenes de Compra</title>
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
                        <i class="fas fa-file-contract me-2 text-primary"></i>
                        Órdenes de Compra
                    </h2>
                    <p class="text-muted mb-0">Gestión de órdenes de compra generadas desde presupuestos aprobados</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('orden_compra.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nueva Orden
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-filter me-2"></i>Filtros de Búsqueda
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('orden_compra.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select class="form-select" id="estado" name="estado">
                                        <option value="">Todos los estados</option>
                                        <option value="3" {{ request('estado') == '3' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="4" {{ request('estado') == '4' ? 'selected' : '' }}>Confirmado</option>
                                        <option value="5" {{ request('estado') == '5' ? 'selected' : '' }}>Anulado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="proveedor" class="form-label">Proveedor</label>
                                    <select class="form-select" id="proveedor" name="proveedor">
                                        <option value="">Todos los proveedores</option>
                                        @foreach($proveedores as $proveedor)
                                            <option value="{{ $proveedor->id }}" {{ request('proveedor') == $proveedor->id ? 'selected' : '' }}>
                                                {{ $proveedor->razon_social }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="fecha_desde" class="form-label">Fecha Desde</label>
                                    <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" value="{{ request('fecha_desde') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                                    <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <a href="{{ route('orden_compra.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de Órdenes -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Lista de Órdenes de Compra
                        </h5>
                        <span class="badge bg-light text-dark fs-6">{{ $ordenes->count() }} órdenes</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($ordenes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha</th>
                                        <th>Proveedor</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th>Condición Pago</th>
                                        <th>Usuario</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ordenes as $orden)
                                        <tr class="orden-row">
                                            <td>
                                                <strong class="text-primary">#{{ $orden->id }}</strong>
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($orden->created_at)->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $orden->proveedor->razon_social ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $orden->proveedor->ruc ?? 'Sin RUC' }}</small>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-success">₲ {{ number_format($orden->monto, 0, ',', '.') }}</div>
                                                @if($orden->condicionPago && $orden->cuota > 1)
                                                    <small class="text-muted">{{ $orden->cuota }} cuotas</small>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $estadoClass = match($orden->estado_id) {
                                                        3 => 'bg-warning',
                                                        4 => 'bg-success',
                                                        5 => 'bg-danger',
                                                        default => 'bg-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $estadoClass }} fs-6">
                                                    {{ $orden->estado->descripcion ?? 'Sin estado' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $orden->condicionPago->descripcion ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $orden->metodoPago->descripcion ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $orden->usuario->persona->nombre ?? 'N/A' }} {{ $orden->usuario->persona->apellido ?? '' }}</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($orden->created_at)->format('d/m/Y') }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="#"
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($orden->estado_id == 3)
                                                        <a href="#"
                                                           class="btn btn-sm btn-outline-warning"
                                                           title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button onclick="anularOrden({{ $orden->id }})"
                                                                class="btn btn-sm btn-outline-danger"
                                                                title="Anular">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    @endif
                                                    <button onclick="window.print()"
                                                            class="btn btn-sm btn-outline-info"
                                                            title="Imprimir">
                                                        <i class="fas fa-print"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-contract fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay órdenes de compra</h5>
                            <p class="text-muted">No se encontraron órdenes de compra con los filtros aplicados.</p>
                            <a href="{{ route('orden_compra.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Crear Primera Orden
                            </a>
                        </div>
                    @endif
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

.orden-row {
    transition: all 0.3s ease;
}

.orden-row:hover {
    background-color: rgba(0, 123, 255, 0.05);
    transform: translateY(-1px);
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

<script>
function anularOrden(id) {
    if (confirm('¿Está seguro de anular esta orden de compra?\n\nEsta acción no se puede deshacer.')) {
        // Crear formulario para enviar POST
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/orden-compra/${id}/anular`;
        form.style.display = 'none';

        // Token CSRF
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfInput);

        // Agregar al DOM y enviar
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
