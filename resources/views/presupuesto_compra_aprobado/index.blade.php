<!-- filepath: c:\laragon\www\TesisGyA\resources\views\presupuesto_compra_aprobado\index.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Presupuestos Aprobados</title>
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
                        <i class="fas fa-check-circle me-2 text-success"></i>
                        Presupuestos Aprobados
                    </h2>
                    <p class="text-muted mb-0">Gestión de presupuestos aprobados para compras</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('presupuesto_compra_aprobado.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Aprobar Presupuesto
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
                    <form method="GET" action="{{ route('presupuesto_compra_aprobado.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="proveedor" class="form-label">Proveedor</label>
                                    <input type="text"
                                           class="form-control"
                                           id="proveedor"
                                           name="proveedor"
                                           value="{{ request('proveedor') }}"
                                           placeholder="Buscar por proveedor...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="fecha_desde" class="form-label">Desde</label>
                                    <input type="date"
                                           class="form-control"
                                           id="fecha_desde"
                                           name="fecha_desde"
                                           value="{{ request('fecha_desde') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="fecha_hasta" class="form-label">Hasta</label>
                                    <input type="date"
                                           class="form-control"
                                           id="fecha_hasta"
                                           name="fecha_hasta"
                                           value="{{ request('fecha_hasta') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary flex-fill">
                                            <i class="fas fa-search me-1"></i>Buscar
                                        </button>
                                        <a href="{{ route('presupuesto_compra_aprobado.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de Presupuestos Aprobados -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Lista de Presupuestos Aprobados
                        <span class="badge bg-light text-dark ms-2">{{ $presupuestos->total() }} registros</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($presupuestos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="8%">ID</th>
                                        <th width="25%">Nombre</th>
                                        <th width="20%">Proveedor</th>
                                        <th width="10%">Pedido</th>
                                        <th width="12%">Fecha Aprobación</th>
                                        <th width="12%">Aprobado Por</th>
                                        <th width="8%">Total</th>
                                        <th width="5%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($presupuestos as $presupuesto)
                                        @php
                                            $total = 0;
                                            foreach($presupuesto->detalles as $detalle) {
                                                $subtotal = $detalle->cantidad * $detalle->precio_unitario;
                                                $impuesto = 0;
                                                if ($detalle->impuesto_id !== 1) {
                                                    $impuesto = round($subtotal / $detalle->impuesto->calculo);
                                                }
                                                $total += $subtotal + $impuesto;
                                            }
                                        @endphp

                                        <tr class="presupuesto-row">
                                            <td>
                                                <span class="badge bg-success fs-6">#{{ $presupuesto->id }}</span>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong class="d-block">{{ $presupuesto->nombre }}</strong>
                                                    @if($presupuesto->descripcion)
                                                        <small class="text-muted">{{ Str::limit($presupuesto->descripcion, 50) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong class="d-block">{{ $presupuesto->proveedor->razon_social }}</strong>
                                                    <small class="text-muted">RUC: {{ $presupuesto->proveedor->ruc }}</small>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary fs-6">#{{ $presupuesto->pedido_compra_id }}</span>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong class="d-block">{{ $presupuesto->fecha_aprobacion->format('d/m/Y') }}</strong>
                                                    <small class="text-muted">{{ $presupuesto->fecha_aprobacion->format('H:i') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong class="d-block">{{ $presupuesto->aprobadoPor->persona->nombre }}</strong>
                                                    <small class="text-muted">{{ $presupuesto->aprobadoPor->persona->apellido }}</small>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <strong class="text-success">₲ {{ number_format($total, 0, ',', '.') }}</strong>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('presupuesto_compra_aprobado.show', $presupuesto->id) }}"
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

                        <!-- Paginación -->
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    Mostrando {{ $presupuestos->firstItem() }} a {{ $presupuestos->lastItem() }}
                                    de {{ $presupuestos->total() }} registros
                                </div>
                                {{ $presupuestos->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay presupuestos aprobados</h5>
                            <p class="text-muted">Aún no se han aprobado presupuestos en el sistema.</p>
                            <a href="{{ route('presupuesto_compra_aprobado.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Aprobar Primer Presupuesto
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

.presupuesto-row:hover {
    background-color: rgba(40, 167, 69, 0.05);
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
