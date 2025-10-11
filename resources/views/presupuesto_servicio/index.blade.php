<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Presupuestos de Servicio</title>
    @include('partials.head')
    <style>
        .main-content {
            margin-left: 60px;
            min-height: 100vh;
            background-color: #f8f9fa;
            transition: margin-left 0.3s cubic-bezier(.4,2,.6,1);
            overflow-x: hidden;
            box-sizing: border-box;
            width: auto;
            max-width: 100vw;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 50px;
            }
        }
        .sidebar-nav {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 60px;
            transition: width 0.3s cubic-bezier(.4,2,.6,1);
            overflow-x: hidden;
            z-index: 10000;
        }
        .sidebar-nav:hover {
            width: 280px;
            box-shadow: 2px 0 16px rgba(0,0,0,0.07);
        }
        .sidebar-nav:hover ~ .main-content {
            margin-left: 280px;
        }
        @media (max-width: 768px) {
            .sidebar-nav:hover {
                width: 250px;
            }
            .sidebar-nav:hover ~ .main-content {
                margin-left: 250px;
            }
        }
        .content-wrapper {
            padding: 15px;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: auto;
        }
        .table th {
            background-color: #343a40;
            color: white;
            border: none;
        }
        .table td {
            vertical-align: middle;
        }
        .btn-actions {
            white-space: nowrap;
        }
        .badge {
            font-size: 0.8em;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content fade-in">
        <div class="content-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary"><i class="fas fa-file-invoice-dollar me-2"></i>Presupuestos de Servicio</h2>
                <a href="{{ route('presupuesto_servicio.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo Presupuesto
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filtros de búsqueda -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <button class="btn btn-link p-0 text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#filtrosCollapse" aria-expanded="false" aria-controls="filtrosCollapse">
                            <i class="fas fa-filter me-2"></i>Filtros de Búsqueda
                        </button>
                    </h5>
                </div>
                <div class="collapse" id="filtrosCollapse">
                    <div class="card-body">
                        <form method="GET" action="{{ route('presupuesto_servicio.index') }}" class="row g-3">
                            <div class="col-md-3">
                                <label for="cliente" class="form-label">Cliente</label>
                                <input type="text" name="cliente" id="cliente" class="form-control" value="{{ request('cliente') }}" placeholder="Buscar por razón social">
                            </div>
                            <div class="col-md-3">
                                <label for="obra" class="form-label">Obra</label>
                                <input type="text" name="obra" id="obra" class="form-control" value="{{ request('obra') }}" placeholder="Buscar por descripción">
                            </div>
                            <div class="col-md-2">
                                <label for="estado_id" class="form-label">Estado</label>
                                <select name="estado_id" id="estado_id" class="form-select">
                                    <option value="">Todos</option>
                                    @foreach($estados as $estado)
                                        <option value="{{ $estado->id }}" {{ request('estado_id') == $estado->id ? 'selected' : '' }}>
                                            {{ $estado->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="fecha_desde" class="form-label">Fecha Desde</label>
                                <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                                <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="numero_presupuesto" class="form-label">Número Presupuesto</label>
                                <input type="text" name="numero_presupuesto" id="numero_presupuesto" class="form-control" value="{{ request('numero_presupuesto') }}" placeholder="Buscar por número">
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search me-1"></i>Buscar
                                </button>
                                <a href="{{ route('presupuesto_servicio.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Limpiar Filtros
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Lista de Presupuestos</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-hashtag me-1"></i>ID</th>
                                    <th><i class="fas fa-file-invoice me-1"></i>Número</th>
                                    <th><i class="fas fa-user me-1"></i>Cliente</th>
                                    <th><i class="fas fa-building me-1"></i>Obra</th>
                                    <th><i class="fas fa-dollar-sign me-1"></i>Monto</th>
                                    <th><i class="fas fa-info-circle me-1"></i>Estado</th>
                                    <th><i class="fas fa-calendar me-1"></i>Fecha</th>
                                    <th><i class="fas fa-user-tie me-1"></i>Usuario</th>
                                    <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($presupuestos as $presupuesto)
                                    <tr>
                                        <td><strong>{{ $presupuesto->id }}</strong></td>
                                        <td>{{ $presupuesto->numero_presupuesto ?? '-' }}</td>
                                        <td>{{ $presupuesto->cliente->razon_social ?? '-' }}</td>
                                        <td>{{ $presupuesto->obra->descripcion ?? '-' }}</td>
                                        <td>S/ {{ number_format($presupuesto->monto, 2) }}</td>
                                        <td>
                                            <span class="badge {{ $presupuesto->estado_id == 1 ? 'bg-success' : 'bg-warning' }}">
                                                {{ $presupuesto->estado->descripcion ?? '-' }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($presupuesto->fecha)->format('d/m/Y') }}</td>
                                        <td>{{ $presupuesto->usuario->name ?? '-' }}</td>
                                        <td class="btn-actions">
                                            <a href="{{ route('presupuesto_servicio.show', $presupuesto->id) }}" class="btn btn-sm btn-outline-info me-1" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('presupuesto_servicio.edit', $presupuesto->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('presupuesto_servicio.destroy', $presupuesto->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este presupuesto?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="empty-state">
                                            <i class="fas fa-inbox"></i>
                                            <p>No hay presupuestos registrados.</p>
                                            <a href="{{ route('presupuesto_servicio.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Crear el primer presupuesto
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($presupuestos->hasPages())
                        <div class="card-footer bg-light">
                            {{ $presupuestos->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
