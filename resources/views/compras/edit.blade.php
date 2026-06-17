<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Compra - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-file-invoice-dollar"></i> Editar Compra</h2>
                    <small>{{ $compra->nro_factura }} — solo editable mientras esté en estado Pendiente</small>
                </div>
                <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>

            {{-- Errores --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Corregí los siguientes errores:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('compras.update', $compra->id) }}" id="compraForm" onsubmit="return validarFechas()">
                @csrf
                @method('PUT')

                {{-- Información de la Orden --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-info-circle me-2"></i>Información de la Orden</span>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <label class="form-label">Proveedor</label>
                                <div class="info-value"><i class="fas fa-building"></i>{{ $compra->proveedor->razon_social ?? '-' }}</div>
                            </div>
                            <div class="info-item">
                                <label class="form-label">Orden de Compra</label>
                                <div class="info-value"><i class="fas fa-file-contract"></i>#{{ $compra->ordenCompra->id ?? '-' }}</div>
                            </div>
                            <div class="info-item">
                                <label class="form-label">Condición de Pago</label>
                                <div class="info-value"><i class="fas fa-file-invoice"></i>{{ $compra->condicionPago->descripcion ?? '-' }}</div>
                            </div>
                            <div class="info-item">
                                <label class="form-label">Método de Pago</label>
                                <div class="info-value"><i class="fas fa-credit-card"></i>{{ $compra->metodoPago->descripcion ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Artículos (solo lectura) --}}
                <div class="card table-card">
                    <div class="card-header-section">
                        <span><i class="fas fa-boxes me-2"></i>Artículos de la Compra</span>
                        <span class="results-count">{{ $compra->detalles->count() }} ítem(s)</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Insumo</th>
                                        <th style="width:120px;">Marca</th>
                                        <th style="width:100px;">Unidad</th>
                                        <th style="width:90px;" class="text-center">Cantidad</th>
                                        <th style="width:130px;" class="text-end">Precio Unit.</th>
                                        <th style="width:110px;">Impuesto</th>
                                        <th style="width:130px;" class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $iva5 = 0; $iva10 = 0; $exento = 0; @endphp
                                    @foreach($compra->detalles as $detalle)
                                        @php
                                            $subtotal = $detalle->precio_unitario * $detalle->cantidad;
                                            if ($detalle->impuesto_id == 2)      $iva5  += $subtotal / 21;
                                            elseif ($detalle->impuesto_id == 3)  $iva10 += $subtotal / 11;
                                            else                                  $exento += $subtotal;
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $detalle->insumo->descripcion ?? '-' }}</strong></td>
                                            <td><span class="tag tag-secondary">{{ $detalle->insumo->marca->descripcion ?? '-' }}</span></td>
                                            <td><span class="tag tag-secondary">{{ $detalle->insumo->unidadMedida->descripcion ?? '-' }}</span></td>
                                            <td class="text-center">{{ $detalle->cantidad }}</td>
                                            <td class="text-end">₲ {{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                                            <td><span class="tag tag-secondary">{{ $detalle->impuesto->descripcion ?? '-' }}</span></td>
                                            <td class="text-end"><span class="amount">₲ {{ number_format($subtotal, 0, ',', '.') }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Resumen Financiero --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-calculator me-2"></i>Resumen Financiero</span>
                    </div>
                    <div class="card-body">
                        @php $totalIva = $iva5 + $iva10; @endphp
                        <div class="totals-box">
                            <div class="totals-row"><span>IVA 5%</span><strong>₲ {{ number_format($iva5, 0, ',', '.') }}</strong></div>
                            <div class="totals-row"><span>IVA 10%</span><strong>₲ {{ number_format($iva10, 0, ',', '.') }}</strong></div>
                            <div class="totals-row"><span>Exento</span><strong>₲ {{ number_format($exento, 0, ',', '.') }}</strong></div>
                            <div class="totals-row"><span>Total IVA</span><strong>₲ {{ number_format($totalIva, 0, ',', '.') }}</strong></div>
                            <div class="totals-row totals-final"><span>Total Compra</span><strong>₲ {{ number_format($compra->monto, 0, ',', '.') }}</strong></div>
                        </div>
                    </div>
                </div>

                {{-- Datos editables de la Factura --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-file-invoice me-2"></i>Datos de la Factura del Proveedor</span>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div>
                                <label for="nro_factura" class="form-label">N° Factura *</label>
                                <input type="text" class="form-control form-control-sm @error('nro_factura') is-invalid @enderror"
                                       id="nro_factura" name="nro_factura"
                                       value="{{ old('nro_factura', $compra->nro_factura) }}"
                                       required maxlength="15" placeholder="001-002-1234567">
                                @error('nro_factura')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="nro_timbrado" class="form-label">N° Timbrado *</label>
                                <input type="text" class="form-control form-control-sm @error('nro_timbrado') is-invalid @enderror"
                                       id="nro_timbrado" name="nro_timbrado"
                                       value="{{ old('nro_timbrado', $compra->nro_timbrado) }}"
                                       required maxlength="8" placeholder="12345678">
                                @error('nro_timbrado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="fecha_emision" class="form-label">Fecha de Emisión *</label>
                                <input type="date" class="form-control form-control-sm @error('fecha_emision') is-invalid @enderror"
                                       id="fecha_emision" name="fecha_emision"
                                       value="{{ old('fecha_emision', $compra->fecha_emision->format('Y-m-d')) }}" required>
                                @error('fecha_emision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                                <input type="date" class="form-control form-control-sm @error('fecha_vencimiento') is-invalid @enderror"
                                       id="fecha_vencimiento" name="fecha_vencimiento"
                                       value="{{ old('fecha_vencimiento', $compra->fecha_vencimiento?->format('Y-m-d')) }}">
                                @error('fecha_vencimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="span-2">
                                <label for="observacion" class="form-label">Observación</label>
                                <textarea class="form-control form-control-sm" id="observacion" name="observacion"
                                          rows="1" maxlength="300">{{ old('observacion', $compra->observacion) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="form-actions">
                    <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar Cambios
                    </button>
                </div>

            </form>
        </div>
    </div>

    @include('partials.footer')

<script>
function validarFechas() {
    const emision = document.getElementById('fecha_emision').value;
    const venc    = document.getElementById('fecha_vencimiento').value;
    if (venc && venc < emision) {
        alert('La fecha de vencimiento no puede ser anterior a la de emisión.');
        return false;
    }
    return true;
}

document.getElementById('nro_factura').addEventListener('input', function () {
    let val = this.value.replace(/\D/g, '').slice(0, 13);
    let fmt = '';
    if (val.length > 0) fmt += val.slice(0, 3);
    if (val.length > 3) fmt += '-' + val.slice(3, 6);
    if (val.length > 6) fmt += '-' + val.slice(6, 13);
    this.value = fmt;
});

document.getElementById('nro_timbrado').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').slice(0, 8);
});
</script>

</body>
</html>

<style>
.content-wrapper { display: flex; flex-direction: column; gap: 1rem; }

.page-header {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 0.75rem;
    padding-bottom: 1rem; border-bottom: 1px solid #e2e8f0;
}
.page-header h2 { margin: 0; font-size: 1.25rem; font-weight: 600; color: #1e293b; }
.page-header h2 i { color: #94a3b8; margin-right: 0.4rem; }
.page-header small { color: #94a3b8; font-size: 0.8rem; }

.card { border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: none; }
.card-header-section {
    padding: 0.65rem 1rem; border-bottom: 1px solid #e2e8f0;
    display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;
    font-weight: 600; font-size: 0.85rem; color: #1e293b;
}
.results-count { font-weight: 400; font-size: 0.78rem; color: #94a3b8; }
.card-body { padding: 1rem 1.25rem; }

.info-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; }
.info-item .form-label,
.form-grid .form-label {
    display: block; font-size: 0.7rem; font-weight: 500;
    color: #94a3b8; margin-bottom: 0.25rem;
    text-transform: uppercase; letter-spacing: 0.4px;
}
.info-value {
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px;
    padding: 0.5rem 0.75rem; font-size: 0.85rem; color: #374151;
}
.info-value i { color: #94a3b8; margin-right: 0.5rem; width: 14px; text-align: center; }

.form-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; }
.form-grid .span-2 { grid-column: span 2; }

@media (max-width: 900px) {
    .info-grid, .form-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) {
    .info-grid, .form-grid { grid-template-columns: 1fr; }
    .form-grid .span-2 { grid-column: span 1; }
}

.table-card { display: flex; flex-direction: column; }
.table-container { overflow: auto; }
.data-table { width: 100%; min-width: 800px; border-collapse: collapse; table-layout: fixed; }
.data-table thead th {
    background: #f8fafc; color: #64748b; font-size: 0.72rem; font-weight: 600;
    padding: 0.6rem 0.65rem; border-bottom: 1px solid #e2e8f0;
    text-align: left; text-transform: uppercase; letter-spacing: 0.4px;
}
.data-table tbody td {
    padding: 0.6rem 0.65rem; font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9; vertical-align: middle; color: #374151;
}
.data-table tbody tr:hover { background: #f8fafc; }
.data-table tbody tr:last-child td { border-bottom: none; }

.tag { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 4px; font-size: 0.72rem; font-weight: 600; background: #eff6ff; color: #2563eb; }
.tag-secondary { background: #f1f5f9; color: #64748b; }
.amount { font-weight: 700; color: #10b981; }

.totals-box {
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;
    padding: 1rem; max-width: 360px; margin-left: auto;
}
.totals-row { display: flex; justify-content: space-between; font-size: 0.85rem; color: #374151; padding: 0.25rem 0; }
.totals-row.totals-final {
    border-top: 1px solid #e2e8f0; margin-top: 0.5rem; padding-top: 0.5rem;
    font-size: 1rem; font-weight: 700; color: #1e293b;
}
.totals-final strong { color: #10b981; }

.form-actions { display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 0.5rem; }
</style>
