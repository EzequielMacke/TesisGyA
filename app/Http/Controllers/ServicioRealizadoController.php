<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Estado;
use App\Models\InsumoUtilizado;
use App\Models\Obra;
use App\Models\OrdenServicio;
use App\Models\PresupuestoServicioDetalle;
use App\Models\ServicioRealizado;
use App\Models\ServicioRealizadoFoto;
use App\Models\ServicioRealizadoInsumo;
use App\Models\ServicioRealizadoPlano;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ServicioRealizadoController extends Controller
{
    public function index(Request $request)
    {
        $query = ServicioRealizado::with([
            'solicitudServicio',
            'visitaPrevia',
            'presupuestoServicio',
            'contrato',
            'ordenServicio',
            'cliente',
            'obra',
            'usuario',
            'estado',
            'insumos.insumoUtilizado',
            'fotos',
            'planos',
        ]);

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

        $serviciosRealizados = $query->orderBy('created_at', 'desc')->get();

        // Datos para filtros
        $obras = Obra::where('estado_id', 1)->orderBy('descripcion')->get();
        $estados = Estado::orderBy('descripcion')->get();

        return view('servicio_realizado.index', compact('serviciosRealizados', 'obras', 'estados'));
    }

    public function create()
    {
        // Clientes con al menos una orden de servicio pendiente
        $clientes = Cliente::where('estado_id', 1)
            ->whereIn('id', function ($query) {
                $query->select('cliente_id')
                    ->from('orden_servicio')
                    ->where('estado_id', 3);
            })
            ->orderBy('razon_social')->get();

        return view('servicio_realizado.create', compact('clientes'));
    }

    public function obrasPorCliente($cliente_id)
    {
        // Solo obras del cliente con al menos una orden de servicio pendiente
        $obras = Obra::where('cliente_id', $cliente_id)
            ->where('estado_id', 1)
            ->whereIn('id', function ($query) {
                $query->select('obra_id')
                    ->from('orden_servicio')
                    ->where('estado_id', 3);
            })
            ->orderBy('descripcion')->get(['id', 'descripcion']);

        return response()->json($obras);
    }

    public function ordenesPorObra($obra_id)
    {
        $ordenes = OrdenServicio::where('obra_id', $obra_id)
            ->where('estado_id', 3)
            ->orderBy('nro')
            ->get(['id', 'nro']);

        return response()->json($ordenes);
    }

    public function datosPorOrden($orden_servicio_id)
    {
        $ordenServicio = OrdenServicio::with([
            'cliente',
            'obra',
            'contrato',
            'presupuestoServicio.visitaPrevia.solicitudServicio',
            'funcionarios.funcionario.persona',
        ])->findOrFail($orden_servicio_id);

        $contrato = $ordenServicio->contrato;
        $presupuesto = $ordenServicio->presupuestoServicio;
        $visitaPrevia = $presupuesto->visitaPrevia ?? null;
        $solicitudServicio = $visitaPrevia->solicitudServicio ?? null;

        $insumosUtilizados = InsumoUtilizado::with('detalles.insumo.unidadMedida', 'estado')
            ->where('orden_servicio_id', $ordenServicio->id)
            ->get()
            ->map(function ($insumoUtilizado) {
                return [
                    'id' => $insumoUtilizado->id,
                    'nro' => $insumoUtilizado->nro,
                    'fecha_registro' => $insumoUtilizado->fecha_registro ? $insumoUtilizado->fecha_registro->format('d/m/Y') : null,
                    'estado' => $insumoUtilizado->estado->descripcion ?? '-',
                    'detalles' => $insumoUtilizado->detalles->map(function ($detalle) {
                        return [
                            'descripcion' => $detalle->insumo->descripcion ?? '-',
                            'unidad' => $detalle->insumo->unidadMedida->descripcion ?? '-',
                            'cantidad' => $detalle->cantidad,
                        ];
                    })->values(),
                ];
            })->values();

        $servicios = PresupuestoServicioDetalle::with(['ensayo', 'servicio'])
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
                            'descripcion' => $detalle->ensayo->descripcion ?? '-',
                            'cantidad' => $detalle->cantidad,
                        ];
                    })->values(),
                ];
            })->values();

        $funcionarios = $ordenServicio->funcionarios->map(function ($asignacion) {
            $persona = $asignacion->funcionario->persona ?? null;
            return [
                'id' => $asignacion->funcionario_id,
                'nombre' => trim(($persona->nombre ?? '') . ' ' . ($persona->apellido ?? '')) ?: '-',
            ];
        })->values();

        return response()->json([
            'id' => $ordenServicio->id,
            'nro' => $ordenServicio->nro,
            'cliente' => $ordenServicio->cliente->razon_social ?? '-',
            'obra' => $ordenServicio->obra->descripcion ?? '-',
            'contrato' => [
                'id' => $contrato->id ?? null,
                'fecha_firma' => $contrato && $contrato->fecha_firma ? $contrato->fecha_firma->format('d/m/Y') : '-',
                'monto' => $contrato->monto ?? null,
                'plazo_dias' => $contrato->plazo_dias ?? null,
                'garantia_meses' => $contrato->garantia_meses ?? null,
            ],
            'presupuesto' => [
                'id' => $presupuesto->id ?? null,
                'numero_presupuesto' => $presupuesto->numero_presupuesto ?? '-',
                'descripcion' => $presupuesto->descripcion ?? '-',
            ],
            'visita_previa' => [
                'id' => $visitaPrevia->id ?? null,
                'fecha_visita' => $visitaPrevia && $visitaPrevia->fecha_visita ? Carbon::parse($visitaPrevia->fecha_visita)->format('d/m/Y') : '-',
            ],
            'solicitud_servicio' => [
                'id' => $solicitudServicio->id ?? null,
                'fecha' => $solicitudServicio && $solicitudServicio->fecha ? Carbon::parse($solicitudServicio->fecha)->format('d/m/Y') : '-',
            ],
            'insumos_utilizados' => $insumosUtilizados,
            'servicios' => $servicios,
            'funcionarios' => $funcionarios,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'orden_servicio_id' => 'required|exists:orden_servicio,id',
            'observacion' => 'nullable|string',
            'insumos_utilizados' => 'nullable|array',
            'insumos_utilizados.*' => 'exists:insumo_utilizado,id',
            'fotos.*' => 'nullable|file|image',
            'planos.*' => 'nullable|file',
        ], [
            'orden_servicio_id.required' => 'Debe seleccionar una orden de servicio.',
        ]);

        DB::beginTransaction();
        try {
            $ordenServicio = OrdenServicio::with('presupuestoServicio.visitaPrevia')->findOrFail($request->orden_servicio_id);
            $visitaPrevia = $ordenServicio->presupuestoServicio->visitaPrevia ?? null;

            $servicioRealizado = ServicioRealizado::create([
                'solicitud_servicio_id' => $visitaPrevia->solicitud_servicio_id ?? null,
                'visita_previa_id' => $visitaPrevia->id ?? null,
                'presupuesto_servicio_id' => $ordenServicio->presupuesto_servicio_id,
                'contrato_id' => $ordenServicio->contrato_id,
                'orden_servicio_id' => $ordenServicio->id,
                'cliente_id' => $ordenServicio->cliente_id,
                'obra_id' => $ordenServicio->obra_id,
                'usuario_id' => session('user_id'),
                'fecha_registro' => Carbon::today(),
                'estado_id' => 3,
                'observacion' => $request->observacion,
            ]);

            foreach ($request->input('insumos_utilizados', []) as $insumoUtilizadoId) {
                ServicioRealizadoInsumo::create([
                    'servicio_realizado_id' => $servicioRealizado->id,
                    'insumo_utilizado_id' => $insumoUtilizadoId,
                ]);
            }

            Storage::disk('public')->makeDirectory('servicios_realizados/fotos');
            Storage::disk('public')->makeDirectory('servicios_realizados/planos');

            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    $path = $foto->store('servicios_realizados/fotos', 'public');
                    ServicioRealizadoFoto::create([
                        'servicio_realizado_id' => $servicioRealizado->id,
                        'nombre_foto' => basename($path),
                    ]);
                }
            }

            if ($request->hasFile('planos')) {
                foreach ($request->file('planos') as $plano) {
                    $path = $plano->store('servicios_realizados/planos', 'public');
                    ServicioRealizadoPlano::create([
                        'servicio_realizado_id' => $servicioRealizado->id,
                        'nombre_plano' => basename($path),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('servicio_realizado.index')
                            ->with('success', 'Servicio realizado registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->withInput()
                        ->with('error', 'Error al registrar el servicio realizado: ' . $e->getMessage());
        }
    }
}
