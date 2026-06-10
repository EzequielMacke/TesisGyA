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

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-eye"></i> Pedido de Compra #{{ str_pad($pedido->id, 3, '0', STR_PAD_LEFT) }}</h2>
                    <small>Detalle del pedido y productos solicitados</small>
                </div>
                <div class="header-actions">
                    @if($pedido->estado_id == 3)
                        <a href="{{ route('pedido_compra.edit', $pedido->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Editar
                        </a>
                        <button type="button" class="btn btn-danger" onclick="anularPedido({{ $pedido->id }})">
                            <i class="fas fa-ban me-2"></i>Anular Pedido
                        </button>
                    @endif
                    <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                    <a href="{{ route('pedido_compra.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            {{-- Alerts --}}
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

            {{-- Información General --}}
            <div class="card">
                <div class="card-header-section">
                    <span><i class="fas fa-info-circle me-2"></i>Información General</span>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label class="form-label">Código del Pedido</label>
                            <div class="info-value"><i class="fas fa-hashtag"></i>{{ str_pad($pedido->id, 3, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Estado</label>
                            <div class="info-value">
                                @switch($pedido->estado_id)
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
                                        <span class="estado"><i class="estado-dot"></i>{{ $pedido->estado->descripcion }}</span>
                                @endswitch
                            </div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Usuario Solicitante</label>
                            <div class="info-value"><i class="fas fa-user"></i>{{ $pedido->usuario->usuario }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Fecha de Pedido</label>
                            <div class="info-value">
                                <i class="fas fa-calendar"></i>{{ $pedido->fecha->format('d/m/Y') }}
                                <span class="text-muted ms-1">{{ $pedido->created_at->format('H:i') }}</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Sucursal</label>
                            <div class="info-value"><i class="fas fa-building"></i>{{ $pedido->sucursal->descripcion }}</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label">Depósito</label>
                            <div class="info-value"><i class="fas fa-warehouse"></i>{{ $pedido->deposito->descripcion }}</div>
                        </div>
                    </div>

                    @if($pedido->observacion)
                        <div class="mt-3">
                            <label class="form-label">Observación General</label>
                            <div class="info-value observation-box">{{ $pedido->observacion }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Estadísticas --}}
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-icon"><i class="fas fa-boxes"></i></div>
                    <div>
                        <div class="stat-value">{{ $pedido->detalles->count() }}</div>
                        <div class="stat-label">Productos Solicitados</div>
                    </div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon"><i class="fas fa-sort-numeric-up"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($pedido->detalles->sum('cantidad'), 0, ',', '.') }}</div>
                        <div class="stat-label">Cantidad Total</div>
                    </div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="stat-value">{{ $pedido->created_at->diffForHumans() }}</div>
                        <div class="stat-label">Tiempo Transcurrido</div>
                    </div>
                </div>
            </div>

            {{-- Detalle de Productos --}}
            <div class="card table-card">
                <div class="card-header-section">
                    <span><i class="fas fa-list me-2"></i>Detalle de Productos</span>
                    <span class="results-count">{{ $pedido->detalles->count() }} producto(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        @if($pedido->detalles->count() > 0)
                            <table id="detalleTable">
                                <thead>
                                    <tr>
                                        <th style="width:50px;" class="text-center">#</th>
                                        <th>Producto</th>
                                        <th style="width:120px;" class="text-center">Cantidad</th>
                                        <th style="width:100px;" class="text-center">Unidad</th>
                                        <th>Observación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pedido->detalles as $index => $detalle)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <i class="fas fa-cube text-muted me-2"></i><strong>{{ $detalle->insumo->descripcion }}</strong>
                                                @if($detalle->insumo->marca->descripcion)
                                                    <br><span class="tag tag-secondary mt-1">{{ $detalle->insumo->marca->descripcion }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="tag">{{ number_format($detalle->cantidad, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="tag tag-secondary">{{ $detalle->insumo->unidadMedida->descripcion ?? 'Unidad' }}</span>
                                            </td>
                                            <td>
                                                @if($detalle->observacion)
                                                    {{ $detalle->observacion }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-end">Total</th>
                                        <th class="text-center">
                                            <span class="tag">{{ number_format($pedido->detalles->sum('cantidad'), 0, ',', '.') }}</span>
                                        </th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <h5 class="text-muted mb-2">Sin productos</h5>
                                <p class="text-muted mb-0" style="font-size:0.85rem;">
                                    Este pedido no tiene productos asociados.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Auditoría --}}
            <div class="card">
                <div class="card-header-section">
                    <span><i class="fas fa-history me-2"></i>Información de Auditoría</span>
                </div>
                <div class="card-body">
                    <div class="audit-grid">
                        <div class="audit-item">
                            <i class="fas fa-plus-circle"></i>
                            <div>
                                <strong>Creado:</strong> {{ $pedido->created_at->format('d/m/Y H:i:s') }}
                                <span class="text-muted">({{ $pedido->created_at->diffForHumans() }})</span>
                            </div>
                        </div>
                        <div class="audit-item">
                            <i class="fas fa-edit"></i>
                            <div>
                                <strong>Última modificación:</strong> {{ $pedido->updated_at->format('d/m/Y H:i:s') }}
                                <span class="text-muted">({{ $pedido->updated_at->diffForHumans() }})</span>
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
.header-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }

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
.results-count { font-weight: 400; font-size: 0.78rem; color: #94a3b8; }

/* ── Información general ── */
.info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
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
.observation-box {
    white-space: pre-wrap;
    line-height: 1.5;
}

@media (max-width: 900px) {
    .info-grid { grid-template-columns: repeat(2, 1fr); }
    .page-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .info-grid { grid-template-columns: 1fr; }
}

/* ── Estado ── */
.estado { display: inline-flex; align-items: center; gap: 0.4rem; }
.estado-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #94a3b8; flex-shrink: 0; }
.estado-pendiente .estado-dot  { background: #f59e0b; }
.estado-confirmado .estado-dot { background: #10b981; }
.estado-anulado .estado-dot    { background: #ef4444; }

/* ── Estadísticas ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}
.stat-box {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.85rem;
}
.stat-icon {
    width: 42px; height: 42px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 8px;
    background: #eff6ff; color: #2563eb;
    font-size: 1.1rem; flex-shrink: 0;
}
.stat-value { font-size: 1.3rem; font-weight: 700; color: #1e293b; line-height: 1.2; }
.stat-label { font-size: 0.72rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.4px; }

@media (max-width: 768px) {
    .stats-grid { grid-template-columns: 1fr; }
}

/* ── Tabla de productos ── */
.table-card { display: flex; flex-direction: column; }
.table-container { overflow: auto; }

#detalleTable {
    width: 100%;
    min-width: 700px;
    border-collapse: collapse;
    table-layout: fixed;
}
#detalleTable thead th {
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
#detalleTable tbody td {
    padding: 0.6rem 0.65rem;
    font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
#detalleTable tbody tr:hover { background: #f8fafc; }
#detalleTable tbody tr:last-child td { border-bottom: none; }
#detalleTable tfoot th {
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    padding: 0.6rem 0.65rem;
    font-size: 0.78rem;
    font-weight: 600;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

/* Tags */
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

/* Empty state */
.empty-state {
    min-height: 240px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 2rem; color: #94a3b8; text-align: center;
}
.empty-state i { color: #cbd5e1; }

/* ── Auditoría ── */
.audit-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
    font-size: 0.82rem;
    color: #374151;
}
.audit-item { display: flex; align-items: flex-start; gap: 0.5rem; }
.audit-item i { color: #94a3b8; margin-top: 2px; }

@media (max-width: 768px) {
    .audit-grid { grid-template-columns: 1fr; }
    .table-container { font-size: 0.875rem; }
}

/* ── Impresión ── */
@media print {
    .main-content { margin-left: 0 !important; width: 100% !important; }
    .header-actions { display: none !important; }
    .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    window.anularPedido = function (pedidoId) {
        const confirm_ = () => enviarAnulacion(pedidoId);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Anular Pedido?',
                text: 'El pedido pasará a estado "Anulado" y no podrá revertirse.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, Anular',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then(r => { if (r.isConfirmed) confirm_(); });
        } else {
            if (confirm('¿Anular este pedido?')) confirm_();
        }
    };

    function enviarAnulacion(pedidoId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/pedido_compra/${pedidoId}/anular`;
        const csrf = document.querySelector('meta[name="csrf-token"]');
        if (csrf) {
            const t = document.createElement('input');
            t.type = 'hidden'; t.name = '_token'; t.value = csrf.content;
            form.appendChild(t);
        }
        const m = document.createElement('input');
        m.type = 'hidden'; m.name = '_method'; m.value = 'PATCH';
        form.appendChild(m);
        document.body.appendChild(form);
        form.submit();
    }
});
</script>
