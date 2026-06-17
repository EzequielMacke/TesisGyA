<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Nota de Compra - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-file-alt"></i> Editar Nota de Compra</h2>
                    <small>{{ $nota->nro_nota }} — solo editable mientras esté en estado Pendiente</small>
                </div>
                <a href="{{ route('notas_compra.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>

            {{-- Errores de validación --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Corregí los siguientes errores:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('notas_compra.update', $nota->id) }}" method="POST" id="notaCompraForm">
                @csrf
                @method('PUT')

                {{-- PASO 1: Proveedor y Factura --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-building me-2"></i>Proveedor y Factura de Referencia</span>
                    </div>
                    <div class="card-body">
                        <div class="form-grid-2">
                            <div>
                                <label for="proveedor_id" class="form-label">Proveedor *</label>
                                <select class="form-select form-select-sm" id="proveedor_id" name="proveedor_id" required>
                                    <option value="">Seleccione un proveedor...</option>
                                    @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}"
                                            {{ old('proveedor_id', $nota->proveedor_id) == $proveedor->id ? 'selected' : '' }}>
                                            {{ $proveedor->razon_social }} — {{ $proveedor->ruc ?? 'Sin RUC' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="factura_id" class="form-label">Factura de Referencia *</label>
                                <select class="form-select form-select-sm" id="factura_id" name="factura_id" required>
                                    <option value="">Seleccione una factura...</option>
                                    @foreach($facturas as $f)
                                        <option value="{{ $f->id }}"
                                                data-monto="{{ $f->monto }}"
                                            {{ old('factura_id', $nota->factura_id) == $f->id ? 'selected' : '' }}>
                                            {{ $f->nro_factura }} — {{ \Carbon\Carbon::parse($f->fecha_emision)->format('d/m/Y') }} — ₲ {{ number_format($f->monto, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Al cambiar el proveedor se recargan las facturas</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PASO 2: Tipo de Documento --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-file-invoice me-2"></i>Tipo de Documento</span>
                    </div>
                    <div class="card-body">
                        <div class="tipo-doc-grid">
                            @foreach($tiposDocumento as $tipo)
                                @php $selTipo = old('tipo_documento_id', $nota->tipo_documento_id); @endphp
                                <label class="tipo-doc-option {{ $selTipo == $tipo->id ? 'selected' : '' }}">
                                    <input type="radio" name="tipo_documento_id" value="{{ $tipo->id }}"
                                           {{ $selTipo == $tipo->id ? 'checked' : '' }} required>
                                    <div class="tipo-doc-content">
                                        @if($tipo->id == 2)
                                            <i class="fas fa-arrow-down tipo-credito"></i>
                                            <span class="tipo-label tipo-credito">Nota de Crédito</span>
                                            <small>Reduce el importe a pagar</small>
                                        @else
                                            <i class="fas fa-arrow-up tipo-debito"></i>
                                            <span class="tipo-label tipo-debito">Nota de Débito</span>
                                            <small>Aumenta el importe a pagar</small>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- PASO 3: Datos del Documento --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-edit me-2"></i>Datos del Documento</span>
                    </div>
                    <div class="card-body">
                        <div class="form-grid-3">
                            <div>
                                <label for="nro_nota" class="form-label">N° de Nota *</label>
                                <input type="text" class="form-control form-control-sm @error('nro_nota') is-invalid @enderror"
                                       id="nro_nota" name="nro_nota"
                                       value="{{ old('nro_nota', $nota->nro_nota) }}"
                                       placeholder="001-001-0000001"
                                       maxlength="15" required>
                                <small class="text-muted">Formato: XXX-XXX-XXXXXXX</small>
                                @error('nro_nota')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="timbrado" class="form-label">Timbrado *</label>
                                <input type="text" class="form-control form-control-sm @error('timbrado') is-invalid @enderror"
                                       id="timbrado" name="timbrado"
                                       value="{{ old('timbrado', $nota->timbrado) }}"
                                       placeholder="12345678"
                                       maxlength="8" required>
                                <small class="text-muted">8 dígitos</small>
                                @error('timbrado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="iva_id" class="form-label">Tipo de IVA *</label>
                                <select class="form-select form-select-sm @error('iva_id') is-invalid @enderror"
                                        id="iva_id" name="iva_id" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($impuestos as $imp)
                                        <option value="{{ $imp->id }}"
                                            {{ old('iva_id', $nota->iva_id) == $imp->id ? 'selected' : '' }}>
                                            {{ $imp->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('iva_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="fecha_emision" class="form-label">Fecha de Emisión *</label>
                                <input type="date" class="form-control form-control-sm @error('fecha_emision') is-invalid @enderror"
                                       id="fecha_emision" name="fecha_emision"
                                       value="{{ old('fecha_emision', $nota->fecha_emision->format('Y-m-d')) }}" required>
                                @error('fecha_emision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento *</label>
                                <input type="date" class="form-control form-control-sm @error('fecha_vencimiento') is-invalid @enderror"
                                       id="fecha_vencimiento" name="fecha_vencimiento"
                                       value="{{ old('fecha_vencimiento', $nota->fecha_vencimiento->format('Y-m-d')) }}" required>
                                @error('fecha_vencimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="monto" class="form-label">Monto *</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">₲</span>
                                    <input type="number" class="form-control form-control-sm @error('monto') is-invalid @enderror"
                                           id="monto" name="monto"
                                           value="{{ old('monto', $nota->monto) }}"
                                           placeholder="0" min="1" step="1" required>
                                </div>
                                @error('monto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div id="monto-credito-feedback" class="invalid-feedback" style="display:none;"></div>
                            </div>
                        </div>

                        {{-- Concepto --}}
                        <div class="mt-3">
                            <label for="concepto" class="form-label">Concepto</label>
                            <input type="text" class="form-control form-control-sm @error('concepto') is-invalid @enderror"
                                   id="concepto" name="concepto"
                                   value="{{ old('concepto', $nota->concepto) }}"
                                   placeholder="Descripción opcional de la nota..."
                                   maxlength="255">
                            @error('concepto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="form-actions">
                    <a href="{{ route('notas_compra.index') }}" class="btn btn-secondary">
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
const facturasUrl = "{{ url('/notas-compra/facturas') }}";

document.getElementById('proveedor_id').addEventListener('change', function () {
    const proveedorId = this.value;
    const facturaSelect = document.getElementById('factura_id');

    facturaSelect.innerHTML = '<option value="">Cargando facturas...</option>';
    facturaSelect.disabled = true;

    if (!proveedorId) {
        facturaSelect.innerHTML = '<option value="">Seleccione primero un proveedor...</option>';
        return;
    }

    fetch(`${facturasUrl}/${proveedorId}`)
        .then(r => r.json())
        .then(data => {
            if (data.length === 0) {
                facturaSelect.innerHTML = '<option value="">Sin facturas disponibles</option>';
                return;
            }
            facturaSelect.innerHTML = '<option value="">Seleccione una factura...</option>';
            data.forEach(f => {
                const fecha = new Date(f.fecha_emision).toLocaleDateString('es-PY');
                const monto = Number(f.monto).toLocaleString('es-PY');
                const opt = document.createElement('option');
                opt.value = f.id;
                opt.dataset.monto = f.monto;
                opt.textContent = `${f.nro_factura} — ${fecha} — ₲ ${monto}`;
                facturaSelect.appendChild(opt);
            });
            facturaSelect.disabled = false;
        })
        .catch(() => {
            facturaSelect.innerHTML = '<option value="">Error al cargar facturas</option>';
        });
});

// Resaltar opción seleccionada de tipo de documento
document.querySelectorAll('input[name="tipo_documento_id"]').forEach(radio => {
    radio.addEventListener('change', function () {
        document.querySelectorAll('.tipo-doc-option').forEach(el => el.classList.remove('selected'));
        this.closest('.tipo-doc-option').classList.add('selected');
    });
});

// Máscara para nro_nota
document.getElementById('nro_nota').addEventListener('input', function () {
    let val = this.value.replace(/\D/g, '');
    if (val.length > 3)  val = val.slice(0,3) + '-' + val.slice(3);
    if (val.length > 7)  val = val.slice(0,7) + '-' + val.slice(7);
    if (val.length > 15) val = val.slice(0,15);
    this.value = val;
});

// Solo dígitos en timbrado
document.getElementById('timbrado').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').slice(0, 8);
});

// Validar monto contra factura cuando el tipo es Nota de Crédito
function getMontoFactura() {
    const sel = document.getElementById('factura_id');
    const opt = sel.options[sel.selectedIndex];
    return opt && opt.dataset.monto ? parseFloat(opt.dataset.monto) : null;
}

function getTipoDoc() {
    const checked = document.querySelector('input[name="tipo_documento_id"]:checked');
    return checked ? parseInt(checked.value) : null;
}

function validarMontoCredito() {
    const montoInput = document.getElementById('monto');
    const montoFactura = getMontoFactura();
    const tipo = getTipoDoc();
    const monto = parseFloat(montoInput.value);
    const feedback = document.getElementById('monto-credito-feedback');

    if (tipo === 2 && montoFactura !== null && monto > montoFactura) {
        montoInput.classList.add('is-invalid');
        feedback.textContent = `El monto no puede superar el de la factura (₲ ${montoFactura.toLocaleString('es-PY')}).`;
        feedback.style.display = 'block';
        return false;
    }
    montoInput.classList.remove('is-invalid');
    feedback.style.display = 'none';
    return true;
}

document.getElementById('monto').addEventListener('input', validarMontoCredito);
document.getElementById('factura_id').addEventListener('change', validarMontoCredito);
document.querySelectorAll('input[name="tipo_documento_id"]').forEach(r => r.addEventListener('change', validarMontoCredito));

document.getElementById('notaCompraForm').addEventListener('submit', function (e) {
    if (!validarMontoCredito()) e.preventDefault();
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
    display: flex; align-items: center; gap: 0.5rem;
    font-weight: 600; font-size: 0.85rem; color: #1e293b;
}
.card-body { padding: 1rem 1.25rem; }

.form-label { font-size: 0.78rem; font-weight: 500; color: #374151; margin-bottom: 0.3rem; }

.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }

@media (max-width: 900px) { .form-grid-3 { grid-template-columns: 1fr 1fr; } }
@media (max-width: 600px) { .form-grid-2, .form-grid-3 { grid-template-columns: 1fr; } }

.tipo-doc-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; max-width: 500px;
}
.tipo-doc-option {
    display: flex; align-items: center; gap: 0.75rem;
    border: 2px solid #e2e8f0; border-radius: 8px; padding: 1rem 1.25rem;
    cursor: pointer; transition: border-color 0.15s, background 0.15s;
}
.tipo-doc-option input[type="radio"] { display: none; }
.tipo-doc-option:hover { border-color: #94a3b8; background: #f8fafc; }
.tipo-doc-option.selected { border-color: #2563eb; background: #eff6ff; }
.tipo-doc-content {
    display: flex; flex-direction: column; align-items: flex-start; gap: 0.2rem;
}
.tipo-doc-content i { font-size: 1.3rem; }
.tipo-doc-content small { font-size: 0.72rem; color: #94a3b8; }
.tipo-label { font-size: 0.85rem; font-weight: 600; }
.tipo-credito { color: #059669; }
.tipo-debito  { color: #ea580c; }

.form-actions {
    display: flex; justify-content: flex-end; gap: 0.75rem;
    padding-top: 0.5rem;
}
</style>
