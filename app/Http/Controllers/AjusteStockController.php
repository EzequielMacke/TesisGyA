<?php

namespace App\Http\Controllers;

use App\Models\AjusteStock;
use App\Models\AjusteStockDetalle;
use App\Models\Cliente;
use App\Models\Deposito;
use App\Models\Inventario;
use App\Models\Obra;
use Illuminate\Http\Request;

class AjusteStockController extends Controller
{
    public function index(Request $request)
    {
        $estado_id   = $request->input('estado_id');
        $destino     = $request->input('destino');
        $fecha_desde = $request->input('fecha_desde');
        $search      = $request->input('search');

        $query = AjusteStock::with(['obra', 'deposito', 'estado', 'usuario', 'detalles.insumo']);

        if ($estado_id) {
            $query->where('estado_id', $estado_id);
        }
        if ($destino === 'obra') {
            $query->whereNotNull('obra_id');
        } elseif ($destino === 'deposito') {
            $query->whereNotNull('deposito_id');
        }
        if ($fecha_desde) {
            $query->whereDate('fecha', '>=', $fecha_desde);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('observacion', 'like', "%$search%")
                    ->orWhereHas('obra', fn($q2) => $q2->where('descripcion', 'like', "%$search%"))
                    ->orWhereHas('deposito', fn($q2) => $q2->where('descripcion', 'like', "%$search%"));
            });
        }

        $ajustes = $query->orderByDesc('id')->get();

        return view('ajuste_stock.index', compact('ajustes'));
    }

    public function create()
    {
        $depositoIds = Inventario::whereNull('obra_id')->distinct()->pluck('deposito_id');
        $depositos = Deposito::whereIn('id', $depositoIds)
            ->where('estado_id', 1)
            ->orderBy('descripcion')
            ->get();

        $clienteIds = Inventario::whereNotNull('obra_id')
            ->join('obras', 'inventario.obra_id', '=', 'obras.id')
            ->distinct()
            ->pluck('obras.cliente_id');
        $clientes = Cliente::whereIn('id', $clienteIds)
            ->orderBy('razon_social')
            ->get(['id', 'razon_social']);

        return view('ajuste_stock.create', compact('depositos', 'clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_destino' => 'required|in:deposito,obra',
            'fecha'        => 'required|date',
            'detalles'     => 'required|array|min:1',
        ]);

        $usuarioId = session('user_id');
        if (!$usuarioId) {
            return back()->withInput()->with('error', 'Sesión expirada. Volvé a iniciar sesión.');
        }

        $tipoDestino = $request->tipo_destino;
        $obraId      = null;
        $depositoId  = null;

        if ($tipoDestino === 'obra') {
            $request->validate(['obra_id' => 'required|exists:obras,id']);
            $obraId = $request->obra_id;
        } else {
            $request->validate(['deposito_id' => 'required|exists:deposito,id']);
            $depositoId = $request->deposito_id;
        }

        $detalles = collect($request->detalles)
            ->filter(fn($d) => isset($d['cantidad']) && $d['cantidad'] > 0);

        if ($detalles->isEmpty()) {
            return back()->withInput()->with('error', 'Debe ingresar al menos un insumo con cantidad mayor a 0.');
        }

        foreach ($detalles as $det) {
            if (empty($det['motivo'])) {
                return back()->withInput()->with('error', 'El motivo es obligatorio para todos los insumos con cantidad.');
            }

            if (($det['tipo_ajuste'] ?? 1) == 2) {
                $inv = $obraId
                    ? Inventario::where('obra_id', $obraId)->where('insumo_id', $det['insumo_id'])->first()
                    : Inventario::where('deposito_id', $depositoId)->whereNull('obra_id')->where('insumo_id', $det['insumo_id'])->first();

                if (!$inv || $inv->cantidad < $det['cantidad']) {
                    return back()->withInput()->with('error', 'La cantidad a restar excede el stock disponible para uno o más insumos.');
                }
            }
        }

        try {
            $ajuste = AjusteStock::create([
                'obra_id'     => $obraId,
                'deposito_id' => $depositoId,
                'observacion' => $request->observacion,
                'estado_id'   => 3,
                'fecha'       => $request->fecha,
                'usuario_id'  => $usuarioId,
            ]);

            foreach ($detalles as $det) {
                AjusteStockDetalle::create([
                    'ajuste_stock_id' => $ajuste->id,
                    'insumo_id'       => $det['insumo_id'],
                    'cantidad'        => $det['cantidad'],
                    'motivo'          => $det['motivo'],
                    'tipo_ajuste'     => $det['tipo_ajuste'] ?? 1,
                    'observacion'     => $det['observacion'] ?? null,
                ]);
            }

            return redirect()->route('ajuste_stocks.index')
                ->with('success', 'Ajuste #' . str_pad($ajuste->id, 3, '0', STR_PAD_LEFT) . ' creado correctamente. Confirmarlo para aplicar al inventario.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al crear el ajuste: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $ajuste = AjusteStock::with([
            'obra', 'deposito', 'detalles.insumo.marca', 'detalles.insumo.unidadMedida',
        ])->findOrFail($id);

        if ($ajuste->estado_id != 3) {
            return redirect()->route('ajuste_stocks.index')
                ->with('error', 'Solo se pueden editar ajustes pendientes.');
        }

        $invQuery = Inventario::with(['insumo.marca', 'insumo.unidadMedida']);
        if ($ajuste->obra_id) {
            $invQuery->where('obra_id', $ajuste->obra_id);
        } else {
            $invQuery->where('deposito_id', $ajuste->deposito_id)->whereNull('obra_id');
        }

        $inventarioItems = $invQuery->orderByDesc('cantidad')->get()->map(fn($item) => [
            'inventario_id'   => $item->id,
            'insumo_id'       => $item->insumo_id,
            'descripcion'     => $item->insumo->descripcion ?? '-',
            'marca'           => $item->insumo->marca->descripcion ?? '-',
            'unidad'          => $item->insumo->unidadMedida->descripcion ?? '-',
            'cantidad_actual' => (float) $item->cantidad,
        ])->values()->toArray();

        $detallesExistentes = $ajuste->detalles->keyBy('insumo_id')->map(fn($d) => [
            'cantidad'    => (float) $d->cantidad,
            'tipo_ajuste' => (int) $d->tipo_ajuste,
            'motivo'      => $d->motivo ?? '',
            'observacion' => $d->observacion ?? '',
        ])->toArray();

        return view('ajuste_stock.edit', compact('ajuste', 'inventarioItems', 'detallesExistentes'));
    }

    public function update(Request $request, $id)
    {
        $ajuste = AjusteStock::with('detalles')->findOrFail($id);

        if ($ajuste->estado_id != 3) {
            return back()->with('error', 'Solo se pueden editar ajustes pendientes.');
        }

        $request->validate([
            'fecha'    => 'required|date',
            'detalles' => 'required|array|min:1',
        ]);

        $detalles = collect($request->detalles)
            ->filter(fn($d) => isset($d['cantidad']) && $d['cantidad'] > 0);

        if ($detalles->isEmpty()) {
            return back()->withInput()->with('error', 'Debe ingresar al menos un insumo con cantidad mayor a 0.');
        }

        foreach ($detalles as $det) {
            if (empty($det['motivo'])) {
                return back()->withInput()->with('error', 'El motivo es obligatorio para todos los insumos con cantidad.');
            }

            if (($det['tipo_ajuste'] ?? 1) == 2) {
                $inv = $ajuste->obra_id
                    ? Inventario::where('obra_id', $ajuste->obra_id)->where('insumo_id', $det['insumo_id'])->first()
                    : Inventario::where('deposito_id', $ajuste->deposito_id)->whereNull('obra_id')->where('insumo_id', $det['insumo_id'])->first();

                if (!$inv || $inv->cantidad < $det['cantidad']) {
                    return back()->withInput()->with('error', 'La cantidad a restar excede el stock disponible para uno o más insumos.');
                }
            }
        }

        try {
            $ajuste->update([
                'fecha'       => $request->fecha,
                'observacion' => $request->observacion,
            ]);

            $ajuste->detalles()->delete();

            foreach ($detalles as $det) {
                AjusteStockDetalle::create([
                    'ajuste_stock_id' => $ajuste->id,
                    'insumo_id'       => $det['insumo_id'],
                    'cantidad'        => $det['cantidad'],
                    'motivo'          => $det['motivo'],
                    'tipo_ajuste'     => $det['tipo_ajuste'] ?? 1,
                    'observacion'     => $det['observacion'] ?? null,
                ]);
            }

            return redirect()->route('ajuste_stocks.index')
                ->with('success', 'Ajuste #' . str_pad($ajuste->id, 3, '0', STR_PAD_LEFT) . ' actualizado correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al actualizar el ajuste: ' . $e->getMessage());
        }
    }

    public function confirmar($id)
    {
        $ajuste = AjusteStock::with('detalles')->findOrFail($id);

        if ($ajuste->estado_id != 3) {
            return back()->with('error', 'Solo se pueden confirmar ajustes pendientes.');
        }

        foreach ($ajuste->detalles as $det) {
            $query = Inventario::where('insumo_id', $det->insumo_id);
            if ($ajuste->obra_id) {
                $query->where('obra_id', $ajuste->obra_id);
            } else {
                $query->where('deposito_id', $ajuste->deposito_id)->whereNull('obra_id');
            }

            $inv = $query->first();
            if ($inv) {
                if ($det->tipo_ajuste == 1) {
                    $inv->increment('cantidad', $det->cantidad);
                } else {
                    $inv->decrement('cantidad', $det->cantidad);
                }
            }
        }

        $ajuste->estado_id = 4;
        $ajuste->save();

        return redirect()->route('ajuste_stocks.index')
            ->with('success', 'Ajuste confirmado y aplicado al inventario correctamente.');
    }

    public function anular($id)
    {
        $ajuste = AjusteStock::findOrFail($id);

        if ($ajuste->estado_id != 3) {
            return back()->with('error', 'Solo se pueden anular ajustes pendientes.');
        }

        $ajuste->estado_id = 5;
        $ajuste->save();

        return redirect()->route('ajuste_stocks.index')->with('success', 'Ajuste anulado correctamente.');
    }

    public function apiObrasPorCliente($clienteId)
    {
        $obraIds = Inventario::whereNotNull('obra_id')->distinct()->pluck('obra_id');
        $obras = Obra::where('cliente_id', $clienteId)
            ->whereIn('id', $obraIds)
            ->get(['id', 'descripcion']);

        return response()->json($obras);
    }

    public function apiInventarioDeposito($depositoId)
    {
        $items = Inventario::with(['insumo.marca', 'insumo.unidadMedida'])
            ->where('deposito_id', $depositoId)
            ->whereNull('obra_id')
            ->orderByDesc('cantidad')
            ->get()
            ->map(fn($item) => [
                'inventario_id'  => $item->id,
                'insumo_id'      => $item->insumo_id,
                'descripcion'    => $item->insumo->descripcion ?? '-',
                'marca'          => $item->insumo->marca->descripcion ?? '-',
                'unidad'         => $item->insumo->unidadMedida->descripcion ?? '-',
                'cantidad_actual' => (float) $item->cantidad,
            ]);

        return response()->json(['items' => $items]);
    }

    public function apiInventarioObra($obraId)
    {
        $items = Inventario::with(['insumo.marca', 'insumo.unidadMedida'])
            ->where('obra_id', $obraId)
            ->orderByDesc('cantidad')
            ->get()
            ->map(fn($item) => [
                'inventario_id'  => $item->id,
                'insumo_id'      => $item->insumo_id,
                'descripcion'    => $item->insumo->descripcion ?? '-',
                'marca'          => $item->insumo->marca->descripcion ?? '-',
                'unidad'         => $item->insumo->unidadMedida->descripcion ?? '-',
                'cantidad_actual' => (float) $item->cantidad,
            ]);

        return response()->json(['items' => $items]);
    }
}
