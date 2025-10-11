<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Cliente;
use App\Models\Obra;
use App\Models\PresupuestoServicio;
use App\Models\Estado;
use Illuminate\Http\Request;

class ContratoController extends Controller
{
    public function index(Request $request)
    {
        $query = Contrato::with(['cliente', 'obra', 'presupuestoServicio', 'estado', 'usuario']);

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
            $query->whereDate('fecha_firma', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_firma', '<=', $request->fecha_hasta);
        }

        $contratos = $query->orderBy('created_at', 'desc')->get();

        // Datos para filtros
        $clientes = Cliente::where('estado_id', 1)->orderBy('razon_social')->get();
        $obras = Obra::where('estado_id', 1)->orderBy('descripcion')->get();
        $estados = Estado::whereIn('id', [3, 4, 5])->orderBy('descripcion')->get();

        return view('contrato.index', compact('contratos', 'clientes', 'obras', 'estados'));
    }

    public function create()
    {
        // Obtener clientes activos
        $clientes = Cliente::where('estado_id', 1)->orderBy('razon_social')->get();

        // Obtener obras activas
        $obras = Obra::where('estado_id', 1)->orderBy('descripcion')->get();

        // Obtener presupuestos activos
        $presupuestos = PresupuestoServicio::where('estado_id', 3)->orderBy('numero_presupuesto')->get();

        // Obtener estados activos
        $estados = Estado::whereIn('id', [3, 4, 5])->orderBy('descripcion')->get();

        return view('contrato.create', compact('clientes', 'obras', 'presupuestos', 'estados'));
    }

    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'obra_id' => 'required|exists:obras,id',
            'presupuesto_servicio_id' => 'required|exists:presupuesto_servicio,id',
            'plazo_dias' => 'required|integer|min:1',
            'fecha_firma' => 'required|date',
            'monto' => 'required|numeric|min:0',
            'anticipo' => 'required|numeric|min:0|max:100',
            'pago_mitad' => 'required|numeric|min:0|max:100',
            'pago_final' => 'required|numeric|min:0|max:100',
            'garantia_meses' => 'required|integer|min:0',
            'ciudad' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
        ], [
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'obra_id.required' => 'Debe seleccionar una obra.',
            'presupuesto_servicio_id.required' => 'Debe seleccionar un presupuesto.',
            'plazo_dias.required' => 'El plazo en días es obligatorio.',
            'fecha_firma.required' => 'La fecha de firma es obligatoria.',
            'monto.required' => 'El monto es obligatorio.',
            'anticipo.required' => 'El anticipo es obligatorio.',
            'pago_mitad.required' => 'El pago a la mitad es obligatorio.',
            'pago_final.required' => 'El pago final es obligatorio.',
            'garantia_meses.required' => 'La garantía en meses es obligatoria.',
            'ciudad.required' => 'La ciudad es obligatoria.',
        ]);

        try {
            // Crear el contrato
            $contrato = Contrato::create([
                'cliente_id' => $request->cliente_id,
                'obra_id' => $request->obra_id,
                'presupuesto_servicio_id' => $request->presupuesto_servicio_id,
                'usuario_id' => session('user_id'),
                'plazo_dias' => $request->plazo_dias,
                'fecha_firma' => $request->fecha_firma,
                'fecha_registro' => $request->fecha_registro,
                'monto' => $request->monto,
                'anticipo' => $request->anticipo,
                'pago_mitad' => $request->pago_mitad,
                'pago_final' => $request->pago_final,
                'garantia_meses' => $request->garantia_meses,
                'ciudad' => $request->ciudad,
                'observaciones' => $request->observaciones,
                'estado_id' => $request->estado_id,
            ]);

            // Cambiar el estado del presupuesto a 4 (contratado)
            PresupuestoServicio::where('id', $request->presupuesto_servicio_id)->update(['estado_id' => 4]);

            return redirect()->route('contrato.index')
                            ->with('success', 'Contrato creado exitosamente.');

        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Error al crear el contrato: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $contrato = Contrato::with(['cliente', 'obra', 'presupuestoServicio.detalles.ensayo.servicio', 'presupuestoServicio.detalles.impuesto', 'estado', 'usuario'])
                            ->findOrFail($id);

        return view('contrato.show', compact('contrato'));
    }

    public function obrasPorCliente($cliente_id)
    {
        $obras = Obra::where('cliente_id', $cliente_id)->where('estado_id', 1)->orderBy('descripcion')->get();
        return response()->json($obras);
    }

    public function presupuestosPorObra($obra_id)
    {
        $presupuestos = PresupuestoServicio::where('obra_id', $obra_id)->where('estado_id', 3)->orderBy('numero_presupuesto')->get();
        return response()->json($presupuestos);
    }
}
