<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    @include('partials.head')
</head>
<body>
<div class="container" style="max-width: 400px; margin-top: 50px;">
    <h2 class="mb-4 text-center">Iniciar Sesión</h2>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <!-- Aquí iría el mensaje de error si existe -->
    <form method="POST" action="{{ route('login.store') }}">
        <!-- CSRF Token -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="mb-3">
            <label for="email" class="form-label">Usuario o Email</label>
            <input type="text" class="form-control" id="email" name="email" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Ingresar</button>
    </form>
    <div class="mt-3 text-center">
        <a href="{{ route('recuperar.contraseña') }}">¿Olvidaste tu contraseña?</a>
    </div>
    <div class="mt-2 text-center">
        <a href="{{ route('register') }}">Registrarse</a>
    </div>
</div>
</body>
</html>
