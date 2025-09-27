<!-- filepath: c:\laragon\www\TesisGyA\resources\views\menu\datos_proveedor.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Datos de Proveedor - TesisGyA</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-xl-8 col-lg-9 col-md-10">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="avatar-lg bg-info rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                <i class="fas fa-building fa-3x text-white"></i>
                            </div>
                            <h1 class="h3 text-gray-900 mb-2">Completar Datos de Proveedor</h1>
                            <p class="text-muted mb-0">Bienvenido {{ session('user_nombre') }} {{ session('user_apellido') }}</p>
                        </div>

                        <!-- Información -->
                        <div class="alert alert-info border-left-info mb-4" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-2x text-info me-3"></i>
                                <div>
                                    <h5 class="alert-heading mb-2">¡Datos Requeridos!</h5>
                                    <p class="mb-0">
                                        Para acceder al sistema como proveedor, necesitas completar la información de tu empresa.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Mensajes -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Formulario -->
                        <form method="POST" action="{{ route('guardar.datos.proveedor') }}">
                            @csrf

                            <div class="row">
                                <!-- Razón Social -->
                                <div class="col-md-6 mb-3">
                                    <label for="razon_social" class="form-label">
                                        <i class="fas fa-building me-1 text-primary"></i>Razón Social *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('razon_social') is-invalid @enderror"
                                           id="razon_social"
                                           name="razon_social"
                                           value="{{ old('razon_social', $proveedor->razon_social ?? '') }}"
                                           required>
                                    @error('razon_social')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- RUC -->
                                <div class="col-md-6 mb-3">
                                    <label for="ruc" class="form-label">
                                        <i class="fas fa-id-card me-1 text-primary"></i>RUC *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('ruc') is-invalid @enderror"
                                           id="ruc"
                                           name="ruc"
                                           value="{{ old('ruc', $proveedor->ruc ?? '') }}"
                                           required>
                                    @error('ruc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Dirección -->
                                <div class="col-12 mb-3">
                                    <label for="direccion" class="form-label">
                                        <i class="fas fa-map-marker-alt me-1 text-primary"></i>Dirección *
                                    </label>
                                    <textarea class="form-control @error('direccion') is-invalid @enderror"
                                              id="direccion"
                                              name="direccion"
                                              rows="2"
                                              required>{{ old('direccion', $proveedor->direccion ?? '') }}</textarea>
                                    @error('direccion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Teléfono -->
                                <div class="col-md-6 mb-3">
                                    <label for="telefono" class="form-label">
                                        <i class="fas fa-phone me-1 text-primary"></i>Teléfono *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('telefono') is-invalid @enderror"
                                           id="telefono"
                                           name="telefono"
                                           value="{{ old('telefono', $proveedor->telefono ?? '') }}"
                                           required>
                                    @error('telefono')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-1 text-primary"></i>Email Comercial *
                                    </label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $proveedor->email ?? session('user_email')) }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>Guardar Datos
                                </button>
                                <a href="{{ route('logout') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                </a>
                            </div>
                        </form>

                        <!-- Footer -->
                        <div class="text-center mt-4 pt-3 border-top">
                            <p class="text-muted small mb-0">
                                <i class="fas fa-shield-alt me-1"></i>
                                Todos los datos son confidenciales y están protegidos.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #8cb0ce 0%, #4e81e0 100%);
    min-height: 100vh;
}

.border-left-info {
    border-left: 4px solid #36b9cc !important;
}

.card {
    border-radius: 15px;
    backdrop-filter: blur(10px);
}

.avatar-lg {
    width: 80px;
    height: 80px;
}

.form-control:focus {
    border-color: #36b9cc;
    box-shadow: 0 0 0 0.2rem rgba(54, 185, 204, 0.25);
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
</style>
