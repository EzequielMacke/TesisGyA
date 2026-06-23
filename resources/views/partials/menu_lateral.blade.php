{{-- Botón hamburguesa para mobile --}}
<button class="sidebar-mobile-btn d-lg-none" id="mobileTrigger" onclick="openMobileSidebar()">
    <i class="fas fa-bars"></i>
</button>

{{-- Backdrop mobile --}}
<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeMobileSidebar()"></div>

{{-- Sidebar --}}
<nav id="sidebarNav" class="sidebar-nav">
    <div class="sidebar-inner">

        {{-- Header --}}
        <div class="sidebar-header">
            <div class="sidebar-brand">
                
                <div class="brand-texts">
                    <span class="brand-name">TesisGyA</span>
                    <span class="brand-sub">Sistema de Gestión</span>
                </div>
            </div>
            <button class="sidebar-collapse-btn d-none d-lg-flex" id="collapseBtn" onclick="toggleDesktopSidebar()" title="Colapsar">
                <i class="fas fa-chevron-left" id="collapseIcon"></i>
            </button>
            <button class="sidebar-close-btn d-lg-none" onclick="closeMobileSidebar()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Usuario --}}
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                <i class="fas fa-user"></i>
                <span class="avatar-online"></span>
            </div>
            <div class="sidebar-user-info">
                <span class="user-name">{{ session('user_usuario', 'Usuario') }}</span>
                <span class="user-role">{{ session('user_cargo', 'Administrador') }}</span>
            </div>
        </div>

        {{-- Menú --}}
        <div class="sidebar-menu">

            <a href="{{ route('menu.index') }}"
               class="sidebar-link {{ request()->routeIs('menu.*') ? 'is-active' : '' }}">
                <i class="fas fa-home sidebar-icon"></i>
                <span class="sidebar-label">Dashboard</span>
            </a>

            {{-- Gestión de Insumos --}}
            @php $insumosOpen = request()->routeIs('inventario.*', 'solicitud_materiales.*', 'movimiento_insumos.*', 'ajuste_stocks.*'); @endphp
            <div class="sidebar-group {{ $insumosOpen ? 'is-open' : '' }}">
                <button class="sidebar-link sidebar-group-btn" type="button" onclick="toggleGroup(this)">
                    <i class="fas fa-warehouse sidebar-icon"></i>
                    <span class="sidebar-label">Gestión de Inventario</span>
                    <i class="fas fa-chevron-down group-chevron"></i>
                </button>
                <div class="sidebar-sublinks">
                    <a href="{{ route('inventario.index') }}" class="sidebar-sublink {{ request()->routeIs('inventario.*') ? 'is-active' : '' }}">
                        <i class="fas fa-warehouse"></i><span>Inventario</span>
                    </a>
                    <a href="{{ route('solicitud_materiales.index') }}" class="sidebar-sublink {{ request()->routeIs('solicitud_materiales.*') ? 'is-active' : '' }}">
                        <i class="fas fa-clipboard-list"></i><span>Solicitud de Insumos</span>
                    </a>
                    <a href="{{ route('movimiento_insumos.index') }}" class="sidebar-sublink {{ request()->routeIs('movimiento_insumos.*') ? 'is-active' : '' }}">
                        <i class="fas fa-truck"></i><span>Movimiento de Insumos</span>
                    </a>
                    <a href="{{ route('ajuste_stocks.index') }}" class="sidebar-sublink {{ request()->routeIs('ajuste_stocks.*') ? 'is-active' : '' }}">
                        <i class="fas fa-sliders-h"></i><span>Ajuste de Inventario</span>
                    </a>
                </div>
            </div>

            {{-- Compra --}}
            @php $compraOpen = request()->routeIs('pedido_compra.*','presupuesto_compra_aprobado.*','orden_compra.*','nota_remision_compra.*','compras.*'); @endphp
            <div class="sidebar-group {{ $compraOpen ? 'is-open' : '' }}">
                <button class="sidebar-link sidebar-group-btn" type="button" onclick="toggleGroup(this)">
                    <i class="fas fa-shopping-cart sidebar-icon"></i>
                    <span class="sidebar-label">Compra</span>
                    <i class="fas fa-chevron-down group-chevron"></i>
                </button>
                <div class="sidebar-sublinks">
                    <a href="{{ route('pedido_compra.index') }}" class="sidebar-sublink {{ request()->routeIs('pedido_compra.*') ? 'is-active' : '' }}">
                        <i class="fas fa-file-alt"></i><span>Pedidos de Compra</span>
                    </a>
                    <a href="{{ route('presupuesto_compra_aprobado.index') }}" class="sidebar-sublink {{ request()->routeIs('presupuesto_compra_aprobado.*') ? 'is-active' : '' }}">
                        <i class="fas fa-check-circle"></i><span>Presupuestos Aprobados</span>
                    </a>
                    <a href="{{ route('orden_compra.index') }}" class="sidebar-sublink {{ request()->routeIs('orden_compra.*') ? 'is-active' : '' }}">
                        <i class="fas fa-file-contract"></i><span>Órdenes de Compra</span>
                    </a>
                    <a href="{{ route('nota_remision_compra.index') }}" class="sidebar-sublink {{ request()->routeIs('nota_remision_compra.*') ? 'is-active' : '' }}">
                        <i class="fas fa-truck"></i><span>Notas de Remisión</span>
                    </a>
                    <a href="{{ route('compras.index') }}" class="sidebar-sublink {{ request()->routeIs('compras.*') ? 'is-active' : '' }}">
                        <i class="fas fa-file-invoice-dollar"></i><span>Compras</span>
                    </a>
                    <a href="{{ route('notas_compra.index') }}" class="sidebar-sublink {{ request()->routeIs('notas_compra.*') ? 'is-active' : '' }}">
                        <i class="fas fa-file-alt"></i><span>Notas de Compra</span>
                    </a>
                </div>
            </div>

            {{-- Servicio --}}
            @php $servicioOpen = request()->routeIs('solicitud_servicio.*','visita_previa.*','presupuesto_servicio.*','contrato.*','orden_servicio.*','insumos_utilizados.*'); @endphp
            <div class="sidebar-group {{ $servicioOpen ? 'is-open' : '' }}">
                <button class="sidebar-link sidebar-group-btn" type="button" onclick="toggleGroup(this)">
                    <i class="fas fa-tools sidebar-icon"></i>
                    <span class="sidebar-label">Servicio</span>
                    <i class="fas fa-chevron-down group-chevron"></i>
                </button>
                <div class="sidebar-sublinks">
                    <a href="{{ route('solicitud_servicio.index') }}" class="sidebar-sublink {{ request()->routeIs('solicitud_servicio.*') ? 'is-active' : '' }}">
                        <i class="fas fa-file-alt"></i><span>Solicitud de Servicio</span>
                    </a>
                    <a href="{{ route('visita_previa.index') }}" class="sidebar-sublink {{ request()->routeIs('visita_previa.*') ? 'is-active' : '' }}">
                        <i class="fas fa-clipboard-list"></i><span>Visitas Previas</span>
                    </a>
                    <a href="{{ route('presupuesto_servicio.index') }}" class="sidebar-sublink {{ request()->routeIs('presupuesto_servicio.*') ? 'is-active' : '' }}">
                        <i class="fas fa-file-invoice-dollar"></i><span>Presupuestos de Servicio</span>
                    </a>
                    <a href="{{ route('contrato.index') }}" class="sidebar-sublink {{ request()->routeIs('contrato.*') ? 'is-active' : '' }}">
                        <i class="fas fa-file-contract"></i><span>Contratos</span>
                    </a>
                    <a href="{{ route('orden_servicio.index') }}" class="sidebar-sublink {{ request()->routeIs('orden_servicio.*') ? 'is-active' : '' }}">
                        <i class="fas fa-tasks"></i><span>Orden de Servicio</span>
                    </a>
                    <a href="{{ route('insumos_utilizados.index') }}" class="sidebar-sublink {{ request()->routeIs('insumos_utilizados.*') ? 'is-active' : '' }}">
                        <i class="fas fa-boxes"></i><span>Insumos Utilizados</span>
                    </a>
                </div>
            </div>

            {{-- Referenciales --}}
            @php $refOpen = request()->routeIs('marca.*','insumo.*'); @endphp
            <div class="sidebar-group {{ $refOpen ? 'is-open' : '' }}">
                <button class="sidebar-link sidebar-group-btn" type="button" onclick="toggleGroup(this)">
                    <i class="fas fa-database sidebar-icon"></i>
                    <span class="sidebar-label">Referenciales</span>
                    <i class="fas fa-chevron-down group-chevron"></i>
                </button>
                <div class="sidebar-sublinks">
                    <a href="{{ route('marca.index') }}" class="sidebar-sublink {{ request()->routeIs('marca.*') ? 'is-active' : '' }}">
                        <i class="fas fa-tag"></i><span>Marcas</span>
                    </a>
                    <a href="{{ route('insumo.index') }}" class="sidebar-sublink {{ request()->routeIs('insumo.*') ? 'is-active' : '' }}">
                        <i class="fas fa-boxes"></i><span>Insumos</span>
                    </a>
                </div>
            </div>

            <a href="{{ route('presupuesto_compra.index') }}"
               class="sidebar-link {{ request()->routeIs('presupuesto_compra.*') ? 'is-active' : '' }}">
                <i class="fas fa-file-invoice-dollar sidebar-icon"></i>
                <span class="sidebar-label">Presupuestar Pedidos</span>
            </a>

            <a href="#" class="sidebar-link">
                <i class="fas fa-user sidebar-icon"></i>
                <span class="sidebar-label">Mi Perfil</span>
            </a>

            <a href="#" class="sidebar-link">
                <i class="fas fa-cog sidebar-icon"></i>
                <span class="sidebar-label">Configuración</span>
            </a>

        </div>

        {{-- Logout --}}
        <div class="sidebar-footer">
            <a href="{{ route('logout') }}" class="sidebar-link sidebar-logout">
                <i class="fas fa-sign-out-alt sidebar-icon"></i>
                <span class="sidebar-label">Cerrar Sesión</span>
            </a>
        </div>

    </div>
