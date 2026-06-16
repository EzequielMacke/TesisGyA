<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Ajuste de Inventario - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-sliders-h"></i> Nuevo Ajuste de Inventario</h2>
                    <small>Registrar entrada o salida manual de stock</small>
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

            <form action="{{ route('ajuste_stocks.store') }}" method="POST" id="ajusteForm">
                @csrf

                {{-- Card: Destino del ajuste --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-map-marker-alt me-2"></i>Destino del Ajuste</span>
                    </div>
                    <div class="card-body">

                        <div class="destino-toggle mb-3">
                            <button type="button" class="destino-btn active" id="btn-deposito" onclick="setTipoDestino('deposito')">
                                <i class="fas fa-warehouse me-2"></i>Depósito
                            </button>
                            <button type="button" class="destino-btn" id="btn-obra" onclick="setTipoDestino('obra')">
                                <i class="fas fa-hard-hat me-2"></i>Obra
                            </button>
                        </div>
                        <input type="hidden" name="tipo_destino" id="tipo_destino" value="deposito">

                        {{-- Sección Depósito --}}
                        <div id="seccion-deposito">
                            <label class="form-label">Depósito *</label>
                            <select class="form-select form-select-sm" name="deposito_id" id="deposito_id">
                                <option value="">Seleccione un depósito...</option>
                                @foreach($depositos as $deposito)
                                    <option value="{{ $deposito->id }}">{{ $deposito->descripcion }}</option>
                                @endforeach
                            </select>
                            @if($depositos->isEmpty())
                                <small class="text-muted"><i class="fas fa-info-circle me-1"></i>No hay depósitos con inventario.</small>
                            @else
                                <small class="text-muted">Solo se muestran depósitos con inventario registrado.</small>
                            @endif
                        </div>

                        {{-- Sección Obra --}}
                        <div id="seccion-obra" style="display:none;">
                            <div class="form-grid-2">
                                <div>
                                    <label class="form-label">Cliente *</label>
                                    <select class="form-select form-select-sm" id="cliente_id">
                                        <option value="">Seleccione un cliente...</option>
                                        @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->id }}">{{ $cliente->razon_social }}</option>
                                        @endforeach
                                    </select>
                                    @if($clientes->isEmpty())
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>No hay clientes con inventario en obras.</small>
                                    @else
                                        <small class="text-muted">Solo clientes con obras que tienen inventario.</small>
                                    @endif
                                </div>
                                <div>
                                    <label class="form-label">Obra *</label>
                                    <select class="form-select form-select-sm" name="obra_id" id="obra_id" disabled>
                                        <option value="">Primero seleccione un cliente...</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Inventario AJAX --}}
                <div id="inventario-container"></div>

                {{-- Datos generales --}}
                <div class="card" id="datos-generales" style="display:none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-edit me-2"></i>Datos del Ajuste</span>
                    </div>
                    <div class="card-body">
                        <div class="form-grid-2">
                            <div>
                                <label class="form-label">Fecha *</label>
                                <input type="date" class="form-control form-control-sm" name="fecha" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div>
                                <label class="form-label">Observación general</label>
                                <input type="text" class="form-control form-control-sm" name="observacion" maxlength="255" placeholder="Opcional...">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="card" id="botones-accion" style="display:none;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <a href="{{ route('ajuste_stocks.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Guardar Ajuste
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

/* Cabecera */
.page-header {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 0.75rem; padding-bottom: 1rem; border-bottom: 1px solid #e2e8f0;
}
.page-header h2 { margin: 0; font-size: 1.25rem; font-weight: 600; color: #1e293b; }
.page-header h2 i { color: #94a3b8; margin-right: 0.4rem; }
.page-header small { color: #94a3b8; font-size: 0.8rem; }

/* Form */
#ajusteForm { display: flex; flex-direction: column; gap: 1rem; }

/* Cards */
.card { border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: none; }
.card-header-section {
    padding: 0.65rem 1rem; border-bottom: 1px solid #e2e8f0;
    display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;
    font-weight: 600; font-size: 0.85rem; color: #1e293b;
}

/* Toggle destino */
.destino-toggle { display: flex; gap: 0.5rem; }
.destino-btn {
    padding: 0.45rem 1.25rem; border: 1.5px solid #e2e8f0; border-radius: 6px;
    background: #fff; color: #64748b; font-size: 0.85rem; font-weight: 500;
    cursor: pointer; transition: all 0.15s;
}
.destino-btn:hover { border-color: #94a3b8; color: #1e293b; }
.destino-btn.active { border-color: #2563eb; background: #eff6ff; color: #2563eb; font-weight: 600; }

/* Form grid */
.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
.form-label {
    display: block; font-size: 0.7rem; font-weight: 500; color: #94a3b8;
    margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.4px;
}

/* Tabla inventario */
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

/* Tipo ajuste toggle */
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
.tag-danger { background: #fee2e2; color: #dc2626; }

/* Empty state */
.empty-state {
    min-height: 160px; display: flex; flex-direction: column;
    align-items: center; justify-content: center; padding: 2rem; color: #94a3b8; text-align: center;
}
.empty-state i { color: #cbd5e1; }

@media (max-width: 640px) {
    .form-grid-2 { grid-template-columns: 1fr; }
    .page-header { flex-direction: column; align-items: flex-start; }
}
</style>

<script>
let inventarioItems = [];

/* ── Tipo destino ── */
function setTipoDestino(tipo) {
    document.getElementById('tipo_destino').value = tipo;
    const activo = tipo === 'deposito';
    document.getElementById('btn-deposito').classList.toggle('active', activo);
    document.getElementById('btn-obra').classList.toggle('active', !activo);
    document.getElementById('seccion-deposito').style.display = activo ? '' : 'none';
    document.getElementById('seccion-obra').style.display     = activo ? 'none' : '';
    if (activo) {
        document.getElementById('obra_id').disabled = true;
    } else {
        document.getElementById('deposito_id').value = '';
    }
    limpiarInventario();
}

/* ── Inventario ── */
function limpiarInventario() {
    inventarioItems = [];
    document.getElementById('inventario-container').innerHTML = '';
    document.getElementById('datos-generales').style.display = 'none';
    document.getElementById('botones-accion').style.display  = 'none';
}

function cargarInventario(url) {
    document.getElementById('inventario-container').innerHTML = `
        <div class="card"><div class="card-body">
            <div class="empty-state"><i class="fas fa-spinner fa-spin fa-2x mb-2"></i><span>Cargando inventario...</span></div>
        </div></div>`;

    fetch(url)
        .then(r => r.json())
        .then(data => { inventarioItems = data.items || []; renderTabla(); })
        .catch(() => {
            document.getElementById('inventario-container').innerHTML = `
                <div class="card"><div class="card-body">
                    <div class="empty-state"><i class="fas fa-exclamation-triangle fa-2x mb-2"></i><span>Error al cargar el inventario.</span></div>
                </div></div>`;
        });
}

/* ── Render ── */
function renderTabla() {
    const container = document.getElementById('inventario-container');

    if (inventarioItems.length === 0) {
        container.innerHTML = `
            <div class="card table-card">
                <div class="card-header-section"><span><i class="fas fa-boxes me-2"></i>Inventario</span></div>
                <div class="card-body">
                    <div class="empty-state"><i class="fas fa-inbox fa-2x mb-2"></i><span>No hay insumos registrados en este destino.</span></div>
                </div>
            </div>`;
        document.getElementById('datos-generales').style.display = 'none';
        document.getElementById('botones-accion').style.display  = 'none';
        return;
    }

    let filas = '';
    inventarioItems.forEach((item, i) => {
        const sinStock = item.cantidad_actual <= 0;
        filas += `
            <tr class="${sinStock ? 'sin-stock' : ''}">
                <td>
                    <strong>${escHtml(item.descripcion)}</strong>
                    <span class="tag tag-secondary ms-1">${escHtml(item.marca)}</span>
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
                    <input type="number" class="form-control form-control-sm" name="detalles[${i}][cantidad]"
                           id="cant_${i}" min="0" step="0.01" placeholder="0.00"
                           data-stock="${item.cantidad_actual}">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="detalles[${i}][motivo]"
                           maxlength="255" placeholder="Requerido si hay cantidad...">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="detalles[${i}][observacion]"
                           maxlength="255" placeholder="Opcional...">
                </td>
            </tr>`;
    });

    container.innerHTML = `
        <div class="card table-card">
            <div class="card-header-section">
                <span><i class="fas fa-boxes me-2"></i>Inventario — complete los insumos a ajustar</span>
                <span style="font-size:0.78rem;font-weight:400;color:#94a3b8;">${inventarioItems.length} insumo(s)</span>
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
                        <tbody>${filas}</tbody>
                    </table>
                </div>
            </div>
        </div>`;

    document.getElementById('datos-generales').style.display = '';
    document.getElementById('botones-accion').style.display  = '';
}

/* ── Toggle tipo ── */
function toggleTipo(idx, tipo) {
    document.getElementById('tipo_' + idx).value = tipo;
    const btnS = document.getElementById('btn-suma-'  + idx);
    const btnR = document.getElementById('btn-resta-' + idx);
    const cantInput = document.getElementById('cant_' + idx);

    if (tipo === 1) {
        btnS.classList.add('active-suma');    btnS.classList.remove('active-resta');
        btnR.classList.remove('active-suma', 'active-resta');
        cantInput.removeAttribute('max');
    } else {
        btnR.classList.add('active-resta');   btnR.classList.remove('active-suma');
        btnS.classList.remove('active-suma', 'active-resta');
        cantInput.setAttribute('max', cantInput.dataset.stock || 0);
    }
}

/* ── Escape HTML ── */
function escHtml(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(str));
    return d.innerHTML;
}

/* ── Eventos ── */
document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('deposito_id').addEventListener('change', function () {
        if (!this.value) { limpiarInventario(); return; }
        cargarInventario(`/api/ajuste-stocks/inventario-deposito/${this.value}`);
    });

    document.getElementById('cliente_id').addEventListener('change', function () {
        const obraSelect = document.getElementById('obra_id');
        obraSelect.innerHTML = '<option value="">Cargando obras...</option>';
        obraSelect.disabled = true;
        limpiarInventario();

        if (!this.value) {
            obraSelect.innerHTML = '<option value="">Primero seleccione un cliente...</option>';
            return;
        }

        fetch(`/api/ajuste-stocks/obras/${this.value}`)
            .then(r => r.json())
            .then(obras => {
                if (!obras.length) {
                    obraSelect.innerHTML = '<option value="">Sin obras con inventario</option>';
                    return;
                }
                obraSelect.innerHTML = '<option value="">Seleccione una obra...</option>' +
                    obras.map(o => `<option value="${o.id}">${escHtml(o.descripcion)}</option>`).join('');
                obraSelect.disabled = false;
            });
    });

    document.getElementById('obra_id').addEventListener('change', function () {
        if (!this.value) { limpiarInventario(); return; }
        cargarInventario(`/api/ajuste-stocks/inventario-obra/${this.value}`);
    });

    /* Validación cliente */
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
