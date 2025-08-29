<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación de Doble Factor</title>
    @include('partials.head')
</head>
<body>
<div class="container" style="max-width: 400px; margin-top: 50px;">
    <div class="text-center mb-4">
        <h2>Verificación de Doble Factor</h2>
    </div>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-body">
            <p>Se ha enviado un código de verificación al correo:</p>
            <strong>{{ $email }}</strong>
            <form method="POST" action="{{ route('verificar.codigo') }}">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <div class="mb-3">
                    <input type="text" class="form-control text-center" name="codigo_autenticacion" maxlength="6" placeholder="Código de 6 dígitos" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Verificar</button>
            </form>
            <div class="mt-3 text-center">
                <a href="{{ route('reenviar.codigo', ['email' => $email]) }}" class="btn btn-link">Reenviar código</a>
            </div>
        </div>
    </div>
    <div class="mt-3 text-center">
        <a href="{{ route('login') }}" class="text-muted">Volver al inicio de sesión</a>
    </div>
</div>
</body>
</html>
