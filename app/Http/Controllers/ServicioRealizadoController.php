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

    public function clienteInfo($cliente_id)
    {
        $cliente = Cliente::with(['estado', 'persona', 'usuario'])->findOrFail($cliente_id);

        return response()->json([
            'razon_social' => $cliente->razon_social,
            'ruc' => $cliente->ruc,
            'direccion' => $cliente->direccion ?? '-',
            'telefono' => $cliente->telefono ?? '-',
            'email' => $cliente->email ?? '-',
            'fecha' => $cliente->fecha ? Carbon::parse($cliente->fecha)->format('d/m/Y') : '-',
            'estado' => $cliente->estado->descripcion ?? '-',
            'persona' => $cliente->persona ? trim($cliente->persona->nombre . ' ' . $cliente->persona->apellido) : null,
            'registrado_por' => $cliente->usuario->usuario ?? '-',
        ]);
    }

    public function obraInfo($obra_id)
    {
        $obra = Obra::with(['estado', 'usuario', 'cliente'])->findOrFail($obra_id);

        return response()->json([
            'descripcion' => $obra->descripcion,
            'ubicacion' => $obra->ubicacion ?? '-',
            'metros_cuadrados' => $obra->metros_cuadrados ?? '-',
            'niveles' => $obra->niveles ?? '-',
            'observacion' => $obra->observacion ?? '-',
            'fecha' => $obra->fecha ? Carbon::parse($obra->fecha)->format('d/m/Y') : '-',
            'estado' => $obra->estado->descripcion ?? '-',
            'cliente' => $obra->cliente->razon_social ?? '-',
            'registrado_por' => $obra->usuario->usuario ?? '-',
        ]);
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
        return response()->json($this->datosOrdenArray($orden_servicio_id));
    }

    private function datosOrdenArray($orden_servicio_id)
    {
        $ordenServicio = OrdenServicio::with([
            'cliente',
            'obra',
            'contrato.cliente',
            'presupuestoServicio.usuario',
            'presupuestoServicio.detalles.servicio',
            'presupuestoServicio.detalles.ensayo',
            'presupuestoServicio.detalles.impuesto',
            'presupuestoServicio.visitaPrevia.usuario',
            'presupuestoServicio.visitaPrevia.fotos',
            'presupuestoServicio.visitaPrevia.planos',
            'presupuestoServicio.visitaPrevia.solicitudServicio.usuario',
            'presupuestoServicio.visitaPrevia.solicitudServicio.detalles.servicio',
            'funcionarios.funcionario.persona',
            'funcionarios.funcionario.cargo',
            'funcionarios.funcionario.estado',
        ])->findOrFail($orden_servicio_id);

        $contrato = $ordenServicio->contrato;
        $presupuesto = $ordenServicio->presupuestoServicio;
        $visitaPrevia = $presupuesto->visitaPrevia ?? null;
        $solicitudServicio = $visitaPrevia->solicitudServicio ?? null;

        $insumosUtilizados = InsumoUtilizado::with('detalles.insumo.unidadMedida', 'detalles.insumo.marca', 'estado', 'usuario')
            ->where('orden_servicio_id', $ordenServicio->id)
            ->where('estado_id', 4) // Confirmado
            ->get()
            ->map(function ($insumoUtilizado) {
                return [
                    'id' => $insumoUtilizado->id,
                    'nro' => $insumoUtilizado->nro,
                    'fecha_registro' => $insumoUtilizado->fecha_registro ? $insumoUtilizado->fecha_registro->format('d/m/Y') : null,
                    'estado' => $insumoUtilizado->estado->descripcion ?? '-',
                    'usuario' => $insumoUtilizado->usuario->usuario ?? '-',
                    'detalles' => $insumoUtilizado->detalles->map(function ($detalle) {
                        return [
                            'descripcion' => $detalle->insumo->descripcion ?? '-',
                            'marca' => $detalle->insumo->marca->descripcion ?? '-',
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

        $fotosVisita = $visitaPrevia
            ? $visitaPrevia->fotos->map(function ($foto) {
                return [
                    'url' => Storage::disk('public')->url('visitas_previas/fotos/' . $foto->ruta_foto),
                    'fecha' => $foto->fecha ? Carbon::parse($foto->fecha)->format('d/m/Y') : '-',
                ];
            })->values()
            : collect();

        $planosVisita = $visitaPrevia
            ? $visitaPrevia->planos->map(function ($plano) {
                return [
                    'url' => Storage::disk('public')->url('visitas_previas/planos/' . $plano->ruta_plano),
                    'fecha' => $plano->fecha ? Carbon::parse($plano->fecha)->format('d/m/Y') : '-',
                    'es_pdf' => strtolower(pathinfo($plano->ruta_plano, PATHINFO_EXTENSION)) === 'pdf',
                ];
            })->values()
            : collect();

        $presupuestoServicios = collect();
        $totalServiciosPresupuesto = 0;
        $totalImpuestosPresupuesto = 0;
        $impuestosPorTipo = [];

        if ($presupuesto) {
            $detallesPorServicio = $presupuesto->detalles->groupBy(function ($detalle) {
                return $detalle->servicio->descripcion ?? 'Sin Servicio';
            });

            foreach ($detallesPorServicio as $servicioNombre => $detallesServicio) {
                $subtotalServicio = 0;
                $ensayos = [];

                foreach ($detallesServicio as $detalle) {
                    $subtotal = round($detalle->precio_unitario * $detalle->cantidad);
                    $ivaMonto = 0;
                    if ($detalle->impuesto_id == 2) {
                        $ivaMonto = round($subtotal / 11);
                    } elseif ($detalle->impuesto_id == 3) {
                        $ivaMonto = round($subtotal / 21);
                    }

                    $subtotalServicio += $subtotal;
                    $totalServiciosPresupuesto += $subtotal;
                    $totalImpuestosPresupuesto += $ivaMonto;

                    if ($ivaMonto > 0) {
                        $tipo = $detalle->impuesto->descripcion ?? '-';
                        $impuestosPorTipo[$tipo] = ($impuestosPorTipo[$tipo] ?? 0) + $ivaMonto;
                    }

                    $ensayos[] = [
                        'descripcion' => $detalle->ensayo->descripcion ?? '-',
                        'precio_unitario' => $detalle->precio_unitario,
                        'cantidad' => $detalle->cantidad,
                        'impuesto' => $detalle->impuesto->descripcion ?? '-',
                        'iva' => $ivaMonto,
                        'subtotal' => $subtotal,
                    ];
                }

                $presupuestoServicios->push([
                    'servicio' => $servicioNombre,
                    'ensayos' => $ensayos,
                    'subtotal_servicio' => $subtotalServicio,
                ]);
            }
        }

        $totalGeneralPresupuesto = $totalServiciosPresupuesto + $totalImpuestosPresupuesto;
        $montoAnticipo = $presupuesto ? round($totalGeneralPresupuesto * $presupuesto->anticipo / 100) : 0;

        $funcionarios = $ordenServicio->funcionarios->map(function ($asignacion) {
            $funcionario = $asignacion->funcionario;
            $persona = $funcionario->persona ?? null;
            return [
                'id' => $asignacion->funcionario_id,
                'nombre' => trim(($persona->nombre ?? '') . ' ' . ($persona->apellido ?? '')) ?: '-',
                'ci' => $persona->ci ?? '-',
                'telefono' => $persona->telefono ?? '-',
                'direccion' => $persona->direccion ?? '-',
                'cargo' => $funcionario->cargo->descripcion ?? '-',
                'fecha_ingreso' => $funcionario->fecha_ingreso ? $funcionario->fecha_ingreso->format('d/m/Y') : '-',
                'estado' => $funcionario->estado->descripcion ?? '-',
            ];
        })->values();

        return [
            'id' => $ordenServicio->id,
            'nro' => $ordenServicio->nro,
            'cliente' => $ordenServicio->cliente->razon_social ?? '-',
            'obra' => $ordenServicio->obra->descripcion ?? '-',
            'contrato' => [
                'id' => $contrato->id ?? null,
                'fecha_firma' => $contrato && $contrato->fecha_firma ? $contrato->fecha_firma->format('d/m/Y') : '-',
                'fecha_firma_dia' => $contrato && $contrato->fecha_firma ? $contrato->fecha_firma->format('d') : '___',
                'fecha_firma_mes' => $contrato && $contrato->fecha_firma ? $contrato->fecha_firma->format('m') : '___',
                'fecha_firma_anio' => $contrato && $contrato->fecha_firma ? $contrato->fecha_firma->format('Y') : '___',
                'monto' => $contrato->monto ?? null,
                'plazo_dias' => $contrato->plazo_dias ?? null,
                'garantia_meses' => $contrato->garantia_meses ?? null,
                'anticipo' => $contrato->anticipo ?? null,
                'pago_mitad' => $contrato->pago_mitad ?? null,
                'pago_final' => $contrato->pago_final ?? null,
                'ciudad' => $contrato->ciudad ?? '-',
                'observaciones' => $contrato->observaciones ?? null,
                'cliente_razon_social' => $contrato?->cliente->razon_social ?? '-',
                'cliente_direccion' => $contrato?->cliente->direccion ?? '-',
                'cliente_ruc' => $contrato?->cliente->ruc ?? '-',
            ],
            'presupuesto' => [
                'id' => $presupuesto->id ?? null,
                'numero_presupuesto' => $presupuesto->numero_presupuesto ?? '-',
                'descripcion' => $presupuesto->descripcion ?? '-',
                'fecha' => $presupuesto && $presupuesto->fecha ? Carbon::parse($presupuesto->fecha)->format('d/m/Y') : '-',
                'validez' => $presupuesto->validez ?? '-',
                'anticipo' => $presupuesto->anticipo ?? 0,
                'observacion' => $presupuesto->observacion ?? null,
                'usuario' => $presupuesto?->usuario->usuario ?? '-',
                'servicios' => $presupuestoServicios,
                'impuestos_por_tipo' => $impuestosPorTipo,
                'total_servicios' => $totalServiciosPresupuesto,
                'total_impuestos' => $totalImpuestosPresupuesto,
                'total_general' => $totalGeneralPresupuesto,
                'monto_anticipo' => $montoAnticipo,
            ],
            'visita_previa' => [
                'id' => $visitaPrevia->id ?? null,
                'fecha_visita' => $visitaPrevia && $visitaPrevia->fecha_visita ? Carbon::parse($visitaPrevia->fecha_visita)->format('d/m/Y') : '-',
                'usuario' => $visitaPrevia?->usuario->usuario ?? '-',
                'fotos' => $fotosVisita,
                'planos' => $planosVisita,
            ],
            'solicitud_servicio' => [
                'id' => $solicitudServicio->id ?? null,
                'fecha' => $solicitudServicio && $solicitudServicio->fecha ? Carbon::parse($solicitudServicio->fecha)->format('d/m/Y') : '-',
                'usuario' => $solicitudServicio?->usuario->usuario ?? '-',
                'servicios' => $solicitudServicio
                    ? $solicitudServicio->detalles->pluck('servicio.descripcion')->filter()->values()
                    : [],
            ],
            'insumos_utilizados' => $insumosUtilizados,
            'servicios' => $servicios,
            'funcionarios' => $funcionarios,
        ];
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

            $ordenServicio->update(['estado_id' => 4]); // Confirmado

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

    public function confirmar($id)
    {
        $servicioRealizado = ServicioRealizado::findOrFail($id);

        if ($servicioRealizado->estado_id != 3) {
            return back()->with('error', 'Solo se pueden confirmar servicios realizados en estado Pendiente.');
        }

        $servicioRealizado->update(['estado_id' => 4]);

        return redirect()->route('servicio_realizado.index')
            ->with('success', 'Servicio realizado confirmado correctamente.');
    }

    public function anular($id)
    {
        $servicioRealizado = ServicioRealizado::with('ordenServicio')->findOrFail($id);

        if ($servicioRealizado->estado_id != 3) {
            return back()->with('error', 'Solo se pueden anular servicios realizados en estado Pendiente.');
        }

        DB::transaction(function () use ($servicioRealizado) {
            $servicioRealizado->update(['estado_id' => 5]);

            if ($servicioRealizado->ordenServicio) {
                $servicioRealizado->ordenServicio->update(['estado_id' => 3]);
            }
        });

        return redirect()->route('servicio_realizado.index')
            ->with('success', 'Servicio realizado anulado correctamente.');
    }

    public function edit($id)
    {
        $servicioRealizado = ServicioRealizado::with([
            'cliente.estado',
            'cliente.persona',
            'cliente.usuario',
            'obra.estado',
            'obra.usuario',
            'ordenServicio',
            'fotos',
            'planos',
        ])->findOrFail($id);

        if ($servicioRealizado->estado_id != 3) {
            return redirect()->route('servicio_realizado.index')
                ->with('error', 'Solo se pueden editar servicios realizados en estado Pendiente.');
        }

        $datosOrden = $this->datosOrdenArray($servicioRealizado->orden_servicio_id);

        return view('servicio_realizado.edit', compact('servicioRealizado', 'datosOrden'));
    }

    public function update(Request $request, $id)
    {
        $servicioRealizado = ServicioRealizado::findOrFail($id);

        if ($servicioRealizado->estado_id != 3) {
            return redirect()->route('servicio_realizado.index')
                ->with('error', 'Solo se pueden editar servicios realizados en estado Pendiente.');
        }

        $request->validate([
            'observacion' => 'nullable|string',
            'fotos.*' => 'nullable|file|image',
            'planos.*' => 'nullable|file',
            'fotos_eliminar' => 'nullable|array',
            'fotos_eliminar.*' => 'exists:servicio_realizado_fotos,id',
            'planos_eliminar' => 'nullable|array',
            'planos_eliminar.*' => 'exists:servicio_realizado_planos,id',
        ]);

        DB::beginTransaction();
        try {
            $servicioRealizado->update([
                'observacion' => $request->observacion,
            ]);

            foreach ($request->input('fotos_eliminar', []) as $fotoId) {
                $foto = ServicioRealizadoFoto::where('servicio_realizado_id', $servicioRealizado->id)->find($fotoId);
                if ($foto) {
                    Storage::disk('public')->delete('servicios_realizados/fotos/' . $foto->nombre_foto);
                    $foto->delete();
                }
            }

            foreach ($request->input('planos_eliminar', []) as $planoId) {
                $plano = ServicioRealizadoPlano::where('servicio_realizado_id', $servicioRealizado->id)->find($planoId);
                if ($plano) {
                    Storage::disk('public')->delete('servicios_realizados/planos/' . $plano->nombre_plano);
                    $plano->delete();
                }
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
                            ->with('success', 'Servicio realizado actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->withInput()
                        ->with('error', 'Error al actualizar el servicio realizado: ' . $e->getMessage());
        }
    }
}
