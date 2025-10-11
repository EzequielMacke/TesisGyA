<?php

namespace App\Http\Controllers;

use App\Models\Impuesto;
use App\Models\PedidoCompra;
use App\Models\PresupuestoCompra;
use App\Models\PresupuestoCompraDetalle;
use App\Models\PresupuestoServicio;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresupuestoCompraController extends Controller
{
    public function index()
    {
        // Solo mostrar pedidos con estado 3 (pendientes)
        $pedidos = PedidoCompra::with([
            'usuario.persona',
            'sucursal',
            'deposito',
            'estado',
            'detalles.insumo.marca'
        ])
        ->where('estado_id', 3) // Solo pedidos pendientes
        ->withCount(['presupuestos']) // Contar presupuestos por pedido
        ->orderBy('fecha', 'desc')
        ->get();

        return view('presupuesto_compra.index', compact('pedidos'));
    }

    public function showPedido($id)
    {
        // Obtener pedido con todas las relaciones necesarias
        $pedido = PedidoCompra::with([
            'usuario.persona',
            'sucursal',
            'deposito',
            'estado',
            'detalles.insumo.marca',
            'detalles.insumo.unidadMedida',
            'presupuestos' => function($query) {
                $query->with(['proveedor', 'estado'])
                    ->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id);

        // Verificar que el pedido esté en estado pendiente (3)
        if ($pedido->estado_id !== 3) {
            return redirect()->route('presupuesto_compra.index')
                            ->with('error', 'Este pedido no está disponible para presupuestar.');
        }

        return view('presupuesto_compra.show_pedido', compact('pedido'));
    }


    public function create($pedidoId)
    {
        // Obtener el pedido con sus detalles
        $pedido = PedidoCompra::with([
            'usuario.persona',
            'sucursal',
            'deposito',
            'estado',
            'detalles.insumo.marca',
            'detalles.insumo.unidadMedida'
        ])->findOrFail($pedidoId);

        // Verificar que el pedido esté pendiente
        if ($pedido->estado_id !== 3) {
            return redirect()->route('presupuesto_compra.index')
                            ->with('error', 'Este pedido no está disponible para presupuestar.');
        }

        // Obtener el proveedor del usuario logueado
        $proveedor = Proveedor::where('usuario_id', session('user_id'))
                            ->where('estado_id', 1)
                            ->first();

        if (!$proveedor) {
            return redirect()->route('presupuesto_compra.index')
                            ->with('error', 'No tiene un proveedor asociado a su usuario.');
        }

        // Obtener impuestos activos
        $impuestos = Impuesto::where('estado_id', 1)
                            ->orderBy('descripcion')
                            ->get();

        // Obtener el siguiente número de presupuesto para este proveedor y pedido
        $numeroPresupuesto = PresupuestoCompra::where('pedido_compra_id', $pedidoId)
                                            ->where('proveedor_id', $proveedor->id)
                                            ->count() + 1;

        return view('presupuesto_compra.create', compact('pedido', 'proveedor', 'impuestos', 'numeroPresupuesto'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pedido_compra_id' => 'required|exists:pedido_compras,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'proveedor_id' => 'required|exists:proveedor,id',
            'validez' => 'required|integer|min:1|max:365',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'required|date|after:fecha_emision',
            'detalles' => 'required|array|min:1',
            'detalles.*.insumo_id' => 'required|exists:insumo,id',
            'detalles.*.cantidad' => 'required|numeric|min:0.01',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
            'detalles.*.impuesto_id' => 'required|exists:impuestos,id',
            'detalles.*.observacion' => 'nullable|string|max:300' // ← NUEVA VALIDACIÓN
        ]);

        try {
            DB::beginTransaction();

            // Crear el presupuesto
            $presupuesto = PresupuestoCompra::create([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'proveedor_id' => $validated['proveedor_id'],
                'validez' => $validated['validez'],
                'fecha_emision' => $validated['fecha_emision'],
                'fecha_vencimiento' => $validated['fecha_vencimiento'],
                'estado_id' => 3,
                'usuario_id' => session('user_id'),
                'pedido_compra_id' => $validated['pedido_compra_id']
            ]);

            // Crear los detalles del presupuesto
            foreach ($validated['detalles'] as $detalle) {
                $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];

                // Obtener el impuesto
                $impuesto = Impuesto::find($detalle['impuesto_id']);

                // Calcular impuesto (si no es exenta)
                $montoImpuesto = 0;
                if ($impuesto->id !== 1) { // Si no es "Exentas"
                    $montoImpuesto = round($subtotal / $impuesto->calculo);
                }

                PresupuestoCompraDetalle::create([
                    'presupuesto_compra_id' => $presupuesto->id,
                    'insumo_id' => $detalle['insumo_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'impuesto_id' => $detalle['impuesto_id'],
                    'observacion' => $detalle['observacion'] ?? null, // ← NUEVA LÍNEA
                ]);
            }

            DB::commit();

            return redirect()->route('presupuesto_compra.show_pedido', $validated['pedido_compra_id'])
                            ->with('success', 'Presupuesto creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Error al crear el presupuesto: ' . $e->getMessage());
        }
    }

    public function obtenerSiguienteNumero($pedidoId, $proveedorId)
    {
        $numeroSiguiente = PresupuestoCompra::where('pedido_compra_id', $pedidoId)
                                        ->where('proveedor_id', $proveedorId)
                                        ->count() + 1;

        return response()->json(['numero' => $numeroSiguiente]);
    }

    public function show($id)
    {
        $presupuesto = PresupuestoServicio::with([
            'cliente',
            'obra',
            'visitaPrevia.fotos',
            'visitaPrevia.planos',
            'visitaPrevia.estado',
            'detalles.ensayo.servicio',
            'detalles.impuesto',
            'usuario'
        ])->findOrFail($id);

        return view('presupuesto_servicio.show', compact('presupuesto'));
    }
}
