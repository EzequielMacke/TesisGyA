<?php

use App\Http\Controllers\AjusteStockController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\InformeController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\InsumoUtilizadoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\MovimientoMaterialController;
use App\Http\Controllers\NotaCompraController;
use App\Http\Controllers\NotaRemisionCompraController;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\OrdenServicioController;
use App\Http\Controllers\PedidoCompraController;
use App\Http\Controllers\PresupuestoCompraAprobadoController;
use App\Http\Controllers\PresupuestoCompraController;
use App\Http\Controllers\PresupuestoServicioController;
use App\Http\Controllers\ReclamoController;
use App\Http\Controllers\ServicioRealizadoController;
use App\Http\Controllers\SolicitudMaterialController;
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

Route::get('/manual-usuario', function () {
    return view('manual_usuario.manual_user');
})->name('manual_usuario.index');

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
Route::put('/marca/{id}', [MarcaController::class, 'update'])->name('marca.update');
Route::delete('/marca/{id}', [MarcaController::class, 'destroy'])->name('marca.destroy');
Route::patch('/marca/{id}/activate', [MarcaController::class, 'activate'])->name('marca.activate');

// Rutas de insumos
Route::get('/insumos', [InsumoController::class, 'index'])->name('insumo.index');
Route::get('/insumos/create', [InsumoController::class, 'create'])->name('insumo.create');
Route::post('/insumos', [InsumoController::class, 'store'])->name('insumo.store');
Route::put('/insumos/{id}', [InsumoController::class, 'update'])->name('insumo.update');
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

// Rutas para informes
Route::get('/informes', [InformeController::class, 'index'])->name('informes.index');
Route::get('/informes/presupuestos-compra', [InformeController::class, 'presupuestosCompraForm'])->name('informes.presupuestos_compra.form');
Route::get('/informes/presupuestos-compra/pdf', [InformeController::class, 'presupuestosCompraPdf'])->name('informes.presupuestos_compra.pdf');
Route::get('/informes/cuentas-pagar', [InformeController::class, 'cuentasPagarForm'])->name('informes.cuentas_pagar.form');
Route::get('/informes/cuentas-pagar/pdf', [InformeController::class, 'cuentasPagarPdf'])->name('informes.cuentas_pagar.pdf');
Route::get('/informes/compras', [InformeController::class, 'comprasForm'])->name('informes.compras.form');
Route::get('/informes/compras/pdf', [InformeController::class, 'comprasPdf'])->name('informes.compras.pdf');
Route::get('/informes/libro-compras', [InformeController::class, 'libroComprasForm'])->name('informes.libro_compras.form');
Route::get('/informes/libro-compras/pdf', [InformeController::class, 'libroComprasPdf'])->name('informes.libro_compras.pdf');
Route::get('/informes/pedidos-compra', [InformeController::class, 'pedidosCompraForm'])->name('informes.pedidos_compra.form');
Route::get('/informes/pedidos-compra/pdf', [InformeController::class, 'pedidosCompraPdf'])->name('informes.pedidos_compra.pdf');
Route::get('/informes/inventario', [InformeController::class, 'inventarioForm'])->name('informes.inventario.form');
Route::get('/informes/inventario/pdf', [InformeController::class, 'inventarioPdf'])->name('informes.inventario.pdf');
Route::get('/informes/presupuestos-servicio', [InformeController::class, 'presupuestosServicioForm'])->name('informes.presupuestos_servicio.form');
Route::get('/informes/presupuestos-servicio/pdf', [InformeController::class, 'presupuestosServicioPdf'])->name('informes.presupuestos_servicio.pdf');
Route::get('/informes/reclamos', [InformeController::class, 'reclamosForm'])->name('informes.reclamos.form');
Route::get('/informes/reclamos/pdf', [InformeController::class, 'reclamosPdf'])->name('informes.reclamos.pdf');
Route::get('/informes/insumos-utilizados', [InformeController::class, 'insumosUtilizadosForm'])->name('informes.insumos_utilizados.form');
Route::get('/informes/insumos-utilizados/pdf', [InformeController::class, 'insumosUtilizadosPdf'])->name('informes.insumos_utilizados.pdf');
Route::get('/informes/orden-servicio', [InformeController::class, 'ordenServicioForm'])->name('informes.orden_servicio.form');
Route::get('/informes/orden-servicio/pdf', [InformeController::class, 'ordenServicioPdf'])->name('informes.orden_servicio.pdf');

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

