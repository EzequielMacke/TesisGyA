<?php

namespace App\Http\Controllers;

use App\Models\CondicionPago;
use Illuminate\Http\Request;
use App\Models\OrdenCompra;
use App\Models\PresupuestoCompraAprobado;
use App\Models\Proveedor;
use App\Models\Estado;
use App\Models\MetodoPago;
use App\Models\OrdenCompraDetalle;
use Illuminate\Support\Facades\DB;

class OrdenCompraController extends Controller
{
    public function index(Request $request)
    {
        // Obtener proveedores para el filtro
        $proveedores = Proveedor::where('estado_id', 1)->orderBy('razon_social')->get();

        // Consulta base de órdenes con relaciones
        $query = OrdenCompra::with([
            'usuario.persona',
            'proveedor',
            'estado',
            'condicionPago',
            'metodoPago',
            'presupuestoCompraAprobado'
        ]);

        // Filtros opcionales
        if ($request->filled('estado')) {
            $query->where('estado_id', $request->estado);
        }

        if ($request->filled('proveedor')) {
            $query->where('proveedor_id', $request->proveedor);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        // Obtener órdenes ordenadas por fecha descendente
        $ordenes = $query->orderBy('created_at', 'desc')
                        ->orderBy('id', 'desc')
                        ->get();

        return view('orden_compra.index', compact('ordenes', 'proveedores'));
    }

    public function create()
    {
        // Obtener presupuestos aprobados pendientes (estado 3)
        $presupuestosAprobados = PresupuestoCompraAprobado::with([
            'proveedor',
            'pedidoCompra.sucursal',
            'detalles'
        ])->where('estado_id', 3)
        ->orderBy('created_at', 'desc')
        ->get();

        // Obtener condiciones y métodos de pago activos
        $condicionesPago = CondicionPago::where('estado_id', 1)->orderBy('descripcion')->get();
        $metodosPago = MetodoPago::where('estado_id', 1)->orderBy('descripcion')->get();

        return view('orden_compra.create', compact('presupuestosAprobados', 'condicionesPago', 'metodosPago'));
    }

    /**
     * Guardar nueva orden de compra
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'presupuesto_compra_aprobado_id' => 'required|exists:presupuesto_compra_aprobados,id',
            'condicion_pago_id' => 'required|exists:condicion_pago,id',
            'metodo_pago_id' => 'required|exists:metodo_pago,id',
            'fecha' => 'required|date',
            'monto' => 'required|numeric|min:0',
            'intervalo' => 'nullable|integer|min:1',
            'cuota' => 'nullable|integer|min:1',
            'observacion' => 'nullable|string|max:500',
            'detalles' => 'required|array|min:1',
            'detalles.*.insumo_id' => 'required|exists:insumo,id',
            'detalles.*.cantidad' => 'required|numeric|min:0.01',
            'detalles.*.precio_unitario' => 'required|numeric|min:0.01',
            'detalles.*.impuesto_id' => 'required|exists:impuestos,id',
            'detalles.*.observacion' => 'nullable|string|max:300'
        ]);

        try {
            DB::beginTransaction();

            // Obtener el presupuesto aprobado
            $presupuestoAprobado = PresupuestoCompraAprobado::findOrFail($validated['presupuesto_compra_aprobado_id']);

            // Verificar que esté pendiente
            if ($presupuestoAprobado->estado_id !== 3) {
                throw new \Exception('El presupuesto aprobado no está en estado pendiente.');
            }

            // Crear la orden de compra
            $ordenCompra = OrdenCompra::create([
                'usuario_id' => session('user_id'),
                'proveedor_id' => $presupuestoAprobado->proveedor_id,
                'condicion_pago_id' => $validated['condicion_pago_id'],
                'estado_id' => 3, // Pendiente
                'metodo_pago_id' => $validated['metodo_pago_id'],
                'fecha' => $validated['fecha'],
                'monto' => $validated['monto'],
                'presupuesto_compra_aprobado_id' => $validated['presupuesto_compra_aprobado_id'],
                'intervalo' => $validated['intervalo'],
                'cuota' => $validated['cuota'],
                'observacion' => $validated['observacion']
            ]);

            // Crear los detalles de la orden
            foreach ($validated['detalles'] as $detalle) {
                OrdenCompraDetalle::create([
                    'orden_compra_id' => $ordenCompra->id,
                    'insumo_id' => $detalle['insumo_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'estado_id' => 3, // Pendiente
                    'impuesto_id' => $detalle['impuesto_id'],
                    'observacion' => $detalle['observacion'] ?? null
                ]);
            }

            // Cambiar estado del presupuesto aprobado a confirmado (4)
            $presupuestoAprobado->update(['estado_id' => 4]);

            DB::commit();

            return redirect()->route('orden_compra.index')
                            ->with('success', 'Orden de compra creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Error al crear la orden de compra: ' . $e->getMessage());
        }
    }

    /**
     * Obtener detalle de presupuesto aprobado
     */
    public function getPresupuestoDetalle($id)
    {
        try {
            $presupuesto = PresupuestoCompraAprobado::with([
                'detalles.insumo.marca',
                'detalles.insumo.unidadMedida',
                'detalles.impuesto'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'detalles' => $presupuesto->detalles
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
