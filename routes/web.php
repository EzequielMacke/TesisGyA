<?php

use App\Http\Controllers\CompraController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\NotaRemisionCompraController;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\PedidoCompraController;
use App\Http\Controllers\PresupuestoCompraAprobadoController;
use App\Http\Controllers\PresupuestoCompraController;
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

Route::get('/espera', [LoginController::class, 'mostrarEspera'])->name('espera');
Route::get('/verificar-cargo', [LoginController::class, 'verificarCargo'])->name('verificar.cargo');
Route::get('/datos-proveedor', [LoginController::class, 'mostrarDatosProveedor'])->name('datos.proveedor');
Route::post('/datos-proveedor', [LoginController::class, 'guardarDatosProveedor'])->name('guardar.datos.proveedor');

// Rutas para Marca
Route::get('/marca', [MarcaController::class, 'index'])->name('marca.index');
Route::get('/marca/create', [MarcaController::class, 'create'])->name('marca.create');
Route::post('/marca', [MarcaController::class, 'store'])->name('marca.store');
Route::delete('/marca/{id}', [MarcaController::class, 'destroy'])->name('marca.destroy');
Route::patch('/marca/{id}/activate', [MarcaController::class, 'activate'])->name('marca.activate');

// Rutas de insumos
Route::get('/insumos', [InsumoController::class, 'index'])->name('insumo.index');
Route::get('/insumos/create', [InsumoController::class, 'create'])->name('insumo.create');
Route::post('/insumos', [InsumoController::class, 'store'])->name('insumo.store');
Route::delete('/insumos/{id}', [InsumoController::class, 'destroy'])->name('insumo.destroy');
Route::patch('/insumos/{id}/activate', [InsumoController::class, 'activate'])->name('insumo.activate');

// Rutas de inventario
Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');

// Rutas para Pedidos de Compra
Route::get('/pedido-compra', [PedidoCompraController::class, 'index'])->name('pedido_compra.index');
Route::get('/pedido-compra/crear', [PedidoCompraController::class, 'create'])->name('pedido_compra.create');
Route::post('/pedido-compra', [PedidoCompraController::class, 'store'])->name('pedido_compra.store');
Route::get('/pedido-compra/{id}', [PedidoCompraController::class, 'show'])->name('pedido_compra.show');
Route::get('/pedido-compra/{id}/editar', [PedidoCompraController::class, 'edit'])->name('pedido_compra.edit');
Route::put('/pedido-compra/{id}', [PedidoCompraController::class, 'update'])->name('pedido_compra.update');
Route::patch('pedido_compra/{id}/anular', [PedidoCompraController::class, 'anular'])->name('pedido_compra.anular');

// Rutas para presupuestos de compra (proveedores)
Route::get('/presupuesto-compra', [PresupuestoCompraController::class, 'index'])->name('presupuesto_compra.index');
Route::get('/presupuesto-compra/pedido/{pedido}', [PresupuestoCompraController::class, 'showPedido'])->name('presupuesto_compra.show_pedido');
Route::get('/presupuesto-compra/crear/{pedido}', [PresupuestoCompraController::class, 'create'])->name('presupuesto_compra.create');
Route::post('/presupuesto-compra', [PresupuestoCompraController::class, 'store'])->name('presupuesto_compra.store');
Route::get('/presupuesto-compra/numero-siguiente/{pedido}/{proveedor}', [PresupuestoCompraController::class, 'obtenerSiguienteNumero'])->name('presupuesto_compra.siguiente_numero');
Route::get('/presupuesto-compra/{id}', [PresupuestoCompraController::class, 'show'])->name('presupuesto_compra.show');

// Rutas para presupuestos de compra aprobados (administración)
Route::get('/presupuesto-compra-aprobado', [PresupuestoCompraAprobadoController::class, 'index'])->name('presupuesto_compra_aprobado.index');
Route::get('/presupuesto-compra-aprobado/create', [PresupuestoCompraAprobadoController::class, 'create'])->name('presupuesto_compra_aprobado.create');
Route::get('/presupuesto-compra-aprobado/presupuestos/{pedido_id}', [PresupuestoCompraAprobadoController::class, 'getPresupuestosPedido'])->name('presupuesto_compra_aprobado.presupuestos');
Route::post('/presupuesto-compra-aprobado/aprobar', [PresupuestoCompraAprobadoController::class, 'store'])->name('presupuesto_compra_aprobado.store');
Route::get('/pedido-compra/detalle/{pedido_id}', [PresupuestoCompraAprobadoController::class, 'getDetallePedido'])->name('pedido_compra.detalle');
Route::get('/presupuesto-compra-aprobado/{id}', [PresupuestoCompraAprobadoController::class, 'show'])->name('presupuesto_compra_aprobado.show');

// Rutas para órdenes de compra
Route::get('/orden-compra', [OrdenCompraController::class, 'index'])->name('orden_compra.index');
Route::get('/orden-compra/create', [OrdenCompraController::class, 'create'])->name('orden_compra.create');
Route::post('/orden-compra', [OrdenCompraController::class, 'store'])->name('orden_compra.store');
Route::get('/orden-compra/presupuesto-detalle/{id}', [OrdenCompraController::class, 'getPresupuestoDetalle'])->name('orden_compra.presupuesto_detalle');
Route::get('orden-compra/{ordenCompra}', [OrdenCompraController::class, 'show'])->name('orden_compra.show');

// Rutas para notas de remisión de compra
Route::get('nota-remision-compra', [NotaRemisionCompraController::class, 'index'])->name('nota_remision_compra.index');
Route::get('nota-remision-compra/create', [NotaRemisionCompraController::class, 'create'])->name('nota_remision_compra.create');
Route::post('nota-remision-compra', [NotaRemisionCompraController::class, 'store'])->name('nota_remision_compra.store');
Route::get('nota-remision-compra/{id}', [NotaRemisionCompraController::class, 'show'])->name('nota_remision_compra.show');
Route::get('api/orden-compra/{orden}/detalles-pendientes', [NotaRemisionCompraController::class, 'detallesPendientes']);

// Rutas para compras (facturas de proveedor)
Route::get('/compras', [CompraController::class, 'index'])->name('compras.index');
Route::get('/compras/create/{orden_id?}', [CompraController::class, 'create'])->name('compras.create');
Route::post('/compras', [CompraController::class, 'store'])->name('compras.store');
