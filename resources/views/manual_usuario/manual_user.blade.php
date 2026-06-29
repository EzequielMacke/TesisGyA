<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Manual de Usuario</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content">
        <div class="content-wrapper">

            {{-- Cabecera --}}
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-book"></i> Manual de Usuario</h2>
                    <small>Guía paso a paso para cargar procesos en el sistema</small>
                </div>
            </div>

            {{-- Índice --}}
            <div class="card">
                <div class="card-header-section">
                    <span><i class="fas fa-list me-2"></i>Contenido</span>
                </div>
                <div class="card-body manual-index-list">
                    <a href="#registrar-reclamo" class="manual-index-link">
                        <i class="fas fa-exclamation-circle"></i>
                        Cómo registrar un Reclamo
                    </a>
                    <a href="#registrar-servicio-realizado" class="manual-index-link">
                        <i class="fas fa-clipboard-check"></i>
                        Cómo registrar un Servicio Realizado
                    </a>
                    <a href="#registrar-presupuesto-servicio" class="manual-index-link">
                        <i class="fas fa-file-invoice-dollar"></i>
                        Cómo registrar un Presupuesto de Servicio
                    </a>
                </div>
            </div>

            {{-- ════════════════════════════════════════ --}}
            {{-- Manual: Registrar Reclamo --}}
            {{-- ════════════════════════════════════════ --}}
            <div class="card" id="registrar-reclamo">
                <div class="card-header-section">
                    <span><i class="fas fa-exclamation-circle me-2"></i>Cómo registrar un Reclamo</span>
                </div>
                <div class="card-body manual-body">

                    <p class="manual-intro">
                        Seguí estos pasos para registrar el reclamo de un cliente sobre un servicio ya realizado.
                    </p>

                    {{-- Paso 1 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">1</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                Desde el listado de <strong>Reclamos</strong>, hacé clic en el botón
                                <strong>"Registrar Reclamo"</strong> para abrir el formulario.
                            </p>
                            <div class="manual-mockup">
                                <button type="button" class="btn btn-primary" disabled>
                                    <i class="fas fa-plus me-2"></i>Registrar Reclamo
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 2 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">2</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                En la sección <strong>"Selección de Datos"</strong>, elegí primero el
                                <strong>Cliente</strong>. Al seleccionarlo, se habilita el campo <strong>Obra</strong>
                                con las obras de ese cliente; al elegir la obra, se habilita
                                <strong>Servicio Realizado</strong> con los servicios disponibles para esa obra.
                            </p>
                            <div class="manual-mockup">
                                <div class="card">
                                    <div class="card-header-section">
                                        <span><i class="fas fa-search me-2"></i>Selección de Datos</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-grid form-grid-3">
                                            <div>
                                                <label class="form-label">
                                                    Cliente <span class="manual-tag">1</span>
                                                </label>
                                                <select class="form-select form-select-sm" disabled>
                                                    <option>Constructora Itapúa S.A.</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="form-label">
                                                    Obra <span class="manual-tag">2</span>
                                                </label>
                                                <select class="form-select form-select-sm" disabled>
                                                    <option>Edificio Costa Azul</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="form-label">
                                                    Servicio Realizado <span class="manual-tag">3</span>
                                                </label>
                                                <select class="form-select form-select-sm" disabled>
                                                    <option>Servicio Realizado #14 - 12/05/2026</option>
                                                </select>
                                            </div>
                                        </div>
                                        <p class="text-muted mt-3 mb-0" style="font-size:0.78rem;">
                                            <i class="fas fa-info-circle me-1"></i>Solo se listan clientes, obras y servicios realizados que cuenten con un Servicio Realizado en estado <strong>Confirmado</strong>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 3 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">3</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                Una vez elegido el Servicio Realizado, aparecen las secciones de
                                <strong>Fotografías</strong> y <strong>Planos</strong>. Arrastrá los archivos
                                hasta el recuadro, o hacé clic en el botón para seleccionarlos desde tu
                                computadora. Podés cargar varias fotos y varios planos.
                            </p>
                            <div class="manual-mockup">
                                <div class="card">
                                    <div class="card-header-section">
                                        <span><i class="fas fa-camera me-2"></i>Fotografías</span>
                                        <span class="results-count">2 fotos seleccionadas</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="file-upload-section">
                                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                            <p>Arrastra y suelta las fotos aquí o haz clic para seleccionar</p>
                                            <button type="button" class="btn btn-primary btn-sm" disabled>
                                                <i class="fas fa-upload me-2"></i>Seleccionar Fotos
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 4 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">4</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                Lo mismo aplica para los planos relacionados al reclamo (no es obligatorio
                                si no corresponde).
                            </p>
                            <div class="manual-mockup">
                                <div class="card">
                                    <div class="card-header-section">
                                        <span><i class="fas fa-file-alt me-2"></i>Planos</span>
                                        <span class="results-count">0 archivos seleccionados</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="file-upload-section">
                                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                            <p>Arrastra y suelta los planos aquí o haz clic para seleccionar</p>
                                            <button type="button" class="btn btn-primary btn-sm" disabled>
                                                <i class="fas fa-upload me-2"></i>Seleccionar Planos
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 5 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">5</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                Por último, describí el reclamo en el campo <strong>Observación</strong> y
                                hacé clic en <strong>"Guardar Reclamo"</strong>.
                            </p>
                            <div class="manual-mockup">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Observación</label>
                                            <textarea class="form-control form-control-sm" rows="2" disabled placeholder="Describa el reclamo del cliente..."></textarea>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <button type="button" class="btn btn-secondary" disabled>
                                                <i class="fas fa-times me-2"></i>Cancelar
                                            </button>
                                            <button type="button" class="btn btn-primary" disabled>
                                                <i class="fas fa-save me-2"></i>Guardar Reclamo
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 6 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">6</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                El sistema guarda el reclamo en estado <strong>Pendiente</strong> y te lleva de
                                vuelta al listado. Desde ahí podés:
                            </p>
                            <ul class="manual-list">
                                <li><i class="fas fa-eye"></i> <strong>Ver</strong> el detalle del reclamo, con sus fotos y planos.</li>
                                <li><i class="fas fa-pen"></i> <strong>Editar</strong> el reclamo mientras esté Pendiente.</li>
                                <li><i class="fas fa-check"></i> <strong>Confirmar</strong> el reclamo para pasarlo a estado Confirmado.</li>
                                <li><i class="fas fa-ban"></i> <strong>Anular</strong> el reclamo si ya no corresponde.</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ════════════════════════════════════════ --}}
            {{-- Manual: Registrar Servicio Realizado --}}
            {{-- ════════════════════════════════════════ --}}
            <div class="card" id="registrar-servicio-realizado">
                <div class="card-header-section">
                    <span><i class="fas fa-clipboard-check me-2"></i>Cómo registrar un Servicio Realizado</span>
                </div>
                <div class="card-body manual-body">

                    <p class="manual-intro">
                        Seguí estos pasos para registrar un servicio realizado a partir de una orden de
                        servicio ya generada.
                    </p>

                    {{-- Paso 1 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">1</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                Desde el listado de <strong>Servicios Realizados</strong>, hacé clic en el botón
                                <strong>"Registrar Servicio Realizado"</strong> para abrir el formulario.
                            </p>
                            <div class="manual-mockup">
                                <button type="button" class="btn btn-primary" disabled>
                                    <i class="fas fa-plus me-2"></i>Registrar Servicio Realizado
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 2 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">2</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                En <strong>"Selección de Datos"</strong>, elegí el <strong>Cliente</strong>.
                                Esto habilita el campo <strong>Obra</strong> con las obras de ese cliente, y al
                                elegir la obra se habilita <strong>Orden de Servicio</strong> con las órdenes
                                pendientes de esa obra. Al seleccionar Cliente y Obra, el sistema muestra
                                automáticamente sus datos completos debajo (solo informativo).
                            </p>
                            <div class="manual-mockup">
                                <div class="card">
                                    <div class="card-header-section">
                                        <span><i class="fas fa-search me-2"></i>Selección de Datos</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-grid form-grid-3">
                                            <div>
                                                <label class="form-label">
                                                    Cliente <span class="manual-tag">1</span>
                                                </label>
                                                <select class="form-select form-select-sm" disabled>
                                                    <option>Constructora Itapúa S.A.</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="form-label">
                                                    Obra <span class="manual-tag">2</span>
                                                </label>
                                                <select class="form-select form-select-sm" disabled>
                                                    <option>Edificio Costa Azul</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="form-label">
                                                    Orden de Servicio <span class="manual-tag">3</span>
                                                </label>
                                                <select class="form-select form-select-sm" disabled>
                                                    <option>Orden Nro 8</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="info-grid mt-3">
                                            <div class="detail-box">
                                                <div class="detail-box-title">Datos del Cliente</div>
                                                <div class="detail-row"><i class="fas fa-building"></i><span>Se muestran automáticamente al elegir el Cliente.</span></div>
                                            </div>
                                            <div class="detail-box">
                                                <div class="detail-box-title">Datos de la Obra</div>
                                                <div class="detail-row"><i class="fas fa-map-marker-alt"></i><span>Se muestran automáticamente al elegir la Obra.</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 3 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">3</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                Al elegir la <strong>Orden de Servicio</strong>, el sistema completa
                                automáticamente toda la información relacionada: la Solicitud de Servicio, la
                                Visita Previa (con sus fotos y planos), el Presupuesto de Servicio (con los
                                ensayos y totales) y el Contrato (con la condición de pago y un botón para ver
                                el contrato completo). También se listan los Insumos Utilizados, los Servicios
                                Realizados y los Funcionarios Asignados a la orden. Estas secciones son solo de
                                lectura, no requieren ninguna acción.
                            </p>
                            <div class="manual-mockup">
                                <div class="card mb-2">
                                    <div class="card-header-section">
                                        <span><i class="fas fa-info-circle me-2"></i>Información Relacionada</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-grid">
                                            <div class="detail-box">
                                                <div class="detail-box-title">Solicitud de Servicio</div>
                                                <div class="detail-row"><i class="fas fa-file-alt"></i><span>Datos de la solicitud original.</span></div>
                                            </div>
                                            <div class="detail-box">
                                                <div class="detail-box-title">Visita Previa</div>
                                                <div class="detail-row"><i class="fas fa-clipboard-list"></i><span>Fotos y planos relevados en la visita.</span></div>
                                            </div>
                                            <div class="detail-box">
                                                <div class="detail-box-title">Presupuesto de Servicio</div>
                                                <div class="detail-row"><i class="fas fa-file-invoice-dollar"></i><span>Ensayos cotizados y totales.</span></div>
                                            </div>
                                            <div class="detail-box detail-box-wide">
                                                <div class="detail-box-title d-flex justify-content-between align-items-center">
                                                    <span>Contrato</span>
                                                    <button type="button" class="btn btn-outline-primary btn-sm" disabled>
                                                        <i class="fas fa-expand me-2"></i>Ver contrato completo
                                                    </button>
                                                </div>
                                                <div class="detail-row"><i class="fas fa-file-contract"></i><span>Condición de pago y datos del contrato firmado.</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header-section">
                                        <span><i class="fas fa-boxes me-2"></i>Insumos Utilizados / Servicios Realizados / Funcionarios</span>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted mb-0" style="font-size:0.8rem;">
                                            Listados informativos, ya confirmados previamente para esta orden.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 4 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">4</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                Cargá las <strong>Fotografías</strong> del servicio realizado: arrastrá los
                                archivos al recuadro o hacé clic en el botón para seleccionarlos.
                            </p>
                            <div class="manual-mockup">
                                <div class="card">
                                    <div class="card-header-section">
                                        <span><i class="fas fa-camera me-2"></i>Fotografías</span>
                                        <span class="results-count">3 fotos seleccionadas</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="file-upload-section">
                                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                            <p>Arrastra y suelta las fotos aquí o haz clic para seleccionar</p>
                                            <button type="button" class="btn btn-primary btn-sm" disabled>
                                                <i class="fas fa-upload me-2"></i>Seleccionar Fotos
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 5 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">5</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                Hacé lo mismo para los <strong>Planos</strong> del servicio realizado (si
                                corresponde).
                            </p>
                            <div class="manual-mockup">
                                <div class="card">
                                    <div class="card-header-section">
                                        <span><i class="fas fa-file-alt me-2"></i>Planos</span>
                                        <span class="results-count">0 archivos seleccionados</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="file-upload-section">
                                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                            <p>Arrastra y suelta los planos aquí o haz clic para seleccionar</p>
                                            <button type="button" class="btn btn-primary btn-sm" disabled>
                                                <i class="fas fa-upload me-2"></i>Seleccionar Planos
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 6 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">6</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                Por último, escribí una <strong>Observación</strong> si corresponde y hacé clic
                                en <strong>"Guardar Servicio Realizado"</strong>.
                            </p>
                            <div class="manual-mockup">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Observación</label>
                                            <textarea class="form-control form-control-sm" rows="2" disabled placeholder="Ingrese una observación..."></textarea>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <button type="button" class="btn btn-secondary" disabled>
                                                <i class="fas fa-times me-2"></i>Cancelar
                                            </button>
                                            <button type="button" class="btn btn-primary" disabled>
                                                <i class="fas fa-save me-2"></i>Guardar Servicio Realizado
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 7 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">7</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                El sistema guarda el servicio realizado en estado <strong>Pendiente</strong> y
                                te lleva de vuelta al listado. Desde ahí podés:
                            </p>
                            <ul class="manual-list">
                                <li><i class="fas fa-eye"></i> <strong>Ver</strong> el detalle del servicio realizado.</li>
                                <li><i class="fas fa-pen"></i> <strong>Editar</strong> mientras esté Pendiente.</li>
                                <li><i class="fas fa-check"></i> <strong>Confirmar</strong> para pasarlo a estado Confirmado.</li>
                                <li><i class="fas fa-ban"></i> <strong>Anular</strong> si ya no corresponde.</li>
                                <li><i class="fas fa-file-pdf"></i> <strong>Descargar PDF</strong> una vez Confirmado.</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ════════════════════════════════════════ --}}
            {{-- Manual: Registrar Presupuesto de Servicio --}}
            {{-- ════════════════════════════════════════ --}}
            <div class="card" id="registrar-presupuesto-servicio">
                <div class="card-header-section">
                    <span><i class="fas fa-file-invoice-dollar me-2"></i>Cómo registrar un Presupuesto de Servicio</span>
                </div>
                <div class="card-body manual-body">

                    <p class="manual-intro">
                        Seguí estos pasos para armar el presupuesto de servicio a partir de una visita previa
                        ya realizada.
                    </p>

                    {{-- Paso 1 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">1</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                Desde el listado de <strong>Presupuestos de Servicio</strong>, hacé clic en el
                                botón <strong>"Crear Presupuesto de Servicio"</strong> para abrir el formulario.
                            </p>
                            <div class="manual-mockup">
                                <button type="button" class="btn btn-primary" disabled>
                                    <i class="fas fa-plus me-2"></i>Crear Presupuesto de Servicio
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 2 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">2</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                En <strong>"Selección de Datos"</strong>, elegí el <strong>Cliente</strong>,
                                luego la <strong>Obra</strong> (se habilita al elegir el cliente) y por último la
                                <strong>Visita Previa</strong> correspondiente (se habilita al elegir la obra).
                            </p>
                            <div class="manual-mockup">
                                <div class="card">
                                    <div class="card-header-section">
                                        <span><i class="fas fa-search me-2"></i>Selección de Datos</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-grid form-grid-3">
                                            <div>
                                                <label class="form-label">
                                                    Cliente <span class="manual-tag">1</span>
                                                </label>
                                                <select class="form-select form-select-sm" disabled>
                                                    <option>Constructora Itapúa S.A.</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="form-label">
                                                    Obra <span class="manual-tag">2</span>
                                                </label>
                                                <select class="form-select form-select-sm" disabled>
                                                    <option>Edificio Costa Azul</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="form-label">
                                                    Visita Previa <span class="manual-tag">3</span>
                                                </label>
                                                <select class="form-select form-select-sm" disabled>
                                                    <option>12/05/2026 - Confirmado</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 3 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">3</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                Al elegir la Visita Previa, el sistema muestra automáticamente los datos del
                                Cliente, la Obra y la Visita, junto con las fotos y planos relevados. Esta
                                sección es solo informativa.
                            </p>
                            <div class="manual-mockup">
                                <div class="card">
                                    <div class="card-header-section">
                                        <span><i class="fas fa-info-circle me-2"></i>Información de la Visita</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-grid form-grid-3 mb-3">
                                            <div class="detail-box">
                                                <div class="detail-box-title">Datos del Cliente</div>
                                                <div class="detail-row"><i class="fas fa-building"></i><span>Razón Social, RUC, Dirección.</span></div>
                                            </div>
                                            <div class="detail-box">
                                                <div class="detail-box-title">Datos de la Obra</div>
                                                <div class="detail-row"><i class="fas fa-map-marker-alt"></i><span>Descripción, Ubicación, m².</span></div>
                                            </div>
                                            <div class="detail-box">
                                                <div class="detail-box-title">Datos de la Visita Previa</div>
                                                <div class="detail-row"><i class="fas fa-calendar"></i><span>Fecha, Estado, Observación.</span></div>
                                            </div>
                                        </div>
                                        <div class="info-grid-2">
                                            <div>
                                                <h6 class="subsection-title"><i class="fas fa-camera me-2"></i>Fotos de la Visita</h6>
                                                <div class="file-gallery">
                                                    <div class="file-item">
                                                        <div class="file-placeholder"><i class="fas fa-image"></i></div>
                                                        <div class="file-info">Ver imagen</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="subsection-title"><i class="fas fa-file-alt me-2"></i>Planos de la Obra</h6>
                                                <div class="file-gallery">
                                                    <div class="file-item">
                                                        <div class="file-placeholder"><i class="fas fa-file-pdf"></i></div>
                                                        <div class="file-info">Ver archivo</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 4 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">4</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                En <strong>"Seleccionar Ensayos"</strong>, marcá los ensayos a presupuestar.
                                Están agrupados por servicio, y los que vas marcando aparecen como etiquetas en
                                el panel de la derecha.
                            </p>
                            <div class="manual-mockup">
                                <div class="card">
                                    <div class="card-header-section">
                                        <span><i class="fas fa-flask me-2"></i>Seleccionar Ensayos</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="ensayos-grid">
                                            <div>
                                                <h6 class="subsection-title">Ensayos Disponibles</h6>
                                                <div class="servicio-group">
                                                    <h6>Estudio de Suelos</h6>
                                                    <div class="servicios-grid">
                                                        <label class="servicio-check checked">
                                                            <input type="checkbox" disabled checked>
                                                            <span>Ensayo de Compactación</span>
                                                        </label>
                                                        <label class="servicio-check">
                                                            <input type="checkbox" disabled>
                                                            <span>Ensayo de Granulometría</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="subsection-title">Ensayos Seleccionados</h6>
                                                <div class="selected-ensayos">
                                                    <span class="tag tag-secondary">Ensayo de Compactación</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 5 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">5</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                En <strong>"Detalles del Presupuesto"</strong>, completá la <strong>Validez</strong>
                                en días y el <strong>Anticipo</strong> (%). El Monto de Anticipo, la Fecha, el
                                N° de Presupuesto y el Usuario se completan solos. Agregá una
                                <strong>Observación</strong> si corresponde.
                            </p>
                            <div class="manual-mockup">
                                <div class="card">
                                    <div class="card-header-section">
                                        <span><i class="fas fa-clipboard-list me-2"></i>Detalles del Presupuesto</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-grid">
                                            <div>
                                                <label class="form-label">
                                                    Validez (días) <span class="manual-tag">1</span>
                                                </label>
                                                <input type="number" class="form-control form-control-sm" value="30" disabled>
                                            </div>
                                            <div>
                                                <label class="form-label">
                                                    Anticipo (%) <span class="manual-tag">2</span>
                                                </label>
                                                <input type="number" class="form-control form-control-sm" value="30" disabled>
                                            </div>
                                            <div>
                                                <label class="form-label">Monto Anticipo</label>
                                                <input type="text" class="form-control form-control-sm readonly-field" value="₲ 450.000" readonly>
                                            </div>
                                            <div>
                                                <label class="form-label">Fecha</label>
                                                <input type="text" class="form-control form-control-sm readonly-field" value="28/06/2026" readonly>
                                            </div>
                                            <div class="span-2">
                                                <label class="form-label">N° Presupuesto</label>
                                                <input type="text" class="form-control form-control-sm readonly-field" value="PRES-2026-0341" readonly>
                                            </div>
                                            <div class="span-2">
                                                <label class="form-label">Usuario</label>
                                                <input type="text" class="form-control form-control-sm readonly-field" value="jgonzalez" readonly>
                                            </div>
                                            <div class="span-4">
                                                <label class="form-label">
                                                    Observación <span class="manual-tag">3</span>
                                                </label>
                                                <textarea class="form-control form-control-sm" rows="2" disabled placeholder="Ingrese una observación..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 6 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">6</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                En <strong>"Precios, Cantidades e Impuestos"</strong>, por cada ensayo
                                seleccionado completá el <strong>Precio</strong>, la <strong>Cantidad</strong> y
                                elegí el <strong>Impuesto</strong>. El IVA y el Subtotal de cada fila se calculan
                                solos.
                            </p>
                            <div class="manual-mockup">
                                <div class="card">
                                    <div class="card-header-section">
                                        <span><i class="fas fa-calculator me-2"></i>Precios, Cantidades e Impuestos</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="precio-servicio-block">
                                            <div class="precio-servicio-header">Estudio de Suelos</div>
                                            <div class="table-container">
                                                <table class="data-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Ensayo</th>
                                                            <th style="width:140px;">Precio <span class="manual-tag">1</span></th>
                                                            <th style="width:110px;" class="text-center">Cantidad <span class="manual-tag">2</span></th>
                                                            <th style="width:170px;">Impuesto <span class="manual-tag">3</span></th>
                                                            <th style="width:120px;" class="text-center">IVA</th>
                                                            <th style="width:130px;" class="text-center">Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Ensayo de Compactación</td>
                                                            <td><input type="number" class="form-control form-control-sm" value="150000" disabled></td>
                                                            <td class="text-center"><input type="number" class="form-control form-control-sm" value="10" disabled></td>
                                                            <td>
                                                                <select class="form-select form-select-sm" disabled>
                                                                    <option>IVA 10%</option>
                                                                </select>
                                                            </td>
                                                            <td class="text-center">₲ 136.364</td>
                                                            <td class="text-center"><span class="amount">₲ 1.500.000</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 7 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">7</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                Revisá el <strong>"Resumen del Presupuesto"</strong> (se calcula solo a medida
                                que completás los precios) y hacé clic en
                                <strong>"Guardar Presupuesto"</strong>.
                            </p>
                            <div class="manual-mockup">
                                <div class="card">
                                    <div class="card-header-section">
                                        <span><i class="fas fa-chart-line me-2"></i>Resumen del Presupuesto</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="totals-grid">
                                            <div class="totals-box">
                                                <div class="totals-box-title">Desglose por Servicio</div>
                                                <div class="totals-row"><span>Estudio de Suelos</span><strong>₲ 1.500.000</strong></div>
                                            </div>
                                            <div class="totals-box">
                                                <div class="totals-box-title">Totales</div>
                                                <div class="totals-row"><span>IVA 10%</span><strong>₲ 136.364</strong></div>
                                                <div class="totals-row"><span>Total Servicios</span><strong>₲ 1.500.000</strong></div>
                                                <div class="totals-row"><span>Total Impuestos</span><strong>₲ 136.364</strong></div>
                                                <div class="totals-row totals-final"><span>TOTAL GENERAL</span><strong>₲ 1.636.364</strong></div>
                                                <div class="totals-row"><span>Anticipo (30%)</span><strong>₲ 450.000</strong></div>
                                            </div>
                                        </div>
                                        <div class="text-center mt-3">
                                            <button type="button" class="btn btn-success" disabled>
                                                <i class="fas fa-save me-2"></i>Guardar Presupuesto
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 8 --}}
                    <div class="manual-step">
                        <div class="manual-step-number">8</div>
                        <div class="manual-step-content">
                            <p class="manual-step-text">
                                El sistema guarda el presupuesto en estado <strong>Pendiente</strong> y te lleva
                                de vuelta al listado. Desde ahí podés:
                            </p>
                            <ul class="manual-list">
                                <li><i class="fas fa-eye"></i> <strong>Ver Detalles</strong> del presupuesto.</li>
                                <li><i class="fas fa-pen"></i> <strong>Editar</strong> mientras esté Pendiente.</li>
                                <li><i class="fas fa-ban"></i> <strong>Anular</strong> si ya no corresponde.</li>
                            </ul>
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

