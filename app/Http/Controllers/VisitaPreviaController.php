<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\VisitaPrevia;
use App\Models\VisitaPreviaFoto;
use App\Models\VisitaPreviaPlano;
use App\Models\VisitaPreviaEnsayos;
use App\Models\SolicitudServicio;
use App\Models\Ensayo;
use App\Models\Estado;
use App\Models\Obra;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class VisitaPreviaController extends Controller
{
    public function index(Request $request)
    {
        $visitas = VisitaPrevia::with(['cliente', 'obra', 'solicitudServicio', 'estado', 'usuario']);

        // Filtros
        if ($request->filled('cliente')) {
            $visitas->whereHas('cliente', function($q) use ($request) {
                $q->where('razon_social', 'like', '%' . $request->cliente . '%');
            });
        }

        if ($request->filled('obra')) {
            $visitas->whereHas('obra', function($q) use ($request) {
                $q->where('descripcion', 'like', '%' . $request->obra . '%');
            });
        }

        if ($request->filled('estado_id')) {
            $visitas->where('estado_id', $request->estado_id);
        }

        if ($request->filled('fecha_desde')) {
            $visitas->where('fecha_visita', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $visitas->where('fecha_visita', '<=', $request->fecha_hasta);
        }

        if ($request->filled('solicitud_id')) {
            $visitas->where('solicitud_servicio_id', $request->solicitud_id);
        }

        $visitas = $visitas->orderBy('fecha_visita', 'desc')->paginate(10);

        $estados = Estado::whereIn('id', [3, 4, 5])->get(); // Estados relevantes para visitas previas

        return view('visita_previa.index', compact('visitas', 'estados'));
    }

    public function create()
    {
        $clientes = Cliente::where('estado_id', 1)->get();

        return view('visita_previa.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'obra_id' => 'required|exists:obras,id',
            'solicitud_servicio_id' => 'required|exists:solicitud_servicio,id',
            'fecha_visita' => 'required|date',
            'metros_cuadrados' => 'required|numeric|min:0',
            'niveles' => 'required|string|max:255',
            'observacion' => 'nullable|string',
            'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'planos.*' => 'nullable|mimes:pdf,jpeg,png,jpg,gif|max:5120',
            'ensayos' => 'nullable|array',
            'ensayos.*' => 'exists:ensayos,id',
        ]);

        DB::beginTransaction();
        try {
            // Crear visita previa
            $visita = VisitaPrevia::create([
                'cliente_id' => $request->cliente_id,
                'obra_id' => $request->obra_id,
                'solicitud_servicio_id' => $request->solicitud_servicio_id,
                'fecha_visita' => $request->fecha_visita,
                'observacion' => $request->observacion,
                'usuario_id' => session('user_id'),
                'estado_id' => 3,
            ]);

            // Actualizar obra con metros_cuadrados y niveles
            $obra = Obra::find($request->obra_id);
            $obra->update([
                'metros_cuadrados' => $request->metros_cuadrados,
                'niveles' => $request->niveles,
            ]);

            $solicitud = SolicitudServicio::find($request->solicitud_servicio_id);
            $solicitud->update(['estado_id' => 4]);

            // Crear carpetas si no existen
            Storage::disk('public')->makeDirectory('visitas_previas/fotos');
            Storage::disk('public')->makeDirectory('visitas_previas/planos');

            // Guardar fotos
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    $path = $foto->store('visitas_previas/fotos', 'public');
                    VisitaPreviaFoto::create([
                        'visita_previa_id' => $visita->id,
                        'ruta_foto' => $path,
                        'fecha' => now(),
                        'usuario_id' => session('user_id'),
                        'obra_id' => $request->obra_id,
                    ]);
                }
            }

            // Guardar planos
            if ($request->hasFile('planos')) {
                foreach ($request->file('planos') as $plano) {
                    $path = $plano->store('visitas_previas/planos', 'public');
                    VisitaPreviaPlano::create([
                        'visita_previa_id' => $visita->id,
                        'ruta_plano' => $path,
                        'fecha' => now(),
                        'usuario_id' => session('user_id'),
                        'obra_id' => $request->obra_id,
                    ]);
                }
            }

            // Guardar ensayos seleccionados
            if ($request->has('ensayos')) {
                foreach ($request->ensayos as $ensayoId) {
                    VisitaPreviaEnsayos::create([
                        'visita_previa_id' => $visita->id,
                        'ensayo_id' => $ensayoId,
                        'servicio_id' => Ensayo::find($ensayoId)->servicio_id,
                        'usuario_id' => session('user_id'),
                        'obra_id' => $request->obra_id,
                        'estado_id' => 1,
                        'fecha' => $request->fecha_visita,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('visita_previa.index')->with('success', 'Visita previa creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Error al crear la visita previa: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $visita = VisitaPrevia::with([
            'cliente',
            'obra',
            'solicitudServicio',
            'estado',
            'usuario',
            'fotos',
            'planos',
            'ensayos.ensayo'
        ])->findOrFail($id);

        return view('visita_previa.show', compact('visita'));
    }

    public function edit($id)
    {
        $visita = VisitaPrevia::findOrFail($id);
        $clientes = Cliente::where('estado_id', 1)->get();

        return view('visita_previa.edit', compact('visita', 'clientes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'obra_id' => 'required|exists:obras,id',
            'solicitud_servicio_id' => 'required|exists:solicitud_servicio,id',
            'fecha_visita' => 'required|date',
            'metros_cuadrados' => 'required|numeric|min:0',
            'niveles' => 'required|string|max:255',
            'observacion' => 'nullable|string',
            'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'planos.*' => 'nullable|mimes:pdf,jpeg,png,jpg,gif|max:5120',
            'ensayos' => 'nullable|array',
            'ensayos.*' => 'exists:ensayos,id',
        ]);

        DB::beginTransaction();
        try {
            $visita = VisitaPrevia::findOrFail($id);
            $visita->update([
                'cliente_id' => $request->cliente_id,
                'obra_id' => $request->obra_id,
                'solicitud_servicio_id' => $request->solicitud_servicio_id,
                'fecha_visita' => $request->fecha_visita,
                'observacion' => $request->observacion,
            ]);

            // Actualizar obra con metros_cuadrados y niveles
            $obra = Obra::find($request->obra_id);
            $obra->update([
                'metros_cuadrados' => $request->metros_cuadrados,
                'niveles' => $request->niveles,
            ]);

            // Crear carpetas si no existen
            Storage::disk('public')->makeDirectory('visitas_previas/fotos');
            Storage::disk('public')->makeDirectory('visitas_previas/planos');

            // Actualizar fotos si se suben nuevas
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    $path = $foto->store('visitas_previas/fotos', 'public');
                    VisitaPreviaFoto::create([
                        'visita_previa_id' => $visita->id,
                        'ruta_foto' => $path,
                        'fecha' => now(),
                        'usuario_id' => session('user_id'),
                        'obra_id' => $request->obra_id,
                    ]);
                }
            }

            // Actualizar planos si se suben nuevos
            if ($request->hasFile('planos')) {
                foreach ($request->file('planos') as $plano) {
                    $path = $plano->store('visitas_previas/planos', 'public');
                    VisitaPreviaPlano::create([
                        'visita_previa_id' => $visita->id,
                        'ruta_plano' => $path,
                        'fecha' => now(),
                        'usuario_id' => session('user_id'),
                        'obra_id' => $request->obra_id,
                    ]);
                }
            }

            // Actualizar ensayos
            VisitaPreviaEnsayos::where('visita_previa_id', $visita->id)->delete(); // Eliminar anteriores
            if ($request->has('ensayos')) {
                foreach ($request->ensayos as $ensayoId) {
                    VisitaPreviaEnsayos::create([
                        'visita_previa_id' => $visita->id,
                        'ensayo_id' => $ensayoId,
                        'servicio_id' => Ensayo::find($ensayoId)->servicio_id,
                        'usuario_id' => session('user_id'),
                        'obra_id' => $request->obra_id,
                        'estado_id' => 1,
                        'fecha' => $request->fecha_visita,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('visita_previa.index')->with('success', 'Visita previa actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Error al actualizar la visita previa: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $visita = VisitaPrevia::findOrFail($id);

        // Eliminar archivos físicos
        foreach ($visita->fotos as $foto) {
            Storage::disk('public')->delete($foto->ruta_foto);
        }
        foreach ($visita->planos as $plano) {
            Storage::disk('public')->delete($plano->ruta_plano);
        }

        $visita->delete();

        return redirect()->route('visita_previa.index')->with('success', 'Visita previa eliminada exitosamente.');
    }

    // Métodos AJAX
    public function ajaxObras($clienteId)
    {
        $obras = Obra::where('cliente_id', $clienteId)
            ->where('estado_id', 1)
            ->get(['id', 'descripcion']);

        return response()->json($obras);
    }

    public function ajaxSolicitudes($obraId)
    {
        $solicitudes = SolicitudServicio::where('obra_id', $obraId)
            ->where('estado_id', 3) // Solo solicitudes pendientes
            ->with('estado')
            ->get(['id', 'fecha', 'estado_id']);

        $solicitudes = $solicitudes->map(function($solicitud) {
            return [
                'id' => $solicitud->id,
                'fecha' => $solicitud->fecha,
                'estado' => $solicitud->estado->descripcion ?? '',
            ];
        });

        return response()->json($solicitudes);
    }

    public function ajaxSolicitud($id)
    {
        $solicitud = SolicitudServicio::with([
            'cliente',
            'obra',
            'estado',
            'detalles.servicio'
        ])->findOrFail($id);

        return response()->json([
            'id' => $solicitud->id,
            'fecha' => $solicitud->fecha,
            'cliente' => $solicitud->cliente->razon_social ?? '',
            'obra' => $solicitud->obra->descripcion ?? '',
            'estado' => $solicitud->estado->descripcion ?? '',
            'observacion' => $solicitud->observacion,
            'detalle' => $solicitud->detalles->map(function($d) {
                return $d->servicio->descripcion ?? '';
            })->toArray(),
            'servicios' => $solicitud->detalles->pluck('servicio_id')->toArray(),
            'metros_cuadrados' => $solicitud->obra->metros_cuadrados ?? '',
            'niveles' => $solicitud->obra->niveles ?? '',
        ]);
    }

    public function ajaxEnsayosPorSolicitud(Request $request)
    {
        $serviciosIds = $request->input('servicios', []);

        if (empty($serviciosIds)) {
            return response()->json([]);
        }

        $ensayos = Ensayo::whereIn('servicio_id', $serviciosIds)
            ->where('estado_id', 1)
            ->with('servicio')
            ->get(['id', 'descripcion', 'servicio_id']);

        $ensayosAgrupados = $ensayos->groupBy('servicio_id')->map(function($ensayosServicio, $servicioId) {
            $servicio = $ensayosServicio->first()->servicio;
            return [
                'servicio' => $servicio ? $servicio->descripcion : 'Servicio desconocido',
                'ensayos' => $ensayosServicio->map(function($ensayo) {
                    return [
                        'id' => $ensayo->id,
                        'descripcion' => $ensayo->descripcion,
                    ];
                }),
            ];
        })->values();

        return response()->json($ensayosAgrupados);
    }
}
