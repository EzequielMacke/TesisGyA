<?php

namespace App\Http\Controllers;

use App\Models\PedidoCompra;
use App\Models\PedidoCompraDetalle;
use App\Models\PresupuestoCompra;
use App\Models\PresupuestoCompraAprobado;
use App\Models\PresupuestoCompraDetalleAprobado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresupuestoCompraAprobadoController extends Controller
{
    /**
     * Mostrar lista de presupuestos aprobados
     */
    public function index(Request $request)
    {
        try {
            $query = PresupuestoCompraAprobado::with([
                'proveedor',
                'aprobadoPor.persona',
                'detalles.impuesto'
            ]);

            // Filtros
            if ($request->filled('proveedor')) {
                $query->whereHas('proveedor', function($q) use ($request) {
                    $q->where('razon_social', 'like', '%' . $request->proveedor . '%');
                });
            }

            if ($request->filled('fecha_desde')) {
                $query->whereDate('fecha_aprobacion', '>=', $request->fecha_desde);
            }

            if ($request->filled('fecha_hasta')) {
                $query->whereDate('fecha_aprobacion', '<=', $request->fecha_hasta);
            }

            // Ordenar por fecha de aprobación descendente
            $presupuestos = $query->orderBy('fecha_aprobacion', 'desc')->paginate(15);

            return view('presupuesto_compra_aprobado.index', compact('presupuestos'));

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al cargar los presupuestos aprobados: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            // Obtener pedidos pendientes (estado 3) que tengan presupuestos
            $pedidos = PedidoCompra::with(['sucursal', 'deposito', 'usuario.persona'])
                                ->where('estado_id', 3)
                                ->withCount(['presupuestos' => function($query) {
                                    $query->where('estado_id', 3);
                                }])
                                ->having('presupuestos_count', '>', 0)
                                ->orderBy('fecha', 'desc')
                                ->get();

            return view('presupuesto_compra_aprobado.create', compact('pedidos'));

        } catch (\Exception $e) {
            return redirect()->route('presupuesto_compra_aprobado.index')
                            ->with('error', 'Error al cargar la vista de aprobación: ' . $e->getMessage());
        }
    }
    /**
     * Obtener presupuestos de un pedido específico
     */
    public function getPresupuestosPedido($pedidoId)
    {
        try {
            $presupuestos = PresupuestoCompra::with([
                'proveedor',
                'estado',
                'detalles.insumo.marca',
                'detalles.insumo.unidadMedida',
                'detalles.impuesto'
            ])->where('pedido_compra_id', $pedidoId)
            ->where('estado_id', 3) // Solo pendientes
            ->orderBy('fecha_emision', 'desc')
            ->get();

            return response()->json([
                'success' => true,
                'presupuestos' => $presupuestos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aprobar un presupuesto específico
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'presupuesto_id' => 'required|exists:presupuesto_compras,id'
        ]);

        try {
            DB::beginTransaction();

            // Obtener el presupuesto original
            $presupuestoOriginal = PresupuestoCompra::with(['detalles', 'pedidoCompra'])
                                                    ->findOrFail($validated['presupuesto_id']);

            // Verificar que esté pendiente
            if ($presupuestoOriginal->estado_id !== 3) {
                throw new \Exception('El presupuesto no está en estado pendiente.');
            }

            // Crear el presupuesto aprobado
            $presupuestoAprobado = PresupuestoCompraAprobado::create([
                'nombre' => $presupuestoOriginal->nombre,
                'descripcion' => $presupuestoOriginal->descripcion,
                'proveedor_id' => $presupuestoOriginal->proveedor_id,
                'validez' => $presupuestoOriginal->validez,
                'fecha_emision' => $presupuestoOriginal->fecha_emision,
                'fecha_vencimiento' => $presupuestoOriginal->fecha_vencimiento,
                'estado_id' => 3, // Pendiente en tabla de aprobados
                'usuario_id' => $presupuestoOriginal->usuario_id,
                'pedido_compra_id' => $presupuestoOriginal->pedido_compra_id,
                'aprobado_por' => session('user_id'),
                'fecha_aprobacion' => now()
            ]);

            // Copiar los detalles
            foreach ($presupuestoOriginal->detalles as $detalle) {
                PresupuestoCompraDetalleAprobado::create([
                    'pre_com_apr_id' => $presupuestoAprobado->id,
                    'insumo_id' => $detalle->insumo_id,
                    'cantidad' => $detalle->cantidad,
                    'precio_unitario' => $detalle->precio_unitario,
                    'observacion' => $detalle->observacion,
                    'impuesto_id' => $detalle->impuesto_id
                ]);
            }

            // Cambiar estado del presupuesto original a confirmado (4)
            $presupuestoOriginal->update(['estado_id' => 4]);

            // Cambiar estado del pedido a confirmado (4)
            $presupuestoOriginal->pedidoCompra->update(['estado_id' => 4]);

            DB::commit();

            // Redirigir al index con mensaje de éxito
            return redirect()->route('presupuesto_compra_aprobado.index')
                            ->with('success', 'Presupuesto aprobado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();

            // Redirigir al index con mensaje de error
            return redirect()->route('presupuesto_compra_aprobado.index')
                            ->with('error', 'Error al aprobar el presupuesto: ' . $e->getMessage());
        }
    }

    public function getDetallePedido($pedidoId)
    {
        try {
            $detalles = PedidoCompraDetalle::with([
                'insumo.marca',
                'insumo.unidadMedida'
            ])->where('pedido_compra_id', $pedidoId)
            ->get();

            return response()->json([
                'success' => true,
                'detalles' => $detalles
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $presupuestoAprobado = PresupuestoCompraAprobado::with([
                'proveedor',
                'estado',
                'usuario.persona',
                'aprobadoPor.persona',
                'pedidoCompra.sucursal',
                'pedidoCompra.deposito',
                'detalles.insumo.marca',
                'detalles.insumo.unidadMedida',
                'detalles.impuesto'
            ])->findOrFail($id);

            return view('presupuesto_compra_aprobado.show', compact('presupuestoAprobado'));

        } catch (\Exception $e) {
            return redirect()->route('presupuesto_compra_aprobado.index')
                            ->with('error', 'Error al cargar el presupuesto: ' . $e->getMessage());
        }
    }

}
