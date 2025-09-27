<!-- filepath: c:\laragon\www\TesisGyA\resources\views\presupuesto_compra\index.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos de Compra - Presupuestos</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content fade-in">
        <div class="content-wrapper">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Pedidos de Compra Pendientes</h2>
                    <p class="text-muted mb-0">Crea presupuestos para los pedidos disponibles</p>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-info me-2">
                        <i class="fas fa-user me-1"></i>{{ session('user_cargo') }}
                    </span>
                    <span class="badge bg-secondary">
                        {{ $pedidos->count() }} pedidos disponibles
                    </span>
                </div>
            </div>

            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Filtrar por fecha:</label>
                            <input type="date" class="form-control" id="filtroFecha" title="Filtrar pedidos por fecha">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Buscar por sucursal:</label>
                            <input type="text" class="form-control" id="filtroSucursal" placeholder="Nombre de sucursal...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estado de presupuestos:</label>
                            <select class="form-select" id="filtroPresupuestos">
                                <option value="">Todos</option>
                                <option value="sin_presupuestos">Sin presupuestos</option>
                                <option value="con_presupuestos">Con presupuestos</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="limpiarFiltros()">
                                <i class="fas fa-eraser me-2"></i>Limpiar Filtros
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Pedidos -->
            <div class="row" id="pedidosContainer">
                @forelse($pedidos as $pedido)
                    <div class="col-xl-4 col-lg-6 col-md-6 mb-4 pedido-card"
                        data-fecha="{{ $pedido->fecha->format('Y-m-d') }}"
                        data-sucursal="{{ strtolower($pedido->sucursal->descripcion ?? '') }}"
                        data-presupuestos="{{ $pedido->presupuestos_count }}">
                        <div class="card h-100 shadow-sm border-0 hover-card">
                            <!-- Header del Card -->
                            <div class="card-header bg-gradient-primary text-white border-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-shopping-cart me-2"></i>
                                        Pedido #{{ $pedido->id }}
                                    </h6>
                                    <div class="d-flex align-items-center">
                                        @if($pedido->presupuestos_count > 0)
                                            <span class="badge bg-warning text-dark me-2">
                                                {{ $pedido->presupuestos_count }} presupuesto{{ $pedido->presupuestos_count > 1 ? 's' : '' }}
                                            </span>
                                        @endif
                                        <span class="badge bg-light text-dark">
                                            {{ $pedido->estado->descripcion }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Información del Pedido -->
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Usuario:</small>
                                        <strong class="text-dark">{{ $pedido->usuario->usuario ?? 'N/A' }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Fecha:</small>
                                        <strong class="text-dark">{{ $pedido->fecha->format('d/m/Y') }}</strong>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Sucursal:</small>
                                        <strong class="text-primary">{{ $pedido->sucursal->descripcion }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Depósito:</small>
                                        <strong class="text-info">{{ $pedido->deposito->descripcion }}</strong>
                                    </div>
                                </div>

                                <!-- Insumos solicitados -->
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-2">Insumos solicitados:</small>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($pedido->detalles->take(3) as $detalle)
                                            <span class="badge bg-light text-dark border"
                                                  title="{{ $detalle->insumo->descripcion }} - Cant: {{ $detalle->cantidad }}">
                                                {{ Str::limit($detalle->insumo->descripcion, 20) }}
                                            </span>
                                        @endforeach
                                        @if($pedido->detalles->count() > 3)
                                            <span class="badge bg-secondary">
                                                +{{ $pedido->detalles->count() - 3 }} más
                                            </span>
                                        @endif
                                    </div>
                                    <small class="text-muted mt-1">
                                        Total: {{ $pedido->detalles->count() }} insumo{{ $pedido->detalles->count() > 1 ? 's' : '' }}
                                    </small>
                                </div>

                                <!-- Observación -->
                                @if($pedido->observacion)
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Observación:</small>
                                        <p class="small text-dark mb-0 bg-light p-2 rounded border-start border-3 border-info">
                                            {{ Str::limit($pedido->observacion, 100) }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Footer con acciones -->
                            <div class="card-footer bg-light border-0">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('presupuesto_compra.show_pedido', $pedido->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-2"></i>Ver Detalles del Pedido
                                    </a>

                                    @if($pedido->presupuestos_count > 0)
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('presupuesto_compra.create', $pedido->id) }}" class="btn btn-success btn-sm">
                                                <i class="fas fa-plus me-2"></i>Nuevo Presupuesto
                                            </a>

                                        </div>
                                    @else
                                        <a href="{{ route('presupuesto_compra.create', $pedido->id) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-plus me-2"></i>Crear Primer Presupuesto
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0">
                            <div class="card-body text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-inbox fa-4x text-muted"></i>
                                </div>
                                <h5 class="text-muted mb-2">No hay pedidos pendientes</h5>
                                <p class="text-muted">
                                    Por el momento no hay pedidos de compra disponibles para presupuestar.
                                </p>
                            </div>
                        </div>
                    </div>
                @endforelse
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

.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    border-bottom: none;
    padding: 1rem 1.25rem;
}

.card-footer {
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    padding: 1rem 1.25rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.5rem;
}

.btn-sm {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
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
document.addEventListener('DOMContentLoaded', function() {
    // Filtros en tiempo real
    const filtroFecha = document.getElementById('filtroFecha');
    const filtroSucursal = document.getElementById('filtroSucursal');
    const filtroPresupuestos = document.getElementById('filtroPresupuestos');

    function aplicarFiltros() {
        const fecha = filtroFecha.value;
        const sucursal = filtroSucursal.value.toLowerCase();
        const presupuestos = filtroPresupuestos.value;

        document.querySelectorAll('.pedido-card').forEach(card => {
            const cardFecha = card.dataset.fecha;
            const cardSucursal = card.dataset.sucursal;
            const cardPresupuestos = parseInt(card.dataset.presupuestos);

            let mostrar = true;

            // Filtro por fecha
            if (fecha && cardFecha !== fecha) {
                mostrar = false;
            }

            // Filtro por sucursal
            if (sucursal && !cardSucursal.includes(sucursal)) {
                mostrar = false;
            }

            // Filtro por presupuestos
            if (presupuestos === 'sin_presupuestos' && cardPresupuestos > 0) {
                mostrar = false;
            } else if (presupuestos === 'con_presupuestos' && cardPresupuestos === 0) {
                mostrar = false;
            }

            card.style.display = mostrar ? 'block' : 'none';
        });

        // Mostrar contador de resultados filtrados
        const pedidosVisibles = document.querySelectorAll('.pedido-card[style="display: block"], .pedido-card:not([style*="display: none"])').length;
        const badgeTotal = document.querySelector('.badge.bg-secondary');
        if (badgeTotal) {
            badgeTotal.textContent = `${pedidosVisibles} pedidos mostrados`;
        }
    }

    filtroFecha.addEventListener('change', aplicarFiltros);
    filtroSucursal.addEventListener('input', aplicarFiltros);
    filtroPresupuestos.addEventListener('change', aplicarFiltros);

    window.limpiarFiltros = function() {
        filtroFecha.value = '';
        filtroSucursal.value = '';
        filtroPresupuestos.value = '';
        aplicarFiltros();

        // Restaurar contador original
        const badgeTotal = document.querySelector('.badge.bg-secondary');
        if (badgeTotal) {
            badgeTotal.textContent = `{{ $pedidos->count() }} pedidos disponibles`;
        }
    };

});
</script>
