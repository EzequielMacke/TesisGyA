<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Insumos - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content fade-in">

        <!-- Cabecera -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Insumos</h1>
                <p class="page-sub">Gestión de insumos de construcción</p>
            </div>
            <button class="btn-nuevo" data-bs-toggle="modal" data-bs-target="#modalCrear">
                <i class="fas fa-plus"></i>
                Nuevo Insumo
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Buscador -->
        <div class="search-bar mb-3">
            <i class="fas fa-search search-icon"></i>
            <input type="text"
                   id="searchInput"
                   class="search-input"
                   placeholder="Buscar por descripción, marca, unidad, estado o usuario..."
                   autocomplete="off">
            <button class="search-clear" id="clearSearch" title="Limpiar">
                <i class="fas fa-times"></i>
            </button>
            <span class="search-count">
                <span id="totalRows">{{ $insumos->count() }}</span> registro(s)
            </span>
        </div>

        <!-- Tabla -->
        <div class="table-card">
            <div class="table-scroll">
                <table class="data-table" id="insumosTable">
                    <thead>
                        <tr>
                            <th style="width:60px">ID</th>
                            <th>Descripción</th>
                            <th style="width:140px">Marca</th>
                            <th style="width:100px">Unidad</th>
                            <th style="width:110px">Fecha</th>
                            <th style="width:90px">Estado</th>
                            <th style="width:120px">Usuario</th>
                            <th style="width:100px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($insumos as $insumo)
                            <tr class="insumo-row">
                                <td class="td-id">{{ $insumo->id }}</td>
                                <td title="{{ $insumo->descripcion }}">{{ $insumo->descripcion }}</td>
                                <td title="{{ $insumo->marca->descripcion }}">
                                    <span class="badge-soft badge-marca">{{ $insumo->marca->descripcion }}</span>
                                </td>
                                <td>
                                    <span class="badge-soft badge-unidad">
                                        {{ $insumo->unidadMedida->abreviatura ?? $insumo->unidadMedida->descripcion }}
                                    </span>
                                </td>
                                <td class="td-muted">{{ $insumo->fecha->format('d/m/Y') }}</td>
                                <td>
                                    @if($insumo->estado->id == 1)
                                        <span class="badge-soft badge-activo">Activo</span>
                                    @else
                                        <span class="badge-soft badge-inactivo">Inactivo</span>
                                    @endif
                                </td>
                                <td class="td-muted" title="{{ $insumo->usuario->usuario }}">{{ $insumo->usuario->usuario }}</td>
                                <td>
                                    <div class="action-btns">
                                        @if($insumo->estado->id == 1)
                                            <button type="button"
                                                    class="action-btn action-edit"
                                                    title="Editar"
                                                    onclick="abrirEditar({{ $insumo->id }}, '{{ addslashes($insumo->descripcion) }}', {{ $insumo->marca_id }}, {{ $insumo->unidad_medida_id }}, '{{ $insumo->fecha->format('Y-m-d') }}')">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <button type="button"
                                                    class="action-btn action-delete"
                                                    title="Desactivar"
                                                    onclick="abrirDesactivar({{ $insumo->id }}, '{{ addslashes($insumo->descripcion) }}')">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        @else
                                            <button type="button"
                                                    class="action-btn action-activate"
                                                    title="Activar"
                                                    onclick="abrirActivar({{ $insumo->id }}, '{{ addslashes($insumo->descripcion) }}')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="noRecords">
                                <td colspan="8" class="empty-state">
                                    <i class="fas fa-boxes"></i>
                                    <p>No hay insumos registrados</p>
                                </td>
                            </tr>
                        @endforelse
                        <tr id="noResults" style="display:none">
                            <td colspan="8" class="empty-state">
                                <i class="fas fa-search"></i>
                                <p>Sin resultados para la búsqueda</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    @include('partials.footer')

    {{-- ══ MODAL: CREAR ══ --}}
    <div class="modal fade" id="modalCrear" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-clean">
                <div class="modal-header-clean">
                    <div class="modal-icon-wrap modal-icon-blue">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div>
                        <h5 class="modal-title-clean">Nuevo Insumo</h5>
                        <p class="modal-sub-clean">Completá los datos para registrar un nuevo insumo</p>
                    </div>
                    <button type="button" class="modal-close-btn" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('insumo.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="estado_id" value="1">
                    <input type="hidden" name="fecha" value="{{ date('Y-m-d') }}">
                    <div class="modal-body-clean">
                        <div class="field-group">
                            <label class="field-label" for="crear_descripcion">Descripción</label>
                            <input type="text"
                                   id="crear_descripcion"
                                   name="descripcion"
                                   class="field-input"
                                   placeholder="Ej: Cemento Portland, Pintura látex..."
                                   required
                                   autocomplete="off">
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="crear_marca_id">Marca</label>
                            <select id="crear_marca_id" name="marca_id" class="field-input" required>
                                <option value="">Seleccioná una marca...</option>
                                @foreach($marcas as $marca)
                                    <option value="{{ $marca->id }}">{{ $marca->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="crear_unidad_medida_id">Unidad de Medida</label>
                            <select id="crear_unidad_medida_id" name="unidad_medida_id" class="field-input" required>
                                <option value="">Seleccioná una unidad...</option>
                                @foreach($unidadesMedida as $um)
                                    <option value="{{ $um->id }}">{{ $um->descripcion }}{{ $um->abreviatura ? ' ('.$um->abreviatura.')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer-clean">
                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-modal-confirm btn-blue">
                            <i class="fas fa-plus me-1"></i> Crear Insumo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══ MODAL: EDITAR ══ --}}
    <div class="modal fade" id="modalEditar" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-clean">
                <div class="modal-header-clean">
                    <div class="modal-icon-wrap modal-icon-blue">
                        <i class="fas fa-pencil-alt"></i>
                    </div>
                    <div>
                        <h5 class="modal-title-clean">Editar Insumo</h5>
                        <p class="modal-sub-clean">Modificá los datos del insumo seleccionado</p>
                    </div>
                    <button type="button" class="modal-close-btn" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="formEditar" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body-clean">
                        <div class="field-group">
                            <label class="field-label" for="editar_descripcion">Descripción</label>
                            <input type="text"
                                   id="editar_descripcion"
                                   name="descripcion"
                                   class="field-input"
                                   required
                                   autocomplete="off">
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="editar_marca_id">Marca</label>
                            <select id="editar_marca_id" name="marca_id" class="field-input" required>
                                <option value="">Seleccioná una marca...</option>
                                @foreach($marcas as $marca)
                                    <option value="{{ $marca->id }}">{{ $marca->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="editar_unidad_medida_id">Unidad de Medida</label>
                            <select id="editar_unidad_medida_id" name="unidad_medida_id" class="field-input" required>
                                <option value="">Seleccioná una unidad...</option>
                                @foreach($unidadesMedida as $um)
                                    <option value="{{ $um->id }}">{{ $um->descripcion }}{{ $um->abreviatura ? ' ('.$um->abreviatura.')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="editar_fecha">Fecha</label>
                            <input type="date"
                                   id="editar_fecha"
                                   name="fecha"
                                   class="field-input"
                                   required>
                        </div>
                    </div>
                    <div class="modal-footer-clean">
                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-modal-confirm btn-blue">
                            <i class="fas fa-save me-1"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══ MODAL: DESACTIVAR ══ --}}
    <div class="modal fade" id="modalDesactivar" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content modal-clean">
                <div class="modal-header-clean">
                    <div class="modal-icon-wrap modal-icon-red">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div>
                        <h5 class="modal-title-clean">Desactivar Insumo</h5>
                        <p class="modal-sub-clean">Esta acción se puede revertir</p>
                    </div>
                    <button type="button" class="modal-close-btn" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body-clean">
                    <p class="confirm-text">¿Desactivar el insumo <strong id="desactivarNombre"></strong>?</p>
                </div>
                <form id="formDesactivar" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-footer-clean">
                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-modal-confirm btn-red">
                            <i class="fas fa-ban me-1"></i> Desactivar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══ MODAL: ACTIVAR ══ --}}
    <div class="modal fade" id="modalActivar" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content modal-clean">
                <div class="modal-header-clean">
                    <div class="modal-icon-wrap modal-icon-green">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <h5 class="modal-title-clean">Activar Insumo</h5>
                        <p class="modal-sub-clean">El insumo volverá a estar disponible</p>
                    </div>
                    <button type="button" class="modal-close-btn" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body-clean">
                    <p class="confirm-text">¿Activar el insumo <strong id="activarNombre"></strong>?</p>
                </div>
                <form id="formActivar" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-footer-clean">
                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-modal-confirm btn-green">
                            <i class="fas fa-check me-1"></i> Activar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>

<style>
body { overflow-x: hidden; }

/* ── Cabecera ── */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    gap: 1rem;
}
.page-title {
    font-size: 1.35rem;
    font-weight: 600;
    color: #1e2530;
    margin: 0;
    line-height: 1.2;
}
.page-sub {
    font-size: 0.8rem;
    color: #9ba3af;
    margin: 0;
    margin-top: 2px;
}
.btn-nuevo {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.5rem 1rem;
    background: #4a6fa5;
    color: #fff;
    border-radius: 8px;
    font-size: 0.845rem;
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: background 0.2s ease;
    white-space: nowrap;
}
.btn-nuevo:hover { background: #3d5f8f; color: #fff; }

/* ── Buscador ── */
.search-bar {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #fff;
    border: 1px solid #e8eaed;
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.search-icon { color: #9ba3af; font-size: 0.85rem; flex-shrink: 0; }
.search-input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 0.845rem;
    color: #1e2530;
    background: transparent;
    min-width: 0;
}
.search-input::placeholder { color: #b0b8c4; }
.search-clear {
    background: none;
    border: none;
    color: #b0b8c4;
    cursor: pointer;
    padding: 0 2px;
    font-size: 0.8rem;
    line-height: 1;
    transition: color 0.2s;
}
.search-clear:hover { color: #5a6370; }
.search-count {
    font-size: 0.75rem;
    color: #9ba3af;
    white-space: nowrap;
    padding-left: 0.5rem;
    border-left: 1px solid #e8eaed;
}

/* ── Tabla ── */
.table-card {
    background: #fff;
    border: 1px solid #e8eaed;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}
.table-scroll {
    overflow-x: auto;
    overflow-y: auto;
    max-height: calc(100vh - 280px);
}
.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.845rem;
    table-layout: fixed;
}
.data-table thead th {
    position: sticky;
    top: 0;
    background: #f7f8fa;
    color: #5a6370;
    font-weight: 600;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    padding: 0.7rem 1rem;
    border-bottom: 1px solid #e8eaed;
    white-space: nowrap;
    z-index: 10;
}
.data-table tbody td {
    padding: 0.7rem 1rem;
    color: #374151;
    border-bottom: 1px solid #f2f4f7;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    vertical-align: middle;
}
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: #fafbfc; }
.td-id { font-weight: 600; color: #1e2530; }
.td-muted { color: #9ba3af; }

/* ── Badges ── */
.badge-soft {
    display: inline-block;
    padding: 0.25em 0.65em;
    border-radius: 5px;
    font-size: 0.75rem;
    font-weight: 500;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    vertical-align: middle;
}
.badge-activo   { background: #ecfdf5; color: #065f46; }
.badge-inactivo { background: #fef2f2; color: #991b1b; }
.badge-marca    { background: #eef1f8; color: #4a6fa5; }
.badge-unidad   { background: #f3f4f6; color: #4b5563; }

/* ── Botones de acción ── */
.action-btns { display: flex; gap: 4px; align-items: center; }
.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 6px;
    border: 1px solid #e8eaed;
    background: #fff;
    font-size: 0.75rem;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.2s, border-color 0.2s, color 0.2s;
    color: #9ba3af;
}
.action-edit:hover     { background: #eef1f8; border-color: #c6d0e0; color: #4a6fa5; }
.action-delete:hover   { background: #fef2f2; border-color: #fcc;    color: #dc2626; }
.action-activate:hover { background: #ecfdf5; border-color: #a7f3d0; color: #059669; }

/* ── Estado vacío ── */
.empty-state { text-align: center; padding: 3rem 1rem; color: #b0b8c4; }
.empty-state i { font-size: 2rem; display: block; margin-bottom: 0.75rem; }
.empty-state p { margin: 0; font-size: 0.875rem; }

/* ── Highlight búsqueda ── */
.highlight { background: #fef9c3; color: #713f12; border-radius: 2px; padding: 1px 2px; }

/* ── Scrollbar ── */
.table-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
.table-scroll::-webkit-scrollbar-track { background: transparent; }
.table-scroll::-webkit-scrollbar-thumb { background: #dde0e5; border-radius: 3px; }

/* ══ MODALES ══ */
.modal-content.modal-clean {
    border: 1px solid #e8eaed;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.10);
    overflow: hidden;
}
.modal-header-clean {
    display: flex;
    align-items: flex-start;
    gap: 0.875rem;
    padding: 1.25rem 1.25rem 1rem;
    border-bottom: 1px solid #f2f4f7;
    position: relative;
}
.modal-icon-wrap {
    width: 38px;
    height: 38px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    flex-shrink: 0;
}
.modal-icon-blue  { background: #eef1f8; color: #4a6fa5; }
.modal-icon-red   { background: #fef2f2; color: #dc2626; }
.modal-icon-green { background: #ecfdf5; color: #059669; }
.modal-title-clean {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1e2530;
    margin: 0 0 2px;
    line-height: 1.2;
}
.modal-sub-clean {
    font-size: 0.775rem;
    color: #9ba3af;
    margin: 0;
}
.modal-close-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 26px;
    height: 26px;
    background: transparent;
    border: 1px solid #e8eaed;
    border-radius: 6px;
    color: #9ba3af;
    font-size: 0.7rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
}
.modal-close-btn:hover { background: #f7f8fa; color: #374151; }
.modal-body-clean { padding: 1.1rem 1.25rem; }
.modal-footer-clean {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
    padding: 0.875rem 1.25rem;
    border-top: 1px solid #f2f4f7;
    background: #fafbfc;
}

/* Campos del formulario */
.field-group { margin-bottom: 0.875rem; }
.field-group:last-child { margin-bottom: 0; }
.field-label {
    display: block;
    font-size: 0.8rem;
    font-weight: 500;
    color: #5a6370;
    margin-bottom: 0.35rem;
}
.field-input {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #e8eaed;
    border-radius: 7px;
    font-size: 0.845rem;
    color: #1e2530;
    background: #fff;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    appearance: auto;
}
.field-input:focus {
    border-color: #4a6fa5;
    box-shadow: 0 0 0 3px rgba(74,111,165,0.1);
}

/* Texto de confirmación */
.confirm-text {
    font-size: 0.875rem;
    color: #374151;
    margin: 0;
    line-height: 1.5;
}

/* Botones del footer del modal */
.btn-modal-cancel {
    padding: 0.45rem 0.9rem;
    border: 1px solid #e8eaed;
    border-radius: 7px;
    background: #fff;
    color: #5a6370;
    font-size: 0.835rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s;
}
.btn-modal-cancel:hover { background: #f7f8fa; }
.btn-modal-confirm {
    padding: 0.45rem 1rem;
    border: none;
    border-radius: 7px;
    font-size: 0.835rem;
    font-weight: 500;
    cursor: pointer;
    color: #fff;
    transition: opacity 0.2s;
}
.btn-modal-confirm:hover { opacity: 0.88; }
.btn-blue  { background: #4a6fa5; }
.btn-red   { background: #dc2626; }
.btn-green { background: #059669; }
</style>

<script>
/* ── Modales de acción ── */
function abrirEditar(id, descripcion, marcaId, unidadId, fecha) {
    document.getElementById('formEditar').action = `/insumos/${id}`;
    document.getElementById('editar_descripcion').value = descripcion;
    document.getElementById('editar_marca_id').value = marcaId;
    document.getElementById('editar_unidad_medida_id').value = unidadId;
    document.getElementById('editar_fecha').value = fecha;
    new bootstrap.Modal(document.getElementById('modalEditar')).show();
}

function abrirDesactivar(id, nombre) {
    document.getElementById('formDesactivar').action = `/insumos/${id}`;
    document.getElementById('desactivarNombre').textContent = nombre;
    new bootstrap.Modal(document.getElementById('modalDesactivar')).show();
}

function abrirActivar(id, nombre) {
    document.getElementById('formActivar').action = `/insumos/${id}/activate`;
    document.getElementById('activarNombre').textContent = nombre;
    new bootstrap.Modal(document.getElementById('modalActivar')).show();
}

/* ── Buscador ── */
(function() {
    const searchInput   = document.getElementById('searchInput');
    const clearButton   = document.getElementById('clearSearch');
    const table         = document.getElementById('insumosTable');
    const noResults     = document.getElementById('noResults');
    const totalRowsSpan = document.getElementById('totalRows');

    if (!searchInput || !table) return;

    const rows      = Array.from(table.querySelectorAll('.insumo-row'));
    const totalRows = rows.length;

    // 0=ID (exacto), 1=Descripción, 2=Marca, 3=Unidad, 5=Estado, 6=Usuario
    const SEARCH_COLS   = [1, 2, 3, 5, 6];
    const HIGHLIGHT_COL = 1;

    const cache = rows.map(row => {
        const cells = Array.from(row.querySelectorAll('td'));
        return {
            cells,
            texts: cells.map(td => td.textContent.trim()),
            origHTML: cells.map(td => td.innerHTML),
        };
    });

    function highlightText(text, search) {
        const escaped = search.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        return text.replace(new RegExp(`(${escaped})`, 'gi'), '<span class="highlight">$1</span>');
    }

    function filterTable() {
        const raw        = searchInput.value.trim();
        const searchTerm = raw.toLowerCase();
        let visibleCount = 0;

        cache.forEach(({ cells, texts, origHTML }, i) => {
            const row = rows[i];
            cells.forEach((td, j) => { td.innerHTML = origHTML[j]; });

            if (!searchTerm) {
                row.style.display = '';
                visibleCount++;
                return;
            }

            const idMatch  = texts[0] === raw;
            const colMatch = SEARCH_COLS.some(j => texts[j].toLowerCase().includes(searchTerm));
            const found    = idMatch || colMatch;

            if (found) {
                if (texts[HIGHLIGHT_COL].toLowerCase().includes(searchTerm)) {
                    cells[HIGHLIGHT_COL].innerHTML = highlightText(texts[HIGHLIGHT_COL], searchTerm);
                }
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        totalRowsSpan.textContent = visibleCount;
        if (noResults) {
            noResults.style.display = (visibleCount === 0 && searchTerm && totalRows > 0) ? '' : 'none';
        }
    }

    searchInput.addEventListener('input', filterTable);

    clearButton.addEventListener('click', function () {
        searchInput.value = '';
        filterTable();
        searchInput.focus();
    });

    document.addEventListener('keydown', function (e) {
        if (e.ctrlKey && e.key === 'f') { e.preventDefault(); searchInput.focus(); }
    });

    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { this.value = ''; filterTable(); }
    });
})();
</script>
