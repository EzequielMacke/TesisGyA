<!-- filepath: c:\laragon\www\TesisGyA\resources\views\marca\create.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Marca - TesisGyA</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content fade-in">
        <div class="content-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-plus me-2"></i>Nueva Marca</h2>
                <a href="{{ route('marca.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('marca.store') }}" id="marcaForm">
                        @csrf

                        <!-- Información del Usuario (Solo lectura) -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="usuario" class="form-label">
                                    <i class="fas fa-user me-1"></i>Usuario
                                </label>
                                <input type="text" class="form-control-plaintext bg-light border rounded px-3 py-2"
                                       id="usuario"
                                       value="{{ session('user_usuario', 'Usuario no definido') }}"
                                       readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="sucursal" class="form-label">
                                    <i class="fas fa-building me-1"></i>Sucursal
                                </label>
                                <input type="text" class="form-control-plaintext bg-light border rounded px-3 py-2"
                                       id="sucursal"
                                       value="{{ session('user_sucursal', 'Sucursal no definida') }}"
                                       readonly>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Campos del formulario -->
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">
                                <i class="fas fa-tag me-1"></i>Descripción *
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="descripcion"
                                   name="descripcion"
                                   value="{{ old('descripcion') }}"
                                   placeholder="Ingrese la descripción de la marca"
                                   required>
                            <div id="marcaExisteMensaje" class="text-danger mt-2" style="display: none;">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Esta marca ya existe en el sistema.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="fecha" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Fecha *
                            </label>
                            <input type="date"
                                   class="form-control"
                                   id="fecha"
                                   name="fecha"
                                   value="{{ old('fecha', date('Y-m-d')) }}"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="estado_id" class="form-label">
                                <i class="fas fa-toggle-on me-1"></i>Estado *
                            </label>
                            <input type="text"
                                class="form-control-plaintext bg-light border rounded px-3 py-2"
                                id="estado_display"
                                value="Activo"
                                readonly>
                            <input type="hidden"
                                name="estado_id"
                                value="1">
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('marca.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary" id="guardarBtn">
                                <i class="fas fa-save me-2"></i>Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>

<style>
/* Usar los mismos estilos del index */
.main-content {
    margin-left: 60px;
    width: calc(100vw - 60px);
    min-height: 100vh;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
    overflow-x: hidden;
    box-sizing: border-box;
}

.content-wrapper {
    padding: 20px;
    max-width: 100%;
    box-sizing: border-box;
}

/* Estilos para campos de solo lectura */
.form-control-plaintext {
    background-color: #f8f9fa !important;
    border: 1px solid #dee2e6 !important;
    color: #6c757d;
    font-weight: 500;
}

/* Estilos para la card */
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

/* Estilos para formularios */
.form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 6px;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    transform: translateY(-1px);
}

.form-control.error {
    border-color: #dc3545;
}

.form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .main-content {
        margin-left: 50px;
        width: calc(100vw - 50px);
    }

    .content-wrapper {
        padding: 15px;
    }
}

@media (max-width: 576px) {
    .main-content {
        margin-left: 45px;
        width: calc(100vw - 45px);
    }

    .content-wrapper {
        padding: 10px;
    }
}

/* Ajuste cuando el menú se expande */
.sidebar-nav:hover ~ .main-content {
    margin-left: 280px;
    width: calc(100vw - 280px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const descripcionInput = document.getElementById('descripcion');
    const marcaExisteMensaje = document.getElementById('marcaExisteMensaje');
    const guardarBtn = document.getElementById('guardarBtn');
    const form = document.getElementById('marcaForm');

    // Array con las marcas existentes (se pasa desde el controlador)
    const marcasExistentes = @json($marcasExistentes ?? []);

    function verificarMarcaExiste() {
        const valorInput = descripcionInput.value.trim().toLowerCase();

        if (!valorInput) {
            ocultarMensajeError();
            return;
        }

        // Verificar si la marca existe (comparación sin distinguir mayúsculas/minúsculas)
        const marcaExiste = marcasExistentes.some(marca =>
            marca.toLowerCase() === valorInput
        );

        if (marcaExiste) {
            mostrarMensajeError();
        } else {
            ocultarMensajeError();
        }
    }

    function mostrarMensajeError() {
        descripcionInput.classList.add('error');
        marcaExisteMensaje.style.display = 'block';
        guardarBtn.disabled = true;
        guardarBtn.innerHTML = '<i class="fas fa-ban me-2"></i>No se puede guardar';
        guardarBtn.classList.remove('btn-primary');
        guardarBtn.classList.add('btn-danger');
    }

    function ocultarMensajeError() {
        descripcionInput.classList.remove('error');
        marcaExisteMensaje.style.display = 'none';
        guardarBtn.disabled = false;
        guardarBtn.innerHTML = '<i class="fas fa-save me-2"></i>Guardar';
        guardarBtn.classList.remove('btn-danger');
        guardarBtn.classList.add('btn-primary');
    }

    // Event listener para el input
    descripcionInput.addEventListener('input', verificarMarcaExiste);

    // Verificar al perder el foco
    descripcionInput.addEventListener('blur', verificarMarcaExiste);

    // Prevenir envío del formulario si la marca existe
    form.addEventListener('submit', function(e) {
        const valorInput = descripcionInput.value.trim().toLowerCase();
        const marcaExiste = marcasExistentes.some(marca =>
            marca.toLowerCase() === valorInput
        );

        if (marcaExiste) {
            e.preventDefault();
            alert('No se puede guardar porque esta marca ya existe en el sistema.');
            descripcionInput.focus();
            return false;
        }
    });

    // Verificar al cargar la página si hay un valor previo
    if (descripcionInput.value) {
        verificarMarcaExiste();
    }
});
</script>
