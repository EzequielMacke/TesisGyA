<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de Visita Previa #{{ $visita->id }}</title>
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
                box-shadow: 2px 0 16px rgba(0,0,0,0.07);
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
        .info-card {
            border-left: 4px solid #0d6efd;
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .info-card h5 {
            color: #0d6efd;
            margin-bottom: 1rem;
        }
        .file-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .file-item {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            overflow: hidden;
            background: white;
        }
        .file-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .file-item .file-info {
            padding: 0.5rem;
            font-size: 0.9rem;
        }
        .file-item .file-info a {
            color: #0d6efd;
            text-decoration: none;
        }
        .file-item .file-info a:hover {
            text-decoration: underline;
        }
        .ensayos-list {
            list-style: none;
            padding: 0;
        }
        .ensayos-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        .ensayos-list li:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content fade-in">
        <div class="content-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary"><i class="fas fa-eye me-2"></i>Detalles de Visita Previa #{{ $visita->id }}</h2>
                <div>
                    <a href="#" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-1"></i>Editar
                    </a>
                    <a href="{{ route('visita_previa.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Datos de la Visita Previa -->
                <div class="col-lg-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Datos de la Visita Previa</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <strong>ID:</strong> {{ $visita->id }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>Fecha de Visita:</strong> {{ \Carbon\Carbon::parse($visita->fecha_visita)->format('d/m/Y') }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>Estado:</strong>
                                    <span class="badge {{ $visita->estado_id == 3 ? 'bg-warning text-dark' : 'bg-success' }}">
                                        {{ $visita->estado->descripcion ?? '-' }}
                                    </span>
                                </div>
                                <div class="col-sm-6">
                                    <strong>Usuario:</strong> {{ $visita->usuario->usuario ?? '-' }}
                                </div>
                            </div>
                            <div class="mt-3">
                                <strong>Observación:</strong>
                                <p class="mt-1">{{ $visita->observacion ?? 'Sin observación' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Datos del Cliente -->
                <div class="col-lg-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Datos del Cliente</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <strong>Razón Social:</strong> {{ $visita->cliente->razon_social ?? '-' }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>RUC:</strong> {{ $visita->cliente->ruc ?? '-' }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>Teléfono:</strong> {{ $visita->cliente->telefono ?? '-' }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>Email:</strong> {{ $visita->cliente->email ?? '-' }}
                                </div>
                            </div>
                            <div class="mt-3">
                                <strong>Dirección:</strong>
                                <p class="mt-1">{{ $visita->cliente->direccion ?? 'Sin dirección' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Datos de la Obra -->
                <div class="col-lg-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-building me-2"></i>Datos de la Obra</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <strong>Descripción:</strong> {{ $visita->obra->descripcion ?? '-' }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>Ubicación:</strong> {{ $visita->obra->ubicacion ?? '-' }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>Metros Cuadrados:</strong> {{ $visita->obra->metros_cuadrados ?? '-' }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>Niveles:</strong> {{ $visita->obra->niveles ?? '-' }}
                                </div>
                            </div>
                            <div class="mt-3">
                                <strong>Fecha:</strong> {{ $visita->obra->fecha ? \Carbon\Carbon::parse($visita->obra->fecha)->format('d/m/Y') : '-' }}
                            </div>
                            <div class="mt-3">
                                <strong>Observación:</strong>
                                <p class="mt-1">{{ $visita->obra->observacion ?? 'Sin observación' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Datos de la Solicitud de Servicio -->
                <div class="col-lg-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Datos de la Solicitud de Servicio</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <strong>ID:</strong> {{ $visita->solicitudServicio->id ?? '-' }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>Fecha:</strong> {{ $visita->solicitudServicio->fecha ? \Carbon\Carbon::parse($visita->solicitudServicio->fecha)->format('d/m/Y') : '-' }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>Estado:</strong>
                                    <span class="badge bg-secondary">{{ $visita->solicitudServicio->estado->descripcion ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="mt-3">
                                <strong>Observación:</strong>
                                <p class="mt-1">{{ $visita->solicitudServicio->observacion ?? 'Sin observación' }}</p>
                            </div>
                            <div class="mt-3">
                                <strong>Servicios Solicitados:</strong>
                                <ul class="mt-1">
                                    @forelse($visita->solicitudServicio->detalles ?? [] as $detalle)
                                        <li>{{ $detalle->servicio->descripcion ?? '-' }}</li>
                                    @empty
                                        <li>Sin servicios</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ensayos Seleccionados -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-flask me-2"></i>Ensayos Seleccionados</h5>
                </div>
                <div class="card-body">
                    @if($visita->ensayos->count() > 0)
                        <ul class="ensayos-list">
                            @foreach($visita->ensayos as $ensayo)
                                <li>
                                    <strong>{{ $ensayo->ensayo->descripcion ?? '-' }}</strong> - Servicio: {{ $ensayo->servicio->descripcion ?? '-' }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No hay ensayos seleccionados.</p>
                    @endif
                </div>
            </div>

            <!-- Fotos -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-camera me-2"></i>Fotos de la Visita</h5>
                </div>
                <div class="card-body">
                    @if($visita->fotos->count() > 0)
                        <div class="file-gallery">
                            @foreach($visita->fotos as $foto)
                                <div class="file-item">
                                    <img src="{{ asset('storage/' . $foto->ruta_foto) }}" alt="Foto">
                                    <div class="file-info">
                                        <a href="{{ asset('storage/' . $foto->ruta_foto) }}" target="_blank">Ver imagen</a>
                                        <br><small>{{ $foto->fecha ? \Carbon\Carbon::parse($foto->fecha)->format('d/m/Y') : '-' }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No hay fotos disponibles.</p>
                    @endif
                </div>
            </div>

            <!-- Planos -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Planos de la Obra</h5>
                </div>
                <div class="card-body">
                    @if($visita->planos->count() > 0)
                        <div class="file-gallery">
                            @foreach($visita->planos as $plano)
                                <div class="file-item">
                                    @if(strtolower(pathinfo($plano->ruta_plano, PATHINFO_EXTENSION)) == 'pdf')
                                        <div style="height: 150px; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                                            <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                        </div>
                                    @else
                                        <img src="{{ asset('storage/' . $plano->ruta_plano) }}" alt="Plano">
                                    @endif
                                    <div class="file-info">
                                        <a href="{{ asset('storage/' . $plano->ruta_plano) }}" target="_blank">Ver archivo</a>
                                        <br><small>{{ $plano->fecha ? \Carbon\Carbon::parse($plano->fecha)->format('d/m/Y') : '-' }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No hay planos disponibles.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
