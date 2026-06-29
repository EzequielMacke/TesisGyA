<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Compra;
use App\Models\CuentaPagar;
use App\Models\Deposito;
use App\Models\Estado;
use App\Models\Impuesto;
use App\Models\InsumoUtilizado;
use App\Models\InsumoUtilizadoDetalle;
use App\Models\Inventario;
use App\Models\LibroCompra;
use App\Models\Obra;
use App\Models\OrdenServicio;
use App\Models\PedidoCompra;
use App\Models\PresupuestoCompra;
use App\Models\PresupuestoServicio;
use App\Models\Proveedor;
use App\Models\Reclamo;
use App\Models\TipoDocumento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InformeController extends Controller
{
    public function index()
    {
        return view('informes.select');
    }

    public function presupuestosCompraForm()
    {
        $proveedores = Proveedor::orderBy('razon_social')->get();
        $estados = Estado::whereIn('id', PresupuestoCompra::query()->distinct()->pluck('estado_id'))->get();

        return view('informes.presupuestos_compra', compact('proveedores', 'estados'));
    }

    public function presupuestosCompraPdf(Request $request)
    {
        $validated = $request->validate([
            'proveedor_id' => 'nullable|exists:proveedor,id',
            'estado_id' => 'nullable|exists:estados,id',
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date|after_or_equal:fecha_desde',
        ]);

        $presupuestos = PresupuestoCompra::with([
                'proveedor',
                'estado',
                'pedidoCompra',
                'detalles.impuesto',
            ])
            ->when($validated['proveedor_id'] ?? null, fn ($q, $v) => $q->where('proveedor_id', $v))
            ->when($validated['estado_id'] ?? null, fn ($q, $v) => $q->where('estado_id', $v))
            ->when($validated['fecha_desde'] ?? null, fn ($q, $v) => $q->whereDate('fecha_emision', '>=', $v))
            ->when($validated['fecha_hasta'] ?? null, fn ($q, $v) => $q->whereDate('fecha_emision', '<=', $v))
            ->orderBy('fecha_emision', 'desc')
            ->get();

        $totalGeneral = 0;

        foreach ($presupuestos as $presupuesto) {
            $presupuesto->total_calculado = $presupuesto->detalles->sum(function ($detalle) {
                $subtotal = $detalle->cantidad * $detalle->precio_unitario;
                $impuesto = $detalle->impuesto;

                if ($impuesto && $impuesto->id !== 1) {
                    $subtotal += round($subtotal / $impuesto->calculo);
                }

                return $subtotal;
            });

            $totalGeneral += $presupuesto->total_calculado;
        }

        $proveedor = isset($validated['proveedor_id']) ? Proveedor::find($validated['proveedor_id']) : null;
        $estado = isset($validated['estado_id']) ? Estado::find($validated['estado_id']) : null;

        $pdf = Pdf::loadView('informes.presupuestos_compra_pdf', [
            'presupuestos' => $presupuestos,
            'totalGeneral' => $totalGeneral,
            'filtros' => [
                'proveedor' => $proveedor->razon_social ?? null,
                'estado' => $estado->descripcion ?? null,
                'fecha_desde' => $validated['fecha_desde'] ?? null,
                'fecha_hasta' => $validated['fecha_hasta'] ?? null,
            ],
            'generadoEn' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->stream('informe_presupuestos_compra_' . now()->format('Ymd_His') . '.pdf');
    }

    public function cuentasPagarForm()
    {
        $proveedores = Proveedor::orderBy('razon_social')->get();
        $estados = Estado::whereIn('id', CuentaPagar::query()->distinct()->pluck('estado_id'))->get();

        return view('informes.cuentas_pagar', compact('proveedores', 'estados'));
    }

    public function cuentasPagarPdf(Request $request)
    {
        $validated = $request->validate([
            'proveedor_id' => 'nullable|exists:proveedor,id',
            'estado_id' => 'nullable|exists:estados,id',
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date|after_or_equal:fecha_desde',
        ]);

        $cuentas = CuentaPagar::with(['compra', 'proveedor', 'estado', 'metodoPago'])
            ->when($validated['proveedor_id'] ?? null, fn ($q, $v) => $q->where('proveedor_id', $v))
            ->when($validated['estado_id'] ?? null, fn ($q, $v) => $q->where('estado_id', $v))
            ->when($validated['fecha_desde'] ?? null, fn ($q, $v) => $q->whereDate('fecha_vencimiento', '>=', $v))
            ->when($validated['fecha_hasta'] ?? null, fn ($q, $v) => $q->whereDate('fecha_vencimiento', '<=', $v))
            ->orderBy('fecha_vencimiento')
            ->get();

        $totalMonto = $cuentas->sum('monto');
        $totalPagado = $cuentas->sum('monto_pagado');
        $totalDescuento = $cuentas->sum('descuento');
        $totalAumento = $cuentas->sum('aumento');
        $totalSaldo = $cuentas->sum(fn ($cuenta) => $cuenta->saldo_neto ?? $cuenta->saldo);

        $proveedor = isset($validated['proveedor_id']) ? Proveedor::find($validated['proveedor_id']) : null;
        $estado = isset($validated['estado_id']) ? Estado::find($validated['estado_id']) : null;

        $pdf = Pdf::loadView('informes.cuentas_pagar_pdf', [
            'cuentas' => $cuentas,
            'totalMonto' => $totalMonto,
            'totalPagado' => $totalPagado,
            'totalDescuento' => $totalDescuento,
            'totalAumento' => $totalAumento,
            'totalSaldo' => $totalSaldo,
            'filtros' => [
                'proveedor' => $proveedor->razon_social ?? null,
                'estado' => $estado->descripcion ?? null,
                'fecha_desde' => $validated['fecha_desde'] ?? null,
                'fecha_hasta' => $validated['fecha_hasta'] ?? null,
            ],
            'generadoEn' => now()->format('d/m/Y H:i'),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('informe_cuentas_pagar_' . now()->format('Ymd_His') . '.pdf');
    }

    public function comprasForm()
    {
        $proveedores = Proveedor::orderBy('razon_social')->get();
        $estados = Estado::whereIn('id', Compra::query()->distinct()->pluck('estado_id'))->get();

        return view('informes.compras', compact('proveedores', 'estados'));
    }

    public function comprasPdf(Request $request)
    {
        $validated = $request->validate([
            'proveedor_id' => 'nullable|exists:proveedor,id',
            'estado_id' => 'nullable|exists:estados,id',
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date|after_or_equal:fecha_desde',
        ]);

        $compras = Compra::with(['proveedor', 'estado', 'tipoDocumento', 'ordenCompra'])
            ->when($validated['proveedor_id'] ?? null, fn ($q, $v) => $q->where('proveedor_id', $v))
            ->when($validated['estado_id'] ?? null, fn ($q, $v) => $q->where('estado_id', $v))
            ->when($validated['fecha_desde'] ?? null, fn ($q, $v) => $q->whereDate('fecha_emision', '>=', $v))
            ->when($validated['fecha_hasta'] ?? null, fn ($q, $v) => $q->whereDate('fecha_emision', '<=', $v))
            ->orderBy('fecha_emision', 'desc')
            ->get();

        $totalGeneral = $compras->sum('monto');

        $proveedor = isset($validated['proveedor_id']) ? Proveedor::find($validated['proveedor_id']) : null;
        $estado = isset($validated['estado_id']) ? Estado::find($validated['estado_id']) : null;

        $pdf = Pdf::loadView('informes.compras_pdf', [
            'compras' => $compras,
            'totalGeneral' => $totalGeneral,
            'filtros' => [
                'proveedor' => $proveedor->razon_social ?? null,
                'estado' => $estado->descripcion ?? null,
                'fecha_desde' => $validated['fecha_desde'] ?? null,
                'fecha_hasta' => $validated['fecha_hasta'] ?? null,
            ],
            'generadoEn' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->stream('informe_compras_' . now()->format('Ymd_His') . '.pdf');
    }

    public function libroComprasForm()
    {
        $proveedores = Proveedor::orderBy('razon_social')->get();
        $tiposDocumento = TipoDocumento::whereIn('id', LibroCompra::query()->distinct()->pluck('tipo_documento_id'))->get();

        return view('informes.libro_compras', compact('proveedores', 'tiposDocumento'));
    }

    public function libroComprasPdf(Request $request)
    {
        $validated = $request->validate([
            'proveedor_id' => 'nullable|exists:proveedor,id',
            'tipo_documento_id' => 'nullable|exists:tipo_documento,id',
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date|after_or_equal:fecha_desde',
        ]);

        $registros = LibroCompra::with(['proveedor', 'tipoDocumento'])
            ->when($validated['proveedor_id'] ?? null, fn ($q, $v) => $q->where('proveedor_id', $v))
            ->when($validated['tipo_documento_id'] ?? null, fn ($q, $v) => $q->where('tipo_documento_id', $v))
            ->when($validated['fecha_desde'] ?? null, fn ($q, $v) => $q->whereDate('fecha_emision', '>=', $v))
            ->when($validated['fecha_hasta'] ?? null, fn ($q, $v) => $q->whereDate('fecha_emision', '<=', $v))
            ->orderBy('fecha_emision')
            ->get();

        $totalMonto = $registros->sum('monto');
        $totalIva5 = $registros->sum('iva5');
        $totalIva10 = $registros->sum('iva10');
        $totalExento = $registros->sum('iva_exento');
        $totalIva = $registros->sum('total_iva');

        $proveedor = isset($validated['proveedor_id']) ? Proveedor::find($validated['proveedor_id']) : null;
        $tipoDocumento = isset($validated['tipo_documento_id']) ? TipoDocumento::find($validated['tipo_documento_id']) : null;

        $pdf = Pdf::loadView('informes.libro_compras_pdf', [
            'registros' => $registros,
            'totalMonto' => $totalMonto,
            'totalIva5' => $totalIva5,
            'totalIva10' => $totalIva10,
            'totalExento' => $totalExento,
            'totalIva' => $totalIva,
            'filtros' => [
                'proveedor' => $proveedor->razon_social ?? null,
                'tipo_documento' => $tipoDocumento->descripcion ?? null,
                'fecha_desde' => $validated['fecha_desde'] ?? null,
                'fecha_hasta' => $validated['fecha_hasta'] ?? null,
            ],
            'generadoEn' => now()->format('d/m/Y H:i'),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('informe_libro_compras_' . now()->format('Ymd_His') . '.pdf');
    }

    public function pedidosCompraForm()
    {
        $depositos = Deposito::orderBy('descripcion')->get();
        $estados = Estado::whereIn('id', PedidoCompra::query()->distinct()->pluck('estado_id'))->get();

        return view('informes.pedidos_compra', compact('depositos', 'estados'));
    }

    public function pedidosCompraPdf(Request $request)
    {
        $validated = $request->validate([
            'deposito_id' => 'nullable|exists:deposito,id',
            'estado_id' => 'nullable|exists:estados,id',
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date|after_or_equal:fecha_desde',
        ]);

        $pedidos = PedidoCompra::with(['usuario', 'deposito', 'sucursal', 'estado', 'detalles'])
            ->when($validated['deposito_id'] ?? null, fn ($q, $v) => $q->where('deposito_id', $v))
            ->when($validated['estado_id'] ?? null, fn ($q, $v) => $q->where('estado_id', $v))
            ->when($validated['fecha_desde'] ?? null, fn ($q, $v) => $q->whereDate('fecha', '>=', $v))
            ->when($validated['fecha_hasta'] ?? null, fn ($q, $v) => $q->whereDate('fecha', '<=', $v))
            ->orderBy('fecha', 'desc')
            ->get();

        foreach ($pedidos as $pedido) {
            $pedido->cant_items = $pedido->detalles->count();
            $pedido->cant_total = $pedido->detalles->sum('cantidad');
        }

        $totalItems = $pedidos->sum('cant_items');
        $totalCantidad = $pedidos->sum('cant_total');

        $deposito = isset($validated['deposito_id']) ? Deposito::find($validated['deposito_id']) : null;
        $estado = isset($validated['estado_id']) ? Estado::find($validated['estado_id']) : null;

        $pdf = Pdf::loadView('informes.pedidos_compra_pdf', [
            'pedidos' => $pedidos,
            'totalItems' => $totalItems,
            'totalCantidad' => $totalCantidad,
            'filtros' => [
                'deposito' => $deposito->descripcion ?? null,
                'estado' => $estado->descripcion ?? null,
                'fecha_desde' => $validated['fecha_desde'] ?? null,
                'fecha_hasta' => $validated['fecha_hasta'] ?? null,
            ],
            'generadoEn' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->stream('informe_pedidos_compra_' . now()->format('Ymd_His') . '.pdf');
    }

    public function inventarioForm()
    {
        $depositos = Deposito::orderBy('descripcion')->get();
        $obras = Obra::orderBy('descripcion')->get();

        return view('informes.inventario', compact('depositos', 'obras'));
    }

    public function inventarioPdf(Request $request)
    {
        $validated = $request->validate([
            'deposito_id' => 'nullable|exists:deposito,id',
            'obra_id' => 'nullable|exists:obras,id',
        ]);

        $inventarios = Inventario::with(['deposito', 'obra', 'insumo.marca', 'insumo.unidadMedida'])
            ->when($validated['deposito_id'] ?? null, fn ($q, $v) => $q->where('deposito_id', $v))
            ->when($validated['obra_id'] ?? null, fn ($q, $v) => $q->where('obra_id', $v))
            ->where('cantidad', '>', 0)
            ->get()
            ->sortBy(fn ($inventario) => $inventario->insumo->descripcion ?? '')
            ->values();

        $totalItems = $inventarios->count();

        $deposito = isset($validated['deposito_id']) ? Deposito::find($validated['deposito_id']) : null;
        $obra = isset($validated['obra_id']) ? Obra::find($validated['obra_id']) : null;

        $pdf = Pdf::loadView('informes.inventario_pdf', [
            'inventarios' => $inventarios,
            'totalItems' => $totalItems,
            'filtros' => [
                'deposito' => $deposito->descripcion ?? null,
                'obra' => $obra->descripcion ?? null,
            ],
            'generadoEn' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->stream('informe_inventario_' . now()->format('Ymd_His') . '.pdf');
    }

    public function presupuestosServicioForm()
    {
        $clientes = Cliente::orderBy('razon_social')->get();
        $estados = Estado::whereIn('id', PresupuestoServicio::query()->distinct()->pluck('estado_id'))->get();

        return view('informes.presupuestos_servicio', compact('clientes', 'estados'));
    }

    public function presupuestosServicioPdf(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'estado_id' => 'nullable|exists:estados,id',
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date|after_or_equal:fecha_desde',
        ]);

        $presupuestos = PresupuestoServicio::with(['cliente', 'obra', 'estado'])
            ->when($validated['cliente_id'] ?? null, fn ($q, $v) => $q->where('cliente_id', $v))
            ->when($validated['estado_id'] ?? null, fn ($q, $v) => $q->where('estado_id', $v))
            ->when($validated['fecha_desde'] ?? null, fn ($q, $v) => $q->whereDate('fecha', '>=', $v))
            ->when($validated['fecha_hasta'] ?? null, fn ($q, $v) => $q->whereDate('fecha', '<=', $v))
            ->orderBy('fecha', 'desc')
            ->get();

        $totalGeneral = $presupuestos->sum('monto');

        $cliente = isset($validated['cliente_id']) ? Cliente::find($validated['cliente_id']) : null;
        $estado = isset($validated['estado_id']) ? Estado::find($validated['estado_id']) : null;

        $pdf = Pdf::loadView('informes.presupuestos_servicio_pdf', [
            'presupuestos' => $presupuestos,
            'totalGeneral' => $totalGeneral,
            'filtros' => [
                'cliente' => $cliente->razon_social ?? null,
                'estado' => $estado->descripcion ?? null,
                'fecha_desde' => $validated['fecha_desde'] ?? null,
                'fecha_hasta' => $validated['fecha_hasta'] ?? null,
            ],
            'generadoEn' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->stream('informe_presupuestos_servicio_' . now()->format('Ymd_His') . '.pdf');
    }

    public function reclamosForm()
    {
        $clientes = Cliente::orderBy('razon_social')->get();
        $estados = Estado::whereIn('id', Reclamo::query()->distinct()->pluck('estado_id'))->get();

        return view('informes.reclamos', compact('clientes', 'estados'));
    }

    public function reclamosPdf(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'estado_id' => 'nullable|exists:estados,id',
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date|after_or_equal:fecha_desde',
        ]);

        $reclamos = Reclamo::with(['cliente', 'obra', 'usuario', 'estado'])
            ->when($validated['cliente_id'] ?? null, fn ($q, $v) => $q->where('cliente_id', $v))
            ->when($validated['estado_id'] ?? null, fn ($q, $v) => $q->where('estado_id', $v))
            ->when($validated['fecha_desde'] ?? null, fn ($q, $v) => $q->whereDate('fecha_registro', '>=', $v))
            ->when($validated['fecha_hasta'] ?? null, fn ($q, $v) => $q->whereDate('fecha_registro', '<=', $v))
            ->orderBy('fecha_registro', 'desc')
            ->get();

        $cliente = isset($validated['cliente_id']) ? Cliente::find($validated['cliente_id']) : null;
        $estado = isset($validated['estado_id']) ? Estado::find($validated['estado_id']) : null;

        $pdf = Pdf::loadView('informes.reclamos_pdf', [
            'reclamos' => $reclamos,
            'filtros' => [
                'cliente' => $cliente->razon_social ?? null,
                'estado' => $estado->descripcion ?? null,
                'fecha_desde' => $validated['fecha_desde'] ?? null,
                'fecha_hasta' => $validated['fecha_hasta'] ?? null,
            ],
            'generadoEn' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->stream('informe_reclamos_' . now()->format('Ymd_His') . '.pdf');
    }

    public function insumosUtilizadosForm()
    {
        $obras = Obra::whereIn('id', InsumoUtilizado::query()->distinct()->pluck('obra_id'))->orderBy('descripcion')->get();
        $estados = Estado::whereIn('id', InsumoUtilizado::query()->distinct()->pluck('estado_id'))->get();

        return view('informes.insumos_utilizados', compact('obras', 'estados'));
    }

    public function insumosUtilizadosPdf(Request $request)
    {
        $validated = $request->validate([
            'obra_id' => 'nullable|exists:obras,id',
            'estado_id' => 'nullable|exists:estados,id',
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date|after_or_equal:fecha_desde',
        ]);

        $detalles = InsumoUtilizadoDetalle::with(['insumo.unidadMedida', 'insumoUtilizado.obra', 'insumoUtilizado.ordenServicio', 'insumoUtilizado.usuario', 'insumoUtilizado.estado'])
            ->whereHas('insumoUtilizado', function ($q) use ($validated) {
                $q->when($validated['obra_id'] ?? null, fn ($q2, $v) => $q2->where('obra_id', $v))
                    ->when($validated['estado_id'] ?? null, fn ($q2, $v) => $q2->where('estado_id', $v))
                    ->when($validated['fecha_desde'] ?? null, fn ($q2, $v) => $q2->whereDate('fecha_registro', '>=', $v))
                    ->when($validated['fecha_hasta'] ?? null, fn ($q2, $v) => $q2->whereDate('fecha_registro', '<=', $v));
            })
            ->get()
            ->sortByDesc(fn ($detalle) => $detalle->insumoUtilizado->fecha_registro)
            ->values();

        $totalItems = $detalles->count();

        $obra = isset($validated['obra_id']) ? Obra::find($validated['obra_id']) : null;
        $estado = isset($validated['estado_id']) ? Estado::find($validated['estado_id']) : null;

        $pdf = Pdf::loadView('informes.insumos_utilizados_pdf', [
            'detalles' => $detalles,
            'totalItems' => $totalItems,
            'filtros' => [
                'obra' => $obra->descripcion ?? null,
                'estado' => $estado->descripcion ?? null,
                'fecha_desde' => $validated['fecha_desde'] ?? null,
                'fecha_hasta' => $validated['fecha_hasta'] ?? null,
            ],
            'generadoEn' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->stream('informe_insumos_utilizados_' . now()->format('Ymd_His') . '.pdf');
    }

    public function ordenServicioForm()
    {
        $clientes = Cliente::whereIn('id', OrdenServicio::query()->distinct()->pluck('cliente_id'))->orderBy('razon_social')->get();
        $estados = Estado::whereIn('id', OrdenServicio::query()->distinct()->pluck('estado_id'))->get();

        return view('informes.orden_servicio', compact('clientes', 'estados'));
    }

    public function ordenServicioPdf(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'estado_id' => 'nullable|exists:estados,id',
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date|after_or_equal:fecha_desde',
        ]);

        $ordenes = OrdenServicio::with(['cliente', 'obra', 'estado', 'detalles'])
            ->when($validated['cliente_id'] ?? null, fn ($q, $v) => $q->where('cliente_id', $v))
            ->when($validated['estado_id'] ?? null, fn ($q, $v) => $q->where('estado_id', $v))
            ->when($validated['fecha_desde'] ?? null, fn ($q, $v) => $q->whereDate('fecha_registro', '>=', $v))
            ->when($validated['fecha_hasta'] ?? null, fn ($q, $v) => $q->whereDate('fecha_registro', '<=', $v))
            ->orderBy('fecha_registro', 'desc')
            ->get();

        foreach ($ordenes as $orden) {
            $orden->cant_ensayos = $orden->detalles->count();
        }

        $cliente = isset($validated['cliente_id']) ? Cliente::find($validated['cliente_id']) : null;
        $estado = isset($validated['estado_id']) ? Estado::find($validated['estado_id']) : null;

        $pdf = Pdf::loadView('informes.orden_servicio_pdf', [
            'ordenes' => $ordenes,
            'filtros' => [
                'cliente' => $cliente->razon_social ?? null,
                'estado' => $estado->descripcion ?? null,
                'fecha_desde' => $validated['fecha_desde'] ?? null,
                'fecha_hasta' => $validated['fecha_hasta'] ?? null,
            ],
            'generadoEn' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->stream('informe_orden_servicio_' . now()->format('Ymd_His') . '.pdf');
    }
}
