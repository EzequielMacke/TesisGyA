<?php

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    session()->forget('user_id'); // Cierra la sesión si existe
    return view('login.index');
})->name('login');

Route::get('/register', function () {
    return view('login.registrar');
})->name('register');


//Rutas para el registro
Route::post('/register', [LoginController::class, 'registrar'])->name('register.store');
Route::post('/register', [LoginController::class, 'registrar'])->name('register.store');
Route::get('/confirmar-cuenta', [LoginController::class, 'confirmarCuenta'])->name('confirmar.cuenta');
Route::post('/verificar-codigo', [LoginController::class, 'verificarCodigo'])->name('verificar.codigo');
Route::post('/login', [LoginController::class, 'login'])->name('login.store');

Route::get('/verificacion-2fa', function (Request $request) {
    $email = $request->email;
    return view('login.verificacion', compact('email'));
})->name('verificacion.2fa');

Route::get('/reenviar-codigo', function (Request $request) {
    $email = $request->email;
    // Puedes llamar aquí al método del controlador si lo prefieres
    return app(LoginController::class)->reenviarCodigo($request);
})->name('reenviar.codigo');

Route::get('/menu', function () {
    if (!session()->has('user_id')) {
        return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder al menú.');
    }
    return view('menu.index');
})->name('menu.index');

Route::post('/recuperar-contraseña', [LoginController::class, 'recuperarContraseña'])->name('recuperar.contraseña');

Route::get('/recuperar-contraseña', function() {
    return view('login.recuperar_contraseña');
})->name('recuperar.contraseña');

Route::get('/logout', function () {
    session()->forget('user_id');
    return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
})->name('logout');
