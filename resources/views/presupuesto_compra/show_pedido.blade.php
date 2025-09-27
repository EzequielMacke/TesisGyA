<!-- filepath: c:\laragon\www\TesisGyA\resources\views\presupuesto_compra\show_pedido.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Pedido #{{ $pedido->id }} - Presupuestos</title>
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
                        <i class="fas fa-shopping-cart me-2 text-primary"></i>
                        Pedido de Compra #{{ $pedido->id }}
                    </h2>
                    <p class="text-muted mb-0">Detalles completos del pedido y presupuestos asociados</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('presupuesto_compra.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                    </a>
                    <button type="button" class="btn btn-success" onclick="crearPresupuesto()">
                        <i class="fas fa-plus me-2"></i>Crear Presupuesto
                    </button>
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

            <div class="row">
                <!-- Información del Pedido -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Información del Pedido
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-group mb-3">
                                        <label class="text-muted small d-block">Usuario Solicitante:</label>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user text-primary me-2"></i>
                                            <strong>{{ $pedido->usuario->usuario ?? 'N/A' }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group mb-3">
                                        <label class="text-muted small d-block">Fecha del Pedido:</label>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-calendar text-primary me-2"></i>
                                            <strong>{{ $pedido->fecha->format('d/m/Y') }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group mb-3">
                                        <label class="text-muted small d-block">Sucursal:</label>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-building text-success me-2"></i>
                                            <strong>{{ $pedido->sucursal->descripcion }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group mb-3">
                                        <label class="text-muted small d-block">Depósito:</label>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-warehouse text-info me-2"></i>
                                            <strong>{{ $pedido->deposito->descripcion }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="info-group">
                                        <label class="text-muted small d-block">Estado:</label>
                                        <span class="badge bg-warning text-dark fs-6">
                                            <i class="fas fa-clock me-1"></i>{{ $pedido->estado->descripcion }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @if($pedido->observacion)
                                <div class="mt-4">
                                    <label class="text-muted small d-block mb-2">Observación General:</label>
                                    <div class="bg-light p-3 rounded border-start border-4 border-info">
                                        <i class="fas fa-comment text-info me-2"></i>
                                        {{ $pedido->observacion }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Detalles de Insumos -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2"></i>Insumos Solicitados
                                <span class="badge bg-light text-dark ms-2">{{ $pedido->detalles->count() }} items</span>
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="35%">Insumo</th>
                                            <th width="15%">Marca</th>
                                            <th width="10%">Unidad</th>
                                            <th width="10%">Cantidad</th>
                                            <th width="25%">Observación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pedido->detalles as $index => $detalle)
                                            <tr>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2">
                                                            <i class="fas fa-cube text-primary fa-lg"></i>
                                                        </div>
                                                        <div>
                                                            <strong class="text-dark">{{ $detalle->insumo->descripcion }}</strong>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $detalle->insumo->marca->descripcion }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $detalle->insumo->unidadMedida->abreviatura ?? $detalle->insumo->unidadMedida->descripcion }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <strong class="text-primary">{{ number_format($detalle->cantidad, 2, ',', '.') }}</strong>
                                                </td>
                                                <td>
                                                    @if($detalle->observacion)
                                                        <div class="bg-light p-2 rounded border-start border-3 border-info">
                                                            <small class="text-muted d-block mb-1">
                                                                <i class="fas fa-comment me-1"></i>Observación:
                                                            </small>
                                                            <span class="small text-dark">{{ $detalle->observacion }}</span>
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
                </div>

                <!-- Presupuestos Existentes -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-file-invoice-dollar me-2"></i>Presupuestos
                                <span class="badge bg-light text-dark ms-2">{{ $pedido->presupuestos->count() }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($pedido->presupuestos->count() > 0)
                                <div class="presupuestos-list">
                                    @foreach($pedido->presupuestos as $presupuesto)
                                        <div class="presupuesto-item p-3 mb-3 rounded border {{ $loop->first ? 'border-success bg-light' : 'border-secondary' }}">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="mb-0">{{ $presupuesto->nombre }}</h6>
                                                <span class="badge {{ $presupuesto->estado_id == 3 ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $presupuesto->estado->descripcion }}
                                                </span>
                                            </div>

                                            <div class="mb-2">
                                                <small class="text-muted d-block">Proveedor:</small>
                                                <strong class="text-primary">{{ $presupuesto->proveedor->razon_social }}</strong>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Emisión:</small>
                                                    <small class="text-dark">{{ $presupuesto->fecha_emision->format('d/m/Y') }}</small>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Validez:</small>
                                                    <small class="text-dark">{{ $presupuesto->validez }} días</small>
                                                </div>
                                            </div>

                                            @if($presupuesto->descripcion)
                                                <div class="mb-3">
                                                    <small class="text-muted d-block">Descripción:</small>
                                                    <p class="small text-dark mb-0">{{ Str::limit($presupuesto->descripcion, 80) }}</p>
                                                </div>
                                            @endif

                                            <div class="d-grid gap-1">
                                                <a href="{{ route('presupuesto_compra.show', $presupuesto->id) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Ver Detalles">
                                                    <i class="fas fa-eye"></i>Ver Detalles
                                                </a>
                                                @if($presupuesto->estado_id == 1)
                                                    <button class="btn btn-outline-warning btn-sm" onclick="editarPresupuesto({{ $presupuesto->id }})">
                                                        <i class="fas fa-edit me-1"></i>Editar
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted">No hay presupuestos</h6>
                                    <p class="text-muted small mb-3">Este pedido aún no tiene presupuestos asociados.</p>
                                    <button class="btn btn-success btn-sm" onclick="crearPresupuesto()">
                                        <i class="fas fa-plus me-2"></i>Crear Primer Presupuesto
                                    </button>
                                </div>
                            @endif
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

.card:hover {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.info-group {
    background-color: #f8f9fa;
    padding: 0.75rem;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.table th {
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    font-size: 0.875rem;
}

.table td {
    vertical-align: middle;
}

.presupuesto-item {
    transition: all 0.3s ease;
}

.presupuesto-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.badge {
    font-size: 0.75rem;
}

.btn-sm {
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 50px;
        width: calc(100vw - 50px);
    }

    .content-wrapper {
        padding: 15px;
    }

    .table-responsive {
        font-size: 0.875rem;
    }
}

.sidebar-nav:hover ~ .main-content {
    margin-left: 280px;
    width: calc(100vw - 280px);
}
</style>

<script>
function crearPresupuesto() {
    window.location.href = `{{ route('presupuesto_compra.create', $pedido->id) }}`;
}

function verPresupuesto(id) {
    // Redirigir a ver detalles del presupuesto
    window.location.href = `#`; // Aquí irá la ruta de ver presupuesto
}

function editarPresupuesto(id) {
    // Redirigir a editar presupuesto
    window.location.href = `#`; // Aquí irá la ruta de editar presupuesto
}

// Función para imprimir el pedido
function imprimirPedido() {
    window.print();
}

// Efecto de carga suave
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.5s ease';

            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        }, index * 100);
    });
});
</script>
