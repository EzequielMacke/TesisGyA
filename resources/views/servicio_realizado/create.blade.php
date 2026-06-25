<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Servicio Realizado</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-clipboard-check"></i> Registrar Servicio Realizado</h2>
                    <small>Complete los datos para registrar un nuevo servicio realizado</small>
                </div>
                <a href="{{ route('servicio_realizado.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                </a>
            </div>

            {{-- Alerts --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('servicio_realizado.store') }}" enctype="multipart/form-data" id="servicioForm">
                @csrf

                {{-- Selección de Datos --}}
                <div class="card">
                    <div class="card-header-section">
                        <span><i class="fas fa-search me-2"></i>Selección de Datos</span>
                    </div>
                    <div class="card-body">
                        <div class="form-grid form-grid-3">
                            <div>
                                <label for="cliente_id" class="form-label">Cliente</label>
                                <select id="cliente_id" class="form-select form-select-sm select2">
                                    <option value="">Seleccionar Cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->razon_social }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="obra_id" class="form-label">Obra</label>
                                <select id="obra_id" class="form-select form-select-sm select2" disabled>
                                    <option value="">Primero seleccione un cliente</option>
                                </select>
                            </div>
                            <div>
                                <label for="orden_servicio_id" class="form-label">Orden de Servicio</label>
                                <select name="orden_servicio_id" id="orden_servicio_id" class="form-select form-select-sm select2" disabled>
                                    <option value="">Primero seleccione una obra</option>
                                </select>
                            </div>
                        </div>

                        <div class="info-grid mt-3">
                            <div class="detail-box" id="info-cliente-completo" style="display:none;">
                                <div class="detail-box-title">Datos del Cliente</div>
                                <div id="info-cliente-completo-body"></div>
                            </div>
                            <div class="detail-box" id="info-obra-completo" style="display:none;">
                                <div class="detail-box-title">Datos de la Obra</div>
                                <div id="info-obra-completo-body"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Información Relacionada --}}
                <div class="card" id="info-section" style="display:none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-info-circle me-2"></i>Información Relacionada</span>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="detail-box detail-box-wide">
                                <div class="detail-box-title">Solicitud de Servicio</div>
                                <div id="info-solicitud"></div>
                            </div>
                            <div class="detail-box detail-box-wide">
                                <div class="detail-box-title">Visita Previa</div>
                                <div id="info-visita"></div>
                            </div>
                            <div class="detail-box detail-box-wide">
                                <div class="detail-box-title">Presupuesto de Servicio</div>
                                <div id="info-presupuesto"></div>
                            </div>
                            <div class="detail-box">
                                <div class="detail-box-title">Contrato</div>
                                <div id="info-contrato"></div>
                                <button type="button" class="btn btn-outline-primary btn-sm mt-2 w-100" id="btn-ver-contrato" style="display:none;">
                                    <i class="fas fa-expand me-2"></i>Ver contrato completo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Insumos Registrados --}}
                <div class="card" id="insumos-section" style="display:none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-boxes me-2"></i>Insumos Registrados</span>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3" style="font-size:0.8rem;">Seleccione los registros de insumos utilizados que correspondan a este servicio realizado.</p>
                        <div id="insumos-list"></div>
                    </div>
                </div>

                {{-- Servicios Realizados --}}
                <div class="card" id="servicios-section" style="display:none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-flask me-2"></i>Servicios Realizados</span>
                    </div>
                    <div class="card-body">
                        <div id="servicios-list"></div>
                    </div>
                </div>

                {{-- Funcionarios --}}
                <div class="card" id="funcionarios-section" style="display:none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-users me-2"></i>Funcionarios Asignados</span>
                    </div>
                    <div class="card-body">
                        <div id="funcionarios-list" class="funcionarios-grid"></div>
                    </div>
                </div>

                {{-- Fotos --}}
                <div class="card" id="fotos-card" style="display:none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-camera me-2"></i>Fotografías</span>
                        <span class="results-count" id="fotos-count">0 fotos seleccionadas</span>
                    </div>
                    <div class="card-body">
                        <div class="file-upload-section" id="fotos-section">
                            <input type="file" name="fotos[]" id="fotos-input" class="file-input-hidden" multiple accept="image/*">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                            <p>Arrastra y suelta las fotos aquí o haz clic para seleccionar</p>
                            <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('fotos-input').click()">
                                <i class="fas fa-upload me-2"></i>Seleccionar Fotos
                            </button>
                            <div class="file-preview-container" id="fotos-preview"></div>
                        </div>
                    </div>
                </div>

                {{-- Planos --}}
                <div class="card" id="planos-card" style="display:none;">
                    <div class="card-header-section">
                        <span><i class="fas fa-file-alt me-2"></i>Planos</span>
                        <span class="results-count" id="planos-count">0 archivos seleccionados</span>
                    </div>
                    <div class="card-body">
                        <div class="file-upload-section" id="planos-section">
                            <input type="file" name="planos[]" id="planos-input" class="file-input-hidden" multiple>
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                            <p>Arrastra y suelta los planos aquí o haz clic para seleccionar</p>
                            <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('planos-input').click()">
                                <i class="fas fa-upload me-2"></i>Seleccionar Planos
                            </button>
                            <div class="file-preview-container" id="planos-preview"></div>
                        </div>
                    </div>
                </div>

                {{-- Observación y Acciones --}}
                <div class="card" id="final-card" style="display:none;">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="observacion" class="form-label">Observación</label>
                            <textarea name="observacion" id="observacion" class="form-control form-control-sm" rows="2" placeholder="Ingrese una observación...">{{ old('observacion') }}</textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('servicio_realizado.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Servicio Realizado
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    {{-- Modal: Contrato completo --}}
    <div class="modal fade" id="modalContratoCompleto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 900px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-file-contract me-2"></i>Contrato de Prestación de Servicios</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div id="contrato-completo-texto" class="contrato-text"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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

/* ── Formulario ── */
#servicioForm {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

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

/* ── Grillas de formulario ── */
.form-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
}
.form-grid.form-grid-3 { grid-template-columns: repeat(3, 1fr); }
.form-grid .form-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 500;
    color: #94a3b8;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.select2-container--bootstrap-5 .select2-selection {
    min-height: calc(1.5em + 0.5rem + 2px);
}

