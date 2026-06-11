<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Presupuesto de Servicio</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-file-invoice-dollar"></i> Presupuesto de Servicio</h2>
                    <small>{{ $presupuesto->numero_presupuesto ?? '#'.$presupuesto->id }}</small>
                </div>
                <div class="header-actions">
                    @switch($presupuesto->estado_id)
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
                            <span class="estado"><i class="estado-dot"></i>{{ $presupuesto->estado->descripcion ?? '-' }}</span>
                    @endswitch
                    <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                    <a href="{{ route('presupuesto_servicio.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            {{-- Cliente / Obra / Visita Previa --}}
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
                                    <div class="info-value"><i class="fas fa-building"></i>{{ $presupuesto->cliente->razon_social }}</div>
                                </div>
                                <div class="info-item">
                                    <label class="form-label">RUC</label>
                                    <div class="info-value"><i class="fas fa-id-card"></i>{{ $presupuesto->cliente->ruc }}</div>
                                </div>
                                <div class="info-item">
                                    <label class="form-label">Dirección</label>
                                    <div class="info-value"><i class="fas fa-map-marker-alt"></i>{{ $presupuesto->cliente->direccion }}</div>
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
                                    <div class="info-value"><i class="fas fa-file-alt"></i>{{ $presupuesto->obra->descripcion }}</div>
                                </div>
                                <div class="info-item">
                                    <label class="form-label">Ubicación</label>
                                    <div class="info-value"><i class="fas fa-map-marker-alt"></i>{{ $presupuesto->obra->ubicacion }}</div>
                                </div>
                                <div class="info-item">
                                    <label class="form-label">Metros Cuadrados</label>
                                    <div class="info-value"><i class="fas fa-ruler-combined"></i>{{ $presupuesto->obra->metros_cuadrados }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header-section">
                            <span><i class="fas fa-clipboard-check me-2"></i>Visita Previa</span>
                        </div>
                        <div class="card-body">
                            <div class="info-grid info-grid-stack">
                                <div class="info-item">
                                    <label class="form-label">Fecha de Visita</label>
                                    <div class="info-value"><i class="fas fa-calendar"></i>{{ \Carbon\Carbon::parse($presupuesto->visitaPrevia->fecha_visita)->format('d/m/Y') }}</div>
                                </div>
                                <div class="info-item">
                                    <label class="form-label">Estado</label>
                                    <div class="info-value"><i class="fas fa-info-circle"></i>{{ $presupuesto->visitaPrevia->estado->descripcion }}</div>
                                </div>
                                @if($presupuesto->visitaPrevia->observacion)
                                    <div class="info-item">
                                        <label class="form-label">Observación</label>
                                        <div class="info-value observation-box"><i class="fas fa-sticky-note"></i>{{ $presupuesto->visitaPrevia->observacion }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
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
                                @if($presupuesto->visitaPrevia->fotos && $presupuesto->visitaPrevia->fotos->count() > 0)
                                    @foreach($presupuesto->visitaPrevia->fotos as $foto)
                                        <div class="file-item">
                                            <img src="/storage/{{ $foto->ruta_foto }}" alt="Foto">
                                            <div class="file-info">
                                                <a href="/storage/{{ $foto->ruta_foto }}" target="_blank">Ver imagen</a>
                                                <small>{{ $foto->fecha }}</small>
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
                                @if($presupuesto->visitaPrevia->planos && $presupuesto->visitaPrevia->planos->count() > 0)
                                    @foreach($presupuesto->visitaPrevia->planos as $plano)
                                        <div class="file-item">
                                            @if(strtolower(pathinfo($plano->ruta_plano, PATHINFO_EXTENSION)) === 'pdf')
                                                <div class="file-placeholder"><i class="fas fa-file-pdf"></i></div>
                                            @else
                                                <img src="/storage/{{ $plano->ruta_plano }}" alt="Plano">
                                            @endif
                                            <div class="file-info">
                                                <a href="/storage/{{ $plano->ruta_plano }}" target="_blank">Ver archivo</a>
                                                <small>{{ $plano->fecha }}</small>
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

            {{-- Detalles del Presupuesto --}}
            <div class="card">
                <div class="card-header-section">
                    <span><i class="fas fa-clipboard-list me-2"></i>Detalles del Presupuesto</span>
                    <span class="tag">{{ $presupuesto->numero_presupuesto }}</span>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label class="form-label">Fecha</label>
                            <div class="info-value"><i class="fas fa-calendar"></i>{{ \Carbon\Carbon::parse($presupuesto->fecha)->format('d/m/Y') }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Validez (días)</label>
                            <div class="info-value"><i class="fas fa-hourglass-half"></i>{{ $presupuesto->validez }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Anticipo (%)</label>
                            <div class="info-value"><i class="fas fa-percent"></i>{{ $presupuesto->anticipo }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Monto Total</label>
                            <div class="info-value"><i class="fas fa-coins"></i><span class="amount">₲ {{ number_format($presupuesto->monto, 0, ',', '.') }}</span></div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Usuario</label>
                            <div class="info-value"><i class="fas fa-user"></i>{{ $presupuesto->usuario->usuario ?? 'N/A' }}</div>
                        </div>
                    </div>
                    @if($presupuesto->observacion)
                        <div class="mt-3">
                            <label class="form-label">Observación</label>
                            <div class="info-value observation-box">{{ $presupuesto->observacion }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Detalles de Ensayos --}}
            <div class="card">
                <div class="card-header-section">
                    <span><i class="fas fa-flask me-2"></i>Detalles de Ensayos</span>
                </div>
                <div class="card-body">
                    @php
                        $detallesPorServicio = $presupuesto->detalles->groupBy(function($detalle) {
                            return $detalle->ensayo->servicio->descripcion;
                        });
                    @endphp
                    @foreach($detallesPorServicio as $servicio => $detalles)
                        <div class="precio-servicio-block">
                            <div class="precio-servicio-header">{{ $servicio }}</div>
                            <div class="table-container">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Ensayo</th>
                                            <th style="width:150px;" class="text-end">Precio Unit.</th>
                                            <th style="width:100px;" class="text-center">Cantidad</th>
                                            <th style="width:120px;" class="text-center">Impuesto</th>
                                            <th style="width:130px;" class="text-end">IVA</th>
                                            <th style="width:140px;" class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($detalles as $detalle)
                                            @php
                                                $subtotal = round($detalle->precio_unitario * $detalle->cantidad);
                                                $ivaMonto = 0;
                                                if ($detalle->impuesto_id == 2) {
                                                    $ivaMonto = round($subtotal / 11);
                                                } elseif ($detalle->impuesto_id == 3) {
                                                    $ivaMonto = round($subtotal / 21);
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $detalle->ensayo->descripcion }}</td>
                                                <td class="text-end">₲ {{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                                                <td class="text-center">{{ $detalle->cantidad }}</td>
                                                <td class="text-center"><span class="tag tag-secondary">{{ $detalle->impuesto->descripcion }}</span></td>
                                                <td class="text-end">₲ {{ number_format($ivaMonto, 0, ',', '.') }}</td>
                                                <td class="text-end"><span class="amount">₲ {{ number_format($subtotal, 0, ',', '.') }}</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Resumen de Precios --}}
            <div class="card">
                <div class="card-header-section">
                    <span><i class="fas fa-chart-line me-2"></i>Resumen de Precios</span>
                </div>
                <div class="card-body">
                    @php
                        $totalEnsayos = 0;
                        $totalImpuestos = 0;
                        $impuestosPorTipo = [];
                        foreach ($presupuesto->detalles as $detalle) {
                            $subtotal = round($detalle->precio_unitario * $detalle->cantidad);
                            $ivaMonto = 0;
                            if ($detalle->impuesto_id == 2) {
                                $ivaMonto = round($subtotal / 11);
                            } elseif ($detalle->impuesto_id == 3) {
                                $ivaMonto = round($subtotal / 21);
                            }
                            $totalEnsayos += $subtotal;
                            $totalImpuestos += $ivaMonto;
                            if ($ivaMonto > 0) {
                                $tipo = $detalle->impuesto->descripcion;
                                $impuestosPorTipo[$tipo] = ($impuestosPorTipo[$tipo] ?? 0) + $ivaMonto;
                            }
                        }
                        $totalGeneral = $totalEnsayos + $totalImpuestos;
                        $montoAnticipo = round($totalGeneral * $presupuesto->anticipo / 100);
                    @endphp
                    <div class="totals-grid">
                        <div class="totals-box">
                            <div class="totals-box-title">Desglose por Servicio</div>
                            @foreach($detallesPorServicio as $servicio => $detalles)
                                @php
                                    $subtotalServicio = 0;
                                    foreach ($detalles as $detalle) {
                                        $subtotalServicio += round($detalle->precio_unitario * $detalle->cantidad);
                                    }
                                @endphp
                                <div class="totals-row">
                                    <span>{{ $servicio }}</span>
                                    <strong>₲ {{ number_format($subtotalServicio, 0, ',', '.') }}</strong>
                                </div>
                            @endforeach
                        </div>
                        <div class="totals-box">
                            <div class="totals-box-title">Totales</div>
                            @foreach($impuestosPorTipo as $tipo => $monto)
                                <div class="totals-row">
                                    <span>IVA {{ $tipo }}</span>
                                    <strong>₲ {{ number_format($monto, 0, ',', '.') }}</strong>
                                </div>
                            @endforeach
                            <div class="totals-row">
                                <span>Total Servicios</span>
                                <strong>₲ {{ number_format($totalEnsayos, 0, ',', '.') }}</strong>
                            </div>
                            <div class="totals-row">
                                <span>Total Impuestos</span>
                                <strong>₲ {{ number_format($totalImpuestos, 0, ',', '.') }}</strong>
                            </div>
                            <div class="totals-row totals-final">
                                <span>TOTAL GENERAL</span>
                                <strong>₲ {{ number_format($totalGeneral, 0, ',', '.') }}</strong>
                            </div>
                            <div class="totals-row">
                                <span>Anticipo ({{ $presupuesto->anticipo }}%)</span>
                                <strong>₲ {{ number_format($montoAnticipo, 0, ',', '.') }}</strong>
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
.amount { font-weight: 700; color: #10b981; }

/* ── Estado ── */
.estado { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; color: #374151; }
.estado-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #94a3b8; flex-shrink: 0; }
.estado-pendiente .estado-dot  { background: #f59e0b; }
.estado-confirmado .estado-dot { background: #10b981; }
.estado-anulado .estado-dot    { background: #ef4444; }

/* ── Detalles de ensayos ── */
.precio-servicio-block { border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; }
.precio-servicio-block + .precio-servicio-block { margin-top: 1rem; }
.precio-servicio-header {
    padding: 0.6rem 1rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    font-weight: 600;
    font-size: 0.8rem;
    color: #2563eb;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.table-container { overflow: auto; }
.data-table {
    width: 100%;
    min-width: 750px;
    border-collapse: collapse;
    table-layout: fixed;
}
.data-table thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 0.6rem 0.65rem;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.data-table tbody td {
    padding: 0.6rem 0.65rem;
    font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
.data-table tbody tr:hover { background: #f8fafc; }
.data-table tbody tr:last-child td { border-bottom: none; }

/* ── Totales ── */
.totals-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
.totals-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
}
.totals-box-title {
    font-size: 0.7rem;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.5rem;
}
.totals-row { display: flex; justify-content: space-between; font-size: 0.85rem; color: #374151; padding: 0.25rem 0; }
.totals-row.totals-final {
    border-top: 1px solid #e2e8f0;
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
}
.totals-final strong { color: #10b981; }

@media (max-width: 768px) {
    .table-container { font-size: 0.875rem; }
    .totals-grid { grid-template-columns: 1fr; }
}

/* ── Impresión ── */
@media print {
    .main-content { margin-left: 0 !important; width: 100% !important; }
    .header-actions .btn { display: none !important; }
    .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
}
</style>