// Rutas para notas de compra
Route::get('/notas-compra', [NotaCompraController::class, 'index'])->name('notas_compra.index');
Route::get('/notas-compra/create', [NotaCompraController::class, 'create'])->name('notas_compra.create');
Route::post('/notas-compra', [NotaCompraController::class, 'store'])->name('notas_compra.store');
Route::get('/notas-compra/facturas/{proveedor_id}', [NotaCompraController::class, 'facturasPorProveedor'])->name('notas_compra.facturas');
Route::get('/notas-compra/{id}/edit', [NotaCompraController::class, 'edit'])->name('notas_compra.edit');
Route::put('/notas-compra/{id}', [NotaCompraController::class, 'update'])->name('notas_compra.update');
Route::post('/notas-compra/{id}/aprobar', [NotaCompraController::class, 'aprobar'])->name('notas_compra.aprobar');
Route::post('/notas-compra/{id}/anular', [NotaCompraController::class, 'anular'])->name('notas_compra.anular');

// Rutas para compras (facturas de proveedor)
Route::get('/compras', [CompraController::class, 'index'])->name('compras.index');
Route::get('/compras/create/{orden_id?}', [CompraController::class, 'create'])->name('compras.create');
Route::post('/compras', [CompraController::class, 'store'])->name('compras.store');
Route::get('/compras/{id}/edit', [CompraController::class, 'edit'])->name('compras.edit');
Route::put('/compras/{id}', [CompraController::class, 'update'])->name('compras.update');
Route::post('/compras/{id}/aprobar', [CompraController::class, 'aprobar'])->name('compras.aprobar');
Route::post('/compras/{id}/anular', [CompraController::class, 'anular'])->name('compras.anular');


// Rutas para solicitud de servicio
Route::get('solicitud_servicio', [SolicitudServicioController::class, 'index'])->name('solicitud_servicio.index');
Route::get('solicitud_servicio/create', [SolicitudServicioController::class, 'create'])->name('solicitud_servicio.create');
Route::post('solicitud_servicio', [SolicitudServicioController::class, 'store'])->name('solicitud_servicio.store');
Route::get('solicitud_servicio/{id}/edit', [SolicitudServicioController::class, 'edit'])->name('solicitud_servicio.edit');
Route::put('solicitud_servicio/{id}', [SolicitudServicioController::class, 'update'])->name('solicitud_servicio.update');
Route::post('solicitud_servicio/{id}/anular', [SolicitudServicioController::class, 'anular'])->name('solicitud_servicio.anular');
Route::get('api/obras/{cliente_id}', [SolicitudServicioController::class, 'apiObras']);
Route::get('api/obra/{obra_id}', [SolicitudServicioController::class, 'apiObraInfo']);


// Rutas para solicitud de materiales
Route::get('solicitud_materiales', [SolicitudMaterialController::class, 'index'])->name('solicitud_materiales.index');
Route::get('solicitud_materiales/create', [SolicitudMaterialController::class, 'create'])->name('solicitud_materiales.create');
Route::post('solicitud_materiales', [SolicitudMaterialController::class, 'store'])->name('solicitud_materiales.store');
Route::get('solicitud_materiales/{id}/edit', [SolicitudMaterialController::class, 'edit'])->name('solicitud_materiales.edit');
Route::put('solicitud_materiales/{id}', [SolicitudMaterialController::class, 'update'])->name('solicitud_materiales.update');
Route::patch('solicitud_materiales/{id}/anular', [SolicitudMaterialController::class, 'anular'])->name('solicitud_materiales.anular');


// Rutas para movimiento de insumos
Route::get('movimiento_insumos', [MovimientoMaterialController::class, 'index'])->name('movimiento_insumos.index');
Route::get('movimiento_insumos/create', [MovimientoMaterialController::class, 'create'])->name('movimiento_insumos.create');
Route::post('movimiento_insumos', [MovimientoMaterialController::class, 'store'])->name('movimiento_insumos.store');
Route::get('movimiento_insumos/{id}/remision', [MovimientoMaterialController::class, 'remision'])->name('movimiento_insumos.remision');
Route::patch('movimiento_insumos/{id}/confirmar', [MovimientoMaterialController::class, 'confirmar'])->name('movimiento_insumos.confirmar');
Route::patch('movimiento_insumos/{id}/anular', [MovimientoMaterialController::class, 'anular'])->name('movimiento_insumos.anular');
Route::get('api/movimiento-insumos/solicitud/{id}', [MovimientoMaterialController::class, 'solicitudInfo']);
Route::get('api/movimiento-insumos/inventario/{depositoId}', [MovimientoMaterialController::class, 'inventarioDeposito']);


