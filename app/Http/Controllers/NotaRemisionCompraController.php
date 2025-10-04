<?php

namespace App\Http\Controllers;

use App\Models\Deposito;
use App\Models\Funcionario;
use App\Models\Inventario;
use Illuminate\Http\Request;
use App\Models\NotaRemisionCompra;
use App\Models\NotaRemisionCompraDetalle;
use App\Models\OrdenCompra;
use App\Models\Proveedor;
use App\Models\Sucursal;
use App\Models\TipoVehiculo;

class NotaRemisionCompraController extends Controller
{
    /**
     * Mostrar listado de notas de remisión de compra con filtros.
     */
    public function index(Request $request)
    {
        // Obtener proveedores para el filtro
        $proveedores = Proveedor::where('estado_id', 1)->orderBy('razon_social')->get();

        // Consulta base con relaciones
        $query = NotaRemisionCompra::with([
            'proveedor',
            'estado',
            'usuario.persona',
            'tipoVehiculo'
        ]);

        // Filtros opcionales
        if ($request->filled('estado')) {
            $query->where('estado_id', $request->estado);
        }

        if ($request->filled('proveedor')) {
            $query->where('proveedor_id', $request->proveedor);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_emision', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_emision', '<=', $request->fecha_hasta);
        }

        // Obtener notas ordenadas por fecha descendente
        $notas = $query->orderBy('created_at', 'desc')
                       ->orderBy('id', 'desc')
                       ->get();

