<!-- filepath: c:\laragon\www\TesisGyA\resources\views\login\recuperar_contraseña.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    @include('partials.head')
</head>
<body>
<div class="container" style="max-width: 400px; margin-top: 50px;">
    <h2 class="mb-4 text-center">Recuperar Contraseña</h2>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('recuperar.contraseña') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="email" name="email" required autofocus>
        </div>
        <button type="submit" class="btn btn-primary w-100">Recuperar contraseña</button>
    </form>
    <div class="mt-3 text-center">
        <a href="{{ route('login') }}" class="text-muted">Volver al inicio de sesión</a>
    </div>
</div>
</body>
</html>
