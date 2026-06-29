<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Estado;
use App\Models\Obra;
use App\Models\Reclamo;
use App\Models\ReclamoFoto;
use App\Models\ReclamoPlano;
use App\Models\ServicioRealizado;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReclamoController extends Controller
{
    public function index(Request $request)
    {
        $query = Reclamo::with(['cliente', 'obra', 'servicioRealizado', 'usuario', 'estado', 'fotos', 'planos']);

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

        $reclamos = $query->orderBy('created_at', 'desc')->get();

        // Datos para filtros
        $obras = Obra::where('estado_id', 1)->orderBy('descripcion')->get();
        $estados = Estado::orderBy('descripcion')->get();

        return view('reclamos.index', compact('reclamos', 'obras', 'estados'));
    }

    public function create()
    {
        // Clientes con al menos un servicio realizado confirmado
        $clientes = Cliente::where('estado_id', 1)
            ->whereIn('id', function ($query) {
                $query->select('cliente_id')
                    ->from('servicio_realizado')
                    ->where('estado_id', 4);
            })
            ->orderBy('razon_social')->get();

        return view('reclamos.create', compact('clientes'));
    }

    public function obrasPorCliente($cliente_id)
    {
        // Solo obras del cliente con al menos un servicio realizado confirmado
        $obras = Obra::where('cliente_id', $cliente_id)
            ->where('estado_id', 1)
            ->whereIn('id', function ($query) {
                $query->select('obra_id')
                    ->from('servicio_realizado')
                    ->where('estado_id', 4);
            })
            ->orderBy('descripcion')->get(['id', 'descripcion']);

        return response()->json($obras);
    }

    public function serviciosRealizadosPorObra($obra_id)
    {
        $servicios = ServicioRealizado::where('obra_id', $obra_id)
            ->where('estado_id', 4)
            ->orderBy('fecha_registro', 'desc')
            ->get(['id', 'fecha_registro'])
            ->map(function ($servicio) {
                return [
                    'id' => $servicio->id,
                    'fecha_registro' => $servicio->fecha_registro ? $servicio->fecha_registro->format('d/m/Y') : '-',
                ];
            });

        return response()->json($servicios);
    }

    public function store(Request $request)
    {
        $request->validate([
            'servicio_realizado_id' => 'required|exists:servicio_realizado,id',
            'observacion' => 'nullable|string',
            'fotos.*' => 'nullable|file|image',
            'planos.*' => 'nullable|file',
        ], [
            'servicio_realizado_id.required' => 'Debe seleccionar un servicio realizado.',
        ]);

        $servicioRealizado = ServicioRealizado::findOrFail($request->servicio_realizado_id);

        if ($servicioRealizado->estado_id != 4) {
            return back()->withInput()
                ->with('error', 'Solo se pueden registrar reclamos sobre servicios realizados en estado Confirmado.');
        }

        DB::beginTransaction();
        try {
            $reclamo = Reclamo::create([
                'usuario_id' => session('user_id'),
                'fecha_registro' => Carbon::today(),
                'cliente_id' => $servicioRealizado->cliente_id,
                'obra_id' => $servicioRealizado->obra_id,
                'servicio_realizado_id' => $servicioRealizado->id,
                'estado_id' => 3,
                'observacion' => $request->observacion,
            ]);

            Storage::disk('public')->makeDirectory('reclamos/fotos');
            Storage::disk('public')->makeDirectory('reclamos/planos');

            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    $path = $foto->store('reclamos/fotos', 'public');
                    ReclamoFoto::create([
                        'reclamo_id' => $reclamo->id,
                        'nombre_foto' => basename($path),
                    ]);
                }
            }

            if ($request->hasFile('planos')) {
                foreach ($request->file('planos') as $plano) {
                    $path = $plano->store('reclamos/planos', 'public');
                    ReclamoPlano::create([
                        'reclamo_id' => $reclamo->id,
                        'nombre_plano' => basename($path),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('reclamos.index')
                ->with('success', 'Reclamo registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->withInput()
                ->with('error', 'Error al registrar el reclamo: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $reclamo = Reclamo::with(['cliente', 'obra', 'servicioRealizado', 'usuario', 'estado', 'fotos', 'planos'])->findOrFail($id);

        return view('reclamos.show', compact('reclamo'));
    }

    public function confirmar($id)
    {
        $reclamo = Reclamo::findOrFail($id);

        if ($reclamo->estado_id != 3) {
            return back()->with('error', 'Solo se pueden confirmar reclamos en estado Pendiente.');
        }

        $reclamo->update(['estado_id' => 4]);

        return redirect()->route('reclamos.index')
            ->with('success', 'Reclamo confirmado correctamente.');
    }

    public function anular($id)
    {
        $reclamo = Reclamo::findOrFail($id);

        if ($reclamo->estado_id != 3) {
            return back()->with('error', 'Solo se pueden anular reclamos en estado Pendiente.');
        }

        $reclamo->update(['estado_id' => 5]);

        return redirect()->route('reclamos.index')
            ->with('success', 'Reclamo anulado correctamente.');
    }

    public function edit($id)
    {
        $reclamo = Reclamo::with(['cliente', 'obra', 'servicioRealizado', 'fotos', 'planos'])->findOrFail($id);

        if ($reclamo->estado_id != 3) {
            return redirect()->route('reclamos.index')
                ->with('error', 'Solo se pueden editar reclamos en estado Pendiente.');
        }

        return view('reclamos.edit', compact('reclamo'));
    }

    public function update(Request $request, $id)
    {
        $reclamo = Reclamo::findOrFail($id);

        if ($reclamo->estado_id != 3) {
            return redirect()->route('reclamos.index')
                ->with('error', 'Solo se pueden editar reclamos en estado Pendiente.');
        }

        $request->validate([
            'observacion' => 'nullable|string',
            'fotos.*' => 'nullable|file|image',
            'planos.*' => 'nullable|file',
            'fotos_eliminar' => 'nullable|array',
            'fotos_eliminar.*' => 'exists:reclamo_fotos,id',
            'planos_eliminar' => 'nullable|array',
            'planos_eliminar.*' => 'exists:reclamo_planos,id',
        ]);

        DB::beginTransaction();
        try {
            $reclamo->update([
                'observacion' => $request->observacion,
            ]);

            foreach ($request->input('fotos_eliminar', []) as $fotoId) {
                $foto = ReclamoFoto::where('reclamo_id', $reclamo->id)->find($fotoId);
                if ($foto) {
                    Storage::disk('public')->delete('reclamos/fotos/' . $foto->nombre_foto);
                    $foto->delete();
                }
            }

            foreach ($request->input('planos_eliminar', []) as $planoId) {
                $plano = ReclamoPlano::where('reclamo_id', $reclamo->id)->find($planoId);
                if ($plano) {
                    Storage::disk('public')->delete('reclamos/planos/' . $plano->nombre_plano);
                    $plano->delete();
                }
            }

            Storage::disk('public')->makeDirectory('reclamos/fotos');
            Storage::disk('public')->makeDirectory('reclamos/planos');

            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    $path = $foto->store('reclamos/fotos', 'public');
                    ReclamoFoto::create([
                        'reclamo_id' => $reclamo->id,
                        'nombre_foto' => basename($path),
                    ]);
                }
            }

            if ($request->hasFile('planos')) {
                foreach ($request->file('planos') as $plano) {
                    $path = $plano->store('reclamos/planos', 'public');
                    ReclamoPlano::create([
                        'reclamo_id' => $reclamo->id,
                        'nombre_plano' => basename($path),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('reclamos.index')
                ->with('success', 'Reclamo actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->withInput()
                ->with('error', 'Error al actualizar el reclamo: ' . $e->getMessage());
        }
    }
}
