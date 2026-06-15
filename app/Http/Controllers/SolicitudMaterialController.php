<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Deposito;
use App\Models\Insumo;
use App\Models\Marca;
use App\Models\SolicitudMaterial;
use App\Models\SolicitudMaterialDetalle;
use Illuminate\Http\Request;

class SolicitudMaterialController extends Controller
{
    public function index(Request $request)
    {
        $estado_id = $request->input('estado_id');
        $destino = $request->input('destino');
        $fecha_desde = $request->input('fecha_desde');
        $search = $request->input('search');

        $query = SolicitudMaterial::with([
            'usuario',
            'obra',
            'deposito',
            'estado',
            'detalles.insumo.marca',
            'detalles.insumo.unidadMedida',
        ]);

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
                $q->whereHas('usuario', function ($q2) use ($search) {
                    $q2->where('usuario', 'like', "%$search%");
                })
                ->orWhereHas('obra', function ($q2) use ($search) {
                    $q2->where('descripcion', 'like', "%$search%");
                })
                ->orWhereHas('deposito', function ($q2) use ($search) {
                    $q2->where('descripcion', 'like', "%$search%");
                })
                ->orWhere('observacion', 'like', "%$search%");
            });
        }

        $solicitudes = $query->orderByDesc('id')->get();

        return view('solicitud_materiales.index', compact('solicitudes'));
    }

    public function create()
    {
        $clientes = Cliente::where('estado_id', 1)->orderBy('razon_social')->get();
        $depositos = Deposito::where('estado_id', 1)->orderBy('descripcion')->get();
        $marcas = Marca::where('estado_id', 1)->orderBy('descripcion')->get();

        $insumos = Insumo::with(['marca', 'unidadMedida'])
            ->where('estado_id', 1)
            ->whereHas('marca', function ($q) {
                $q->where('estado_id', 1);
            })
            ->orderBy('descripcion')
            ->get();

        return view('solicitud_materiales.create', compact('clientes', 'depositos', 'marcas', 'insumos'));
    }

    public function edit($id)
    {
        $solicitud = SolicitudMaterial::with([
            'obra.cliente',
            'deposito',
            'detalles.insumo.marca',
            'detalles.insumo.unidadMedida',
        ])->findOrFail($id);

        if (!$solicitud->puedeEditarse()) {
            return redirect()->route('solicitud_materiales.index')
                ->with('error', 'No se puede editar la solicitud porque ya tiene insumos entregados.');
        }

        $clientes = Cliente::where('estado_id', 1)->orderBy('razon_social')->get();
        if ($solicitud->obra && $solicitud->obra->cliente && !$clientes->contains('id', $solicitud->obra->cliente_id)) {
            $clientes->push($solicitud->obra->cliente);
        }

        $depositos = Deposito::where('estado_id', 1)->orderBy('descripcion')->get();
        if ($solicitud->deposito && !$depositos->contains('id', $solicitud->deposito_id)) {
            $depositos->push($solicitud->deposito);
        }

        $marcas = Marca::where('estado_id', 1)->orderBy('descripcion')->get();

        $insumos = Insumo::with(['marca', 'unidadMedida'])
            ->where('estado_id', 1)
            ->whereHas('marca', function ($q) {
                $q->where('estado_id', 1);
            })
            ->orderBy('descripcion')
            ->get();

        foreach ($solicitud->detalles as $detalle) {
            if ($detalle->insumo && !$insumos->contains('id', $detalle->insumo_id)) {
                $insumos->push($detalle->insumo);
            }
        }

        return view('solicitud_materiales.edit', compact('solicitud', 'clientes', 'depositos', 'marcas', 'insumos'));
    }

    public function update(Request $request, $id)
    {
        $solicitud = SolicitudMaterial::with('detalles')->findOrFail($id);

        if (!$solicitud->puedeEditarse()) {
            return redirect()->route('solicitud_materiales.index')
                ->with('error', 'No se puede editar la solicitud porque ya tiene insumos entregados.');
        }

        $request->validate([
            'destino_tipo' => 'required|in:obra,deposito',
            'cliente_id' => 'required_if:destino_tipo,obra|nullable|exists:clientes,id',
            'obra_id' => 'required_if:destino_tipo,obra|nullable|exists:obras,id',
            'deposito_id' => 'required_if:destino_tipo,deposito|nullable|exists:deposito,id',
            'fecha' => 'required|date',
            'observacion' => 'nullable|string|max:500',
            'insumos' => 'required|array|min:1',
            'insumos.*.insumo_id' => 'required|exists:insumo,id',
            'insumos.*.cantidad' => 'required|numeric|min:0.01',
            'insumos.*.observacion' => 'nullable|string|max:300',
        ], [
            'destino_tipo.required' => 'Debe seleccionar el destino de la solicitud.',
            'obra_id.required_if' => 'Debe seleccionar una obra.',
            'deposito_id.required_if' => 'Debe seleccionar un depósito.',
            'fecha.required' => 'Debe ingresar la fecha.',
            'insumos.required' => 'Debe agregar al menos un insumo a la solicitud.',
        ]);

        $solicitud->update([
            'obra_id' => $request->destino_tipo === 'obra' ? $request->obra_id : null,
            'deposito_id' => $request->destino_tipo === 'deposito' ? $request->deposito_id : null,
            'fecha' => $request->fecha,
            'observacion' => $request->observacion,
        ]);

        $solicitud->detalles()->delete();

        foreach ($request->insumos as $item) {
            SolicitudMaterialDetalle::create([
                'solicitud_material_id' => $solicitud->id,
                'insumo_id' => $item['insumo_id'],
                'cantidad_solicitada' => $item['cantidad'],
                'observacion' => $item['observacion'] ?? null,
            ]);
        }

        return redirect()->route('solicitud_materiales.index')
            ->with('success', 'Solicitud de insumos actualizada correctamente.');
    }

    public function anular($id)
    {
        $solicitud = SolicitudMaterial::findOrFail($id);

        if ($solicitud->estado_id != 3) {
            return redirect()->route('solicitud_materiales.index')
                ->with('error', 'Solo se pueden anular solicitudes pendientes.');
        }

        $solicitud->estado_id = 5;
        $solicitud->save();

        return redirect()->route('solicitud_materiales.index')
            ->with('success', 'Solicitud anulada correctamente.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'destino_tipo' => 'required|in:obra,deposito',
            'cliente_id' => 'required_if:destino_tipo,obra|nullable|exists:clientes,id',
            'obra_id' => 'required_if:destino_tipo,obra|nullable|exists:obras,id',
            'deposito_id' => 'required_if:destino_tipo,deposito|nullable|exists:deposito,id',
            'fecha' => 'required|date',
            'observacion' => 'nullable|string|max:500',
            'insumos' => 'required|array|min:1',
            'insumos.*.insumo_id' => 'required|exists:insumo,id',
            'insumos.*.cantidad' => 'required|numeric|min:0.01',
            'insumos.*.observacion' => 'nullable|string|max:300',
        ], [
            'destino_tipo.required' => 'Debe seleccionar el destino de la solicitud.',
            'obra_id.required_if' => 'Debe seleccionar una obra.',
            'deposito_id.required_if' => 'Debe seleccionar un depósito.',
            'fecha.required' => 'Debe ingresar la fecha.',
            'insumos.required' => 'Debe agregar al menos un insumo a la solicitud.',
        ]);

        $solicitud = SolicitudMaterial::create([
            'usuario_id' => session('user_id'),
            'obra_id' => $request->destino_tipo === 'obra' ? $request->obra_id : null,
            'deposito_id' => $request->destino_tipo === 'deposito' ? $request->deposito_id : null,
            'fecha' => $request->fecha,
            'estado_id' => 3,
            'observacion' => $request->observacion,
        ]);

        foreach ($request->insumos as $item) {
            SolicitudMaterialDetalle::create([
                'solicitud_material_id' => $solicitud->id,
                'insumo_id' => $item['insumo_id'],
                'cantidad_solicitada' => $item['cantidad'],
                'observacion' => $item['observacion'] ?? null,
            ]);
        }

        return redirect()->route('solicitud_materiales.index')
            ->with('success', 'Solicitud de insumos creada correctamente.');
    }
}
