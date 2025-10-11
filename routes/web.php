<?php

use App\Http\Controllers\CompraController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\NotaRemisionCompraController;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\PedidoCompraController;
use App\Http\Controllers\PresupuestoCompraAprobadoController;
use App\Http\Controllers\PresupuestoCompraController;
use App\Http\Controllers\PresupuestoServicioController;
use App\Http\Controllers\SolicitudServicioController;
use App\Http\Controllers\VisitaPreviaController;
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


// Rutas para solicitud de servicio
Route::get('solicitud_servicio', [SolicitudServicioController::class, 'index'])->name('solicitud_servicio.index');
Route::get('solicitud_servicio/create', [SolicitudServicioController::class, 'create'])->name('solicitud_servicio.create');
Route::post('solicitud_servicio', [SolicitudServicioController::class, 'store'])->name('solicitud_servicio.store');
Route::get('solicitud_servicio/{id}', [SolicitudServicioController::class, 'show'])->name('solicitud_servicio.show');
Route::get('api/obras/{cliente_id}', [SolicitudServicioController::class, 'apiObras']);
Route::get('api/obra/{obra_id}', [SolicitudServicioController::class, 'apiObraInfo']);


// Rutas para visita previa
Route::get('visita_previa', [VisitaPreviaController::class, 'index'])->name('visita_previa.index');
Route::get('visita_previa/create', [VisitaPreviaController::class, 'create'])->name('visita_previa.create');
Route::post('visita_previa', [VisitaPreviaController::class, 'store'])->name('visita_previa.store');
Route::get('visita_previa/{id}', [VisitaPreviaController::class, 'show'])->name('visita_previa.show');
Route::get('/ajax/obras/{clienteId}', [VisitaPreviaController::class, 'ajaxObras']);
Route::get('/ajax/solicitudes/{obraId}', [VisitaPreviaController::class, 'ajaxSolicitudes']);
Route::get('/ajax/solicitud/{id}', [VisitaPreviaController::class, 'ajaxSolicitud']);
Route::post('/ajax/ensayos-por-solicitud', [VisitaPreviaController::class, 'ajaxEnsayosPorSolicitud']);


//Rutas para presupuesto servicios
Route::get('presupuesto_servicio', [PresupuestoServicioController::class, 'index'])->name('presupuesto_servicio.index');
Route::get('presupuesto_servicio/create', [PresupuestoServicioController::class, 'create'])->name('presupuesto_servicio.create');
Route::post('presupuesto_servicio', [PresupuestoServicioController::class, 'store'])->name('presupuesto_servicio.store');
Route::get('presupuesto_servicio/{id}', [PresupuestoServicioController::class, 'show'])->name('presupuesto_servicio.show');
Route::get('presupuesto_servicio/{id}/edit', [PresupuestoServicioController::class, 'edit'])->name('presupuesto_servicio.edit');
Route::put('presupuesto_servicio/{id}', [PresupuestoServicioController::class, 'update'])->name('presupuesto_servicio.update');
Route::delete('presupuesto_servicio/{id}', [PresupuestoServicioController::class, 'destroy'])->name('presupuesto_servicio.destroy');
Route::get('ajax/obras/{clienteId}', [PresupuestoServicioController::class, 'ajaxObras']);
Route::get('ajax/visitas-previas/{obraId}', [PresupuestoServicioController::class, 'ajaxVisitasPrevias']);
Route::get('ajax/visita-previa/{id}', [PresupuestoServicioController::class, 'ajaxVisitaPrevia']);
Route::get('ajax/ensayos-por-visita/{visitaId}', [PresupuestoServicioController::class, 'ajaxEnsayosPorVisita']);


// Rutas para contratos
Route::get('/contrato', [ContratoController::class, 'index'])->name('contrato.index');
Route::get('/contrato/create', [ContratoController::class, 'create'])->name('contrato.create');
Route::get('/contrato/{id}', [ContratoController::class, 'show'])->name('contrato.show');
Route::post('/contrato', [ContratoController::class, 'store'])->name('contrato.store');
Route::get('/obras-por-cliente/{cliente_id}', [ContratoController::class, 'obrasPorCliente'])->name('obras.por.cliente');
Route::get('/presupuestos-por-obra/{obra_id}', [ContratoController::class, 'presupuestosPorObra'])->name('presupuestos.por.obra');
