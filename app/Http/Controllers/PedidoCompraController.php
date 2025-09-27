<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PedidoCompra;
use App\Models\PedidoCompraDetalle;
use App\Models\Sucursal;
use App\Models\Deposito;
use App\Models\Insumo;
use App\Models\Marca;
use Illuminate\Support\Facades\DB;

class PedidoCompraController extends Controller
{
    public function index(Request $request)
    {
        // Obtener todas las sucursales para el filtro
        $sucursales = Sucursal::where('estado_id', 1)->orderBy('descripcion')->get();

        // Consulta base de pedidos con relaciones
        $query = PedidoCompra::with([
            'usuario',
            'sucursal',
            'deposito',
            'estado'
        ]);

        // Filtros opcionales
        if ($request->filled('estado')) {
            $query->where('estado_id', $request->estado);
        }

        if ($request->filled('sucursal')) {
            $query->where('sucursal_id', $request->sucursal);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        // Obtener pedidos ordenados por fecha descendente (más reciente primero)
        $pedidos = $query->orderBy('created_at', 'desc')
                        ->orderBy('id', 'desc')
                        ->get();

        return view('pedido_compra.index', compact('pedidos', 'sucursales'));
    }

    public function create()
    {
        // Obtener la sucursal y depósito del usuario actual (por defecto, puedes ajustar esta lógica)
        $sucursal = Sucursal::where('estado_id', 1)->first();
        $deposito = $sucursal ? Deposito::find($sucursal->deposito_id) : Deposito::where('estado_id', 1)->first();

        // Obtener marcas activas
        $marcas = Marca::where('estado_id', 1)
                      ->orderBy('descripcion')
                      ->get();

        // Obtener insumos activos con sus relaciones
        $insumos = Insumo::with(['marca', 'unidadMedida'])
                         ->where('estado_id', 1)
                         ->whereHas('marca', function($query) {
                             $query->where('estado_id', 1);
                         })
                         ->orderBy('descripcion')
                         ->get();

        return view('pedido_compra.create', compact(
            'sucursal',
            'deposito',
            'marcas',
            'insumos'
        ));
    }

    public function store(Request $request)
    {
        // Validar datos del pedido
        $validated = $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'sucursal_id' => 'required|exists:sucursal,id',
            'deposito_id' => 'required|exists:deposito,id',
            'fecha' => 'required|date',
            'estado_id' => 'required|exists:estados,id',
            'observacion' => 'nullable|string|max:500',
            'insumos' => 'required|array|min:1',
            'insumos.*.insumo_id' => 'required|exists:insumo,id',
            'insumos.*.cantidad' => 'required|numeric|min:0.01',
            'insumos.*.observacion' => 'nullable|string|max:300'
        ], [
            'insumos.required' => 'Debe agregar al menos un insumo al pedido.',
            'insumos.min' => 'Debe agregar al menos un insumo al pedido.',
            'insumos.*.cantidad.min' => 'La cantidad debe ser mayor a 0.',
            'insumos.*.observacion.max' => 'La observación no puede exceder 300 caracteres.'
        ]);

        DB::beginTransaction();

        try {
            // Crear el pedido de compra
            $pedido = PedidoCompra::create([
                'usuario_id' => $validated['usuario_id'],
                'sucursal_id' => $validated['sucursal_id'],
                'deposito_id' => $validated['deposito_id'],
                'fecha' => $validated['fecha'],
                'estado_id' => $validated['estado_id'],
                'observacion' => $validated['observacion']
            ]);

            // Crear los detalles del pedido
            foreach ($validated['insumos'] as $insumoData) {
                PedidoCompraDetalle::create([
                    'pedido_compra_id' => $pedido->id,
                    'insumo_id' => $insumoData['insumo_id'],
                    'cantidad' => $insumoData['cantidad'],
                    'observacion' => $insumoData['observacion'] ?? null
                ]);
            }

            DB::commit();

            return redirect()->route('pedido_compra.index')
                            ->with('success', 'Pedido de compra creado exitosamente con ' . count($validated['insumos']) . ' insumos.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                            ->with('error', 'Error al crear el pedido de compra.')
                            ->withInput();
        }
    }