// Rutas para ajuste de stock
Route::get('ajuste_stocks', [AjusteStockController::class, 'index'])->name('ajuste_stocks.index');
Route::get('ajuste_stocks/create', [AjusteStockController::class, 'create'])->name('ajuste_stocks.create');
Route::post('ajuste_stocks', [AjusteStockController::class, 'store'])->name('ajuste_stocks.store');
Route::get('ajuste_stocks/{id}/edit', [AjusteStockController::class, 'edit'])->name('ajuste_stocks.edit');
Route::patch('ajuste_stocks/{id}', [AjusteStockController::class, 'update'])->name('ajuste_stocks.update');
Route::patch('ajuste_stocks/{id}/confirmar', [AjusteStockController::class, 'confirmar'])->name('ajuste_stocks.confirmar');
Route::patch('ajuste_stocks/{id}/anular', [AjusteStockController::class, 'anular'])->name('ajuste_stocks.anular');
Route::get('api/ajuste-stocks/obras/{clienteId}', [AjusteStockController::class, 'apiObrasPorCliente']);
Route::get('api/ajuste-stocks/inventario-deposito/{depositoId}', [AjusteStockController::class, 'apiInventarioDeposito']);
Route::get('api/ajuste-stocks/inventario-obra/{obraId}', [AjusteStockController::class, 'apiInventarioObra']);


// Rutas para visita previa
Route::get('visita_previa', [VisitaPreviaController::class, 'index'])->name('visita_previa.index');
Route::get('visita_previa/create', [VisitaPreviaController::class, 'create'])->name('visita_previa.create');
Route::post('visita_previa', [VisitaPreviaController::class, 'store'])->name('visita_previa.store');
Route::get('visita_previa/{id}/edit', [VisitaPreviaController::class, 'edit'])->name('visita_previa.edit');
Route::put('visita_previa/{id}', [VisitaPreviaController::class, 'update'])->name('visita_previa.update');
Route::post('visita_previa/{id}/anular', [VisitaPreviaController::class, 'anular'])->name('visita_previa.anular');
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
Route::post('presupuesto_servicio/{id}/anular', [PresupuestoServicioController::class, 'anular'])->name('presupuesto_servicio.anular');
Route::get('ajax/presupuesto/obras/{clienteId}', [PresupuestoServicioController::class, 'ajaxObras']);
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

// Rutas para orden de servicio
Route::get('/orden_servicio', [OrdenServicioController::class, 'index'])->name('orden_servicio.index');
Route::get('/orden_servicio/create', [OrdenServicioController::class, 'create'])->name('orden_servicio.create');
Route::post('/orden_servicio', [OrdenServicioController::class, 'store'])->name('orden_servicio.store');
Route::get('/orden_servicio/{id}/edit', [OrdenServicioController::class, 'edit'])->name('orden_servicio.edit');
Route::put('/orden_servicio/{id}', [OrdenServicioController::class, 'update'])->name('orden_servicio.update');
Route::put('/orden_servicio/{id}/anular', [OrdenServicioController::class, 'anular'])->name('orden_servicio.anular');
Route::get('/obras-con-contrato/{cliente_id}', [OrdenServicioController::class, 'obrasPorCliente'])->name('obras.con.contrato');
Route::get('/contratos-por-obra/{obra_id}', [OrdenServicioController::class, 'contratosPorObra'])->name('contratos.por.obra');
Route::get('/ensayos-por-presupuesto/{presupuesto_servicio_id}', [OrdenServicioController::class, 'ensayosPorPresupuesto'])->name('ensayos.por.presupuesto');

