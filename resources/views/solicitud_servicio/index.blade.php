<!-- filepath: c:\laragon\www\TesisGyA\resources\views\solicitud_servicio\index.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitudes de Servicio - TesisGyA</title>
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
</style>
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content fade-in">
        <div class="content-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-file-alt me-2"></i>Solicitudes de Servicio</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('solicitud_servicio.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Nueva Solicitud
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            <!-- Filtros -->
            <form method="GET" class="card mb-3">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label for="estado_id" class="form-label mb-2">
                                <i class="fas fa-filter me-1"></i>Estado:
                            </label>
                            <select class="form-select" name="estado_id" id="estado_id" onchange="this.form.submit()">
                                <option value="">Todos los estados</option>
                                <option value="3" {{ request('estado_id') == 3 ? 'selected' : '' }}>Pendiente</option>
                                <option value="4" {{ request('estado_id') == 4 ? 'selected' : '' }}>Confirmado</option>
                                <option value="5" {{ request('estado_id') == 5 ? 'selected' : '' }}>Anulado</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="cliente_id" class="form-label mb-2">
                                <i class="fas fa-user me-1"></i>Cliente:
                            </label>
                            <select class="form-select" name="cliente_id" id="cliente_id" onchange="this.form.submit()">
                                <option value="">Todos los clientes</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->razon_social }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="fecha_desde" class="form-label mb-2">
                                <i class="fas fa-calendar me-1"></i>Fecha desde:
                            </label>
                            <input type="date" class="form-control" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}" onchange="this.form.submit()">
                        </div>
                        <div class="col-md-3">
                            <label for="search" class="form-label mb-2">
                                <i class="fas fa-search me-1"></i>Buscar:
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" id="search" value="{{ request('search') }}" placeholder="Cliente, obra, observación...">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-filter"></i>
                                </button>
                                <a href="{{ route('solicitud_servicio.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Tabla de Solicitudes -->
            <div class="card table-card flex-grow-1">
                <div class="card-body p-0 h-100">
                    <div class="table-responsive table-container h-100">
                        @if($solicitudes->count() > 0)
                            <table class="table table-striped table-hover mb-0 h-100">
                                <thead class="table-dark sticky-top">
                                    <tr>
                                        <th style="width: 80px;">#</th>
                                        <th>Cliente</th>
                                        <th>Obra</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Observación</th>
                                        <th>Detalle</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($solicitudes as $solicitud)
                                        <tr>
                                            <td class="text-center">
                                                <span class="badge bg-dark fs-6">
                                                    #{{ str_pad($solicitud->id, 3, '0', STR_PAD_LEFT) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info text-wrap" title="{{ $solicitud->cliente->razon_social }}">
                                                    {{ Str::limit($solicitud->cliente->razon_social, 20) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary text-wrap" title="{{ $solicitud->obra->descripcion }}">
                                                    {{ Str::limit($solicitud->obra->descripcion, 20) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary">
                                                    {{ \Carbon\Carbon::parse($solicitud->fecha)->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge
                                                    @switch($solicitud->estado_id)
                                                        @case(3) bg-warning text-dark @break
                                                        @case(4) bg-success @break
                                                        @case(5) bg-danger @break
                                                        @default bg-secondary @break
                                                    @endswitch">
                                                    @switch($solicitud->estado_id)
                                                        @case(3) Pendiente @break
                                                        @case(4) Confirmado @break
                                                        @case(5) Anulado @break
                                                        @default {{ $solicitud->estado->descripcion }} @break
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td title="{{ $solicitud->observacion }}">
                                                @if($solicitud->observacion)
                                                    <i class="fas fa-comment text-muted me-1" title="{{ $solicitud->observacion }}"></i>
                                                    <span class="text-truncate d-inline-block" style="max-width: 120px;">
                                                        {{ Str::limit($solicitud->observacion, 20) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted fst-italic">Sin observación</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($solicitud->detalles && $solicitud->detalles->count())
                                                    <ul class="mb-0 ps-3">
                                                        @foreach($solicitud->detalles as $detalle)
                                                            <li>
                                                                {{ $detalle->servicio->descripcion ?? '-' }}
                                                                @if($detalle->observacion)
                                                                    <span class="text-muted">({{ $detalle->observacion }})</span>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <span class="text-muted fst-italic">Sin detalle</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('solicitud_servicio.show', $solicitud->id) }}"
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="Ver Detalle">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($solicitud->estado_id == 3)
                                                        <a href="#"
                                                        class="btn btn-sm btn-outline-success"
                                                        title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="#" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Anular Solicitud" onclick="return confirm('¿Está seguro que desea anular esta solicitud?');">
                                                                <i class="fas fa-ban"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $solicitudes->withQueryString()->links() }}
                            </div>
                        @else
                            <div class="empty-state d-flex flex-column align-items-center justify-content-center h-100">
                                <i class="fas fa-file-alt fa-4x text-muted mb-4"></i>
                                <h4 class="text-muted mb-3">No hay solicitudes de servicio</h4>
                                <p class="text-muted mb-4 text-center">
                                    Aún no se han creado solicitudes de servicio en el sistema.
                                </p>
                                <a href="{{ route('solicitud_servicio.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Crear Primera Solicitud
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')


</body>
</html>