/* ── Índice ── */
.manual-index-list { display: flex; flex-direction: column; gap: 0.5rem; }
.manual-index-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    font-weight: 500;
    color: #2563eb;
    text-decoration: none;
}
.manual-index-link:hover { text-decoration: underline; }
.manual-index-link i { color: #94a3b8; }

/* ── Información (réplica detail-box) ── */
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

/* ── Pasos del manual ── */
.manual-body { display: flex; flex-direction: column; gap: 1.5rem; }
.manual-intro { color: #64748b; font-size: 0.88rem; margin: 0; }

.manual-step {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}
.manual-step-number {
    flex-shrink: 0;
    width: 28px; height: 28px;
    border-radius: 50%;
    background: #2563eb;
    color: #fff;
    font-weight: 700;
    font-size: 0.85rem;
    display: flex; align-items: center; justify-content: center;
}
.manual-step-content { flex: 1; min-width: 0; }
.manual-step-text {
    font-size: 0.88rem;
    color: #374151;
    margin: 0 0 0.65rem 0;
    line-height: 1.5;
}
.manual-mockup {
    border: 1px dashed #cbd5e1;
    border-radius: 8px;
    padding: 1rem;
    background: #f8fafc;
}
.manual-mockup .card { background: #fff; }
.manual-mockup button { pointer-events: none; }

.manual-tag {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 16px; height: 16px;
    border-radius: 50%;
    background: #eff6ff;
    color: #2563eb;
    font-size: 0.65rem;
    font-weight: 700;
    margin-left: 0.25rem;
    text-transform: none;
    letter-spacing: 0;
}

.manual-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}
.manual-list li {
    font-size: 0.85rem;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.manual-list li i { color: #94a3b8; width: 16px; text-align: center; }

/* ── Grillas de formulario (réplica) ── */
.form-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
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
.form-grid .span-2 { grid-column: span 2; }
.form-grid .span-4 { grid-column: span 4; }
.readonly-field {
    background-color: #f8fafc !important;
    border-color: #e2e8f0 !important;
    color: #374151;
}

.subsection-title {
    font-size: 0.78rem;
    font-weight: 600;
    color: #2563eb;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.5rem;
}

.info-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
.ensayos-grid {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    gap: 1rem;
}

/* ── Galería de archivos (réplica) ── */
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
.file-item .file-placeholder {
    display: flex; align-items: center; justify-content: center;
    width: 100%; height: 100px;
    background: #f8fafc; color: #cbd5e1; font-size: 1.6rem;
}
.file-item .file-info { padding: 0.4rem 0.5rem; font-size: 0.72rem; color: #2563eb; font-weight: 600; }

/* ── Ensayos ── */
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
    align-items: center;
    gap: 0.5rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-size: 0.85rem;
    color: #374151;
    margin-bottom: 0;
}
.servicio-check.checked { background: #eff6ff; border-color: #2563eb; color: #1e293b; }
.servicio-check input { margin: 0; }

.selected-ensayos {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.6rem 0.75rem;
    min-height: 60px;
    display: flex;
    flex-wrap: wrap;
    align-content: flex-start;
    gap: 0.4rem;
}

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

/* ── Precios y cantidades ── */
.precio-servicio-block { border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; }
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
.data-table { width: 100%; min-width: 700px; border-collapse: collapse; table-layout: fixed; }
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
.data-table tbody tr:last-child td { border-bottom: none; }
.amount { font-weight: 700; color: #10b981; }

/* ── Totales ── */
.totals-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
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

/* ── Carga de archivos (réplica) ── */
.file-upload-section {
    border: 2px dashed #e2e8f0;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    background: #f8fafc;
}
.file-upload-section i.fa-cloud-upload-alt { color: #94a3b8; }
.file-upload-section p { color: #94a3b8; font-size: 0.85rem; margin-bottom: 0.75rem; }

@media (max-width: 900px) {
    .page-header { flex-direction: column; align-items: flex-start; }
    .form-grid, .form-grid.form-grid-3 { grid-template-columns: repeat(2, 1fr); }
    .ensayos-grid, .info-grid-2 { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
    .form-grid, .form-grid.form-grid-3 { grid-template-columns: 1fr; }
    .form-grid .span-2, .form-grid .span-4 { grid-column: span 1; }
    .manual-step { flex-direction: column; }
    .totals-grid { grid-template-columns: 1fr; }
}
</style>
