<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\Estado;
use App\Models\Funcionario;
use App\Models\Obra;
use App\Models\OrdenServicio;
use App\Models\OrdenServicioDetalle;
use App\Models\OrdenServicioFuncionario;
use App\Models\PresupuestoServicioDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrdenServicioController extends Controller
{
    public function index(Request $request)
    {
        $query = OrdenServicio::with(['cliente', 'obra', 'contrato', 'estado', 'usuario', 'detalles.ensayo', 'funcionarios.funcionario.persona']);

        // Filtros
        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }
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

        $ordenesServicio = $query->orderBy('created_at', 'desc')->get();

        // Datos para filtros
        $clientes = Cliente::where('estado_id', 1)->orderBy('razon_social')->get();
        $obras = Obra::where('estado_id', 1)->orderBy('descripcion')->get();
        $estados = Estado::whereIn('id', [3, 4, 5])->orderBy('descripcion')->get();

        return view('orden_servicio.index', compact('ordenesServicio', 'clientes', 'obras', 'estados'));
    }

    public function create()
    {
        // Clientes con al menos un contrato pendiente (sin orden de servicio activa)
        $clientes = Cliente::where('estado_id', 1)
            ->whereIn('id', function ($query) {
                $query->select('cliente_id')
                    ->from('contratos')
                    ->where('estado_id', 3);
            })
            ->orderBy('razon_social')->get();

        $proximoNro = sprintf('%07d', OrdenServicio::count() + 1);

        // Funcionarios activos disponibles para asignar a la orden
        $funcionarios = Funcionario::where('estado_id', 1)->with('persona')->get();

        return view('orden_servicio.create', compact('clientes', 'proximoNro', 'funcionarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'obra_id' => 'required|exists:obras,id',
            'contrato_id' => 'required|exists:contratos,id',
            'observacion' => 'nullable|string',
            'ensayos' => 'required|array|min:1',
            'ensayos.*' => 'exists:ensayos,id',
            'funcionarios' => 'required|array|min:1',
            'funcionarios.*' => 'exists:funcionarios,id',
        ], [
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'obra_id.required' => 'Debe seleccionar una obra.',
            'contrato_id.required' => 'Debe seleccionar un contrato.',
            'ensayos.required' => 'Debe seleccionar al menos un ensayo.',
            'funcionarios.required' => 'Debe seleccionar al menos un funcionario.',
        ]);

        try {
            $contrato = Contrato::findOrFail($request->contrato_id);

            $fechaRegistro = Carbon::today();
            $fechaCulminacionTeorica = $fechaRegistro->copy()->addDays($contrato->plazo_dias);

            $ordenServicio = OrdenServicio::create([
                'nro' => sprintf('%07d', OrdenServicio::count() + 1),
                'datos_empresa_id' => 1,
                'contrato_id' => $contrato->id,
                'presupuesto_servicio_id' => $contrato->presupuesto_servicio_id,
                'cliente_id' => $request->cliente_id,
                'obra_id' => $request->obra_id,
                'estado_id' => 3,
                'fecha_registro' => $fechaRegistro,
                'fecha_culminacion_teorica' => $fechaCulminacionTeorica,
                'observacion' => $request->observacion,
                'usuario_id' => session('user_id'),
            ]);

            foreach ($request->ensayos as $ensayoId) {
                OrdenServicioDetalle::create([
                    'orden_servicio_id' => $ordenServicio->id,
                    'ensayo_id' => $ensayoId,
                ]);
            }

            foreach ($request->funcionarios as $funcionarioId) {
                OrdenServicioFuncionario::create([
                    'orden_servicio_id' => $ordenServicio->id,
                    'funcionario_id' => $funcionarioId,
                ]);
            }

            // Cambiar el estado del contrato a 4 (confirmado)
            $contrato->update(['estado_id' => 4]);

            return redirect()->route('orden_servicio.index')
                            ->with('success', 'Orden de servicio creada exitosamente.');

        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Error al crear la orden de servicio: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $ordenServicio = OrdenServicio::with(['cliente', 'obra', 'contrato.presupuestoServicio', 'usuario'])
            ->findOrFail($id);

        if ($ordenServicio->estado_id != 3) {
            return redirect()->route('orden_servicio.index')
                            ->with('error', 'Solo se pueden editar órdenes de servicio en estado Pendiente.');
        }

        $funcionarios = Funcionario::where('estado_id', 1)->with('persona')->get();

        $funcionariosSeleccionados = OrdenServicioFuncionario::where('orden_servicio_id', $ordenServicio->id)
            ->pluck('funcionario_id')->toArray();

        $ensayosPorServicio = PresupuestoServicioDetalle::with(['ensayo', 'servicio'])
            ->where('presupuesto_servicio_id', $ordenServicio->presupuesto_servicio_id)
            ->get()
            ->groupBy(function ($detalle) {
                return $detalle->servicio->descripcion ?? 'Sin Servicio';
            })
            ->map(function ($detalles, $servicio) {
                return [
                    'servicio' => $servicio,
                    'ensayos' => $detalles->map(function ($detalle) {
                        return [
                            'id' => $detalle->ensayo->id ?? $detalle->ensayos_id,
                            'descripcion' => $detalle->ensayo->descripcion ?? '-',
                        ];
                    })->values(),
                ];
            })
            ->values();

        return view('orden_servicio.edit', compact('ordenServicio', 'funcionarios', 'funcionariosSeleccionados', 'ensayosPorServicio'));
    }

    public function update(Request $request, $id)
    {
        $ordenServicio = OrdenServicio::findOrFail($id);

        if ($ordenServicio->estado_id != 3) {
            return redirect()->route('orden_servicio.index')
                            ->with('error', 'Solo se pueden editar órdenes de servicio en estado Pendiente.');
        }

        $request->validate([
            'observacion' => 'nullable|string',
            'funcionarios' => 'required|array|min:1',
            'funcionarios.*' => 'exists:funcionarios,id',
        ], [
            'funcionarios.required' => 'Debe seleccionar al menos un funcionario.',
        ]);

        try {
            $ordenServicio->update([
                'observacion' => $request->observacion,
            ]);

            OrdenServicioFuncionario::where('orden_servicio_id', $ordenServicio->id)->delete();
            foreach ($request->funcionarios as $funcionarioId) {
                OrdenServicioFuncionario::create([
                    'orden_servicio_id' => $ordenServicio->id,
                    'funcionario_id' => $funcionarioId,
                ]);
            }

            return redirect()->route('orden_servicio.index')
                            ->with('success', 'Orden de servicio actualizada exitosamente.');

        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Error al actualizar la orden de servicio: ' . $e->getMessage());
        }
    }

    public function anular($id)
    {
        $ordenServicio = OrdenServicio::findOrFail($id);

        if ($ordenServicio->estado_id != 3) {
            return redirect()->route('orden_servicio.index')
                            ->with('error', 'Solo se pueden anular órdenes de servicio en estado Pendiente.');
        }

        try {
            $ordenServicio->update(['estado_id' => 5]);

            Contrato::where('id', $ordenServicio->contrato_id)->update(['estado_id' => 3]);

            return redirect()->route('orden_servicio.index')
                            ->with('success', 'Orden de servicio anulada exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al anular la orden de servicio: ' . $e->getMessage());
        }
    }

    public function obrasPorCliente($cliente_id)
    {
        // Solo obras con al menos un contrato pendiente (sin orden de servicio activa)
        $obras = Obra::where('cliente_id', $cliente_id)
            ->where('estado_id', 1)
            ->whereIn('id', function ($query) {
                $query->select('obra_id')
                    ->from('contratos')
                    ->where('estado_id', 3);
            })
            ->orderBy('descripcion')->get();

        return response()->json($obras);
    }

    public function contratosPorObra($obra_id)
    {
        $contratos = Contrato::with('presupuestoServicio')
            ->where('obra_id', $obra_id)
            ->where('estado_id', 3)
            ->orderBy('id')
            ->get()
            ->map(function ($contrato) {
                return [
                    'id' => $contrato->id,
                    'plazo_dias' => $contrato->plazo_dias,
                    'fecha_firma' => $contrato->fecha_firma ? $contrato->fecha_firma->format('d/m/Y') : null,
                    'monto' => $contrato->monto,
                    'garantia_meses' => $contrato->garantia_meses,
                    'presupuesto_servicio_id' => $contrato->presupuesto_servicio_id,
                    'numero_presupuesto' => $contrato->presupuestoServicio->numero_presupuesto ?? null,
                ];
            });

        return response()->json($contratos);
    }

    public function ensayosPorPresupuesto($presupuesto_servicio_id)
    {
        $detalles = PresupuestoServicioDetalle::with(['ensayo', 'servicio'])
            ->where('presupuesto_servicio_id', $presupuesto_servicio_id)
            ->get();

        $data = $detalles
            ->groupBy(function ($detalle) {
                return $detalle->servicio->descripcion ?? 'Sin Servicio';
            })
            ->map(function ($detalles, $servicio) {
                return [
                    'servicio' => $servicio,
                    'ensayos' => $detalles->map(function ($detalle) {
                        return [
                            'id' => $detalle->ensayo->id ?? $detalle->ensayos_id,
                            'descripcion' => $detalle->ensayo->descripcion ?? '-',
                        ];
                    })->values(),
                ];
            })
            ->values();

        return response()->json($data);
    }
}
