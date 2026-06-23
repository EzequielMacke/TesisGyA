<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\InsumoUtilizado;
use App\Models\InsumoUtilizadoDetalle;
use App\Models\Inventario;
use App\Models\Obra;
use App\Models\OrdenServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InsumoUtilizadoController extends Controller
{
    public function index(Request $request)
    {
        $query = InsumoUtilizado::with(['obra', 'ordenServicio', 'estado', 'usuario', 'detalles.insumo']);

        // Filtros
        if ($request->filled('obra_id')) {
            $query->where('obra_id', $request->obra_id);
        }
        if ($request->filled('estado_id')) {
            $query->where('estado_id', $request->estado_id);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_registro', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_registro', '<=', $request->fecha_hasta);
        }

        $insumosUtilizados = $query->orderBy('created_at', 'desc')->get();

        // Datos para filtros
        $obras = Obra::where('estado_id', 1)->orderBy('descripcion')->get();
        $estados = Estado::orderBy('descripcion')->get();

        return view('insumos_utilizados.index', compact('insumosUtilizados', 'obras', 'estados'));
    }

    public function create()
    {
        // Órdenes de servicio pendientes, con su obra y cliente para mostrar info de contexto
        $ordenesServicio = OrdenServicio::with(['obra.cliente'])
            ->where('estado_id', 3)
            ->orderBy('nro')
            ->get();

        $proximoNro = sprintf('%07d', InsumoUtilizado::count() + 1);

        return view('insumos_utilizados.create', compact('ordenesServicio', 'proximoNro'));
    }

    public function insumosPorObra($obra_id)
    {
        $insumos = Inventario::with(['insumo.unidadMedida'])
            ->where('obra_id', $obra_id)
            ->where('estado_id', 1)
            ->where('cantidad', '>', 0)
            ->get()
            ->map(function ($inventario) {
                return [
                    'id' => $inventario->insumo_id,
                    'descripcion' => $inventario->insumo->descripcion ?? '-',
                    'unidad' => $inventario->insumo->unidadMedida->descripcion ?? '-',
                    'cantidad' => $inventario->cantidad,
                ];
            })
            ->values();

        return response()->json($insumos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'orden_servicio_id' => 'required|exists:orden_servicio,id',
            'observacion' => 'nullable|string',
            'insumos' => 'required|array|min:1',
            'insumos.*.insumo_id' => 'required|exists:insumo,id',
            'insumos.*.cantidad' => 'required|numeric|min:0.01',
        ], [
            'orden_servicio_id.required' => 'Debe seleccionar una orden de servicio.',
            'insumos.required' => 'Debe agregar al menos un insumo.',
        ]);

        try {
            $ordenServicio = OrdenServicio::findOrFail($request->orden_servicio_id);

            $insumoUtilizado = InsumoUtilizado::create([
                'orden_servicio_id' => $ordenServicio->id,
                'obra_id' => $ordenServicio->obra_id,
                'estado_id' => 3,
                'usuario_id' => session('user_id'),
                'fecha_registro' => Carbon::today(),
                'nro' => sprintf('%07d', InsumoUtilizado::count() + 1),
                'observacion' => $request->observacion,
            ]);

            foreach ($request->insumos as $item) {
                InsumoUtilizadoDetalle::create([
                    'insumo_utilizado_id' => $insumoUtilizado->id,
                    'insumo_id' => $item['insumo_id'],
                    'cantidad' => $item['cantidad'],
                ]);
            }

            return redirect()->route('insumos_utilizados.index')
                            ->with('success', 'Insumos utilizados registrados exitosamente.');

        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Error al registrar los insumos utilizados: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $insumoUtilizado = InsumoUtilizado::with(['ordenServicio.obra.cliente', 'usuario', 'detalles.insumo.unidadMedida'])
            ->findOrFail($id);

        if ($insumoUtilizado->estado_id != 3 || $insumoUtilizado->ordenServicio->estado_id != 3) {
            return redirect()->route('insumos_utilizados.index')
                            ->with('error', 'Solo se pueden editar registros Pendientes cuya orden de servicio también esté Pendiente.');
        }

        $insumosDisponibles = Inventario::with(['insumo.unidadMedida'])
            ->where('obra_id', $insumoUtilizado->obra_id)
            ->where('estado_id', 1)
            ->where('cantidad', '>', 0)
            ->get()
            ->map(function ($inventario) {
                return [
                    'id' => $inventario->insumo_id,
                    'descripcion' => $inventario->insumo->descripcion ?? '-',
                    'unidad' => $inventario->insumo->unidadMedida->descripcion ?? '-',
                    'cantidad' => $inventario->cantidad,
                ];
            })
            ->values();

        $detallesIniciales = $insumoUtilizado->detalles->map(function ($detalle) use ($insumosDisponibles) {
            $inventarioInsumo = $insumosDisponibles->firstWhere('id', $detalle->insumo_id);

            return [
                'id' => $detalle->insumo_id,
                'descripcion' => $detalle->insumo->descripcion ?? '-',
                'unidad' => $detalle->insumo->unidadMedida->descripcion ?? '-',
                'cantidad' => $detalle->cantidad,
                'disponible' => $inventarioInsumo['cantidad'] ?? 0,
            ];
        })->values();

        return view('insumos_utilizados.edit', compact('insumoUtilizado', 'insumosDisponibles', 'detallesIniciales'));
    }

    public function update(Request $request, $id)
    {
        $insumoUtilizado = InsumoUtilizado::with('ordenServicio')->findOrFail($id);

        if ($insumoUtilizado->estado_id != 3 || $insumoUtilizado->ordenServicio->estado_id != 3) {
            return redirect()->route('insumos_utilizados.index')
                            ->with('error', 'Solo se pueden editar registros Pendientes cuya orden de servicio también esté Pendiente.');
        }

        $request->validate([
            'observacion' => 'nullable|string',
            'insumos' => 'required|array|min:1',
            'insumos.*.insumo_id' => 'required|exists:insumo,id',
            'insumos.*.cantidad' => 'required|numeric|min:0.01',
        ], [
            'insumos.required' => 'Debe agregar al menos un insumo.',
        ]);

        try {
            $insumoUtilizado->update([
                'observacion' => $request->observacion,
            ]);

            InsumoUtilizadoDetalle::where('insumo_utilizado_id', $insumoUtilizado->id)->delete();

            foreach ($request->insumos as $item) {
                InsumoUtilizadoDetalle::create([
                    'insumo_utilizado_id' => $insumoUtilizado->id,
                    'insumo_id' => $item['insumo_id'],
                    'cantidad' => $item['cantidad'],
                ]);
            }

            return redirect()->route('insumos_utilizados.index')
                            ->with('success', 'Insumos utilizados actualizados exitosamente.');

        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Error al actualizar los insumos utilizados: ' . $e->getMessage());
        }
    }

    public function confirmar($id)
    {
        $insumoUtilizado = InsumoUtilizado::with(['ordenServicio', 'detalles.insumo'])->findOrFail($id);

        if ($insumoUtilizado->estado_id != 3 || $insumoUtilizado->ordenServicio->estado_id != 3) {
            return back()->with('error', 'Solo se pueden confirmar registros Pendientes cuya orden de servicio también esté Pendiente.');
        }

        foreach ($insumoUtilizado->detalles as $detalle) {
            $inventario = Inventario::where('obra_id', $insumoUtilizado->obra_id)
                ->where('insumo_id', $detalle->insumo_id)
                ->first();

            if (!$inventario || $inventario->cantidad < $detalle->cantidad) {
                return back()->with('error', 'No hay suficiente stock de "' . ($detalle->insumo->descripcion ?? 'insumo') . '" en el inventario de la obra.');
            }
        }

        DB::transaction(function () use ($insumoUtilizado) {
            foreach ($insumoUtilizado->detalles as $detalle) {
                Inventario::where('obra_id', $insumoUtilizado->obra_id)
                    ->where('insumo_id', $detalle->insumo_id)
                    ->decrement('cantidad', $detalle->cantidad);
            }

            $insumoUtilizado->update(['estado_id' => 4]);
        });

        return redirect()->route('insumos_utilizados.index')
                        ->with('success', 'Insumos utilizados confirmados y descontados del inventario.');
    }

    public function anular($id)
    {
        $insumoUtilizado = InsumoUtilizado::with(['ordenServicio', 'detalles'])->findOrFail($id);

        if ($insumoUtilizado->ordenServicio->estado_id != 3) {
            return back()->with('error', 'Solo se pueden anular registros cuya orden de servicio esté Pendiente.');
        }

        if ($insumoUtilizado->estado_id == 4) {
            // Confirmado -> Pendiente: revierte el descuento de inventario
            DB::transaction(function () use ($insumoUtilizado) {
                foreach ($insumoUtilizado->detalles as $detalle) {
                    Inventario::where('obra_id', $insumoUtilizado->obra_id)
                        ->where('insumo_id', $detalle->insumo_id)
                        ->increment('cantidad', $detalle->cantidad);
                }

                $insumoUtilizado->update(['estado_id' => 3]);
            });

            return redirect()->route('insumos_utilizados.index')
                            ->with('success', 'Registro revertido a Pendiente y se restituyeron las cantidades al inventario.');
        }

        if ($insumoUtilizado->estado_id == 3) {
            // Pendiente -> Anulado: no se había descontado inventario
            $insumoUtilizado->update(['estado_id' => 5]);

            return redirect()->route('insumos_utilizados.index')
                            ->with('success', 'Registro anulado exitosamente.');
        }

        return back()->with('error', 'Solo se pueden anular registros Pendientes o Confirmados.');
    }
}