</nav>

<style>
:root {
    --sb-width: 260px;
    --sb-collapsed: 68px;
    --sb-bg: #ffffff;
    --sb-bg2: #f7f8fa;
    --sb-border: #e8eaed;
    --sb-hover: #f2f4f7;
    --sb-active-bg: #eef1f8;
    --sb-active-bar: #4a6fa5;
    --sb-text: #5a6370;
    --sb-text-active: #1e2530;
    --sb-muted: #9ba3af;
    --sb-ease: 0.25s ease;
}

/* ── Botón hamburguesa mobile ── */
.sidebar-mobile-btn {
    position: fixed;
    top: 0.9rem;
    left: 0.9rem;
    z-index: 10001;
    width: 40px;
    height: 40px;
    background: #fff;
    border: 1px solid var(--sb-border);
    border-radius: 8px;
    color: var(--sb-text-active);
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 6px rgba(0,0,0,0.1);
    cursor: pointer;
    transition: background var(--sb-ease);
}
.sidebar-mobile-btn:hover { background: var(--sb-hover); }

/* ── Backdrop ── */
.sidebar-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.25);
    z-index: 9998;
}
.sidebar-backdrop.is-active { display: block; }

/* ── Nav principal ── */
.sidebar-nav {
    position: fixed;
    left: 0;
    top: 0;
    height: 100vh;
    width: var(--sb-width);
    background: var(--sb-bg);
    border-right: 1px solid var(--sb-border);
    box-shadow: 2px 0 8px rgba(0,0,0,0.06);
    z-index: 9999;
    transition: width var(--sb-ease), transform var(--sb-ease);
    overflow: hidden;
}