    public function show($id)
    {
        $pedido = PedidoCompra::with([
            'usuario',
            'sucursal',
            'deposito',
            'estado',
            'detalles.insumo.marca',
            'detalles.insumo.unidadMedida'
        ])->findOrFail($id);

        return view('pedido_compra.show', compact('pedido'));
    }

    public function edit($id)
    {
        $pedido = PedidoCompra::with([
            'usuario',
            'sucursal',
            'deposito',
            'detalles.insumo.marca',
            'detalles.insumo.unidadMedida'
        ])->findOrFail($id);

        // Solo permitir editar pedidos pendientes
        if ($pedido->estado_id != 3) {
            return redirect()->route('pedido_compra.index')
                           ->with('error', 'Solo se pueden editar pedidos en estado Pendiente.');
        }

        // Obtener marcas activas
        $marcas = Marca::where('estado_id', 1)
                      ->orderBy('descripcion')
                      ->get();

        // Obtener insumos activos
        $insumos = Insumo::with(['marca', 'unidadMedida'])
                         ->where('estado_id', 1)
                         ->whereHas('marca', function($query) {
                             $query->where('estado_id', 1);
                         })
                         ->orderBy('descripcion')
                         ->get();

        return view('pedido_compra.edit', compact('pedido', 'marcas', 'insumos'));
    }

    public function update(Request $request, $id)
    {
        $pedido = PedidoCompra::findOrFail($id);

        // Solo permitir actualizar pedidos pendientes
        if ($pedido->estado_id != 3) {
            return redirect()->route('pedido_compra.index')
                           ->with('error', 'Solo se pueden editar pedidos en estado Pendiente.');
        }

        $request->validate([
            'observacion' => 'nullable|string|max:500',
            'insumos' => 'required|array|min:1',
            'insumos.*.insumo_id' => 'required|exists:insumos,id',
            'insumos.*.cantidad' => 'required|numeric|min:0.01|max:999999.99'
        ], [
            'insumos.required' => 'Debe agregar al menos un insumo al pedido.',
            'insumos.min' => 'Debe agregar al menos un insumo al pedido.'
        ]);

        try {
            DB::beginTransaction();

            // Actualizar el pedido
            $pedido->update([
                'observacion' => $request->observacion
            ]);

            // Eliminar detalles existentes
            $pedido->detalles()->delete();

            // Crear nuevos detalles
            foreach ($request->insumos as $insumoData) {
                PedidoCompraDetalle::create([
                    'pedido_compra_id' => $pedido->id,
                    'insumo_id' => $insumoData['insumo_id'],
                    'cantidad' => $insumoData['cantidad'],
                ]);
            }

            DB::commit();

            return redirect()->route('pedido_compra.index')
                           ->with('success', 'Pedido de compra actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                        ->with('error', 'Error al actualizar el pedido: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $pedido = PedidoCompra::findOrFail($id);

        // Solo permitir eliminar pedidos pendientes
        if ($pedido->estado_id != 3) {
            return redirect()->route('pedido_compra.index')
                           ->with('error', 'Solo se pueden eliminar pedidos en estado Pendiente.');
        }

        try {
            DB::beginTransaction();

            // Eliminar detalles primero
            $pedido->detalles()->delete();

            // Eliminar el pedido
            $pedido->delete();

            DB::commit();

            return redirect()->route('pedido_compra.index')
                           ->with('success', 'Pedido de compra eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar el pedido: ' . $e->getMessage());
        }
    }

    public function anular($id)
    {
        try {
            $pedido = PedidoCompra::findOrFail($id);

            // Verificar que el pedido se puede anular (solo pendientes)
            if ($pedido->estado_id != 3) {
                return redirect()->back()->with('error', 'Solo se pueden anular pedidos en estado Pendiente.');
            }

            // Cambiar estado a Anulado (5)
            $pedido->estado_id = 5;
            $pedido->save();

            return redirect()->back()->with('success', 'Pedido anulado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al anular el pedido: ' . $e->getMessage());
        }
    }

}
