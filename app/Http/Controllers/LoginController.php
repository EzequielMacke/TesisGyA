<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function registrar(Request $request)
    {
        $request->merge([
            'ci' => str_replace('.', '', $request->ci)
        ]);
        $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|string',
            'ci' => 'required|integer|unique:persona,ci',
            'direccion' => 'required|string',
            'telefono' => 'required|string',
            'usuario' => 'required|string|unique:users,usuario',
            'email' => 'required|email|unique:users,email',
            'contraseña' => 'required|string|min:16',
            'repetir_contraseña' => 'required|same:contraseña',
        ]);

        $persona = Persona::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'ci' => $request->ci,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'genero' => $request->genero,
            'estado_id' => 1,
        ]);

        $codigo = Str::random(6);

        $user = User::create([
            'persona_id' => $persona->id,
            'usuario' => $request->usuario,
            'email' => $request->email,
            'contraseña' => bcrypt($request->contraseña),
            'verificado' => 0,
            'acceso_intento' => 0,
            'estado_id' => 1,
            'sucursal_id' => 1,
            'empleado_id' => null,
            'codigo_verificacion' => $codigo,
        ]);

        Mail::raw("Tu código de verificación es: $codigo", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Verificación de cuenta');
        });

        // Redireccionar a la vista de confirmación y pasar el email
        return redirect()->route('confirmar.cuenta', ['email' => $user->email]);
    }

    // Método para mostrar la vista de confirmación
    public function confirmarCuenta(Request $request)
    {
        $email = $request->email;
        return view('login.confirmar', compact('email'));
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string', // Puede ser email o usuario
            'password' => 'required|string',
        ]);

        // Buscar usuario por email o usuario
        $user = User::where('email', $request->email)
            ->orWhere('usuario', $request->email)
            ->first();

        if (!$user) {
            return back()->with('error', 'No existe un usuario registrado con ese usuario o correo electrónico.');
        }

        // Bloqueo por intentos antes de verificar contraseña
        if ($user->acceso_intento > 5) {
            return back()->with('error', 'Has superado el número máximo de intentos. Tu cuenta está bloqueada temporalmente.');
        }

        // Verificar si la contraseña es correcta
        if (!password_verify($request->password, $user->contraseña)) {
            $user->acceso_intento += 1;
            $user->save();
            $intentos_restantes = max(0, 5 - $user->acceso_intento);

            if ($user->acceso_intento > 5) {
                return back()->with('error', 'Has superado el número máximo de intentos. Tu cuenta está bloqueada temporalmente.');
            }

            return back()->with('error', 'Credenciales incorrectas. Intentos restantes: ' . $intentos_restantes);
        }

        // Si el usuario no está verificado, enviar código de verificación solo si no superó los intentos
        if ($user->verificado == 0) {
            $codigo = Str::random(6);
            $user->codigo_verificacion = $codigo;
            $user->save();

            Mail::raw("Tu código de verificación es: $codigo", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Verificación de cuenta');
            });

            return redirect()->route('confirmar.cuenta', ['email' => $user->email])
                ->with('success', 'Se ha enviado un nuevo código de verificación a tu correo.');
        }

        // Si el usuario está verificado, enviar código de doble factor y guardar en 'codigo_autenticacion'
        $codigo2fa = Str::random(6);
        $user->codigo_autenticacion = $codigo2fa;
        $user->save();

        Mail::raw("Tu código de autenticación de doble factor es: $codigo2fa", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Código de Doble Factor');
        });

        // Redirigir a la página de verificación de doble factor
        return redirect()->route('verificacion.2fa', ['email' => $user->email])
            ->with('success', 'Se ha enviado un código de autenticación de doble factor a tu correo.');
    }

    public function reenviarCodigo(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $codigo = Str::random(6);
            $user->codigo_verificacion = $codigo;
            $user->save();

            Mail::raw("Tu nuevo código de verificación es: $codigo", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Nuevo código de verificación');
            });

            return redirect()->route('verificacion.2fa', ['email' => $user->email])
                ->with('success', 'Se ha enviado un nuevo código de verificación a tu correo.');
        }
        return back()->with('error', 'No se encontró el usuario.');
    }

    public function verificarCodigo(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'codigo_verificacion' => 'nullable|string',
            'codigo_autenticacion' => 'nullable|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'No se encontró el usuario.');
        }

        // Si el usuario NO está verificado, validar el código de verificación
        if ($user->verificado == 0) {
            if ($request->filled('codigo_verificacion') && $user->codigo_verificacion === $request->codigo_verificacion) {
                $user->verificado = 1;
                $user->save();
                return redirect()->route('login')->with('success', 'Cuenta verificada correctamente.');
            } else {
                return back()->with('error', 'El código de verificación es incorrecto.');
            }
        }

        // Si el usuario está verificado, validar el código de autenticación
        if ($user->verificado == 1) {
            if ($request->filled('codigo_autenticacion') && $user->codigo_autenticacion === $request->codigo_autenticacion) {
                $user->acceso_intento = 0; // Reiniciar intentos al ingresar correctamente
                $user->save();
                session(['user_id' => $user->id]);
                return redirect()->route('menu.index')->with('success', 'Bienvenido al sistema.');
            } else {
                $user->save();
                return back()->with('error', 'El código de autenticación es incorrecto.');
            }
        }

        return back()->with('error', 'Debe ingresar un código.');
    }

    public function recuperarContraseña(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'No se encontró un usuario con ese correo.');
        }

        $nuevaContraseña = Str::random(10);
        $user->contraseña = bcrypt($nuevaContraseña);
        $user->save();

        Mail::raw("Tu nueva contraseña temporal es: $nuevaContraseña\nPor favor actualiza tu contraseña desde las configuraciones de usuario.", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Recuperación de contraseña');
        });

        return redirect()->route('login')->with('success', 'Contraseña recuperada. Por favor actualiza tu contraseña desde las configuraciones de usuario.');
    }

}