.sidebar-nav.is-collapsed { width: var(--sb-collapsed); }

@media (max-width: 991px) {
    .sidebar-nav {
        transform: translateX(-100%);
        width: var(--sb-width) !important;
    }
    .sidebar-nav.mobile-open { transform: translateX(0); }
}

/* ── Scroll interior ── */
.sidebar-inner {
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow-y: auto;
    overflow-x: hidden;
    scrollbar-width: thin;
    scrollbar-color: #e0e2e6 transparent;
}
.sidebar-inner::-webkit-scrollbar { width: 3px; }
.sidebar-inner::-webkit-scrollbar-thumb { background: #dde0e5; border-radius: 2px; }

/* ── Header ── */
.sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    border-bottom: 1px solid var(--sb-border);
    min-height: 64px;
    flex-shrink: 0;
    gap: 0.5rem;
    background: var(--sb-bg);
}
.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    overflow: hidden;
    flex: 1;
    min-width: 0;
}
.brand-icon-box {
    width: 32px;
    height: 32px;
    background: var(--sb-active-bg);
    border: 1px solid #d6dde8;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    color: var(--sb-active-bar);
    flex-shrink: 0;
}
.brand-texts {
    display: flex;
    flex-direction: column;
    overflow: hidden;
    min-width: 0;
}
.brand-name {
    font-weight: 600;
    font-size: 0.88rem;
    color: var(--sb-text-active);
    white-space: nowrap;
    line-height: 1.2;
}
.brand-sub {
    font-size: 0.67rem;
    color: var(--sb-muted);
    white-space: nowrap;
    line-height: 1.3;
}
.sidebar-collapse-btn,
.sidebar-close-btn {
    width: 26px;
    height: 26px;
    background: transparent;
    border: 1px solid var(--sb-border);
    color: var(--sb-muted);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    font-size: 0.68rem;
    transition: color var(--sb-ease), background var(--sb-ease);
}
.sidebar-collapse-btn:hover,
.sidebar-close-btn:hover { background: var(--sb-hover); color: var(--sb-text-active); }

