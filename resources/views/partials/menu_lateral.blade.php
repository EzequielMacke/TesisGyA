<!-- filepath: c:\laragon\www\TesisGyA\resources\views\partials\menu_lateral.blade.php -->
<nav class="bg-light border-end vh-100" style="width: 220px; position: fixed; left: 0; top: 0;">
    <div class="p-3">
        <h5 class="mb-4">Menú</h5>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link" href="{{ route('menu.index') }}">Inicio</a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link" href="#">Perfil</a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link" href="#">Configuraciones</a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-danger" href="{{ route('logout') }}">Cerrar sesión</a>
            </li>
        </ul>
    </div>
</nav>
