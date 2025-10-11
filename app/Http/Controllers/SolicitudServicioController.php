<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudServicio;
use App\Models\SolicitudServicioDetalle;
use App\Models\Cliente;
use App\Models\Obra;
use App\Models\Servicio;

class SolicitudServicioController extends Controller
{
    public function index(Request $request)
    {
        $estado_id = $request->input('estado_id');
        $cliente_id = $request->input('cliente_id');
        $fecha_desde = $request->input('fecha_desde');
        $search = $request->input('search');

        $clientes = Cliente::orderBy('razon_social')->get();

        $query = SolicitudServicio::with([
            'cliente',
            'obra',
            'estado',
            'detalles.servicio'
        ])->whereIn('estado_id', [3, 4, 5]);

        if ($estado_id) {
            $query->where('estado_id', $estado_id);
        }
        if ($cliente_id) {
            $query->where('cliente_id', $cliente_id);
        }
        if ($fecha_desde) {
            $query->whereDate('fecha', '>=', $fecha_desde);
        }
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('cliente', function($q2) use ($search) {
                    $q2->where('razon_social', 'like', "%$search%");
                })
                ->orWhereHas('obra', function($q2) use ($search) {
                    $q2->where('descripcion', 'like', "%$search%");
                })
                ->orWhere('observacion', 'like', "%$search%");
            });
        }

        $solicitudes = $query->orderByDesc('id')->paginate(20);

        return view('solicitud_servicio.index', compact('solicitudes', 'clientes'));
    }

    public function create()
    {
        $clientes = Cliente::where('estado_id', 1)->orderBy('razon_social')->get();
        $servicios = Servicio::where('estado_id', 1)->orderBy('descripcion')->get();
        return view('solicitud_servicio.create', compact('clientes', 'servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'obra_id' => 'required|exists:obras,id',
            'fecha' => 'required|date',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:servicios,id',
            'observacion' => 'nullable|string|max:255',
        ], [
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'obra_id.required' => 'Debe seleccionar una obra.',
            'fecha.required' => 'Debe ingresar la fecha.',
            'servicios.required' => 'Debe seleccionar al menos un servicio.',
        ]);

        $solicitud = SolicitudServicio::create([
            'cliente_id' => $request->cliente_id,
            'obra_id' => $request->obra_id,
            'fecha' => $request->fecha,
            'estado_id' => 3,
            'observacion' => $request->observacion,
        ]);

        foreach ($request->servicios as $servicio_id) {
            SolicitudServicioDetalle::create([
                'solicitud_servicio_id' => $solicitud->id,
                'servicio_id' => $servicio_id,
                'observacion' => null,
            ]);
        }

        return redirect()->route('solicitud_servicio.index')
            ->with('success', 'Solicitud de servicio creada correctamente.');
    }

    // Obras activas por cliente (AJAX)
    public function apiObras($cliente_id)
    {
        $obras = Obra::where('cliente_id', $cliente_id)->where('estado_id', 1)->orderBy('descripcion')->get(['id', 'descripcion']);
        return response()->json($obras);
    }

    // Info de obra (AJAX)
    public function apiObraInfo($obra_id)
    {
        $obra = Obra::findOrFail($obra_id);
        return response()->json($obra);
    }
}