/* ── Usuario ── */
.sidebar-user {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.7rem 0.875rem;
    margin: 0.625rem;
    background: var(--sb-bg2);
    border: 1px solid var(--sb-border);
    border-radius: 10px;
    flex-shrink: 0;
    overflow: hidden;
}
.sidebar-avatar {
    width: 34px;
    height: 34px;
    background: #e4e8f0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--sb-text);
    font-size: 0.9rem;
    flex-shrink: 0;
    position: relative;
}
.avatar-online {
    position: absolute;
    bottom: 1px;
    right: 1px;
    width: 8px;
    height: 8px;
    background: #34d399;
    border-radius: 50%;
    border: 2px solid var(--sb-bg2);
}
.sidebar-user-info {
    display: flex;
    flex-direction: column;
    overflow: hidden;
    min-width: 0;
}
.user-name {
    font-weight: 500;
    font-size: 0.8rem;
    color: var(--sb-text-active);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.3;
}
.user-role {
    font-size: 0.68rem;
    color: var(--sb-muted);
    white-space: nowrap;
    line-height: 1.3;
}

/* ── Menú ── */
.sidebar-menu {
    flex: 1;
    padding: 0.5rem;
    display: flex;
    flex-direction: column;
    gap: 1px;
}

/* ── Links ── */
.sidebar-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.55rem 0.75rem;
    color: var(--sb-text);
    text-decoration: none;
    border-radius: 8px;
    transition: background var(--sb-ease), color var(--sb-ease);
    white-space: nowrap;
    overflow: hidden;
    border: none;
    background: transparent;
    cursor: pointer;
    width: 100%;
    text-align: left;
    font-size: 0.845rem;
    line-height: 1;
    position: relative;
}
.sidebar-link:hover {
    background: var(--sb-hover);
    color: var(--sb-text-active);
    text-decoration: none;
}
.sidebar-link.is-active {
    background: var(--sb-active-bg);
    color: var(--sb-active-bar);
    font-weight: 600;
}
.sidebar-link.is-active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 20%;
    height: 60%;
    width: 3px;
    background: var(--sb-active-bar);
    border-radius: 0 2px 2px 0;
}

