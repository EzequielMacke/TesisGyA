<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta en Proceso - TesisGyA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-xl-6 col-lg-7 col-md-9">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <!-- Logo/Header -->
                        <div class="text-center mb-4">
                            <div class="avatar-lg bg-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                <i class="fas fa-clock fa-3x text-white"></i>
                            </div>
                            <h1 class="h3 text-gray-900 mb-2">Cuenta en Proceso</h1>
                            <p class="text-muted mb-0">Sistema de Gestión TesisGyA</p>
                        </div>

                        <!-- Información Principal -->
                        <div class="alert alert-warning border-left-warning" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle fa-2x text-warning me-3"></i>
                                <div>
                                    <h4 class="alert-heading mb-2">¡Bienvenido {{ session('user_nombre') }} {{ session('user_apellido') }}!</h4>
                                    <p class="mb-2">
                                        Tu cuenta ha sido creada exitosamente, pero aún no tienes permisos para acceder al sistema.
                                    </p>
                                    <hr>
                                    <p class="mb-0 small">
                                        <strong>Estado:</strong> <span class="badge bg-warning text-dark">Pendiente de Asignación</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Pasos a seguir -->
                        <div class="card border-left-info mb-4">
                            <div class="card-body">
                                <h5 class="card-title text-info">
                                    <i class="fas fa-info-circle me-2"></i>¿Qué sigue ahora?
                                </h5>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; font-size: 14px;">
                                                1
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Espera la asignación de cargo</h6>
                                                <p class="text-muted small mb-0">Un administrador debe asignarte un cargo y permisos.</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; font-size: 14px;">
                                                2
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Recibe notificación</h6>
                                                <p class="text-muted small mb-0">Te enviaremos un email cuando tu cuenta esté lista.</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start">
                                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; font-size: 14px;">
                                                3
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Accede al sistema</h6>
                                                <p class="text-muted small mb-0">Una vez asignado tu cargo, podrás acceder a todas las funcionalidades.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de contacto -->
                        <div class="card border-left-secondary mb-4">
                            <div class="card-body">
                                <h6 class="text-secondary mb-3">
                                    <i class="fas fa-phone me-2"></i>¿Necesitas ayuda?
                                </h6>
                                <p class="small text-muted mb-2">
                                    Si tienes alguna consulta o el proceso está tomando más tiempo del esperado, puedes contactar con:
                                </p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="small mb-1">
                                            <i class="fas fa-envelope text-primary me-2"></i>
                                            <strong>Email:</strong> ezequiel.macke@gmail.com
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Información de la cuenta -->
                        <div class="bg-light p-3 rounded mb-4">
                            <h6 class="text-dark mb-3">
                                <i class="fas fa-user me-2"></i>Información de tu cuenta
                            </h6>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="small mb-1"><strong>Usuario:</strong> {{ session('user_usuario') }}</p>
                                    <p class="small mb-1"><strong>Email:</strong> {{ session('user_email') }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="small mb-1"><strong>Nombre:</strong> {{ session('user_nombre') }} {{ session('user_apellido') }}</p>
                                    <p class="small mb-1"><strong>Cargo:</strong> <span class="text-warning">Sin asignar</span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <button type="button" class="btn btn-outline-primary" onclick="window.location.reload()">
                                <i class="fas fa-sync-alt me-2"></i>Actualizar Estado
                            </button>
                            <a href="{{ route('logout') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                            </a>
                        </div>

                        <!-- Footer -->
                        <div class="text-center mt-4 pt-3 border-top">
                            <p class="text-muted small mb-0">
                                © {{ date('Y') }} TesisGyA - Sistema de Gestión
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto-refresh cada 30 segundos -->
    <script>
        // Auto-refresh para verificar cambios en el estado
        setInterval(function() {
            fetch('{{ route("verificar.cargo") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.tiene_cargo) {
                    alert('¡Tu cuenta ha sido activada!');
                    window.location.href = '{{ route("menu.index") }}';
                }
            })
            .catch(error => console.log('Error:', error));
        }, 30000);

        // Efecto de loading en el botón actualizar
        document.querySelector('[onclick="window.location.reload()"]').addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Verificando...';
            this.disabled = true;

            setTimeout(() => {
                window.location.reload();
            }, 1000);
        });
    </script>
</body>
</html>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #4b51a2 100%);
    min-height: 100vh;
}

.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}

.border-left-info {
    border-left: 4px solid #36b9cc !important;
}

.border-left-secondary {
    border-left: 4px solid #858796 !important;
}

.card {
    border-radius: 15px;
    backdrop-filter: blur(10px);
}

.avatar-lg {
    width: 80px;
    height: 80px;
}

.alert {
    border: none;
    border-radius: 10px;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.625rem 1.25rem;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Animación de pulsación para elementos importantes */
.alert-warning {
    animation: pulse-warning 2s infinite;
}

@keyframes pulse-warning {
    0% { box-shadow: 0 0 0 0 rgba(246, 194, 62, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(246, 194, 62, 0); }
    100% { box-shadow: 0 0 0 0 rgba(246, 194, 62, 0); }
}

/* Responsive */
@media (max-width: 768px) {
    .card-body {
        padding: 2rem !important;
    }

    .d-md-flex {
        flex-direction: column !important;
    }

    .btn {
        margin-bottom: 0.5rem;
    }
}
</style>
