<!-- filepath: c:\laragon\www\TesisGyA\resources\views\movimiento_insumos\remision.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Remisión {{ $movimiento->nro_remision }} - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-file-invoice"></i> Remisión N° {{ $movimiento->nro_remision }}</h2>
                    <small>Movimiento de Insumos #{{ str_pad($movimiento->id, 3, '0', STR_PAD_LEFT) }}</small>
                </div>
                <div class="header-actions">
                    <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                    <a href="{{ route('movimiento_insumos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            {{-- Talonario de remisión --}}
            <div class="remision-sheet">
                @if($movimiento->estado_id == 5)
                    <div class="sello sello-anulado">ANULADO</div>
                @elseif($movimiento->estado_id == 4)
                    <div class="sello sello-confirmado">CONFIRMADO</div>
                @endif

                <div class="remision-topbar">
                    <div class="remision-empresa">
                        <strong>NOTA DE REMISIÓN</strong>
                        <span>Movimiento Interno de Insumos</span>
                    </div>
                    <div class="remision-folio">
                        <span class="remision-folio-label">N°</span>
                        <span class="remision-folio-numero">{{ $movimiento->nro_remision }}</span>
                    </div>
                </div>

                <div class="remision-campos">
                    <div class="campo">
                        <span class="campo-label">Fecha</span>
                        <span class="campo-valor">{{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y') }}</span>
                    </div>
                    <div class="campo">
                        <span class="campo-label">Solicitud</span>
                        <span class="campo-valor">
                            @if($movimiento->solicitudMaterial)
                                #{{ str_pad($movimiento->solicitudMaterial->id, 3, '0', STR_PAD_LEFT) }}
                            @else
                                —
                            @endif
                        </span>
                    </div>
                    <div class="campo campo-ancho">
                        <span class="campo-label">Origen</span>
                        <span class="campo-valor">Depósito: {{ $movimiento->origenDeposito->ubicacion ?? '-' }}</span>
                    </div>
                    <div class="campo campo-ancho">
                        <span class="campo-label">Destino</span>
                        <span class="campo-valor">
                            @if($movimiento->destinoObra)
                                Obra: {{ $movimiento->destinoObra->ubicacion }}
                            @elseif($movimiento->destinoDeposito)
                                Depósito: {{ $movimiento->destinoDeposito->ubicacion }}
                            @else
                                —
                            @endif
                        </span>
                    </div>
                    <div class="campo campo-ancho">
                        <span class="campo-label">Chofer</span>
                        <span class="campo-valor">{{ $movimiento->chofer_nombre }}</span>
                    </div>
                    <div class="campo">
                        <span class="campo-label">C.I.</span>
                        <span class="campo-valor">{{ $movimiento->chofer_ci }}</span>
                    </div>
                    <div class="campo">
                        <span class="campo-label">Chapa</span>
                        <span class="campo-valor">{{ $movimiento->vehiculo_chapa }}</span>
                    </div>
                    <div class="campo">
                        <span class="campo-label">Vehículo</span>
                        <span class="campo-valor">{{ $movimiento->tipoVehiculo->descripcion ?? '-' }}</span>
                    </div>
                    <div class="campo">
                        <span class="campo-label">Registrado por</span>
                        <span class="campo-valor">{{ $movimiento->usuario->usuario ?? '-' }}</span>
                    </div>
                </div>

                <table class="remision-tabla">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:90px;">Cantidad</th>
                            <th style="width:90px;">Unidad</th>
                            <th>Descripción</th>
                            <th style="width:140px;">Marca</th>
                            <th>Observación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimiento->detalles as $detalle)
                            <tr>
                                <td class="text-center"><strong>{{ number_format($detalle->cantidad, 2, ',', '.') }}</strong></td>
                                <td>{{ $detalle->insumo->unidadMedida->descripcion ?? '-' }}</td>
                                <td>{{ $detalle->insumo->descripcion ?? '-' }}</td>
                                <td>{{ $detalle->insumo->marca->descripcion ?? '-' }}</td>
                                <td>{{ $detalle->observacion ?: '' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Sin insumos</td>
                            </tr>
                        @endforelse
                        @for($i = 0; $i < max(0, 4 - $movimiento->detalles->count()); $i++)
                            <tr class="fila-vacia">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>

                <div class="remision-observacion">
                    <span class="campo-label">Observación general</span>
                    <div class="observacion-lineas">{{ $movimiento->observacion }}</div>
                </div>

                <div class="remision-firmas">
                    <div class="firma">
                        <div class="firma-linea"></div>
                        <span>Firma del Chofer</span>
                    </div>
                    <div class="firma">
                        <div class="firma-linea"></div>
                        <span>Recibido Conforme</span>
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

/* ── Talonario ── */
.remision-sheet {
    position: relative;
    max-width: 760px;
    margin: 0 auto;
    background: #fffefb;
    border: 2px solid #1e293b;
    padding: 1.5rem;
    font-family: 'Courier New', Courier, monospace;
    color: #1e293b;
    box-shadow: 0 0 0 4px #fff, 0 0 0 5px #cbd5e1;
}
.remision-sheet::before {
    content: '';
    position: absolute;
    top: 8px; left: 8px; right: 8px; bottom: 8px;
    border: 1px dashed #cbd5e1;
    pointer-events: none;
}

/* ── Topbar ── */
.remision-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    border-bottom: 2px solid #1e293b;
    padding-bottom: 0.75rem;
    margin-bottom: 1rem;
}
.remision-empresa { display: flex; flex-direction: column; gap: 0.2rem; }
.remision-empresa strong { font-size: 1.1rem; letter-spacing: 1px; }
.remision-empresa span { font-size: 0.75rem; color: #64748b; }
.remision-folio {
    border: 2px solid #dc2626;
    border-radius: 4px;
    padding: 0.35rem 0.9rem;
    text-align: center;
    color: #dc2626;
    line-height: 1.2;
    transform: rotate(-2deg);
}
.remision-folio-label { display: block; font-size: 0.65rem; letter-spacing: 2px; }
.remision-folio-numero { display: block; font-size: 1rem; font-weight: 700; }

/* ── Campos tipo formulario ── */
.remision-campos {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.6rem 1rem;
    margin-bottom: 1.25rem;
}
.campo { display: flex; flex-direction: column; gap: 0.15rem; }
.campo-ancho { grid-column: span 2; }
.campo-label {
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #64748b;
}
.campo-valor {
    border-bottom: 1px dotted #94a3b8;
    padding-bottom: 0.2rem;
    font-size: 0.9rem;
    min-height: 1.4em;
}

/* ── Tabla de insumos ── */
.remision-tabla {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1.25rem;
    font-size: 0.85rem;
}
.remision-tabla th, .remision-tabla td {
    border: 1px solid #1e293b;
    padding: 0.4rem 0.5rem;
}
.remision-tabla thead th {
    background: #f1f5f9;
    text-transform: uppercase;
    font-size: 0.68rem;
    letter-spacing: 1px;
    text-align: left;
}
.remision-tabla .fila-vacia td { height: 1.8rem; color: transparent; }

/* ── Observación ── */
.remision-observacion { margin-bottom: 2.5rem; }
.observacion-lineas {
    border-bottom: 1px dotted #94a3b8;
    min-height: 1.6em;
    padding: 0.3rem 0.1rem;
    font-size: 0.85rem;
}

/* ── Firmas ── */
.remision-firmas {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    margin-top: 2.5rem;
}
.firma { text-align: center; }
.firma-linea { border-top: 1px solid #1e293b; margin-bottom: 0.4rem; }
.firma span { font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 1px; }

/* ── Sello de estado ── */
.sello {
    position: absolute;
    top: 1.5rem; right: 2rem;
    font-family: 'Courier New', Courier, monospace;
    font-weight: 700;
    letter-spacing: 4px;
    padding: 0.4rem 1.2rem;
    border: 3px solid;
    border-radius: 6px;
    opacity: 0.55;
    transform: rotate(-12deg);
    pointer-events: none;
}
.sello-confirmado { color: #16a34a; border-color: #16a34a; font-size: 0.95rem; }
.sello-anulado {
    color: #dc2626; border-color: #dc2626;
    font-size: 2.4rem;
    top: 45%; left: 50%; right: auto;
    transform: translate(-50%, -50%) rotate(-18deg);
    white-space: nowrap;
}

@media (max-width: 700px) {
    .remision-campos { grid-template-columns: repeat(2, 1fr); }
    .campo-ancho { grid-column: span 2; }
    .remision-firmas { grid-template-columns: 1fr; gap: 1.5rem; }
    .page-header { flex-direction: column; align-items: flex-start; }
}

/* ── Impresión ── */
@media print {
    .main-content { margin-left: 0 !important; width: 100% !important; }
    .header-actions { display: none !important; }
    .remision-sheet { box-shadow: none !important; }
}
</style>
