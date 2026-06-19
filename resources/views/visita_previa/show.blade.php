<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Visita Previa #{{ $visita->id }}</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-clipboard-list"></i> Visita Previa #{{ $visita->id }}</h2>
                    <small>Fecha de visita: {{ \Carbon\Carbon::parse($visita->fecha_visita)->format('d/m/Y') }}</small>
                </div>
                <div class="header-actions">
                    @switch($visita->estado_id)
                        @case(3)
                            <span class="estado estado-pendiente"><i class="estado-dot"></i>Pendiente</span>
                            @break
                        @case(4)
                            <span class="estado estado-confirmado"><i class="estado-dot"></i>Confirmado</span>
                            @break
                        @case(5)
                            <span class="estado estado-anulado"><i class="estado-dot"></i>Anulado</span>
                            @break
                        @default
                            <span class="estado"><i class="estado-dot"></i>{{ $visita->estado->descripcion ?? '-' }}</span>
                    @endswitch
                    @if($visita->estado_id == 3)
                        <a href="{{ route('visita_previa.edit', $visita->id) }}" class="btn btn-warning">
                            <i class="fas fa-pen me-2"></i>Editar
                        </a>
                    @endif
                    <a href="{{ route('visita_previa.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            {{-- Cliente / Obra / Solicitud de Servicio --}}
            <div class="row g-3">
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header-section">
                            <span><i class="fas fa-user me-2"></i>Cliente</span>
                        </div>
                        <div class="card-body">
                            <div class="info-grid info-grid-stack">
                                <div class="info-item">
                                    <label class="form-label">Razón Social</label>
                                    <div class="info-value"><i class="fas fa-building"></i>{{ $visita->cliente->razon_social ?? '-' }}</div>
                                </div>
                                <div class="info-item">
                                    <label class="form-label">RUC</label>
                                    <div class="info-value"><i class="fas fa-id-card"></i>{{ $visita->cliente->ruc ?? '-' }}</div>
                                </div>
                                <div class="info-item">
                                    <label class="form-label">Teléfono</label>
                                    <div class="info-value"><i class="fas fa-phone"></i>{{ $visita->cliente->telefono ?? '-' }}</div>
                                </div>
                                <div class="info-item">
                                    <label class="form-label">Email</label>
                                    <div class="info-value"><i class="fas fa-envelope"></i>{{ $visita->cliente->email ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header-section">
                            <span><i class="fas fa-building me-2"></i>Obra</span>
                        </div>
                        <div class="card-body">
                            <div class="info-grid info-grid-stack">
                                <div class="info-item">
                                    <label class="form-label">Descripción</label>
                                    <div class="info-value"><i class="fas fa-file-alt"></i>{{ $visita->obra->descripcion ?? '-' }}</div>
                                </div>
                                <div class="info-item">
                                    <label class="form-label">Ubicación</label>
                                    <div class="info-value"><i class="fas fa-map-marker-alt"></i>{{ $visita->obra->ubicacion ?? '-' }}</div>
                                </div>
                                <div class="info-item">
                                    <label class="form-label">Metros Cuadrados</label>
                                    <div class="info-value"><i class="fas fa-ruler-combined"></i>{{ $visita->obra->metros_cuadrados ?? '-' }}</div>
                                </div>
                                <div class="info-item">
                                    <label class="form-label">Niveles</label>
                                    <div class="info-value"><i class="fas fa-layer-group"></i>{{ $visita->obra->niveles ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header-section">
                            <span><i class="fas fa-file-alt me-2"></i>Solicitud de Servicio</span>
                            @if($visita->solicitud_servicio_id)
                                <span class="tag tag-secondary">#{{ $visita->solicitud_servicio_id }}</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="info-grid info-grid-stack">
                                <div class="info-item">
                                    <label class="form-label">Fecha</label>
                                    <div class="info-value"><i class="fas fa-calendar"></i>{{ $visita->solicitudServicio->fecha ? \Carbon\Carbon::parse($visita->solicitudServicio->fecha)->format('d/m/Y') : '-' }}</div>
                                </div>
                                <div class="info-item">
                                    <label class="form-label">Estado</label>
                                    <div class="info-value"><i class="fas fa-info-circle"></i>{{ $visita->solicitudServicio->estado->descripcion ?? '-' }}</div>
                                </div>
                                <div class="info-item">
                                    <label class="form-label">Servicios Solicitados</label>
                                    <ul class="servicios-detalle-list">
                                        @forelse($visita->solicitudServicio->detalles ?? [] as $detalle)
                                            <li>{{ $detalle->servicio->descripcion ?? '-' }}</li>
                                        @empty
                                            <li class="text-muted">Sin servicios</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Datos de la Visita --}}
            <div class="card">
                <div class="card-header-section">
                    <span><i class="fas fa-clipboard-check me-2"></i>Datos de la Visita</span>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label class="form-label">Fecha de Visita</label>
                            <div class="info-value"><i class="fas fa-calendar"></i>{{ \Carbon\Carbon::parse($visita->fecha_visita)->format('d/m/Y') }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Usuario</label>
                            <div class="info-value"><i class="fas fa-user"></i>{{ $visita->usuario->usuario ?? '-' }}</div>
                        </div>
                    </div>
                    @if($visita->observacion)
                        <div class="mt-3">
                            <label class="form-label">Observación</label>
                            <div class="info-value observation-box">{{ $visita->observacion }}</div>
                        </div>
                    @endif
                </div>
            </div>

            

            {{-- Fotos y Planos --}}
            <div class="card">
                <div class="card-header-section">
                    <span><i class="fas fa-images me-2"></i>Fotos y Planos de la Visita</span>
                </div>
                <div class="card-body">
                    <div class="info-grid-2">
                        <div>
                            <h6 class="subsection-title"><i class="fas fa-camera me-2"></i>Fotos de la Visita</h6>
                            <div class="file-gallery">
                                @if($visita->fotos->count() > 0)
                                    @foreach($visita->fotos as $foto)
                                        <div class="file-item">
                                            <img src="{{ Storage::disk('public')->url('visitas_previas/fotos/' . $foto->ruta_foto) }}" alt="Foto">
                                            <div class="file-info">
                                                <a href="{{ Storage::disk('public')->url('visitas_previas/fotos/' . $foto->ruta_foto) }}" target="_blank">Ver imagen</a>
                                                <small>{{ $foto->fecha ? \Carbon\Carbon::parse($foto->fecha)->format('d/m/Y') : '-' }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted mb-0" style="font-size:0.8rem;">No hay fotos disponibles.</p>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h6 class="subsection-title"><i class="fas fa-file-alt me-2"></i>Planos de la Obra</h6>
                            <div class="file-gallery">
                                @if($visita->planos->count() > 0)
                                    @foreach($visita->planos as $plano)
                                        <div class="file-item">
                                            @if(strtolower(pathinfo($plano->ruta_plano, PATHINFO_EXTENSION)) === 'pdf')
                                                <div class="file-placeholder"><i class="fas fa-file-pdf"></i></div>
                                            @else
                                                <img src="{{ Storage::disk('public')->url('visitas_previas/planos/' . $plano->ruta_plano) }}" alt="Plano">
                                            @endif
                                            <div class="file-info">
                                                <a href="{{ Storage::disk('public')->url('visitas_previas/planos/' . $plano->ruta_plano) }}" target="_blank">Ver archivo</a>
                                                <small>{{ $plano->fecha ? \Carbon\Carbon::parse($plano->fecha)->format('d/m/Y') : '-' }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted mb-0" style="font-size:0.8rem;">No hay planos disponibles.</p>
                                @endif
                            </div>
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
.content-wrapper {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* ── Cabecera ── */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e2e8f0;
}
.page-header h2 { margin: 0; font-size: 1.25rem; font-weight: 600; color: #1e293b; }
.page-header h2 i { color: #94a3b8; margin-right: 0.4rem; }
.page-header small { color: #94a3b8; font-size: 0.8rem; }
.header-actions { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

/* ── Cards ── */
.card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: none;
}
.card-header-section {
    padding: 0.65rem 1rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;
    font-weight: 600; font-size: 0.85rem; color: #1e293b;
}

/* ── Información (grids) ── */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 0.75rem;
}
.info-grid-stack { grid-template-columns: 1fr; }
.info-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
.info-item .form-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 500;
    color: #94a3b8;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.info-value {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-size: 0.85rem;
    color: #374151;
}
.info-value i { color: #94a3b8; margin-right: 0.5rem; width: 14px; text-align: center; }
.observation-box { white-space: pre-wrap; line-height: 1.5; }

.subsection-title {
    font-size: 0.78rem;
    font-weight: 600;
    color: #2563eb;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.5rem;
}

@media (max-width: 900px) {
    .page-header { flex-direction: column; align-items: flex-start; }
    .info-grid-2 { grid-template-columns: 1fr; }
}

/* ── Galería de archivos ── */
.file-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 0.6rem;
}
.file-item {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
}
.file-item img,
.file-item .file-placeholder {
    width: 100%;
    height: 100px;
    object-fit: cover;
    display: block;
}
.file-item .file-placeholder {
    display: flex; align-items: center; justify-content: center;
    background: #f8fafc; color: #cbd5e1; font-size: 1.6rem;
}
.file-item .file-info { padding: 0.4rem 0.5rem; font-size: 0.72rem; color: #64748b; }
.file-item .file-info a { color: #2563eb; text-decoration: none; font-weight: 600; }
.file-item .file-info a:hover { text-decoration: underline; }
.file-item .file-info small { display: block; color: #94a3b8; margin-top: 2px; }

/* ── Tags ── */
.tag {
    display: inline-block;
    padding: 0.2rem 0.55rem;
    border-radius: 4px;
    font-size: 0.72rem;
    font-weight: 600;
    background: #eff6ff;
    color: #2563eb;
}
.tag-secondary { background: #f1f5f9; color: #64748b; }

/* ── Estado ── */
.estado { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; color: #374151; }
.estado-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #94a3b8; flex-shrink: 0; }
.estado-pendiente .estado-dot  { background: #f59e0b; }
.estado-confirmado .estado-dot { background: #10b981; }
.estado-anulado .estado-dot    { background: #ef4444; }

/* ── Listas de detalle ── */
.servicios-detalle-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}
.servicios-detalle-list li {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.4rem 0.65rem;
    font-size: 0.82rem;
    color: #374151;
}

/* ── Impresión ── */
@media print {
    .main-content { margin-left: 0 !important; width: 100% !important; }
    .header-actions .btn { display: none !important; }
    .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
}
</style>
