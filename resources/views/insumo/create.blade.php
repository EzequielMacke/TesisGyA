<!-- filepath: c:\laragon\www\TesisGyA\resources\views\insumo\create.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Insumo - TesisGyA</title>
    @include('partials.head')

</head>
<body>
    @include('partials.menu_lateral')

    <div class="main-content fade-in">
        <div class="content-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-plus me-2"></i>Nuevo Insumo</h2>
                <a href="{{ route('insumo.index') }}" class="btn btn-secondary">
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
                    <form method="POST" action="{{ route('insumo.store') }}" id="insumoForm">
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
                                <i class="fas fa-box me-1"></i>Descripción *
                            </label>
                            <input type="text"
                                class="form-control"
                                id="descripcion"
                                name="descripcion"
                                value="{{ old('descripcion') }}"
                                placeholder="Ingrese la descripción del insumo"
                                required>
                            <div id="insumoExisteMensaje" class="text-danger mt-2" style="display: none;">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Ya existe un insumo con esta descripción y marca.
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="marca_id" class="form-label">
                                        <i class="fas fa-tag me-1"></i>Marca *
                                    </label>
                                    <select class="form-select" id="marca_id" name="marca_id" required>
                                        <option value="">Seleccione una marca...</option>
                                        @foreach($marcas as $marca)
                                            <option value="{{ $marca->id }}" {{ old('marca_id') == $marca->id ? 'selected' : '' }}>
                                                {{ $marca->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="unidad_medida_id" class="form-label">
                                        <i class="fas fa-ruler me-1"></i>Unidad de Medida *
                                    </label>
                                    <select class="form-select" id="unidad_medida_id" name="unidad_medida_id" required>
                                        <option value="">Seleccione una unidad...</option>
                                        @foreach($unidadesMedida as $unidad)
                                            <option value="{{ $unidad->id }}" {{ old('unidad_medida_id') == $unidad->id ? 'selected' : '' }}
                                                    data-abrev="{{ $unidad->abreviatura }}">
                                                {{ $unidad->descripcion }}
                                                @if($unidad->abreviatura)
                                                    ({{ $unidad->abreviatura }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
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
                            </div>
                            <div class="col-md-6">
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
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('insumo.index') }}" class="btn btn-secondary me-md-2">
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

    <!-- jQuery (requerido para Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

/* Estilos para Select2 */
.select2-container--bootstrap-5 .select2-selection {
    border: 2px solid #e9ecef !important;
    border-radius: 6px !important;
    min-height: calc(1.5em + 0.75rem + 4px) !important;
    transition: all 0.3s ease;
}

.select2-container--bootstrap-5 .select2-selection:focus-within {
    border-color: #007bff !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
    padding-top: 0.375rem;
    padding-bottom: 0.375rem;
    font-size: 0.95rem;
}

.select2-container {
    width: 100% !important;
}

.select2-dropdown {
    border: 2px solid #007bff;
    border-radius: 6px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.select2-search--dropdown .select2-search__field {
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 0.375rem 0.75rem;
}

.select2-results__option--highlighted {
    background-color: #007bff !important;
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

    .row .col-md-6,
    .row .col-md-8,
    .row .col-md-4 {
        margin-bottom: 1rem;
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
    // Esperar a que jQuery esté listo
    $(document).ready(function() {
        // Inicializar Select2
        $('#marca_id').select2({
            theme: 'bootstrap-5',
            placeholder: 'Buscar marca...',
            allowClear: true,
            language: {
                noResults: function() {
                    return "No se encontraron marcas";
                },
                searching: function() {
                    return "Buscando...";
                }
            }
        });

        $('#unidad_medida_id').select2({
            theme: 'bootstrap-5',
            placeholder: 'Buscar unidad de medida...',
            allowClear: true,
            language: {
                noResults: function() {
                    return "No se encontraron unidades";
                },
                searching: function() {
                    return "Buscando...";
                }
            }
        });

        // Elementos del formulario
        const descripcionInput = document.getElementById('descripcion');
        const marcaSelect = document.getElementById('marca_id');
        const insumoExisteMensaje = document.getElementById('insumoExisteMensaje');
        const guardarBtn = document.getElementById('guardarBtn');
        const form = document.getElementById('insumoForm');

        // Array con las combinaciones existentes (descripción + marca)
        const insumosExistentes = @json($insumosExistentes ?? []);

        let combinacionValida = true;

        function verificarCombinacionExiste() {
            const descripcionValue = descripcionInput.value.trim().toLowerCase();
            const marcaValue = $('#marca_id').val(); // Usar jQuery para Select2

            // Si no hay descripción o marca, ocultar mensaje
            if (!descripcionValue || !marcaValue) {
                ocultarMensajeDescripcion();
                combinacionValida = true;
                actualizarBotonGuardar();
                return;
            }

            // Verificar si existe la combinación descripción + marca
            const combinacionExiste = insumosExistentes.some(insumo =>
                insumo.descripcion.toLowerCase() === descripcionValue &&
                insumo.marca_id == marcaValue
            );

            if (combinacionExiste) {
                mostrarMensajeDescripcion();
                combinacionValida = false;
            } else {
                ocultarMensajeDescripcion();
                combinacionValida = true;
            }
            actualizarBotonGuardar();
        }

        function mostrarMensajeDescripcion() {
            descripcionInput.classList.add('error');
            insumoExisteMensaje.style.display = 'block';
            insumoExisteMensaje.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Ya existe un insumo con esta descripción y marca.';
        }

        function ocultarMensajeDescripcion() {
            descripcionInput.classList.remove('error');
            insumoExisteMensaje.style.display = 'none';
        }

        function actualizarBotonGuardar() {
            if (!combinacionValida) {
                guardarBtn.disabled = true;
                guardarBtn.innerHTML = '<i class="fas fa-ban me-2"></i>No se puede guardar';
                guardarBtn.classList.remove('btn-primary');
                guardarBtn.classList.add('btn-danger');
            } else {
                guardarBtn.disabled = false;
                guardarBtn.innerHTML = '<i class="fas fa-save me-2"></i>Guardar';
                guardarBtn.classList.remove('btn-danger');
                guardarBtn.classList.add('btn-primary');
            }
        }

        // Event listeners - verificar cuando cambien descripción O marca
        descripcionInput.addEventListener('input', verificarCombinacionExiste);
        descripcionInput.addEventListener('blur', verificarCombinacionExiste);

        // Para Select2, usar el evento select2:select
        $('#marca_id').on('select2:select', verificarCombinacionExiste);
        $('#marca_id').on('select2:clear', verificarCombinacionExiste);

        // Prevenir envío del formulario si hay duplicados
        form.addEventListener('submit', function(e) {
            if (!combinacionValida) {
                e.preventDefault();
                alert('No se puede guardar porque ya existe un insumo con esta descripción y marca.');
                return false;
            }

            // Validar que los campos obligatorios estén completos
            if (!descripcionInput.value.trim() || !$('#marca_id').val() || !$('#unidad_medida_id').val()) {
                e.preventDefault();
                alert('Por favor complete todos los campos obligatorios.');
                return false;
            }
        });

        // Verificar al cargar la página si hay valores previos
        if (descripcionInput.value && $('#marca_id').val()) {
            verificarCombinacionExiste();
        }
    });
});
</script>