.sidebar-icon {
    width: 18px;
    text-align: center;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.sidebar-label {
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* ── Grupos con submenú ── */
.group-chevron {
    font-size: 0.62rem;
    margin-left: auto;
    flex-shrink: 0;
    color: var(--sb-muted);
    transition: transform var(--sb-ease);
}
.sidebar-group.is-open > .sidebar-group-btn .group-chevron {
    transform: rotate(180deg);
}
.sidebar-sublinks {
    display: none;
    flex-direction: column;
    gap: 1px;
    padding: 3px 0 3px 6px;
    margin-left: 18px;
    border-left: 1px solid var(--sb-border);
}
.sidebar-group.is-open > .sidebar-sublinks { display: flex; }
.sidebar-sublink {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.7rem;
    color: var(--sb-muted);
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.78rem;
    white-space: nowrap;
    overflow: hidden;
    transition: background var(--sb-ease), color var(--sb-ease);
}
.sidebar-sublink i {
    font-size: 0.68rem;
    width: 13px;
    text-align: center;
    flex-shrink: 0;
}
.sidebar-sublink:hover {
    background: var(--sb-hover);
    color: var(--sb-text-active);
    text-decoration: none;
}
.sidebar-sublink.is-active {
    color: var(--sb-active-bar);
    background: var(--sb-active-bg);
    font-weight: 500;
}

/* ── Footer / Logout ── */
.sidebar-footer {
    padding: 0.5rem;
    border-top: 1px solid var(--sb-border);
    flex-shrink: 0;
}
.sidebar-logout { color: #e05c5c !important; }
.sidebar-logout:hover { background: #fff5f5 !important; color: #c0392b !important; }

/* ══ ESTADO COLAPSADO ══ */
.sidebar-nav.is-collapsed .brand-texts,
.sidebar-nav.is-collapsed .sidebar-user-info,
.sidebar-nav.is-collapsed .sidebar-label,
.sidebar-nav.is-collapsed .group-chevron { display: none; }
.sidebar-nav.is-collapsed .sidebar-user { justify-content: center; padding: 0.65rem 0.4rem; }
.sidebar-nav.is-collapsed .sidebar-link { justify-content: center; padding: 0.6rem; }
.sidebar-nav.is-collapsed .sidebar-brand { justify-content: center; }
.sidebar-nav.is-collapsed .sidebar-header { justify-content: center; }
.sidebar-nav.is-collapsed #collapseIcon { transform: rotate(180deg); }
.sidebar-nav.is-collapsed .sidebar-group.is-open > .sidebar-sublinks { display: none; }
.sidebar-nav.is-collapsed .sidebar-link.is-active::before { display: none; }

/* ══ MAIN CONTENT ══ */
.main-content { transition: margin-left var(--sb-ease); }
@media (min-width: 992px) {
    .main-content { margin-left: var(--sb-width); }
    body.sidebar-is-collapsed .main-content { margin-left: var(--sb-collapsed); }
}
@media (max-width: 991px) {
    .main-content { margin-left: 0 !important; padding-top: 64px; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Restaurar estado colapsado en desktop
    if (window.innerWidth >= 992 && localStorage.getItem('sidebarCollapsed') === 'true') {
        document.getElementById('sidebarNav').classList.add('is-collapsed');
        document.body.classList.add('sidebar-is-collapsed');
    }
});

function toggleDesktopSidebar() {
    const nav = document.getElementById('sidebarNav');
    nav.classList.toggle('is-collapsed');
    document.body.classList.toggle('sidebar-is-collapsed');
    localStorage.setItem('sidebarCollapsed', nav.classList.contains('is-collapsed'));
}

function openMobileSidebar() {
    document.getElementById('sidebarNav').classList.add('mobile-open');
    document.getElementById('sidebarBackdrop').classList.add('is-active');
    document.body.style.overflow = 'hidden';
}

function closeMobileSidebar() {
    document.getElementById('sidebarNav').classList.remove('mobile-open');
    document.getElementById('sidebarBackdrop').classList.remove('is-active');
    document.body.style.overflow = '';
}

function toggleGroup(btn) {
    const nav = document.getElementById('sidebarNav');
    if (nav.classList.contains('is-collapsed')) return;
    btn.closest('.sidebar-group').classList.toggle('is-open');
}
</script>
