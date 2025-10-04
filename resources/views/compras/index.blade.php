<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compras (Facturas de Proveedor)</title>
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
                        Compras (Facturas de Proveedor)
                    </h2>
                    <p class="text-muted mb-0">Gestión de facturas de compra y sus relaciones</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('compras.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nueva Compra
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
                    <form method="GET" action="{{ route('compras.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="proveedor_id" class="form-label">Proveedor</label>
                                    <select class="form-select" id="proveedor_id" name="proveedor_id">
                                        <option value="">Todos los proveedores</option>
                                        @foreach($proveedores as $proveedor)
                                            <option value="{{ $proveedor->id }}" {{ request('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                                {{ $proveedor->razon_social }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="estado_id" class="form-label">Estado</label>
                                    <select class="form-select" id="estado_id" name="estado_id">
                                        <option value="">Todos los estados</option>
                                        @foreach($estados as $estado)
                                            <option value="{{ $estado->id }}" {{ request('estado_id') == $estado->id ? 'selected' : '' }}>
                                                {{ $estado->descripcion }}
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
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de Compras -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Lista de Compras
                        </h5>
                        <span class="badge bg-light text-dark fs-6">{{ $compras->count() }} compras</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($compras->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>N° Factura</th>
                                        <th>Proveedor</th>
                                        <th>Fecha Emisión</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th>Orden Compra</th>
                                        <th>Usuario</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($compras as $compra)
                                        <tr>
                                            <td>
                                                <strong class="text-primary">{{ $compra->nro_factura }}</strong>
                                                <small class="text-muted">Timbrado: {{ $compra->nro_timbrado ?? '-' }}</small>
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $compra->proveedor->razon_social ?? '-' }}</div>
                                                <small class="text-muted">{{ $compra->proveedor->ruc ?? 'Sin RUC' }}</small>
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ \Carbon\Carbon::parse($compra->fecha_emision)->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($compra->created_at)->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="fw-bold">₲ {{ number_format($compra->monto, 0, ',', '.') }}</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary fs-6">
                                                    {{ $compra->estado->descripcion ?? 'Sin estado' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($compra->ordenCompra)
                                                    <strong class="text-primary">#{{ $compra->ordenCompra->id }}</strong>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $compra->usuario->persona->nombre ?? 'N/A' }} {{ $compra->usuario->persona->apellido ?? '' }}</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($compra->created_at)->format('d/m/Y') }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="#"
                                                        class="btn btn-sm btn-outline-primary"
                                                        title="Ver Detalles">
                                                            <i class="fas fa-eye"></i>
                                                    </a>
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
                            <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay compras registradas</h5>
                            <p class="text-muted">No se encontraron compras con los filtros aplicados.</p>
                            <a href="{{ route('compras.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Registrar Primera Compra
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
