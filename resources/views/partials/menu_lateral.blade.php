<!-- filepath: c:\laragon\www\TesisGyA\resources\views\partials\menu_lateral.blade.php -->
<nav class="bg-gradient text-white vh-100 sidebar-nav" style="width: 60px; position: fixed; left: 0; top: 0; transition: width 0.3s ease; z-index: 9999;">
    <div class="p-3 h-100 d-flex flex-column">
        <!-- Logo/Título -->
        <div class="text-center mb-4 brand-section">
            <div class="brand-icon mb-2">
                <i class="fas fa-cube text-light" style="font-size: 2rem;"></i>
            </div>
            <div class="menu-text">
                <h5 class="text-light fw-bold mb-0 brand-text">TesisGyA</h5>
                <small class="text-light">Sistema de Gestión</small>
            </div>
        </div>

        <!-- Datos del Usuario -->
        <div class="user-section mb-4 p-3 rounded">
            <div class="text-center">
                <div class="user-avatar mb-2 position-relative">
                    <div class="avatar-circle">
                        <i class="fas fa-user text-white" style="font-size: 1.2rem;"></i>
                    </div>
                    <div class="status-indicator bg-success"></div>
                </div>
                <div class="menu-text user-info">
                    <h6 class="mb-1 text-white fw-semibold">{{ session('user_usuario', 'Usuario') }}</h6>
                    <small class="text-light">{{ session('user_cargo', 'Administrador') }}</small>
                </div>
            </div>
        </div>

        <!-- Menú Principal -->
        <div class="flex-grow-1">
            <ul class="nav flex-column menu-items">
                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center py-3 px-3 rounded-3 menu-item" href="{{ route('menu.index') }}">
                        <i class="fas fa-home icon-fixed text-white"></i>
                        <span class="menu-text text-white ms-3">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center py-3 px-3 rounded-3 menu-item" href="{{ route('inventario.index') }}">
                        <i class="fas fa-warehouse icon-fixed text-white"></i>
                        <span class="menu-text text-white ms-3">Inventario</span>
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <div class="dropdown-item-menu">
                        <a class="nav-link text-white d-flex align-items-center py-3 px-3 rounded-3 menu-item" href="#" onclick="toggleCompraMenu(event)">
                            <i class="fas fa-shopping-cart icon-fixed text-white"></i>
                            <span class="menu-text text-white ms-3">Compra</span>
                            <i class="fas fa-chevron-down ms-auto menu-text transition-transform text-white" id="compra-arrow"></i>
                        </a>
                        <div class="submenu-container" id="compra-submenu" style="display: none;">
                            <div class="mt-2 ms-3 menu-text">
                                <a class="nav-link text-light d-flex align-items-center py-2 px-3 rounded-3 submenu-item" href="{{ route('pedido_compra.index') }}">
                                    <i class="fas fa-file-alt me-2 text-light" style="font-size: 0.8rem;"></i>
                                    <span class="text-light">Pedidos de Compra</span>
                                </a>
                                <a class="nav-link text-light d-flex align-items-center py-2 px-3 rounded-3 submenu-item" href="{{ route('presupuesto_compra_aprobado.index') }}">
                                    <i class="fas fa-check-circle me-2 text-light" style="font-size: 0.8rem;"></i>
                                    <span class="text-light">Presupuestos Aprobados</span>
                                </a>
                                <a class="nav-link text-light d-flex align-items-center py-2 px-3 rounded-3 submenu-item" href="{{ route('orden_compra.index') }}">
                                    <i class="fas fa-file-contract me-2 text-light" style="font-size: 0.8rem;"></i>
                                    <span class="text-light">Órdenes de Compra</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item mb-1">
                    <div class="dropdown-item-menu">
                        <a class="nav-link text-white d-flex align-items-center py-3 px-3 rounded-3 menu-item" href="#" onclick="toggleMenu(event)">
                            <i class="fas fa-database icon-fixed text-white"></i>
                            <span class="menu-text text-white ms-3">Referenciales</span>
                            <i class="fas fa-chevron-down ms-auto menu-text transition-transform text-white" id="arrow"></i>
                        </a>
                        <div class="submenu-container" id="submenu" style="display: none;">
                            <div class="mt-2 ms-3 menu-text">
                                <a class="nav-link text-light d-flex align-items-center py-2 px-3 rounded-3 submenu-item" href="{{ route('marca.index') }}">
                                    <i class="fas fa-tag me-2 text-light" style="font-size: 0.8rem;"></i>
                                    <span class="text-light">Marcas</span>
                                </a>
                                <a class="nav-link text-light d-flex align-items-center py-2 px-3 rounded-3 submenu-item" href="{{ route('insumo.index') }}">
                                    <i class="fas fa-boxes me-2 text-light" style="font-size: 0.8rem;"></i>
                                    <span class="text-light">Insumos</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center py-3 px-3 rounded-3 menu-item" href="{{ route('presupuesto_compra.index') }}">
                        <i class="fas fa-file-invoice-dollar icon-fixed text-white"></i>
                        <span class="menu-text text-white ms-3">Presupuestar Pedidos</span>
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center py-3 px-3 rounded-3 menu-item" href="#">
                        <i class="fas fa-user icon-fixed text-white"></i>
                        <span class="menu-text text-white ms-3">Mi Perfil</span>
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a class="nav-link text-white d-flex align-items-center py-3 px-3 rounded-3 menu-item" href="#">
                        <i class="fas fa-cog icon-fixed text-white"></i>
                        <span class="menu-text text-white ms-3">Configuración</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Logout Button -->
        <div class="mt-auto">
            <a class="nav-link text-white d-flex align-items-center py-3 px-3 rounded-3 logout-btn" href="{{ route('logout') }}">
                <i class="fas fa-sign-out-alt icon-fixed text-white"></i>
                <span class="menu-text text-white ms-3">Cerrar Sesión</span>
            </a>
        </div>
    </div>
