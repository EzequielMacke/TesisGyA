<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Usuario</title>
    @include('partials.head')
    <style>
        .valid { color: green; }
        .invalid { color: red; }
    </style>
</head>
<body>
<div class="container" style="max-width: 500px; margin-top: 40px;">
    <h2 class="mb-4 text-center">Registrar Usuario</h2>
    <form method="POST" action="{{ route('register.store') }}" id="registerForm">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <fieldset class="border p-3 mb-4">
            <legend class="w-auto px-2">Datos Personales</legend>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombres <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellidos <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="apellido" name="apellido" required>
            </div>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
            </div>
            <div class="mb-3">
                <label for="genero" class="form-label">Género <span class="text-danger">*</span></label>
                <select class="form-control" id="genero" name="genero" required>
                    <option value="">Seleccionar</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="pais" class="form-label">País <span class="text-danger">*</span></label>
                <select class="form-control" id="pais" name="pais" required>
                    <option value="" data-ext="">Seleccionar</option>
                    <option value="Paraguay" data-ext="+595">Paraguay (+595)</option>
                    <option value="Argentina" data-ext="+54">Argentina (+54)</option>
                    <option value="Brasil" data-ext="+55">Brasil (+55)</option>
                    <!-- Agrega más países si lo necesitas -->
                </select>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text" id="extTel">+595</span>
                    <input type="text" class="form-control" id="telefono" name="telefono" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="ci" class="form-label">Cédula de Identidad <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="ci" name="ci" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="direccion" name="direccion" required>
            </div>
        </fieldset>

        <fieldset class="border p-3 mb-4">
            <legend class="w-auto px-2">Datos de Usuario</legend>
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="usuario" name="usuario" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="contraseña" class="form-label">Contraseña <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" class="form-control" id="contraseña" name="contraseña" required>
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                        <span id="eyeIcon">👁️</span>
                    </button>
                </div>
                <ul class="mt-2" id="passwordRules">
                    <li id="ruleMayus" class="invalid">Una mayúscula</li>
                    <li id="ruleMinus" class="invalid">Una minúscula</li>
                    <li id="ruleNum" class="invalid">Un número</li>
                    <li id="ruleChar" class="invalid">Un carácter especial</li>
                    <li id="ruleLength" class="invalid">Mínimo 16 caracteres</li>
                </ul>
            </div>
            <div class="mb-3">
                <label for="repetir_contraseña" class="form-label">Repetir contraseña <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="repetir_contraseña" name="repetir_contraseña" required>
                <div id="matchPassword" class="mt-1"></div>
            </div>
        </fieldset>

        <button type="submit" class="btn btn-success w-100">Registrar</button>
    </form>
    <div class="mt-3 text-center">
        <a href="{{ route('login') }}">¿Ya tienes cuenta? Iniciar sesión</a>
    </div>
</div>
<script>
    // País y extensión telefónica
    document.getElementById('pais').addEventListener('change', function() {
        var ext = this.options[this.selectedIndex].getAttribute('data-ext') || '';
        document.getElementById('extTel').textContent = ext;
    });

    // Formateo de CI con separador de miles
    document.getElementById('ci').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        e.target.value = value;
    });

    // Validación de contraseña
    const passwordInput = document.getElementById('contraseña');
    const rules = {
        mayus: /[A-Z]/,
        minus: /[a-z]/,
        num: /[0-9]/,
        char: /[^A-Za-z0-9]/,
        length: /.{16,}/
    };
    passwordInput.addEventListener('input', function() {
        const val = passwordInput.value;
        document.getElementById('ruleMayus').className = rules.mayus.test(val) ? 'valid' : 'invalid';
        document.getElementById('ruleMinus').className = rules.minus.test(val) ? 'valid' : 'invalid';
        document.getElementById('ruleNum').className = rules.num.test(val) ? 'valid' : 'invalid';
        document.getElementById('ruleChar').className = rules.char.test(val) ? 'valid' : 'invalid';
        document.getElementById('ruleLength').className = rules.length.test(val) ? 'valid' : 'invalid';
    });

    // Mostrar/ocultar contraseña
    document.getElementById('togglePassword').addEventListener('click', function() {
        const input = document.getElementById('contraseña');
        if (input.type === "password") {
            input.type = "text";
            document.getElementById('eyeIcon').textContent = "🙈";
        } else {
            input.type = "password";
            document.getElementById('eyeIcon').textContent = "👁️";
        }
    });

    // Verificar que las contraseñas coincidan
    const repetirInput = document.getElementById('repetir_contraseña');
    function checkMatch() {
        const msg = document.getElementById('matchPassword');
        if (repetirInput.value === passwordInput.value && repetirInput.value.length > 0) {
            msg.textContent = "Las contraseñas coinciden";
            msg.className = "valid";
        } else {
            msg.textContent = "Las contraseñas no coinciden";
            msg.className = "invalid";
        }
    }
    repetirInput.addEventListener('input', checkMatch);
    passwordInput.addEventListener('input', checkMatch);
</script>
</body>
</html>
