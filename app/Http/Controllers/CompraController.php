<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\CompraDetalle;
use App\Models\CuentaPagar;
use App\Models\Proveedor;
use App\Models\Estado;
use App\Models\LibroCompra;
use App\Models\NotaRemisionCompra;
use App\Models\OrdenCompra;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    /**
     * Muestra el listado de compras (facturas de proveedor) con filtros.
     */
    public function index(Request $request)
    {
        // Obtener proveedores y estados para los filtros
        $proveedores = Proveedor::orderBy('razon_social')->get();
        $estados = Estado::whereIn('id', [3, 4, 5])->get();

        // Consulta base con relaciones
        $query = Compra::with([
            'proveedor',
            'usuario',
            'estado',
            'tipoDocumento',
            'ordenCompra'
        ]);

        // Filtros opcionales
        if ($request->filled('proveedor_id')) {
            $query->where('proveedor_id', $request->proveedor_id);
        }
        if ($request->filled('estado_id')) {
            $query->where('estado_id', $request->estado_id);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_emision', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_emision', '<=', $request->fecha_hasta);
        }

        // Obtener compras ordenadas por fecha descendente y paginadas
        $compras = $query->orderBy('fecha_emision', 'desc')->paginate(20);

        return view('compras.index', compact('compras', 'proveedores', 'estados'));
    }

    public function create($orden_id = null)
    {
        // Solo obtener órdenes confirmadas que tengan notas de remisión pendientes
        $ordenes = OrdenCompra::with('proveedor')
            ->where('estado_id', 4) // Confirmadas
            ->whereHas('notasRemision', function($query) {
                $query->where('estado_id', 3); // Notas pendientes
            })
            ->get();

        $ordenSeleccionada = null;
        $datosOrden = null;

        if ($orden_id) {
            $ordenSeleccionada = OrdenCompra::with([
                'proveedor',
                'condicionPago',
                'metodoPago',
                'detalles.insumo.marca',
                'detalles.insumo.unidadMedida',
                'detalles.impuesto'
            ])->find($orden_id);

            if ($ordenSeleccionada) {
                // Obtener solo notas de remisión pendientes (estado 3)
                $notas = NotaRemisionCompra::where('orden_compra_id', $orden_id)
                    ->where('estado_id', 3) // Solo pendientes
                    ->get();

                // Verificar que tenga notas pendientes
                if ($notas->isEmpty()) {
                    return redirect()->route('compras.create')
                        ->with('error', 'La orden seleccionada no tiene notas de remisión pendientes.');
                }

                // Procesar artículos
                $articulos = [];
                $articulosAgrupados = [];

                foreach ($ordenSeleccionada->detalles as $detalle) {
                    $key = $detalle->insumo_id . '_' . $detalle->precio_unitario . '_' . $detalle->impuesto_id;

                    if (!isset($articulosAgrupados[$key])) {
                        $articulosAgrupados[$key] = [
                            'insumo_id' => $detalle->insumo_id,
                            'descripcion' => $detalle->insumo->descripcion ?? '',
                            'marca' => $detalle->insumo->marca->descripcion ?? '',
                            'unidad' => $detalle->insumo->unidadMedida->descripcion ?? '',
                            'cantidad_total' => 0,
                            'precio_unitario' => $detalle->precio_unitario,
                            'impuesto_id' => $detalle->impuesto_id,
                            'impuesto' => $detalle->impuesto->descripcion ?? '',
                            'subtotal' => 0
                        ];
                    }

                    $articulosAgrupados[$key]['cantidad_total'] += $detalle->cantidad;
                    $articulosAgrupados[$key]['subtotal'] = $articulosAgrupados[$key]['cantidad_total'] * $detalle->precio_unitario;
                }

                $articulos = array_values($articulosAgrupados);

                // Calcular totales
                $totalSubtotales = 0;
                $iva5 = 0;
                $iva10 = 0;
                $exento = 0;

                foreach ($articulos as $art) {
                    $totalSubtotales += $art['subtotal'];
                    if ($art['impuesto_id'] == 3) { // IVA 5%
                        $iva5 += $art['subtotal'] / 21;
                    } elseif ($art['impuesto_id'] == 2) { // IVA 10%
                        $iva10 += $art['subtotal'] / 11;
                    } elseif ($art['impuesto_id'] == 1) { // Exento
                        $exento += $art['subtotal'];
                    }
                }

                $totalImpuestos = $iva5 + $iva10;
                $totalCompra = $totalSubtotales + $totalImpuestos;

                // Detalles del presupuesto
                $presupuestoDetalles = $ordenSeleccionada->detalles()->with([
                    'insumo.marca',
                    'insumo.unidadMedida',
                    'impuesto'
                ])->get();

                $datosOrden = [
                    'notas' => $notas,
                    'articulos' => $articulos,
                    'presupuesto' => '₲ ' . number_format($ordenSeleccionada->presupuesto, 0, ',', '.'),
                    'presupuesto_detalles' => $presupuestoDetalles,
                    'condicion_pago' => $ordenSeleccionada->condicionPago->descripcion,
                    'metodo_pago' => $ordenSeleccionada->metodoPago->descripcion,
                    'cuotas' => $ordenSeleccionada->cuota,
                    'intervalo' => $ordenSeleccionada->intervalo,
                    'iva5' => $iva5,
                    'iva10' => $iva10,
                    'exento' => $exento,
                    'total_subtotales' => $totalSubtotales,
                    'total_impuestos' => $totalImpuestos,
                    'total_compra' => $totalCompra
                ];
            }
        }

        return view('compras.create', compact('ordenes', 'ordenSeleccionada', 'datosOrden'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'orden_compra_id' => 'required|exists:orden_compra,id',
            'nro_factura' => 'required|string|max:20',
            'nro_timbrado' => 'required|string|max:20',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_emision',
            'observacion' => 'nullable|string',
            'detalle' => 'required|array',
            'detalle.*.insumo_id' => 'required|integer',
            'detalle.*.cantidad_total' => 'required|numeric|min:0.01',
            'detalle.*.precio_unitario' => 'required|numeric|min:0.01',
            'detalle.*.impuesto_id' => 'required|integer',
        ], [
            'orden_compra_id.required' => 'Debe seleccionar una orden de compra.',
            'orden_compra_id.exists' => 'La orden de compra seleccionada no es válida.',
            'nro_factura.required' => 'El número de factura es obligatorio.',
            'nro_factura.max' => 'El número de factura no puede exceder 20 caracteres.',
            'nro_timbrado.required' => 'El número de timbrado es obligatorio.',
            'nro_timbrado.max' => 'El número de timbrado no puede exceder 20 caracteres.',
            'fecha_emision.required' => 'La fecha de emisión es obligatoria.',
            'fecha_emision.date' => 'La fecha de emisión debe ser una fecha válida.',
            'fecha_vencimiento.date' => 'La fecha de vencimiento debe ser una fecha válida.',
            'fecha_vencimiento.after_or_equal' => 'La fecha de vencimiento no puede ser anterior a la fecha de emisión.',
            'detalle.required' => 'Debe seleccionar al menos un artículo para la compra.',
            'detalle.array' => 'Los datos del detalle no son válidos.',
            'detalle.*.cantidad_total.required' => 'La cantidad es obligatoria para todos los artículos.',
            'detalle.*.cantidad_total.numeric' => 'La cantidad debe ser un número válido.',
            'detalle.*.cantidad_total.min' => 'La cantidad debe ser mayor a 0.',
            'detalle.*.precio_unitario.required' => 'El precio unitario es obligatorio para todos los artículos.',
            'detalle.*.precio_unitario.numeric' => 'El precio unitario debe ser un número válido.',
            'detalle.*.precio_unitario.min' => 'El precio unitario debe ser mayor a 0.',
        ]);

        $orden = OrdenCompra::with(['condicionPago', 'metodoPago'])->findOrFail($request->orden_compra_id);

        // Verificar que la orden tenga notas de remisión pendientes
        $notasPendientes = NotaRemisionCompra::where('orden_compra_id', $request->orden_compra_id)
            ->where('estado_id', 3) // Pendientes
            ->get();

        if ($notasPendientes->isEmpty()) {
            return back()->with('error', 'La orden de compra seleccionada no tiene notas de remisión pendientes.');
        }

        // Crear la compra
        $compra = Compra::create([
            'nro_factura' => $request->nro_factura,
            'nro_timbrado' => $request->nro_timbrado,
            'fecha_emision' => $request->fecha_emision,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'proveedor_id' => $orden->proveedor_id,
            'condicion_pago_id' => $orden->condicion_pago_id,
            'metodo_pago_id' => $orden->metodo_pago_id,
            'usuario_id' => session('user_id'),
            'orden_compra_id' => $request->orden_compra_id,
            'observacion' => $request->observacion,
            'estado_id' => 3,
            'monto' => 0,
            'datos_empresa_id' => 1,
            'presupuesto_compra_aprobado_id' => $orden->presupuesto_compra_aprobado_id,
            'tipo_documento_id' => 1,
        ]);

        $monto = 0;
        $iva5 = 0;
        $iva10 = 0;
        $iva_exento = 0;

        // Crear el detalle de la compra
        foreach ($request->detalle as $item) {
            $subtotal = $item['cantidad_total'] * $item['precio_unitario'];
            $monto += $subtotal;

            CompraDetalle::create([
                'compra_id' => $compra->id,
                'insumo_id' => $item['insumo_id'],
                'precio_unitario' => round($item['precio_unitario']),
                'impuesto_id' => $item['impuesto_id'],
                'cantidad' => round($item['cantidad_total']),
            ]);

            // Calcular impuestos
            if ($item['impuesto_id'] == 3) { // IVA 5%
                $iva5 += $subtotal / 21;
            } elseif ($item['impuesto_id'] == 2) { // IVA 10%
                $iva10 += $subtotal / 11;
            } elseif ($item['impuesto_id'] == 1) { // Exento
                $iva_exento += $subtotal;
            }
        }

        $compra->update(['monto' => round($monto)]);
        $total_iva = $iva5 + $iva10;
        $condicion = strtolower($orden->condicionPago->descripcion);
        $cuotas = $orden->cuota;
        $intervalo = $orden->intervalo;

        // Crear cuentas a pagar
        if ($condicion == 'contado') {
            CuentaPagar::create([
                'compra_id' => $compra->id,
                'cuota' => 1,
                'metodo_pago_id' => $orden->metodo_pago_id,
                'condicion_pago_id' => $orden->condicion_pago_id,
                'proveedor_id' => $orden->proveedor_id,
                'fecha_emision' => $request->fecha_emision,
                'fecha_pago' => null,
                'fecha_vencimiento' => $request->fecha_vencimiento ?? $request->fecha_emision,
                'monto' => round($monto),
                'monto_pagado' => 0,
                'saldo' => round($monto),
                'estado_id' => 3,
            ]);
        } else {
            if (empty($cuotas) || empty($intervalo)) {
                return back()->with('error', 'La orden de compra seleccionada no tiene configuradas las cuotas o el intervalo para crédito.');
            }
            $montoCuota = round($monto / $cuotas);
            $fechaBase = \Carbon\Carbon::parse($request->fecha_emision);
            for ($i = 1; $i <= $cuotas; $i++) {
                $fechaVencimiento = $fechaBase->copy()->addDays($intervalo * ($i - 1));
                CuentaPagar::create([
                    'compra_id' => $compra->id,
                    'cuota' => $i,
                    'metodo_pago_id' => $orden->metodo_pago_id,
                    'condicion_pago_id' => $orden->condicion_pago_id,
                    'proveedor_id' => $orden->proveedor_id,
                    'fecha_emision' => $request->fecha_emision,
                    'fecha_pago' => null,
                    'fecha_vencimiento' => $fechaVencimiento,
                    'monto' => $montoCuota,
                    'monto_pagado' => 0,
                    'saldo' => $montoCuota,
                    'estado_id' => 3,
                ]);
            }
        }

        // Crear registro en libro de compras
        LibroCompra::create([
            'proveedor_id' => $orden->proveedor_id,
            'compra_id' => $compra->id,
            'tipo_documento_id' => 1,
            'monto' => round($monto),
            'iva5' => round($iva5),
            'iva10' => round($iva10),
            'iva_exento' => round($iva_exento),
            'total_iva' => round($total_iva),
            'fecha_emision' => $request->fecha_emision,
            'condicion_pago_id' => $orden->condicion_pago_id,
            'estado_id' => $compra->estado_id,
            'datos_empresa_id' => 1,
            'timbrado' => $request->nro_timbrado,
            'nro_factura' => $request->nro_factura,
        ]);

        // ✅ ACTUALIZAR NOTAS DE REMISIÓN A ESTADO CONFIRMADO (4)
        NotaRemisionCompra::where('orden_compra_id', $request->orden_compra_id)
            ->where('estado_id', 3) // Solo las pendientes
            ->update(['estado_id' => 4]); // Cambiar a confirmado

        return redirect()->route('compras.index')->with('success', 'Compra registrada exitosamente. Las notas de remisión han sido confirmadas.');
    }

    public function datosOrdenCompra($id)
    {
        $orden = OrdenCompra::with([
            'condicionPago',
            'presupuestoCompraAprobado.detalles.insumo.marca',
            'presupuestoCompraAprobado.detalles.insumo.unidadMedida',
            'presupuestoCompraAprobado.detalles.impuesto',
            'notasRemision.proveedor',
            'detalles.insumo.marca',
            'detalles.insumo.unidadMedida',
            'detalles.impuesto'
        ])->findOrFail($id);

        // Condición de pago, cuotas e intervalo
        $condicion_pago = $orden->condicionPago->descripcion ?? '';
        $cuotas = $orden->cuota ?? 1;
        $intervalo = $orden->intervalo ?? 30;

        // Presupuesto aprobado
        $presupuesto = $orden->presupuestoCompraAprobado->descripcion ?? '';
        $presupuesto_detalles = [];
        if ($orden->presupuestoCompraAprobado) {
            foreach ($orden->presupuestoCompraAprobado->detalles as $detalle) {
                $presupuesto_detalles[] = [
                    'insumo' => $detalle->insumo->descripcion ?? '',
                    'marca' => $detalle->insumo->marca->descripcion ?? '',
                    'unidad' => $detalle->insumo->unidadMedida->descripcion ?? '',
                    'cantidad' => $detalle->cantidad,
                    'precio_unitario' => $detalle->precio_unitario,
                    'impuesto_id' => $detalle->impuesto_id,
                    'impuesto' => $detalle->impuesto->descripcion ?? '',
                    'observacion' => $detalle->observacion,
                ];
            }
        }

        // Notas de remisión asociadas
        $notas = [];
        foreach ($orden->notasRemision as $nota) {
            $notas[] = [
                'id' => $nota->id,
                'nombre' => $nota->nombre ?? '',
                'fecha_recepcion' => \Carbon\Carbon::parse($nota->fecha_recepcion)->format('d/m/Y'),
                'proveedor' => $nota->proveedor->razon_social ?? '',
            ];
        }

        // Artículos sumados de las notas de remisión
        $articulos = [];
        foreach ($orden->detalles as $detalle) {
            $articulos[] = [
                'insumo_id' => $detalle->insumo_id,
                'descripcion' => $detalle->insumo->descripcion ?? '',
                'marca' => $detalle->insumo->marca->descripcion ?? '',
                'unidad' => $detalle->insumo->unidadMedida->descripcion ?? '',
                'cantidad_total' => $detalle->cantidad,
                'precio_unitario' => $detalle->precio_unitario,
                'impuesto_id' => $detalle->impuesto_id,
                'impuesto' => $detalle->impuesto->descripcion ?? '',
                'subtotal' => $detalle->precio_unitario * $detalle->cantidad,
            ];
        }

        return response()->json([
            'condicion_pago' => $condicion_pago,
            'condicion_pago_id' => $orden->condicion_pago_id,
            'metodo_pago' => $orden->metodoPago->descripcion ?? '',
            'metodo_pago_id' => $orden->metodo_pago_id,
            'cuotas' => $cuotas,
            'intervalo' => $intervalo,
            'presupuesto' => $presupuesto,
            'presupuesto_detalles' => $presupuesto_detalles,
            'notas' => $notas,
            'articulos' => $articulos,
        ]);
    }
}