</nav>

<style>
/* Gradiente de fondo AZUL OSCURO - COMPLETAMENTE SÓLIDO */
.bg-gradient {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%) !important;
    box-shadow: 2px 0 15px rgba(0, 0, 0, 0.3);
    opacity: 1 !important;
}

/* Navegación principal - MÁXIMO Z-INDEX */
.sidebar-nav {
    overflow: visible;
    border-right: 2px solid #0d47a1;
    position: fixed !important;
    z-index: 9999 !important;
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%) !important;
}

.sidebar-nav:hover {
    width: 280px !important;
    box-shadow: 2px 0 25px rgba(0, 0, 0, 0.3);
}

/* Sección de marca */
.brand-section {
    border-bottom: 2px solid #0d47a1;
    padding-bottom: 1rem;
}

.brand-icon {
    transition: transform 0.3s ease;
}

.sidebar-nav:hover .brand-icon {
    transform: scale(1.1);
}

/* Sección de usuario - COMPLETAMENTE SÓLIDA */
.user-section {
    background: #0d47a1 !important;
    border: 2px solid #1565c0;
    transition: all 0.3s ease;
    opacity: 1 !important;
}

.avatar-circle {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #1565c0 0%, #1976d2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    position: relative;
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    position: absolute;
    bottom: 0;
    right: 0;
    border: 2px solid #0d47a1;
}

/* Texto del menú - SIN OPACIDAD */
.menu-text {
    display: none;
    white-space: nowrap;
    transition: all 0.3s ease;
    opacity: 1 !important;
}

.sidebar-nav:hover .menu-text {
    display: block !important;
    opacity: 1 !important;
}