        return view('nota_remision_compra.index', compact('notas', 'proveedores'));
    }

    public function create()
    {
        $ordenes = OrdenCompra::with('proveedor')
            ->where('estado_id', 3)
            ->orderBy('fecha', 'desc')
            ->get();

        $funcionarios = Funcionario::with('persona')
            ->where('estado_id', 1)
            ->where('cargo_id', 4)
            ->get();

        $tiposVehiculo = TipoVehiculo::where('estado_id', 1)
            ->orderBy('descripcion')
            ->get();

        // Obtener sucursal_id de la sesión
        $sucursalId = session('user_sucursal_id');
        // Buscar el depósito asociado a la sucursal
        $sucursalId = session('user_sucursal_id');
        $deposito = null;
        if ($sucursalId) {
            // Obtener la sucursal y su depósito asociado
            $sucursal = Sucursal::find($sucursalId);
            if ($sucursal && $sucursal->deposito_id) {
                $deposito = Deposito::where('id', $sucursal->deposito_id)
                    ->where('estado_id', 1)
                    ->first();
            }
        }
        return view('nota_remision_compra.create', compact('ordenes', 'funcionarios', 'tiposVehiculo', 'deposito'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'orden_compra_id' => 'required|exists:orden_compra,id',
            'fecha_emision' => 'required|date',
            'fecha_recepcion' => 'required|date',
            'recibido_por' => 'required|exists:funcionarios,id',
            'numero_remision' => 'required|string|max:30',
            'detalle' => 'required|array|min:1',
            'detalle.*.insumo_id' => 'required|exists:insumo,id',
        ]);


        $orden = OrdenCompra::with('detalles')->findOrFail($request->orden_compra_id);

        if ($orden->estado_id != 3) {
            return back()->with('error', 'Solo se pueden crear notas de remisión para órdenes pendientes.');
        }

        // Filtrar los insumos con cantidad entregada mayor a 0
        $detallesARecibir = array_filter($request->detalle, function ($item) {
            return isset($item['cantidad_entregada']) && $item['cantidad_entregada'] > 0;
        });

        if (count($detallesARecibir) == 0) {
            return back()->with('error', 'Debe ingresar al menos un insumo con cantidad entregada mayor a 0.');
        }

        // Validar que no se entregue más de lo pendiente
        foreach ($detallesARecibir as $item) {
            $detalle = $orden->detalles->where('insumo_id', $item['insumo_id'])->first();
            $entregado = NotaRemisionCompraDetalle::where('insumo_id', $item['insumo_id'])
                ->whereHas('notaRemision', function ($q) use ($orden) {
                    $q->where('orden_compra_id', $orden->id);
                })->sum('cantidad_entregada');
            $pendiente = $detalle->cantidad - $entregado;
            if ($item['cantidad_entregada'] > $pendiente) {
                return back()->with('error', 'No puede entregar más de lo pendiente para algún insumo.');
            }
        }

        // Crear la nota de remisión
        $nota = NotaRemisionCompra::create([
            'deposito_id' => $request->deposito_id,
            'nombre' => $request->nombre_remision,
            'nro' => $request->numero_remision,
            'proveedor_id' => $orden->proveedor_id,
            'fecha_recepcion' => $request->fecha_recepcion,
            'fecha_emision' => $request->fecha_emision,
            'usuario_id' => session('user_id'),
            'estado_id' => 3,
            'observacion' => $request->observacion,
            'datos_empresa_id' => 1,
            'conductor_nombre' => $request->conductor_nombre,
            'conductor_ci' => $request->conductor_ci,
            'vehiculo_chapa' => $request->vehiculo_chapa,
            'tipo_vehiculo_id' => $request->tipo_vehiculo_id,
            'orden_compra_id' => $orden->id,
            'origen' => $request->origen,
            'destino' => $request->destino,
            'recibido_por' => $request->recibido_por,
        ]);

        // Guardar los detalles y actualizar inventario solo para insumos con cantidad_entregada > 0
        foreach ($detallesARecibir as $item) {
            NotaRemisionCompraDetalle::create([
                'nota_remision_id' => $nota->id,
                'insumo_id' => $item['insumo_id'],
                'cantidad_pedida' => $orden->detalles->where('insumo_id', $item['insumo_id'])->first()->cantidad,
                'cantidad_entregada' => $item['cantidad_entregada'],
                'observacion' => $item['observacion'] ?? null,
            ]);

            // Actualizar inventario
            $depositoId = $request->deposito_id;
            $inventario = Inventario::firstOrNew([
                'deposito_id' => $depositoId,
                'insumo_id' => $item['insumo_id'],
            ]);
            $inventario->cantidad = ($inventario->cantidad ?? 0) + $item['cantidad_entregada'];
            $inventario->estado_id = 1; // Activo
            $inventario->save();
        }

        // Actualizar estado de los detalles de la orden
        foreach ($orden->detalles as $detalle) {
            $entregado = NotaRemisionCompraDetalle::where('insumo_id', $detalle->insumo_id)
                ->whereHas('notaRemision', function ($q) use ($orden) {
                    $q->where('orden_compra_id', $orden->id);
                })->sum('cantidad_entregada');
            if ($entregado >= $detalle->cantidad) {
                $detalle->estado_id = 4; // Confirmado
                $detalle->save();
            }
        }

        // Si todos los detalles están confirmados, actualizar la orden
        if ($orden->detalles()->where('estado_id', '!=', 4)->count() == 0) {
            $orden->estado_id = 4; // Confirmado
            $orden->save();
        }

        return redirect()->route('nota_remision_compra.index')->with('success', 'Nota de remisión creada correctamente y stock actualizado.');
    }

    public function detallesPendientes($ordenId)
    {
        $orden = OrdenCompra::with(['detalles.insumo', 'detalles.insumo.unidadMedida', 'detalles.impuesto'])->findOrFail($ordenId);

        // Para cada detalle, calcular lo pendiente (cantidad pedida - entregada en notas anteriores)
        $detalles = $orden->detalles->map(function ($detalle) use ($orden) {
            $entregado = NotaRemisionCompraDetalle::where('insumo_id', $detalle->insumo_id)
                ->whereHas('notaRemision', function ($q) use ($orden) {
                    $q->where('orden_compra_id', $orden->id);
                })
                ->sum('cantidad_entregada');
            $pendiente = $detalle->cantidad - $entregado;
            return [
                'id' => $detalle->id,
                'insumo_id' => $detalle->insumo_id,
                'descripcion' => $detalle->insumo->descripcion,
                'unidad' => $detalle->insumo->unidadMedida->descripcion ?? '',
                'cantidad_pedida' => $detalle->cantidad,
                'cantidad_entregada' => $entregado,
                'cantidad_pendiente' => $pendiente,
                'impuesto' => $detalle->impuesto->descripcion ?? '',
                'observacion' => $detalle->observacion,
            ];
        });

        return response()->json($detalles->values());
    }

    public function show($id)
    {
        $nota = NotaRemisionCompra::with([
            'detalles.insumo.unidadMedida',
            'deposito',
            'recibidoPor.persona',
            'tipoVehiculo',
            'usuario.persona',
            'proveedor',
            'estado'
        ])->findOrFail($id);

        $orden = OrdenCompra::with([
            'proveedor',
            'estado',
            'detalles.insumo.unidadMedida',
            'detalles.estado',
            'presupuestoCompraAprobado.detalles'
        ])->findOrFail($nota->orden_compra_id);

        // Crear un array [insumo_id => precio_unitario]
        $precios = [];
        if ($orden->presupuestoCompraAprobado && $orden->presupuestoCompraAprobado->detalles) {
            foreach ($orden->presupuestoCompraAprobado->detalles as $detalle) {
                $precios[$detalle->insumo_id] = $detalle->precio_unitario;
            }
        }

        return view('nota_remision_compra.show', compact('nota', 'orden', 'precios'));
    }
}
