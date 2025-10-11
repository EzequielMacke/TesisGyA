<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PresupuestoServicio;
use App\Models\PresupuestoServicioDetalle;
use App\Models\Cliente;
use App\Models\Obra;
use App\Models\VisitaPrevia;
use App\Models\Ensayo;
use App\Models\Servicio;
use App\Models\Estado;
use App\Models\Impuesto;
use Illuminate\Support\Facades\DB;

class PresupuestoServicioController extends Controller
{
    public function index(Request $request)
    {
        $presupuestos = PresupuestoServicio::with(['cliente', 'obra', 'estado', 'usuario']);

        // Filtros
        if ($request->filled('cliente')) {
            $presupuestos->whereHas('cliente', function($q) use ($request) {
                $q->where('razon_social', 'like', '%' . $request->cliente . '%');
            });
        }

        if ($request->filled('obra')) {
            $presupuestos->whereHas('obra', function($q) use ($request) {
                $q->where('descripcion', 'like', '%' . $request->obra . '%');
            });
        }

        if ($request->filled('estado_id')) {
            $presupuestos->where('estado_id', $request->estado_id);
        }

        if ($request->filled('fecha_desde')) {
            $presupuestos->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $presupuestos->where('fecha', '<=', $request->fecha_hasta);
        }

        if ($request->filled('numero_presupuesto')) {
            $presupuestos->where('numero_presupuesto', 'like', '%' . $request->numero_presupuesto . '%');
        }

        $presupuestos = $presupuestos->orderBy('fecha', 'desc')->paginate(10);

        $estados = Estado::whereIn('id', [1, 2, 3])->get(); // Ajustar según estados relevantes

        return view('presupuesto_servicio.index', compact('presupuestos', 'estados'));
    }

    public function create()
    {
        return view('presupuesto_servicio.create');
    }

    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'obra_id' => 'required|exists:obras,id',
            'visita_previa_id' => 'required|exists:visita_previa,id',
            'validez' => 'required|integer|min:1',
            'anticipo' => 'required|numeric|min:0|max:100',
            'numero_presupuesto' => 'required|string',
            'fecha' => 'required|date',
            'observacion' => 'nullable|string',
            'ensayos' => 'required|array',
            'ensayos.*' => 'exists:ensayos,id',
            'precios' => 'required|array',
            'cantidades' => 'required|array',
            'impuestos' => 'required|array',
        ]);

        // Cargar ensayos para obtener servicio_id
        $ensayos = Ensayo::whereIn('id', $request->ensayos)->get()->keyBy('id');

        // Calcular totales
        $totalEnsayos = 0;
        $totalImpuestos = 0;
        $detalles = [];
        foreach ($request->ensayos as $ensayoId) {
            $ensayo = $ensayos[$ensayoId];
            $precio = $request->precios[$ensayoId];
            $cantidad = $request->cantidades[$ensayoId];
            $impuestoId = $request->impuestos[$ensayoId];
            $subtotal = round($precio * $cantidad);
            $ivaMonto = $this->calcularIVA($subtotal, $impuestoId);
            $totalEnsayos += $subtotal;
            $totalImpuestos += $ivaMonto;
            $detalles[] = [
                'ensayos_id' => $ensayoId,
                'servicio_id' => $ensayo->servicio_id,
                'precio_unitario' => $precio,
                'cantidad' => $cantidad,
                'impuesto_id' => $impuestoId,
                'subtotal' => $subtotal,
                'iva_monto' => $ivaMonto,
            ];
        }
        $totalGeneral = $totalEnsayos + $totalImpuestos;

        // Crear presupuesto
         $presupuesto = PresupuestoServicio::create([
            'cliente_id' => $request->cliente_id,
            'obra_id' => $request->obra_id,
            'visita_previa_id' => $request->visita_previa_id,
            'descripcion' => "Presupuesto {$request->numero_presupuesto} para la visita {$request->visita_previa_id}",
            'validez' => $request->validez,
            'anticipo' => $request->anticipo,
            'numero_presupuesto' => $request->numero_presupuesto,
            'fecha' => $request->fecha,
            'observacion' => $request->observacion,
            'monto' => $totalGeneral,
            'estado_id' => 3,
            'usuario_id' => session('user_id'),
        ]);

        // Crear detalles
        foreach ($detalles as $detalle) {
            PresupuestoServicioDetalle::create([
                'presupuesto_servicio_id' => $presupuesto->id,
                ...$detalle,
            ]);
        }

        // Cambiar estado de la visita previa a 4
        VisitaPrevia::where('id', $request->visita_previa_id)->update(['estado_id' => 4]);

        return redirect()->route('presupuesto_servicio.index')->with('success', 'Presupuesto creado exitosamente.');
    }

    private function calcularIVA($subtotal, $impuestoId)
    {
        if ($impuestoId == 2) { // 10%
            return round($subtotal / 11);
        } elseif ($impuestoId == 3) { // 5%
            return round($subtotal / 21);
        } else { // Exenta
            return 0;
        }
    }

    public function show($id)
    {
        $presupuesto = PresupuestoServicio::with([
            'cliente',
            'obra',
            'visitaPrevia.cliente',
            'visitaPrevia.obra',
            'estado',
            'usuario',
            'detalles.ensayo.servicio',
            'detalles.impuesto'
        ])->findOrFail($id);

        return view('presupuesto_servicio.show', compact('presupuesto'));
    }

    // Métodos AJAX
    public function ajaxObras($clienteId)
    {
        $obras = Obra::where('cliente_id', $clienteId)
            ->where('estado_id', 1)
            ->get(['id', 'descripcion']);

        return response()->json($obras);
    }

    public function ajaxVisitasPrevias($obraId)
    {
        $visitas = VisitaPrevia::where('obra_id', $obraId)
            ->where('estado_id', 3)
            ->with('estado')
            ->get(['id', 'fecha_visita as fecha', 'estado_id']);

        $visitas = $visitas->map(function($visita) {
            return [
                'id' => $visita->id,
                'fecha' => $visita->fecha,
                'estado' => $visita->estado->descripcion ?? '',
            ];
        });

        return response()->json($visitas);
    }

    public function ajaxVisitaPrevia($id)
    {
        $visita = VisitaPrevia::with(['cliente', 'obra', 'estado', 'fotos', 'planos'])->findOrFail($id);

        return response()->json($visita);
    }

    public function ajaxEnsayosPorVisita($visitaId)
    {
        $visita = VisitaPrevia::with('ensayos.ensayo.servicio')->findOrFail($visitaId);
        $ensayosVisita = $visita->ensayos->pluck('ensayo_id')->toArray();

        $servicios = Servicio::with(['ensayos' => function($q) {
            $q->where('estado_id', 1);
        }])->get();

        $data = $servicios->map(function($servicio) use ($ensayosVisita) {
            return [
                'servicio' => $servicio->descripcion,
                'ensayos' => $servicio->ensayos->map(function($ensayo) use ($ensayosVisita) {
                    return [
                        'id' => $ensayo->id,
                        'descripcion' => $ensayo->descripcion,
                        'checked' => in_array($ensayo->id, $ensayosVisita),
                    ];
                }),
            ];
        });

        return response()->json($data);
    }
}