/* Iconos fijos - CENTRADO PERFECTO */
.icon-fixed {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

/* Items del menú - COMPLETAMENTE SÓLIDOS */
.menu-item {
    text-decoration: none !important;
    transition: all 0.3s ease;
    border: 1px solid transparent;
    background: transparent !important;
    justify-content: flex-start !important;
}

.menu-item:hover {
    background: #1565c0 !important;
    border-color: #0d47a1;
    transform: translateX(5px);
    opacity: 1 !important;
}

/* Cuando está colapsado, centrar los iconos */
.sidebar-nav:not(:hover) .menu-item {
    justify-content: center !important;
}

.sidebar-nav:not(:hover) .icon-fixed {
    margin: 0 !important;
}

/* Submenu - COMPLETAMENTE SÓLIDO */
.submenu-container {
    transition: all 0.3s ease;
    overflow: hidden;
    background: transparent !important;
}

.submenu-item {
    background: #0d47a1 !important;
    border-left: 3px solid transparent;
    transition: all 0.3s ease;
    opacity: 1 !important;
}

.submenu-item:hover {
    background: #1565c0 !important;
    border-left-color: #ffffff;
    transform: translateX(3px);
    opacity: 1 !important;
}

/* Botón de logout - COMPLETAMENTE SÓLIDO */
.logout-btn {
    background: #d32f2f !important;
    border: 2px solid #b71c1c;
    transition: all 0.3s ease;
    opacity: 1 !important;
    justify-content: flex-start !important;
}

.logout-btn:hover {
    background: #b71c1c !important;
    border-color: #8e0000;
    transform: translateX(5px);
    opacity: 1 !important;
}

/* Cuando está colapsado, centrar el logout también */
.sidebar-nav:not(:hover) .logout-btn {
    justify-content: center !important;
}

/* Contenido principal - MENOR Z-INDEX */
.main-content {
    margin-left: 60px;
    transition: margin-left 0.3s ease;
    padding: 2rem;
    position: relative;
    z-index: 1;
}

/* Animación del chevron */
.transition-transform {
    transition: transform 0.3s ease;
}

/* Efectos responsive */
@media (max-width: 768px) {
    .sidebar-nav {
        width: 50px !important;
        z-index: 9999 !important;
    }

    .sidebar-nav:hover {
        width: 250px !important;
    }

    .main-content {
        margin-left: 50px;
        z-index: 1;
    }
}

/* Scrollbar personalizado */
.sidebar-nav::-webkit-scrollbar {
    width: 6px;
}

.sidebar-nav::-webkit-scrollbar-track {
    background: #0d47a1;
}

.sidebar-nav::-webkit-scrollbar-thumb {
    background: #1565c0;
    border-radius: 3px;
}

.sidebar-nav::-webkit-scrollbar-thumb:hover {
    background: #1976d2;
}

/* FORZAR SOLIDEZ COMPLETA */
.sidebar-nav * {
    opacity: 1 !important;
}
</style>

<script>
function toggleMenu(event) {
    // Prevenir el comportamiento por defecto del enlace
    event.preventDefault();

    const submenu = document.getElementById('submenu');
    const arrow = document.getElementById('arrow');

    if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'block';
        arrow.style.transform = 'rotate(180deg)';
    } else {
        submenu.style.display = 'none';
        arrow.style.transform = 'rotate(0deg)';
    }
}

function toggleCompraMenu(event) {
    // Prevenir el comportamiento por defecto del enlace
    event.preventDefault();

    const submenu = document.getElementById('compra-submenu');
    const arrow = document.getElementById('compra-arrow');

    if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'block';
        arrow.style.transform = 'rotate(180deg)';
    } else {
        submenu.style.display = 'none';
        arrow.style.transform = 'rotate(0deg)';
    }
}

// Efecto de hover mejorado
document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.querySelectorAll('.menu-item, .submenu-item');

    menuItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.2)';
        });

        item.addEventListener('mouseleave', function() {
            this.style.boxShadow = 'none';
        });
    });
});
</script>
