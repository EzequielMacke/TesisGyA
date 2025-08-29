<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar cuenta</title>
    @include('partials.head')
</head>
<body>
<div class="container" style="max-width: 400px; margin-top: 50px;">
    <h2 class="mb-4 text-center">Verificación de cuenta</h2>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="mb-3 text-center">
        <p>Se ha enviado un código de verificación al correo:</p>
        <strong>{{ $email }}</strong>
        <p>Pega aquí el código de verificación recibido:</p>
    </div>
    <form method="POST" action="{{ route('verificar.codigo') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <div class="mb-3">
            <input type="text" class="form-control" name="codigo_verificacion" placeholder="Código de verificación" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Confirmar</button>
    </form>
</div>
</body>
</html>
