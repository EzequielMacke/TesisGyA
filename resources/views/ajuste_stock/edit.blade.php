<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Ajuste #{{ str_pad($ajuste->id, 3, '0', STR_PAD_LEFT) }} - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-sliders-h"></i> Editar Ajuste <span class="header-num">#{{ str_pad($ajuste->id, 3, '0', STR_PAD_LEFT) }}</span></h2>
                    <small>Modificar los datos del ajuste pendiente</small>
                </div>
                <a href="{{ route('ajuste_stocks.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>

            {{-- Alerts --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('ajuste_stocks.update', $ajuste->id) }}" method="POST" id="ajusteForm">
                @csrf
                @method('PATCH')

                {{-- Card: Destino (solo lectura) --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-map-marker-alt me-2"></i>Destino del Ajuste</span>
                        <span class="estado-badge estado-pendiente"><i class="estado-dot"></i>Pendiente</span>
                    </div>
                    <div class="card-body">
                        <div class="destino-readonly">
                            @if($ajuste->obra_id)
                                <i class="fas fa-hard-hat me-2 text-muted"></i>
                                <strong>Obra:</strong>
                                <span class="ms-2">{{ $ajuste->obra->descripcion ?? '—' }}</span>
                            @else
                                <i class="fas fa-warehouse me-2 text-muted"></i>
                                <strong>Depósito:</strong>
                                <span class="ms-2">{{ $ajuste->deposito->descripcion ?? '—' }}</span>
                            @endif
                            <span class="destino-info-hint">
                                <i class="fas fa-lock fa-xs me-1"></i>El destino no puede modificarse
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Tabla inventario --}}
                <div class="card table-card">
                    <div class="card-header-section">
                        <span><i class="fas fa-boxes me-2"></i>Inventario — complete los insumos a ajustar</span>
                        <span id="cant-insumos" style="font-size:0.78rem;font-weight:400;color:#94a3b8;"></span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Insumo</th>
                                        <th style="width:80px;" class="text-center">Unidad</th>
                                        <th style="width:105px;" class="text-center">Stock Actual</th>
                                        <th style="width:85px;" class="text-center">Tipo</th>
                                        <th style="width:115px;">Cantidad</th>
                                        <th style="width:210px;">Motivo *</th>
                                        <th style="width:180px;">Observación</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Datos generales --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-edit me-2"></i>Datos del Ajuste</span>
                    </div>
                    <div class="card-body">
                        <div class="form-grid-2">
                            <div>
                                <label class="form-label">Fecha *</label>
                                <input type="date" class="form-control form-control-sm" name="fecha"
                                       value="{{ $ajuste->fecha->format('Y-m-d') }}" required>
                            </div>
                            <div>
                                <label class="form-label">Observación general</label>
                                <input type="text" class="form-control form-control-sm" name="observacion"
                                       maxlength="255" placeholder="Opcional..."
                                       value="{{ old('observacion', $ajuste->observacion) }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <a href="{{ route('ajuste_stocks.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>

<style>
.content-wrapper { display: flex; flex-direction: column; gap: 1rem; }

.page-header {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 0.75rem; padding-bottom: 1rem; border-bottom: 1px solid #e2e8f0;
}
.page-header h2 { margin: 0; font-size: 1.25rem; font-weight: 600; color: #1e293b; }
.page-header h2 i { color: #94a3b8; margin-right: 0.4rem; }
.page-header small { color: #94a3b8; font-size: 0.8rem; }
.header-num { color: #2563eb; }

#ajusteForm { display: flex; flex-direction: column; gap: 1rem; }

.card { border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: none; }
.card-header-section {
    padding: 0.65rem 1rem; border-bottom: 1px solid #e2e8f0;
    display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;
    font-weight: 600; font-size: 0.85rem; color: #1e293b;
}

/* Destino readonly */
.destino-readonly {
    display: flex; align-items: center; gap: 0.25rem;
    font-size: 0.9rem; color: #374151; padding: 0.25rem 0;
}
.destino-info-hint {
    margin-left: auto; font-size: 0.72rem; color: #94a3b8;
    display: flex; align-items: center;
}

/* Estado badge */
.estado-badge { display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.75rem; font-weight: 500; }
.estado-dot { display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: #94a3b8; flex-shrink: 0; }
.estado-pendiente .estado-dot { background: #f59e0b; }

/* Form grid */
.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
.form-label {
    display: block; font-size: 0.7rem; font-weight: 500; color: #94a3b8;
    margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.4px;
}

/* Tabla */
.table-card { display: flex; flex-direction: column; }
.table-container { overflow: auto; }
.data-table { width: 100%; min-width: 860px; border-collapse: collapse; table-layout: fixed; }
.data-table thead th {
    background: #f8fafc; color: #64748b; font-size: 0.72rem; font-weight: 600;
    padding: 0.6rem 0.65rem; border-bottom: 1px solid #e2e8f0; text-align: left;
    text-transform: uppercase; letter-spacing: 0.4px; position: sticky; top: 0;
}
.data-table tbody td {
    padding: 0.55rem 0.65rem; font-size: 0.82rem; border-bottom: 1px solid #f1f5f9;
    vertical-align: middle; color: #374151;
}
.data-table tbody tr:hover { background: #f8fafc; }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr.sin-stock td { color: #94a3b8; background: #fafafa; }
.data-table tbody tr.con-ajuste { background: #fffbeb; }
.data-table tbody tr.con-ajuste:hover { background: #fef9c3; }

/* Tipo toggle */
.tipo-toggle { display: flex; border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden; width: fit-content; }
.tipo-toggle .t-btn {
    padding: 0.28rem 0.7rem; font-size: 0.85rem; font-weight: 700; border: none;
    background: #f8fafc; color: #94a3b8; cursor: pointer; transition: all 0.12s; line-height: 1.4;
}
.tipo-toggle .t-btn:first-child { border-right: 1px solid #e2e8f0; }
.tipo-toggle .t-btn.active-suma  { background: #dcfce7; color: #16a34a; }
.tipo-toggle .t-btn.active-resta { background: #fee2e2; color: #dc2626; }

/* Tags */
.tag { display: inline-block; padding: 0.15rem 0.45rem; border-radius: 4px; font-size: 0.7rem; font-weight: 600; background: #eff6ff; color: #2563eb; }
.tag-secondary { background: #f1f5f9; color: #64748b; }
.tag-danger    { background: #fee2e2; color: #dc2626; }
.tag-warning   { background: #fef9c3; color: #b45309; }

@media (max-width: 640px) {
    .form-grid-2 { grid-template-columns: 1fr; }
    .page-header { flex-direction: column; align-items: flex-start; }
    .destino-info-hint { display: none; }
}
</style>

<script>
const inventarioItems    = @json($inventarioItems);
const detallesExistentes = @json($detallesExistentes);

function escHtml(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(str ?? ''));
    return d.innerHTML;
}

function renderTabla() {
    const tbody = document.getElementById('tablaBody');
    document.getElementById('cant-insumos').textContent = inventarioItems.length + ' insumo(s)';

    if (!inventarioItems.length) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4">
            <i class="fas fa-inbox me-2"></i>No hay insumos registrados en este destino.
        </td></tr>`;
        return;
    }

    tbody.innerHTML = inventarioItems.map((item, i) => {
        const sinStock    = item.cantidad_actual <= 0;
        const det         = detallesExistentes[item.insumo_id];
        const tieneAjuste = det && det.cantidad > 0;
        return `
            <tr class="${sinStock ? 'sin-stock' : ''} ${tieneAjuste ? 'con-ajuste' : ''}">
                <td>
                    <strong>${escHtml(item.descripcion)}</strong>
                    <span class="tag tag-secondary ms-1">${escHtml(item.marca)}</span>
                    ${tieneAjuste ? '<span class="tag tag-warning ms-1">Ajustado</span>' : ''}
                    <input type="hidden" name="detalles[${i}][insumo_id]" value="${item.insumo_id}">
                    <input type="hidden" name="detalles[${i}][tipo_ajuste]" value="1" id="tipo_${i}">
                </td>
                <td class="text-center" style="white-space:nowrap;">${escHtml(item.unidad)}</td>
                <td class="text-center">
                    <span class="${sinStock ? 'tag tag-danger' : 'tag tag-secondary'}">${Number(item.cantidad_actual).toFixed(2)}</span>
                </td>
                <td class="text-center">
                    <div class="tipo-toggle">
                        <button type="button" class="t-btn active-suma" id="btn-suma-${i}"
                                onclick="toggleTipo(${i}, 1)" title="Sumar al stock">+</button>
                        <button type="button" class="t-btn" id="btn-resta-${i}"
                                onclick="toggleTipo(${i}, 2)" title="Restar del stock">−</button>
                    </div>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm"
                           name="detalles[${i}][cantidad]" id="cant_${i}"
                           min="0" step="0.01" placeholder="0.00"
                           data-stock="${item.cantidad_actual}">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm"
                           name="detalles[${i}][motivo]"
                           maxlength="255" placeholder="Requerido si hay cantidad...">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm"
                           name="detalles[${i}][observacion]"
                           maxlength="255" placeholder="Opcional...">
                </td>
            </tr>`;
    }).join('');

    preFillDetalles();
}

function preFillDetalles() {
    inventarioItems.forEach((item, i) => {
        const det = detallesExistentes[item.insumo_id];
        if (!det || det.cantidad <= 0) return;

        toggleTipo(i, det.tipo_ajuste || 1);

        const cantInput   = document.querySelector(`[name="detalles[${i}][cantidad]"]`);
        const motivoInput = document.querySelector(`[name="detalles[${i}][motivo]"]`);
        const obsInput    = document.querySelector(`[name="detalles[${i}][observacion]"]`);

        if (cantInput)   cantInput.value   = det.cantidad;
        if (motivoInput) motivoInput.value = det.motivo;
        if (obsInput)    obsInput.value    = det.observacion;
    });
}

function toggleTipo(idx, tipo) {
    document.getElementById('tipo_' + idx).value = tipo;
    const btnS      = document.getElementById('btn-suma-'  + idx);
    const btnR      = document.getElementById('btn-resta-' + idx);
    const cantInput = document.getElementById('cant_' + idx);

    if (tipo === 1) {
        btnS.classList.add('active-suma');    btnS.classList.remove('active-resta');
        btnR.classList.remove('active-suma', 'active-resta');
        cantInput && cantInput.removeAttribute('max');
    } else {
        btnR.classList.add('active-resta');   btnR.classList.remove('active-suma');
        btnS.classList.remove('active-suma', 'active-resta');
        cantInput && cantInput.setAttribute('max', cantInput.dataset.stock || 0);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    renderTabla();

    document.getElementById('ajusteForm').addEventListener('submit', function (e) {
        let hayUno = false;

        for (let i = 0; i < inventarioItems.length; i++) {
            const cantInput   = document.querySelector(`[name="detalles[${i}][cantidad]"]`);
            const motivoInput = document.querySelector(`[name="detalles[${i}][motivo]"]`);
            const tipoInput   = document.getElementById('tipo_' + i);
            if (!cantInput) continue;

            const cant = parseFloat(cantInput.value || 0);
            if (cant > 0) {
                hayUno = true;
                if (!motivoInput || !motivoInput.value.trim()) {
                    e.preventDefault();
                    alert('El motivo es obligatorio para todos los insumos con cantidad.');
                    motivoInput && motivoInput.focus();
                    return;
                }
                if (tipoInput && tipoInput.value == 2) {
                    const stock = parseFloat(cantInput.dataset.stock || 0);
                    if (cant > stock) {
                        e.preventDefault();
                        alert(`La cantidad a restar (${cant}) supera el stock disponible (${stock}).`);
                        cantInput.focus();
                        return;
                    }
                }
            }
        }

        if (!hayUno) {
            e.preventDefault();
            alert('Debe ingresar al menos un insumo con cantidad mayor a 0.');
        }
    });
});
</script>