/* ── Información relacionada ── */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 0.75rem;
}
.detail-box-wide { grid-column: 1 / -1; }
.detail-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.6rem 0.75rem;
    font-size: 0.8rem;
    color: #374151;
}
.detail-box-title {
    font-size: 0.7rem;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.4rem;
}
.detail-row { display: flex; align-items: flex-start; gap: 0.4rem; margin-bottom: 0.25rem; }
.detail-row:last-child { margin-bottom: 0; }
.detail-row i { color: #94a3b8; width: 14px; text-align: center; margin-top: 0.15rem; }

/* ── Galería de archivos (fotos/planos de la visita previa) ── */
.file-gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-top: 0.5rem;
}
.file-item {
    flex: 1 1 240px;
    max-width: 320px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
}
.file-item img,
.file-item .file-pdf-preview {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
    border: none;
}
.file-item .file-info { padding: 0.4rem 0.5rem; font-size: 0.72rem; color: #64748b; }
.file-item .file-info a { color: #2563eb; text-decoration: none; font-weight: 600; }
.file-item .file-info a:hover { text-decoration: underline; }
.file-item .file-info small { display: block; color: #94a3b8; margin-top: 2px; }

/* ── Presupuesto formal (servicios, ensayos, impuestos, totales) ── */
.presupuesto-encabezado {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 0.6rem;
    margin-bottom: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px dashed #e2e8f0;
}
.precio-servicio-block { border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; background: #fff; }
.precio-servicio-block + .precio-servicio-block { margin-top: 0.75rem; }
.precio-servicio-header {
    padding: 0.55rem 0.85rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    font-weight: 600;
    font-size: 0.76rem;
    color: #2563eb;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.table-container { overflow: auto; }
.data-table { width: 100%; min-width: 600px; border-collapse: collapse; table-layout: fixed; }
.data-table thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 0.68rem;
    font-weight: 600;
    padding: 0.5rem 0.55rem;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.data-table tbody td {
    padding: 0.5rem 0.55rem;
    font-size: 0.78rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #374151;
}
.data-table tbody tr:last-child td { border-bottom: none; }
.amount { font-weight: 700; color: #10b981; }
.tag {
    display: inline-block;
    padding: 0.15rem 0.5rem;
    border-radius: 4px;
    font-size: 0.68rem;
    font-weight: 600;
    background: #eff6ff;
    color: #2563eb;
}
.tag-secondary { background: #f1f5f9; color: #64748b; }

.totals-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 0.75rem; }
.totals-box { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.85rem; }
.totals-box-title {
    font-size: 0.68rem;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.4rem;
}
.totals-row { display: flex; justify-content: space-between; font-size: 0.8rem; color: #374151; padding: 0.2rem 0; }
.totals-row.totals-final {
    border-top: 1px solid #e2e8f0;
    margin-top: 0.4rem;
    padding-top: 0.4rem;
    font-size: 0.92rem;
    font-weight: 700;
    color: #1e293b;
}
.totals-final strong { color: #10b981; }

/* ── Texto formal del contrato (modal grande) ── */
.contrato-text {
    font-family: 'Times New Roman', serif;
    line-height: 1.6;
    text-align: justify;
}
.contrato-title { text-align: center; font-weight: bold; margin-bottom: 20px; }
.contrato-section { margin-bottom: 15px; }
.contrato-clause { font-weight: bold; text-decoration: underline; }
#modalContratoCompleto .modal-dialog { width: 95%; }

@media (max-width: 900px) {
    .totals-grid { grid-template-columns: 1fr; }
}

/* ── Insumos / Servicios agrupados ── */
.servicio-group { margin-bottom: 1rem; }
.servicio-group:last-child { margin-bottom: 0; }
.servicio-group h6 {
    font-size: 0.78rem;
    font-weight: 600;
    color: #2563eb;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.5rem;
}
.servicios-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 0.6rem;
}
.servicio-check {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-size: 0.82rem;
    color: #374151;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s;
    margin-bottom: 0;
}
.servicio-check:hover { background: #eff6ff; border-color: #bfdbfe; }
.servicio-check.checked { background: #eff6ff; border-color: #2563eb; color: #1e293b; }
.servicio-check input { margin: 0.15rem 0 0 0; cursor: pointer; flex-shrink: 0; }
.servicio-check-readonly { cursor: default; }
.servicio-check-readonly:hover { background: #f8fafc; border-color: #e2e8f0; }
.servicio-check-readonly i { color: #22c55e; margin-top: 0.15rem; flex-shrink: 0; }
.insumo-detalle { font-size: 0.74rem; color: #94a3b8; margin-top: 0.15rem; }

/* ── Funcionarios ── */
.funcionarios-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 0.6rem;
}
.funcionario-chip {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-size: 0.82rem;
    color: #374151;
}
.funcionario-chip i { color: #94a3b8; }

/* ── Carga de archivos ── */
.file-upload-section {
    border: 2px dashed #e2e8f0;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    background: #f8fafc;
    transition: all 0.2s ease;
}
.file-upload-section:hover,
.file-upload-section.dragover {
    border-color: #2563eb;
    background: #eff6ff;
}
.file-upload-section i.fa-cloud-upload-alt { color: #94a3b8; }
.file-upload-section p { color: #94a3b8; font-size: 0.85rem; margin-bottom: 0.75rem; }
.file-input-hidden {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}
.file-preview-container {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-top: 1rem;
    justify-content: center;
}
.file-preview {
    position: relative;
    width: 100px;
    height: 100px;
    border-radius: 6px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    background: #fff;
}
.file-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.file-preview .file-info {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    background: rgba(0, 0, 0, 0.6);
    color: #fff;
    padding: 2px 4px;
    font-size: 0.65rem;
    text-align: center;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.file-preview .remove-file {
    position: absolute;
    top: 4px; right: 4px;
    background: rgba(220, 53, 69, 0.9);
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 20px; height: 20px;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px;
    cursor: pointer;
}
.file-preview .remove-file:hover { background: rgba(220, 53, 69, 1); }

@media (max-width: 900px) {
    .form-grid, .form-grid.form-grid-3 { grid-template-columns: repeat(2, 1fr); }
    .page-header { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .form-grid, .form-grid.form-grid-3 { grid-template-columns: 1fr; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('.select2').select2({ theme: 'bootstrap-5', width: '100%' });

    const $clienteSelect = $('#cliente_id');
    const $obraSelect = $('#obra_id');
    const $ordenSelect = $('#orden_servicio_id');

    const $infoClienteCompleto = $('#info-cliente-completo');
    const $infoObraCompleto = $('#info-obra-completo');
    const $infoSection = $('#info-section');
    const $insumosSection = $('#insumos-section');
    const $serviciosSection = $('#servicios-section');
    const $funcionariosSection = $('#funcionarios-section');
    const $fotosCard = $('#fotos-card');
    const $planosCard = $('#planos-card');
    const $finalCard = $('#final-card');

    let contratoActual = null;

    function ocultarDatosOrden() {
        $infoSection.hide();
        $insumosSection.hide();
        $serviciosSection.hide();
        $funcionariosSection.hide();
        $fotosCard.hide();
        $planosCard.hide();
        $finalCard.hide();
        $('#info-solicitud, #info-visita, #info-presupuesto, #info-contrato, #insumos-list, #servicios-list, #funcionarios-list').empty();
        contratoActual = null;
        $('#btn-ver-contrato').hide();
    }

    function numToLetras(numero) {
        numero = parseInt(numero);
        const unidades = ['', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve'];
        const decenas = ['', 'diez', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];
        const centenas = ['', 'ciento', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos'];

        if (numero === 0) return 'cero';
        if (numero === 100) return 'cien';

        let letras = '';

        let millones = Math.floor(numero / 1000000);
        numero %= 1000000;
        if (millones > 0) {
            letras += numToLetras(millones) + ' millón' + (millones > 1 ? 'es' : '') + ' ';
        }

        let miles = Math.floor(numero / 1000);
        numero %= 1000;
        if (miles > 0) {
            letras += (miles === 1 ? 'mil ' : numToLetras(miles) + ' mil ');
        }

        let centena = Math.floor(numero / 100);
        numero %= 100;
        if (centena > 0) {
            letras += centenas[centena] + ' ';
        }

        let decena = Math.floor(numero / 10);
        let unidad = numero % 10;
        if (decena > 0) {
            if (decena === 1) {
                const especiales = ['', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciseis', 'diecisiete', 'dieciocho', 'diecinueve'];
                letras += especiales[unidad];
                return letras.trim();
            }
            letras += decenas[decena];
            if (unidad > 0) {
                letras += ' y ' + unidades[unidad];
            }
        } else if (unidad > 0) {
            letras += unidades[unidad];
        }

        return letras.trim();
    }

    $('#btn-ver-contrato').on('click', function() {
        if (!contratoActual) return;
        const c = contratoActual;
        const montoLetras = c.monto ? numToLetras(c.monto) : '';
        $('#contrato-completo-texto').html(`
            <div class="contrato-title">CONTRATO DE PRESTACIÓN DE SERVICIOS</div>

            <p><strong>Entre:</strong></p>
            <p>${c.cliente_razon_social}, con domicilio en ${c.cliente_direccion}, identificado con ${c.cliente_ruc}, en adelante denominado "EL CONTRATANTE".</p>

            <p><strong>Y:</strong></p>
            <p>GAVILAN Y ASOCIADOS S.A, con domicilio en Soldado Ovelar Casi Asuncion 1912, identificado con RUC 800.123.456-78, en adelante denominado "EL PRESTADOR".</p>

            <p><strong>MANIFIESTAN</strong></p>
            <p>Que EL CONTRATANTE desea contratar los servicios profesionales de EL PRESTADOR para la evaluación, reparación y/o refuerzo de estructuras de hormigón, en adelante denominados "los servicios", conforme a los términos y condiciones que se detallan a continuación:</p>

            <hr>

            <div class="contrato-section"><p class="contrato-clause">CLÁUSULAS</p></div>

            <div class="contrato-section">
                <p class="contrato-clause">PRIMERA – OBJETO</p>
                <p>EL PRESTADOR se obliga a prestar los servicios de evaluación estructural, diagnóstico de daños, reparación y refuerzo de estructuras de hormigón armado, conforme a las especificaciones técnicas acordadas por ambas partes y según las normas vigentes aplicables.</p>
            </div>

            <div class="contrato-section">
                <p class="contrato-clause">SEGUNDA – ALCANCE DE LOS SERVICIOS</p>
                <p>Los trabajos podrán incluir, entre otros:</p>
                <ul>
                    <li>Inspección visual y técnica de estructuras.</li>
                    <li>Ensayos no destructivos (si fueran necesarios).</li>
                    <li>Elaboración de informes técnicos.</li>
                    <li>Diseño de soluciones de reparación o refuerzo.</li>
                    <li>Ejecución de obras menores de reparación estructural.</li>
                    <li>Supervisión técnica de los trabajos realizados.</li>
                </ul>
                <p>El alcance final será detallado en un Anexo Técnico firmado por ambas partes, el cual formará parte integral del presente contrato.</p>
            </div>

            <div class="contrato-section">
                <p class="contrato-clause">TERCERA – PLAZO DE EJECUCIÓN</p>
                <p>Los trabajos tendrán un plazo estimado de ejecución de ${c.plazo_dias ?? '-'} días hábiles, a partir de la firma del contrato y/o de la entrega del anticipo, salvo causa de fuerza mayor debidamente justificada.</p>
            </div>

            <div class="contrato-section">
                <p class="contrato-clause">CUARTA – HONORARIOS Y FORMA DE PAGO</p>
                <p>EL CONTRATANTE abonará a EL PRESTADOR la suma de ${montoLetras} (₲ ${c.monto ? parseFloat(c.monto).toLocaleString('es-PY') : '-'}), en concepto de pago total por los servicios.</p>
                <p>El pago se realizará de la siguiente forma:</p>
                <ul>
                    <li>${c.anticipo ?? '-'}% como anticipo al momento de la firma del contrato.</li>
                    <li>${c.pago_mitad ?? '-'}% a la mitad del avance de obra.</li>
                    <li>${c.pago_final ?? '-'}% contra entrega del informe final o finalización de los trabajos.</li>
                </ul>
            </div>

            <div class="contrato-section">
                <p class="contrato-clause">QUINTA – OBLIGACIONES DEL PRESTADOR</p>
                <ul>
                    <li>Ejecutar los trabajos con la mayor diligencia, profesionalismo y conforme a las normas técnicas aplicables.</li>
                    <li>Utilizar materiales adecuados y seguros cuando corresponda.</li>
                    <li>Cumplir con las normativas de seguridad laboral vigentes.</li>
                    <li>Informar al contratante sobre cualquier riesgo estructural relevante detectado.</li>
                </ul>
            </div>

            <div class="contrato-section">
                <p class="contrato-clause">SEXTA – OBLIGACIONES DEL CONTRATANTE</p>
                <ul>
                    <li>Facilitar el acceso al lugar donde se realizarán los trabajos.</li>
                    <li>Proveer, cuando sea necesario, planos estructurales y datos técnicos del inmueble.</li>
                    <li>Realizar los pagos conforme al cronograma pactado.</li>
                </ul>
            </div>

            <div class="contrato-section">
                <p class="contrato-clause">SÉPTIMA – RESPONSABILIDAD Y GARANTÍA</p>
                <p>EL PRESTADOR responderá por la correcta ejecución de los servicios, brindando una garantía de ${c.garantia_meses ?? '-'} meses sobre las reparaciones realizadas, contados a partir de la fecha de finalización. Esta garantía no cubre daños ocasionados por terceros o causas externas.</p>
            </div>

            <div class="contrato-section">
                <p class="contrato-clause">OCTAVA – CONFIDENCIALIDAD</p>
                <p>Ambas partes se comprometen a mantener la confidencialidad de toda la información técnica y comercial intercambiada en virtud del presente contrato.</p>
            </div>

            <div class="contrato-section">
                <p class="contrato-clause">NOVENA – TERMINACIÓN ANTICIPADA</p>
                <p>El contrato podrá resolverse de forma anticipada por cualquiera de las partes en caso de incumplimiento de las obligaciones por la contraparte, previa notificación escrita con un plazo de 5 días hábiles.</p>
            </div>

            <div class="contrato-section">
                <p class="contrato-clause">DÉCIMA – JURISDICCIÓN Y LEY APLICABLE</p>
                <p>Para todas las controversias derivadas del presente contrato, las partes se someten a los tribunales ordinarios de la ciudad de ${c.ciudad}, renunciando a cualquier otro fuero que pudiera corresponder, y se regirán por las leyes de la República del Paraguay.</p>
            </div>

            <p><strong>En fe de lo cual, las partes firman el presente contrato en la ciudad de ${c.ciudad}, a los ${c.fecha_firma_dia} días del mes de ${c.fecha_firma_mes} de ${c.fecha_firma_anio}.</strong></p>

            <div class="row mt-5">
                <div class="col-6 text-center">
                    <p>______________________________</p>
                    <p>EL CONTRATANTE</p>
                    <p>${c.cliente_razon_social}</p>
                </div>
                <div class="col-6 text-center">
                    <p>______________________________</p>
                    <p>EL PRESTADOR</p>
                    <p>GAVILAN Y ASOCIADOS S.A</p>
                </div>
            </div>

            ${c.observaciones ? `
                <div class="contrato-section">
                    <p class="contrato-clause">OBSERVACIONES</p>
                    <p>${c.observaciones}</p>
                </div>
            ` : ''}
        `);
        new bootstrap.Modal(document.getElementById('modalContratoCompleto')).show();
    });

    function cargarInfoCliente(clienteId) {
        $infoClienteCompleto.hide();
        $.getJSON(`/servicio_realizado/cliente-info/${clienteId}`, function(data) {
            $('#info-cliente-completo-body').html(`
                <div class="detail-row"><i class="fas fa-building"></i><span><strong>Razón Social:</strong> ${data.razon_social}</span></div>
                <div class="detail-row"><i class="fas fa-id-card"></i><span><strong>RUC:</strong> ${data.ruc}</span></div>
                ${data.persona ? `<div class="detail-row"><i class="fas fa-user"></i><span><strong>Persona de Contacto:</strong> ${data.persona}</span></div>` : ''}
                <div class="detail-row"><i class="fas fa-map-marker-alt"></i><span><strong>Dirección:</strong> ${data.direccion}</span></div>
                <div class="detail-row"><i class="fas fa-phone"></i><span><strong>Teléfono:</strong> ${data.telefono}</span></div>
                <div class="detail-row"><i class="fas fa-envelope"></i><span><strong>Email:</strong> ${data.email}</span></div>
                <div class="detail-row"><i class="fas fa-flag"></i><span><strong>Estado:</strong> ${data.estado}</span></div>
                <div class="detail-row"><i class="fas fa-user-edit"></i><span><strong>Registrado por:</strong> ${data.registrado_por}</span></div>
                <div class="detail-row"><i class="fas fa-calendar"></i><span><strong>Fecha de Registro:</strong> ${data.fecha}</span></div>
            `);
            $infoClienteCompleto.show();
        }).fail(function() {
            $infoClienteCompleto.hide();
        });
    }

    function cargarInfoObra(obraId) {
        $infoObraCompleto.hide();
        $.getJSON(`/servicio_realizado/obra-info/${obraId}`, function(data) {
            $('#info-obra-completo-body').html(`
                <div class="detail-row"><i class="fas fa-hard-hat"></i><span><strong>Descripción:</strong> ${data.descripcion}</span></div>
                <div class="detail-row"><i class="fas fa-map-marker-alt"></i><span><strong>Ubicación:</strong> ${data.ubicacion}</span></div>
                <div class="detail-row"><i class="fas fa-building"></i><span><strong>Cliente:</strong> ${data.cliente}</span></div>
                <div class="detail-row"><i class="fas fa-ruler-combined"></i><span><strong>Metros Cuadrados:</strong> ${data.metros_cuadrados}</span></div>
                <div class="detail-row"><i class="fas fa-layer-group"></i><span><strong>Niveles:</strong> ${data.niveles}</span></div>
                <div class="detail-row"><i class="fas fa-sticky-note"></i><span><strong>Observación:</strong> ${data.observacion}</span></div>
                <div class="detail-row"><i class="fas fa-flag"></i><span><strong>Estado:</strong> ${data.estado}</span></div>
                <div class="detail-row"><i class="fas fa-user-edit"></i><span><strong>Registrado por:</strong> ${data.registrado_por}</span></div>
                <div class="detail-row"><i class="fas fa-calendar"></i><span><strong>Fecha de Registro:</strong> ${data.fecha}</span></div>
            `);
            $infoObraCompleto.show();
        }).fail(function() {
            $infoObraCompleto.hide();
        });
    }

    function cargarObras(clienteId) {
        $obraSelect.empty().append('<option value="">Cargando obras...</option>').trigger('change');
        $ordenSelect.empty().append('<option value="">Primero seleccione una obra</option>').prop('disabled', true).trigger('change');
        ocultarDatosOrden();

        $.getJSON(`/servicio_realizado/obras-por-cliente/${clienteId}`, function(obras) {
            $obraSelect.empty().append('<option value="">Seleccione una obra</option>');
            if (obras && obras.length > 0) {
                $.each(obras, function(i, obra) {
                    $obraSelect.append(`<option value="${obra.id}">${obra.descripcion}</option>`);
                });
                $obraSelect.prop('disabled', false);
            } else {
                $obraSelect.append('<option value="">No hay obras con orden pendiente</option>');
            }
            $obraSelect.trigger('change');
        }).fail(function() {
            $obraSelect.empty().append('<option value="">Error al cargar obras</option>').trigger('change');
        });
    }

    function cargarOrdenes(obraId) {
        $ordenSelect.empty().append('<option value="">Cargando órdenes...</option>').trigger('change');
        ocultarDatosOrden();

        $.getJSON(`/servicio_realizado/ordenes-por-obra/${obraId}`, function(ordenes) {
            $ordenSelect.empty().append('<option value="">Seleccione una orden de servicio</option>');
            if (ordenes && ordenes.length > 0) {
                $.each(ordenes, function(i, orden) {
                    $ordenSelect.append(`<option value="${orden.id}">Orden Nro ${orden.nro}</option>`);
                });
                $ordenSelect.prop('disabled', false);
            } else {
                $ordenSelect.append('<option value="">No hay órdenes pendientes</option>');
            }
            $ordenSelect.trigger('change');
        }).fail(function() {
            $ordenSelect.empty().append('<option value="">Error al cargar órdenes</option>').trigger('change');
        });
    }

    function cargarDatosOrden(ordenId) {
        ocultarDatosOrden();

        $.getJSON(`/servicio_realizado/datos-por-orden/${ordenId}`, function(data) {
            const serviciosSolicitados = (data.solicitud_servicio.servicios && data.solicitud_servicio.servicios.length > 0)
                ? data.solicitud_servicio.servicios.join(', ')
                : 'Sin servicios';
            $('#info-solicitud').html(`
                <div class="detail-row"><i class="fas fa-hashtag"></i><span><strong>Nro:</strong> ${data.solicitud_servicio.id ?? '-'}</span></div>
                <div class="detail-row"><i class="fas fa-user"></i><span><strong>Registrado por:</strong> ${data.solicitud_servicio.usuario}</span></div>
                <div class="detail-row"><i class="fas fa-calendar"></i><span><strong>Fecha:</strong> ${data.solicitud_servicio.fecha}</span></div>
                <div class="detail-row"><i class="fas fa-tools"></i><span><strong>Servicios solicitados:</strong> ${serviciosSolicitados}</span></div>
            `);
            const fotosGaleria = (data.visita_previa.fotos && data.visita_previa.fotos.length > 0)
                ? data.visita_previa.fotos.map(foto => `
                    <div class="file-item">
                        <img src="${foto.url}" alt="Foto">
                        <div class="file-info">
                            <a href="${foto.url}" target="_blank">Ver imagen</a>
                            <small>${foto.fecha}</small>
                        </div>
                    </div>
                `).join('')
                : '<p class="text-muted mb-0" style="font-size:0.78rem;">Sin fotografías cargadas.</p>';

            const planosGaleria = (data.visita_previa.planos && data.visita_previa.planos.length > 0)
                ? data.visita_previa.planos.map(plano => `
                    <div class="file-item">
                        ${plano.es_pdf
                            ? `<embed src="${plano.url}" type="application/pdf" class="file-pdf-preview">`
                            : `<img src="${plano.url}" alt="Plano">`}
                        <div class="file-info">
                            <a href="${plano.url}" target="_blank">${plano.es_pdf ? 'Ver PDF completo' : 'Ver archivo'}</a>
                            <small>${plano.fecha}</small>
                        </div>
                    </div>
                `).join('')
                : '<p class="text-muted mb-0" style="font-size:0.78rem;">Sin planos cargados.</p>';

            $('#info-visita').html(`
                <div class="detail-row"><i class="fas fa-hashtag"></i><span><strong>Nro:</strong> ${data.visita_previa.id ?? '-'}</span></div>
                <div class="detail-row"><i class="fas fa-user"></i><span><strong>Registrado por:</strong> ${data.visita_previa.usuario}</span></div>
                <div class="detail-row"><i class="fas fa-calendar"></i><span><strong>Fecha de Visita:</strong> ${data.visita_previa.fecha_visita}</span></div>
                <div class="detail-row" style="flex-direction:column;"><span><i class="fas fa-camera me-1"></i><strong>Fotografías:</strong></span><div class="file-gallery">${fotosGaleria}</div></div>
                <div class="detail-row" style="flex-direction:column;"><span><i class="fas fa-file-alt me-1"></i><strong>Planos:</strong></span><div class="file-gallery">${planosGaleria}</div></div>
            `);
            const formatGs = (valor) => `₲ ${Number(valor || 0).toLocaleString('es-PY')}`;

            const serviciosPresupuestoHtml = (data.presupuesto.servicios && data.presupuesto.servicios.length > 0)
                ? data.presupuesto.servicios.map(servicioData => `
                    <div class="precio-servicio-block">
                        <div class="precio-servicio-header">${servicioData.servicio}</div>
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Ensayo</th>
                                        <th style="width:110px;" class="text-end">Precio Unit.</th>
                                        <th style="width:80px;" class="text-center">Cantidad</th>
                                        <th style="width:100px;" class="text-center">Impuesto</th>
                                        <th style="width:100px;" class="text-end">IVA</th>
                                        <th style="width:120px;" class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${servicioData.ensayos.map(ensayo => `
                                        <tr>
                                            <td>${ensayo.descripcion}</td>
                                            <td class="text-end">${formatGs(ensayo.precio_unitario)}</td>
                                            <td class="text-center">${ensayo.cantidad}</td>
                                            <td class="text-center"><span class="tag tag-secondary">${ensayo.impuesto}</span></td>
                                            <td class="text-end">${formatGs(ensayo.iva)}</td>
                                            <td class="text-end"><span class="amount">${formatGs(ensayo.subtotal)}</span></td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                `).join('')
                : '<p class="text-muted mb-0" style="font-size:0.8rem;">Sin detalles de presupuesto.</p>';

            const impuestosTipoHtml = Object.entries(data.presupuesto.impuestos_por_tipo || {}).map(([tipo, monto]) => `
                <div class="totals-row"><span>IVA ${tipo}</span><strong>${formatGs(monto)}</strong></div>
            `).join('');

            const desgloseServicioHtml = (data.presupuesto.servicios || []).map(servicioData => `
                <div class="totals-row"><span>${servicioData.servicio}</span><strong>${formatGs(servicioData.subtotal_servicio)}</strong></div>
            `).join('');

            $('#info-presupuesto').html(`
                <div class="presupuesto-encabezado">
                    <div class="detail-row"><i class="fas fa-hashtag"></i><span><strong>N° Presupuesto:</strong> ${data.presupuesto.numero_presupuesto}</span></div>
                    <div class="detail-row"><i class="fas fa-calendar"></i><span><strong>Fecha:</strong> ${data.presupuesto.fecha}</span></div>
                    <div class="detail-row"><i class="fas fa-hourglass-half"></i><span><strong>Validez:</strong> ${data.presupuesto.validez} días</span></div>
                    <div class="detail-row"><i class="fas fa-percent"></i><span><strong>Anticipo:</strong> ${data.presupuesto.anticipo}%</span></div>
                    <div class="detail-row"><i class="fas fa-user"></i><span><strong>Registrado por:</strong> ${data.presupuesto.usuario}</span></div>
                </div>
                <div class="detail-row"><i class="fas fa-align-left"></i><span><strong>Descripción:</strong> ${data.presupuesto.descripcion}</span></div>
                ${data.presupuesto.observacion ? `<div class="detail-row"><i class="fas fa-sticky-note"></i><span><strong>Observación:</strong> ${data.presupuesto.observacion}</span></div>` : ''}

                <div class="mt-3">${serviciosPresupuestoHtml}</div>

                <div class="totals-grid">
                    <div class="totals-box">
                        <div class="totals-box-title">Desglose por Servicio</div>
                        ${desgloseServicioHtml}
                    </div>
                    <div class="totals-box">
                        <div class="totals-box-title">Totales</div>
                        ${impuestosTipoHtml}
                        <div class="totals-row"><span>Total Servicios</span><strong>${formatGs(data.presupuesto.total_servicios)}</strong></div>
                        <div class="totals-row"><span>Total Impuestos</span><strong>${formatGs(data.presupuesto.total_impuestos)}</strong></div>
                        <div class="totals-row totals-final"><span>TOTAL GENERAL</span><strong>${formatGs(data.presupuesto.total_general)}</strong></div>
                        <div class="totals-row"><span>Anticipo (${data.presupuesto.anticipo}%)</span><strong>${formatGs(data.presupuesto.monto_anticipo)}</strong></div>
                    </div>
                </div>
            `);
            $('#info-contrato').html(`
                <div class="detail-row"><i class="fas fa-hashtag"></i><span><strong>N° Contrato:</strong> ${String(data.contrato.id ?? '-').padStart(3, '0')}</span></div>
                <div class="detail-row"><i class="fas fa-coins"></i><span><strong>Monto:</strong> ₲ ${data.contrato.monto ? parseFloat(data.contrato.monto).toLocaleString('es-PY') : '-'}</span></div>
                <div class="detail-row"><i class="fas fa-calendar-check"></i><span><strong>Fecha de Firma:</strong> ${data.contrato.fecha_firma}</span></div>
                <div class="detail-row"><i class="fas fa-clock"></i><span><strong>Plazo:</strong> ${data.contrato.plazo_dias ?? '-'} días</span></div>
            `);
            contratoActual = data.contrato;
            $('#btn-ver-contrato').toggle(!!contratoActual.id);
            $infoSection.show();

            // Insumos registrados (seleccionables)
            const $insumosList = $('#insumos-list');
            $insumosList.empty();
            if (data.insumos_utilizados && data.insumos_utilizados.length > 0) {
                data.insumos_utilizados.forEach(function(insumo) {
                    const detalleTexto = insumo.detalles.map(d => `${d.descripcion} (${d.cantidad} ${d.unidad})`).join(', ') || 'Sin detalle';
                    $insumosList.append(`
                        <label class="servicio-check" for="insumo-${insumo.id}">
                            <input type="checkbox" name="insumos_utilizados[]" value="${insumo.id}" id="insumo-${insumo.id}">
                            <span>
                                <strong>Nro ${insumo.nro}</strong> — ${insumo.estado} — ${insumo.fecha_registro ?? '-'}
                                <div class="insumo-detalle">${detalleTexto}</div>
                            </span>
                        </label>
                    `);
                });
                $insumosList.find('.servicio-check').each(function () {
                    const $label = $(this);
                    const $checkbox = $label.find('input[type="checkbox"]');
                    const sync = () => $label.toggleClass('checked', $checkbox.is(':checked'));
                    sync();
                    $checkbox.on('change', sync);
                });
            } else {
                $insumosList.html('<p class="text-muted mb-0" style="font-size:0.8rem;">No hay insumos registrados para esta orden de servicio.</p>');
            }
            $insumosSection.show();

            // Servicios realizados (solo informativo)
            const $serviciosList = $('#servicios-list');
            $serviciosList.empty();
            if (data.servicios && data.servicios.length > 0) {
                data.servicios.forEach(function(servicioData) {
                    const checks = servicioData.ensayos.map(ensayo => `
                        <div class="servicio-check servicio-check-readonly">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>${ensayo.descripcion} — Cantidad: ${ensayo.cantidad}</span>
                        </div>
                    `).join('');
                    $serviciosList.append(`
                        <div class="servicio-group">
                            <h6>${servicioData.servicio}</h6>
                            <div class="servicios-grid">${checks}</div>
                        </div>
                    `);
                });
            } else {
                $serviciosList.html('<p class="text-muted mb-0" style="font-size:0.8rem;">No hay servicios registrados en el presupuesto.</p>');
            }
            $serviciosSection.show();

            // Funcionarios asignados (solo informativo)
            const $funcionariosList = $('#funcionarios-list');
            $funcionariosList.empty();
            if (data.funcionarios && data.funcionarios.length > 0) {
                data.funcionarios.forEach(function(funcionario) {
                    $funcionariosList.append(`
                        <div class="funcionario-chip"><i class="fas fa-user"></i><span>${funcionario.nombre}</span></div>
                    `);
                });
            } else {
                $funcionariosList.html('<p class="text-muted mb-0" style="font-size:0.8rem;">No hay funcionarios asignados a esta orden de servicio.</p>');
            }
            $funcionariosSection.show();

            $fotosCard.show();
            $planosCard.show();
            $finalCard.show();
        }).fail(function() {
            ocultarDatosOrden();
        });
    }

    $clienteSelect.on('change', function() {
        const clienteId = $(this).val();
        if (clienteId) {
            cargarInfoCliente(clienteId);
            cargarObras(clienteId);
        } else {
            $infoClienteCompleto.hide();
            $infoObraCompleto.hide();
            $obraSelect.empty().append('<option value="">Primero seleccione un cliente</option>').prop('disabled', true).trigger('change');
            $ordenSelect.empty().append('<option value="">Primero seleccione una obra</option>').prop('disabled', true).trigger('change');
            ocultarDatosOrden();
        }
    });

    $obraSelect.on('change', function() {
        const obraId = $(this).val();
        if (obraId) {
            cargarInfoObra(obraId);
            cargarOrdenes(obraId);
        } else {
            $infoObraCompleto.hide();
            $ordenSelect.empty().append('<option value="">Primero seleccione una obra</option>').prop('disabled', true).trigger('change');
            ocultarDatosOrden();
        }
    });

    $ordenSelect.on('change', function() {
        const ordenId = $(this).val();
        if (ordenId) {
            cargarDatosOrden(ordenId);
        } else {
            ocultarDatosOrden();
        }
    });

    // Manejo de archivos: vista previa, contador y eliminación
    function handleFileSelect(event, type) {
        const files = event.target.files;
        const previewContainer = $(`#${type}-preview`);
        const countElement = $(`#${type}-count`);

        previewContainer.empty();

        const label = type === 'fotos' ? 'fotos' : 'archivos';
        countElement.text(`${files.length} ${label} seleccionado${files.length === 1 ? '' : 's'}`);

        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = $('<div class="file-preview"></div>');
                const removeBtn = $('<button type="button" class="remove-file" title="Remover">&times;</button>');

                removeBtn.on('click', function() {
                    const dt = new DataTransfer();
                    const input = $(`#${type}-input`)[0];
                    const filesArray = Array.from(input.files);
                    filesArray.splice(index, 1);
                    filesArray.forEach(f => dt.items.add(f));
                    input.files = dt.files;
                    handleFileSelect({target: input}, type);
                });

                if (file.type.startsWith('image/')) {
                    previewDiv.append(`<img src="${e.target.result}" alt="${file.name}">`);
                } else {
                    previewDiv.append(`
                        <div style="display: flex; align-items: center; justify-content: center; height: 100%; background: #f8fafc;">
                            <i class="fas fa-file-alt fa-2x text-muted"></i>
                        </div>
                    `);
                }

                previewDiv.append(`<div class="file-info">${file.name}</div>`);
                previewDiv.append(removeBtn);
                previewContainer.append(previewDiv);
            };
            reader.readAsDataURL(file);
        });
    }

    function setupDragAndDrop(sectionId, inputId, type) {
        const section = $(`#${sectionId}`);
        const input = $(`#${inputId}`);

        section.on('dragover dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();
            section.addClass('dragover');
        });

        section.on('dragleave dragend', function(e) {
            e.preventDefault();
            e.stopPropagation();
            section.removeClass('dragover');
        });

        section.on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            section.removeClass('dragover');

            const files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                const dt = new DataTransfer();
                Array.from(input[0].files).forEach(f => dt.items.add(f));
                Array.from(files).forEach(f => dt.items.add(f));
                input[0].files = dt.files;
                handleFileSelect({target: input[0]}, type);
            }
        });
    }

    setupDragAndDrop('fotos-section', 'fotos-input', 'fotos');
    setupDragAndDrop('planos-section', 'planos-input', 'planos');

    $('#fotos-input').on('change', function(e) { handleFileSelect(e, 'fotos'); });
    $('#planos-input').on('change', function(e) { handleFileSelect(e, 'planos'); });
});
</script>
