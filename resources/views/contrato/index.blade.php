<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contratos</title>
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
                <h2 class="text-primary"><i class="fas fa-file-contract me-2"></i>Contratos</h2>
                <a href="{{ route('contrato.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Crear Contrato
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
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
                        <form method="GET" action="{{ route('contrato.index') }}" class="row g-3">
                            <div class="col-md-3">
                                <label for="cliente_id" class="form-label">Cliente</label>
                                <select name="cliente_id" id="cliente_id" class="form-select">
                                    <option value="">Todos</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>{{ $cliente->razon_social }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="obra_id" class="form-label">Obra</label>
                                <select name="obra_id" id="obra_id" class="form-select">
                                    <option value="">Todas</option>
                                    @foreach($obras as $obra)
                                        <option value="{{ $obra->id }}" {{ request('obra_id') == $obra->id ? 'selected' : '' }}>{{ $obra->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="estado_id" class="form-label">Estado</label>
                                <select name="estado_id" id="estado_id" class="form-select">
                                    <option value="">Todos</option>
                                    @foreach($estados as $estado)
                                        <option value="{{ $estado->id }}" {{ request('estado_id') == $estado->id ? 'selected' : '' }}>{{ $estado->descripcion }}</option>
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
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search me-1"></i>Buscar
                                </button>
                                <a href="{{ route('contrato.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Limpiar Filtros
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Lista de Contratos</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-hashtag me-1"></i>ID</th>
                                    <th><i class="fas fa-user me-1"></i>Cliente</th>
                                    <th><i class="fas fa-building me-1"></i>Obra</th>
                                    <th><i class="fas fa-dollar-sign me-1"></i>Monto</th>
                                    <th><i class="fas fa-info-circle me-1"></i>Estado</th>
                                    <th><i class="fas fa-calendar me-1"></i>Fecha Firma</th>
                                    <th><i class="fas fa-user-tie me-1"></i>Usuario</th>
                                    <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contratos as $contrato)
                                    <tr>
                                        <td><strong>{{ $contrato->id }}</strong></td>
                                        <td>{{ $contrato->cliente->razon_social ?? '-' }}</td>
                                        <td>{{ $contrato->obra->descripcion ?? '-' }}</td>
                                        <td>Gs {{ number_format($contrato->monto, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge {{ $contrato->estado_id == 3 ? 'bg-success' : ($contrato->estado_id == 4 ? 'bg-warning' : 'bg-secondary') }}">
                                                {{ $contrato->estado->descripcion ?? '-' }}
                                            </span>
                                        </td>
                                        <td>{{ $contrato->fecha_firma ? $contrato->fecha_firma->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $contrato->usuario->usuario ?? '-' }}</td>
                                        <td class="btn-actions">
                                            <a href="{{ route('contrato.show', $contrato->id) }}" class="btn btn-sm btn-outline-info me-1" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="empty-state">
                                            <i class="fas fa-inbox"></i>
                                            <p>No hay contratos registrados.</p>
                                            <a href="{{ route('contrato.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Crear el primer contrato
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
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
