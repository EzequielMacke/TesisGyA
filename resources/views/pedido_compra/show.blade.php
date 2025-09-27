<!-- filepath: c:\laragon\www\TesisGyA\resources\views\pedido_compra\show.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Pedido #{{ str_pad($pedido->id, 3, '0', STR_PAD_LEFT) }} - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content fade-in">
        <div class="content-wrapper">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fas fa-eye me-2"></i>Detalle del Pedido</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('pedido_compra.index') }}" class="text-decoration-none">
                                    <i class="fas fa-shopping-cart me-1"></i>Pedidos de Compra
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Pedido #{{ str_pad($pedido->id, 3, '0', STR_PAD_LEFT) }}
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    @if($pedido->estado_id == 3)
                        <a href="{{ route('pedido_compra.edit', $pedido->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Editar
                        </a>
                        <button type="button" class="btn btn-danger" onclick="anularPedido({{ $pedido->id }})">
                            <i class="fas fa-ban me-2"></i>Anular Pedido
                        </button>
                    @endif
                    <button type="button" class="btn btn-info" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                    <a href="{{ route('pedido_compra.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Información General -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Información General
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="fw-bold text-muted small">CÓDIGO DEL PEDIDO</label>
                                        <div class="info-value">
                                            <span class="badge bg-dark fs-6 px-3 py-2">
                                                #{{ str_pad($pedido->id, 3, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="fw-bold text-muted small">ESTADO</label>
                                        <div class="info-value">
                                            <span class="badge fs-6 px-3 py-2
                                                @switch($pedido->estado_id)
                                                    @case(3) bg-warning text-dark @break
                                                    @case(4) bg-success @break
                                                    @case(5) bg-danger @break
                                                    @default bg-secondary @break
                                                @endswitch">
                                                @switch($pedido->estado_id)
                                                    @case(3)
                                                        <i class="fas fa-clock me-1"></i>Pendiente
                                                    @break
                                                    @case(4)
                                                        <i class="fas fa-check me-1"></i>Confirmado
                                                    @break
                                                    @case(5)
                                                        <i class="fas fa-times me-1"></i>Anulado
                                                    @break
                                                    @default
                                                        {{ $pedido->estado->descripcion }}
                                                    @break
                                                @endswitch
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="fw-bold text-muted small">USUARIO SOLICITANTE</label>
                                        <div class="info-value">
                                            <i class="fas fa-user me-2 text-primary"></i>
                                            <span class="fw-bold">{{ $pedido->usuario->usuario }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="fw-bold text-muted small">FECHA DE PEDIDO</label>
                                        <div class="info-value">
                                            <i class="fas fa-calendar me-2 text-primary"></i>
                                            <span class="fw-bold">{{ $pedido->fecha->format('d/m/Y') }}</span>
                                            <small class="text-muted ms-2">{{ $pedido->created_at->format('H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="fw-bold text-muted small">SUCURSAL</label>
                                        <div class="info-value">
                                            <i class="fas fa-building me-2 text-primary"></i>
                                            <span class="fw-bold">{{ $pedido->sucursal->descripcion }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="fw-bold text-muted small">DEPÓSITO</label>
                                        <div class="info-value">
                                            <i class="fas fa-warehouse me-2 text-primary"></i>
                                            <span class="fw-bold">{{ $pedido->deposito->descripcion }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($pedido->observacion)
                                <div class="mt-4">
                                    <label class="fw-bold text-muted small">OBSERVACIONES</label>
                                    <div class="bg-light p-3 rounded border-start border-4 border-info">
                                        <i class="fas fa-comment me-2 text-info"></i>
                                        {{ $pedido->observacion }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Estadísticas Rápidas -->
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="card bg-gradient-primary text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-boxes fa-3x mb-3 opacity-75"></i>
                                    <h3 class="mb-1">{{ $pedido->detalles->count() }}</h3>
                                    <p class="mb-0">Productos Solicitados</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="card bg-gradient-success text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-sort-numeric-up fa-3x mb-3 opacity-75"></i>
                                    <h3 class="mb-1">{{ number_format($pedido->detalles->sum('cantidad'), 0, ',', '.') }}</h3>
                                    <p class="mb-0">Cantidad Total</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="card bg-gradient-info text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-clock fa-3x mb-3 opacity-75"></i>
                                    <h4 class="mb-1">{{ $pedido->created_at->diffForHumans() }}</h4>
                                    <p class="mb-0">Tiempo Transcurrido</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalle de Productos -->
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Detalle de Productos
                    </h5>
                    <span class="badge bg-light text-dark">
                        {{ $pedido->detalles->count() }} producto(s)
                    </span>
                </div>
                <div class="card-body p-0">
                    @if($pedido->detalles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%" class="text-center">#</th>
                                        <th width="40%">Producto</th>
                                        <th width="15%" class="text-center">Cantidad</th>
                                        <th width="20%" class="text-center">Unidad</th>
                                        <th width="20%" class="text-center">Observación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pedido->detalles as $index => $detalle)
                                        <tr class="align-middle">
                                            <td class="text-center">
                                                <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="product-icon me-3">
                                                        <i class="fas fa-cube fa-2x text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1 fw-bold text-primary">
                                                            {{ $detalle->insumo->descripcion }}
                                                        </h6>
                                                        @if($detalle->insumo->marca->descripcion)
                                                            <small class="text-muted">
                                                                <i class="fas fa-tag me-1"></i>{{ $detalle->insumo->marca->descripcion }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary fs-6 px-3 py-2">
                                                    {{ number_format($detalle->cantidad, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-muted">
                                                    {{ $detalle->insumo->UnidadMedida->descripcion ?? 'Unidad' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($detalle->observacion)
                                                    <div class="bg-light p-2 rounded border-start border-3 border-info">
                                                        <small class="text-muted d-block mb-1">
                                                            <i class="fas fa-comment me-1"></i>Observación:
                                                        </small>
                                                        <span class="text-dark">{{ $detalle->observacion }}</span>
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
                                <tfoot class="table-secondary">
                                    <tr>
                                        <th colspan="3" class="text-end">TOTAL:</th>
                                        <th class="text-center">
                                            <span class="badge bg-dark fs-6 px-3 py-2">
                                                {{ number_format($pedido->detalles->sum('cantidad'), 0, ',', '.') }}
                                            </span>
                                        </th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No hay productos en este pedido</h4>
                            <p class="text-muted">Este pedido no tiene productos asociados.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información de Auditoría -->
            <div class="card mt-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>Información de Auditoría
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-plus-circle me-1"></i>
                                <strong>Creado:</strong> {{ $pedido->created_at->format('d/m/Y H:i:s') }}
                                ({{ $pedido->created_at->diffForHumans() }})
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-edit me-1"></i>
                                <strong>Última modificación:</strong> {{ $pedido->updated_at->format('d/m/Y H:i:s') }}
                                ({{ $pedido->updated_at->diffForHumans() }})
                            </small>
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
/* Estilos base */
.main-content {
    margin-left: 60px;
    width: calc(100vw - 60px);
    min-height: 100vh;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
    overflow-x: auto;
    box-sizing: border-box;
}

.content-wrapper {
    padding: 20px;
    min-height: calc(100vh - 40px);
    box-sizing: border-box;
}

/* Cards con sombras suaves */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    overflow: hidden;
}

.card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
}

/* Headers de cards */
.card-header {
    border: none;
    font-weight: 600;
    padding: 1rem 1.5rem;
}

.card-body {
    padding: 1.5rem;
}

/* Elementos de información */
.info-item {
    position: relative;
}

.info-item label {
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
    display: block;
}

.info-value {
    font-size: 0.95rem;
    color: #495057;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e9ecef;
}

/* Badges mejorados */
.badge {
    font-weight: 500;
    letter-spacing: 0.3px;
}

.badge.fs-6 {
    font-size: 0.875rem !important;
    padding: 0.5rem 0.75rem;
}

/* Cards con gradientes */
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);
}

/* Tabla de productos */
.table th {
    border: none;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
}

.table td {
    border: none;
    border-bottom: 1px solid #e9ecef;
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
    transform: scale(1.001);
    transition: all 0.2s ease;
}

/* Ícono de producto */
.product-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 123, 255, 0.1);
    border-radius: 8px;
}

/* Breadcrumb personalizado */
.breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
    font-size: 0.875rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #6c757d;
    font-weight: bold;
}

/* Botones mejorados */
.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.625rem 1.25rem;
    transition: all 0.3s ease;
    border: none;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Font monospace para códigos */
.font-monospace {
    font-family: 'Courier New', Courier, monospace;
    font-weight: bold;
}

/* Responsive */
@media (max-width: 768px) {
    .main-content {
        margin-left: 50px;
        width: calc(100vw - 50px);
    }

    .content-wrapper {
        padding: 15px;
    }

    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }

    .d-flex.gap-2 {
        justify-content: center;
    }

    .table-responsive {
        font-size: 0.875rem;
    }
}

/* Ajuste del menú lateral */
.sidebar-nav:hover ~ .main-content {
    margin-left: 280px;
    width: calc(100vw - 280px);
}

/* Animaciones */
.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Estilos para impresión */
@media print {
    .main-content {
        margin-left: 0 !important;
        width: 100% !important;
    }

    .btn, .breadcrumb {
        display: none !important;
    }

    .card {
        box-shadow: none !important;
        border: 1px solid #dee2e6 !important;
    }

    .card-header {
        background: #f8f9fa !important;
        color: #333 !important;
    }
}

/* Estados de hover para cards estadísticas */
.bg-gradient-primary:hover,
.bg-gradient-success:hover,
.bg-gradient-info:hover {
    transform: scale(1.05);
    transition: transform 0.3s ease;
}

/* Espaciado mejorado */
.mb-4 {
    margin-bottom: 2rem !important;
}

.mt-4 {
    margin-top: 2rem !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animación de entrada para las estadísticas
    const statsCards = document.querySelectorAll('.bg-gradient-primary, .bg-gradient-success, .bg-gradient-info');

    statsCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('fade-in');
    });

    // Animación de entrada para las filas de la tabla
    const tableRows = document.querySelectorAll('.table tbody tr');

    tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateX(-20px)';

        setTimeout(() => {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '1';
            row.style.transform = 'translateX(0)';
        }, index * 50);
    });

    // Funcionalidad de impresión mejorada
    const printBtn = document.querySelector('[onclick="window.print()"]');
    if (printBtn) {
        printBtn.addEventListener('click', function(e) {
            e.preventDefault();

            // Agregar clase para ocultar elementos no deseados
            document.body.classList.add('printing');

            // Pequeña pausa para que se apliquen los estilos
            setTimeout(() => {
                window.print();
                document.body.classList.remove('printing');
            }, 100);
        });
    }

    // Tooltips para badges
    const badges = document.querySelectorAll('.badge[title]');
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        badges.forEach(badge => {
            new bootstrap.Tooltip(badge, {
                delay: { show: 500, hide: 100 }
            });
        });
    }

    // Copiar código al hacer click
    const codigoBadges = document.querySelectorAll('.font-monospace');
    codigoBadges.forEach(badge => {
        badge.style.cursor = 'pointer';
        badge.title = 'Click para copiar';

        badge.addEventListener('click', function() {
            const text = this.textContent.trim();

            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => {
                    // Feedback visual
                    const original = this.textContent;
                    this.textContent = '¡Copiado!';
                    this.classList.add('bg-success');

                    setTimeout(() => {
                        this.textContent = original;
                        this.classList.remove('bg-success');
                        this.classList.add('bg-info');
                    }, 1000);
                });
            }
        });
    });

    // Auto-refresh de timestamps relativos cada minuto
    setInterval(() => {
        const timeElements = document.querySelectorAll('[data-time]');
        timeElements.forEach(element => {
            // Aquí se podría actualizar el tiempo relativo
            // Por ahora solo actualiza el título si existe
            if (element.title) {
                element.title = 'Actualizado: ' + new Date().toLocaleString();
            }
        });
    }, 60000);

    console.log('Detalle de Pedido de Compra cargado correctamente');

    window.anularPedido = function(pedidoId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Anular Pedido?',
                text: 'Esta acción cambiará el estado del pedido a "Anulado" y no se podrá deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, Anular',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    enviarAnulacion(pedidoId);
                }
            });
        } else {
            if (confirm('¿Está seguro que desea anular este pedido?\n\nEsta acción no se podrá deshacer.')) {
                enviarAnulacion(pedidoId);
            }
        }
    };

    function enviarAnulacion(pedidoId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/pedido_compra/${pedidoId}/anular`;

        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = csrfToken.getAttribute('content');
            form.appendChild(tokenInput);
        }

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PATCH';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
});
</script>