// Rutas para insumos utilizados
Route::get('/insumos_utilizados', [InsumoUtilizadoController::class, 'index'])->name('insumos_utilizados.index');
Route::get('/insumos_utilizados/create', [InsumoUtilizadoController::class, 'create'])->name('insumos_utilizados.create');
Route::post('/insumos_utilizados', [InsumoUtilizadoController::class, 'store'])->name('insumos_utilizados.store');
Route::get('/insumos_utilizados/{id}/edit', [InsumoUtilizadoController::class, 'edit'])->name('insumos_utilizados.edit');
Route::put('/insumos_utilizados/{id}', [InsumoUtilizadoController::class, 'update'])->name('insumos_utilizados.update');
Route::patch('/insumos_utilizados/{id}/confirmar', [InsumoUtilizadoController::class, 'confirmar'])->name('insumos_utilizados.confirmar');
Route::patch('/insumos_utilizados/{id}/anular', [InsumoUtilizadoController::class, 'anular'])->name('insumos_utilizados.anular');
Route::get('/insumos-por-obra/{obra_id}', [InsumoUtilizadoController::class, 'insumosPorObra'])->name('insumos.por.obra');

// Rutas para servicios realizados
Route::get('/servicio_realizado', [ServicioRealizadoController::class, 'index'])->name('servicio_realizado.index');
Route::get('/servicio_realizado/create', [ServicioRealizadoController::class, 'create'])->name('servicio_realizado.create');
Route::post('/servicio_realizado', [ServicioRealizadoController::class, 'store'])->name('servicio_realizado.store');
Route::get('/servicio_realizado/{id}/edit', [ServicioRealizadoController::class, 'edit'])->name('servicio_realizado.edit');
Route::put('/servicio_realizado/{id}', [ServicioRealizadoController::class, 'update'])->name('servicio_realizado.update');
Route::post('/servicio_realizado/{id}/anular', [ServicioRealizadoController::class, 'anular'])->name('servicio_realizado.anular');
Route::patch('/servicio_realizado/{id}/confirmar', [ServicioRealizadoController::class, 'confirmar'])->name('servicio_realizado.confirmar');
Route::get('/servicio_realizado/{id}/pdf', [ServicioRealizadoController::class, 'pdf'])->name('servicio_realizado.pdf');
Route::get('/servicio_realizado/cliente-info/{cliente_id}', [ServicioRealizadoController::class, 'clienteInfo'])->name('servicio_realizado.cliente.info');
Route::get('/servicio_realizado/obra-info/{obra_id}', [ServicioRealizadoController::class, 'obraInfo'])->name('servicio_realizado.obra.info');
Route::get('/servicio_realizado/obras-por-cliente/{cliente_id}', [ServicioRealizadoController::class, 'obrasPorCliente'])->name('servicio_realizado.obras.por.cliente');
Route::get('/servicio_realizado/ordenes-por-obra/{obra_id}', [ServicioRealizadoController::class, 'ordenesPorObra'])->name('servicio_realizado.ordenes.por.obra');
Route::get('/servicio_realizado/datos-por-orden/{orden_servicio_id}', [ServicioRealizadoController::class, 'datosPorOrden'])->name('servicio_realizado.datos.por.orden');

// Rutas para reclamos
Route::get('/reclamos', [ReclamoController::class, 'index'])->name('reclamos.index');
Route::get('/reclamos/create', [ReclamoController::class, 'create'])->name('reclamos.create');
Route::post('/reclamos', [ReclamoController::class, 'store'])->name('reclamos.store');
Route::get('/reclamos/{id}', [ReclamoController::class, 'show'])->name('reclamos.show');
Route::get('/reclamos/{id}/edit', [ReclamoController::class, 'edit'])->name('reclamos.edit');
Route::put('/reclamos/{id}', [ReclamoController::class, 'update'])->name('reclamos.update');
Route::patch('/reclamos/{id}/confirmar', [ReclamoController::class, 'confirmar'])->name('reclamos.confirmar');
Route::post('/reclamos/{id}/anular', [ReclamoController::class, 'anular'])->name('reclamos.anular');
Route::get('/reclamos/obras-por-cliente/{cliente_id}', [ReclamoController::class, 'obrasPorCliente'])->name('reclamos.obras.por.cliente');
Route::get('/reclamos/servicios-realizados-por-obra/{obra_id}', [ReclamoController::class, 'serviciosRealizadosPorObra'])->name('reclamos.servicios.por.obra');
