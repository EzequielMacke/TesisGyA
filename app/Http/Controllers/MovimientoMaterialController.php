<?php

namespace App\Http\Controllers;

use App\Models\Deposito;
use App\Models\Inventario;
use App\Models\MovimientoMaterial;
use App\Models\MovimientoMaterialDetalle;
use App\Models\SolicitudMaterial;
use App\Models\SolicitudMaterialDetalle;
use App\Models\Sucursal;
use App\Models\TipoVehiculo;
use Illuminate\Http\Request;

class MovimientoMaterialController extends Controller
{
    public function index(Request $request)
    {
        $estado_id = $request->input('estado_id');
        $destino = $request->input('destino');
        $fecha_desde = $request->input('fecha_desde');
        $search = $request->input('search');

        $query = MovimientoMaterial::with([
            'usuario',
            'origenDeposito',
            'destinoObra',
            'destinoDeposito',
            'solicitudMaterial',
            'tipoVehiculo',
            'estado',
            'detalles.insumo.marca',
            'detalles.insumo.unidadMedida',
        ]);

        if ($estado_id) {
            $query->where('estado_id', $estado_id);
        }
        if ($destino === 'obra') {
            $query->whereNotNull('destino_obra_id');
        } elseif ($destino === 'deposito') {
            $query->whereNotNull('destino_deposito_id');
        }
        if ($fecha_desde) {
            $query->whereDate('fecha', '>=', $fecha_desde);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nro_remision', 'like', "%$search%")
                    ->orWhere('vehiculo_chapa', 'like', "%$search%")
                    ->orWhere('chofer_ci', 'like', "%$search%")
                    ->orWhereHas('origenDeposito', function ($q2) use ($search) {
                        $q2->where('descripcion', 'like', "%$search%");
                    })
                    ->orWhereHas('destinoObra', function ($q2) use ($search) {
                        $q2->where('descripcion', 'like', "%$search%");
                    })
                    ->orWhereHas('destinoDeposito', function ($q2) use ($search) {
                        $q2->where('descripcion', 'like', "%$search%");
                    });
            });
        }

        $movimientos = $query->orderByDesc('id')->get();

        return view('movimiento_insumos.index', compact('movimientos'));
    }

    public function create()
    {
        $solicitudes = SolicitudMaterial::with(['usuario', 'obra', 'deposito'])
            ->where('estado_id', 3)
            ->orderByDesc('id')
            ->get();

        $depositos = Deposito::where('estado_id', 1)->orderBy('descripcion')->get();
        $tiposVehiculo = TipoVehiculo::where('estado_id', 1)->orderBy('descripcion')->get();

        return view('movimiento_insumos.create', compact('solicitudes', 'depositos', 'tiposVehiculo'));
    }

    public function solicitudInfo($id)
    {
        $solicitud = SolicitudMaterial::with(['obra', 'deposito', 'detalles.insumo.marca', 'detalles.insumo.unidadMedida'])
            ->where('estado_id', 3)
            ->findOrFail($id);

        $destino = null;
        if ($solicitud->obra) {
            $destino = ['tipo' => 'obra', 'descripcion' => $solicitud->obra->descripcion];
        } elseif ($solicitud->deposito) {
            $destino = ['tipo' => 'deposito', 'descripcion' => $solicitud->deposito->descripcion];
        }

        $detalles = $solicitud->detalles->map(function ($detalle) {
            $entregada = $detalle->cantidad_entregada ?? 0;

            return [
                'insumo_id' => $detalle->insumo_id,
                'descripcion' => $detalle->insumo->descripcion ?? '-',
                'marca' => $detalle->insumo->marca->descripcion ?? '-',
                'unidad' => $detalle->insumo->unidadMedida->descripcion ?? '-',
                'cantidad_solicitada' => $detalle->cantidad_solicitada,
                'cantidad_entregada' => $entregada,
                'cantidad_pendiente' => $detalle->cantidad_solicitada - $entregada,
                'observacion' => $detalle->observacion,
            ];
        })->filter(fn ($d) => $d['cantidad_pendiente'] > 0)->values();

        return response()->json([
            'destino' => $destino,
            'observacion' => $solicitud->observacion,
            'detalles' => $detalles,
        ]);
    }

    public function inventarioDeposito($depositoId)
    {
        $inventario = Inventario::where('deposito_id', $depositoId)
            ->pluck('cantidad', 'insumo_id');

        return response()->json([
            'inventario' => $inventario,
            'nro_remision' => $this->generarNroRemision($depositoId),
        ]);
    }

    private function generarNroRemision($depositoId): string
    {
        $sucursal = Sucursal::where('deposito_id', $depositoId)->first();
        $secuencial = MovimientoMaterial::where('origen_deposito_id', $depositoId)->count() + 1;

        return sprintf('%03d-%03d-%07d', $sucursal->id ?? 0, $depositoId, $secuencial);
    }

    public function store(Request $request)
    {
        $request->validate([
            'solicitud_material_id' => 'required|exists:solicitud_materiales,id',
            'origen_deposito_id' => 'required|exists:deposito,id',
            'fecha' => 'required|date',
            'vehiculo_chapa' => 'required|string|max:255',
            'tipo_vehiculo_id' => 'required|exists:tipo_vehiculo,id',
            'chofer_ci' => 'required|string|max:255',
            'chofer_nombre' => 'required|string|max:255',
            'observacion' => 'nullable|string|max:500',
            'detalles' => 'required|array|min:1',
            'detalles.*.insumo_id' => 'required|exists:insumo,id',
            'detalles.*.cantidad' => 'required|numeric|min:0',
        ], [
            'solicitud_material_id.required' => 'Debe seleccionar una solicitud.',
            'origen_deposito_id.required' => 'Debe seleccionar el depósito de origen.',
            'fecha.required' => 'Debe ingresar la fecha.',
            'vehiculo_chapa.required' => 'Debe ingresar la chapa del vehículo.',
            'tipo_vehiculo_id.required' => 'Debe seleccionar el tipo de vehículo.',
            'chofer_ci.required' => 'Debe ingresar la CI del chofer.',
            'chofer_nombre.required' => 'Debe ingresar el nombre del chofer.',
            'detalles.required' => 'Debe enviar al menos un insumo.',
        ]);

        $solicitud = SolicitudMaterial::with('detalles')->findOrFail($request->solicitud_material_id);

        if ($solicitud->estado_id != 3) {
            return back()->with('error', 'Solo se pueden registrar movimientos para solicitudes pendientes.');
        }

        $itemsAEnviar = array_filter($request->detalles, fn ($item) => $item['cantidad'] > 0);

        if (count($itemsAEnviar) == 0) {
            return back()->with('error', 'Debe ingresar al menos un insumo con cantidad mayor a 0.');
        }

        foreach ($itemsAEnviar as $item) {
            $detalleSolicitud = $solicitud->detalles->where('insumo_id', $item['insumo_id'])->first();

            if (!$detalleSolicitud) {
                return back()->with('error', 'El insumo seleccionado no pertenece a la solicitud.');
            }

            $pendiente = $detalleSolicitud->cantidad_solicitada - ($detalleSolicitud->cantidad_entregada ?? 0);
            $disponible = Inventario::where('deposito_id', $request->origen_deposito_id)
                ->where('insumo_id', $item['insumo_id'])
                ->value('cantidad') ?? 0;

            if ($item['cantidad'] > $pendiente || $item['cantidad'] > $disponible) {
                return back()->with('error', 'La cantidad a enviar excede lo pendiente o el stock disponible para algún insumo.');
            }
        }

        $movimiento = MovimientoMaterial::create([
            'usuario_id' => session('user_id'),
            'nro_remision' => $this->generarNroRemision($request->origen_deposito_id),
            'fecha' => $request->fecha,
            'origen_deposito_id' => $request->origen_deposito_id,
            'destino_obra_id' => $solicitud->obra_id,
            'destino_deposito_id' => $solicitud->deposito_id,
            'solicitud_material_id' => $solicitud->id,
            'vehiculo_chapa' => $request->vehiculo_chapa,
            'tipo_vehiculo_id' => $request->tipo_vehiculo_id,
            'chofer_ci' => $request->chofer_ci,
            'chofer_nombre' => $request->chofer_nombre,
            'estado_id' => 3,
            'observacion' => $request->observacion,
        ]);

        foreach ($itemsAEnviar as $item) {
            MovimientoMaterialDetalle::create([
                'movimiento_material_id' => $movimiento->id,
                'insumo_id' => $item['insumo_id'],
                'cantidad' => $item['cantidad'],
                'observacion' => $item['observacion'] ?? null,
            ]);

            Inventario::where('deposito_id', $request->origen_deposito_id)
                ->where('insumo_id', $item['insumo_id'])
                ->decrement('cantidad', $item['cantidad']);

            $inventarioDestino = Inventario::firstOrNew([
                'deposito_id' => $solicitud->deposito_id,
                'obra_id' => $solicitud->obra_id,
                'insumo_id' => $item['insumo_id'],
            ]);
            $inventarioDestino->cantidad = ($inventarioDestino->cantidad ?? 0) + $item['cantidad'];
            $inventarioDestino->estado_id = $inventarioDestino->estado_id ?? 1;
            $inventarioDestino->save();

            $detalleSolicitud = $solicitud->detalles->where('insumo_id', $item['insumo_id'])->first();
            $nuevaEntregada = ($detalleSolicitud->cantidad_entregada ?? 0) + $item['cantidad'];
            $detalleSolicitud->cantidad_entregada = $nuevaEntregada;
            $detalleSolicitud->terminado = $nuevaEntregada >= $detalleSolicitud->cantidad_solicitada ? 1 : 0;
            $detalleSolicitud->save();
        }

        $quedanPendientes = SolicitudMaterialDetalle::where('solicitud_material_id', $solicitud->id)
            ->where('terminado', 0)
            ->exists();

        if (!$quedanPendientes) {
            $solicitud->estado_id = 4; // Confirmado
            $solicitud->save();
        }

        return redirect()->route('movimiento_insumos.index')->with('success', 'Movimiento de insumos registrado correctamente.');
    }

    public function confirmar($id)
    {
        $movimiento = MovimientoMaterial::findOrFail($id);

        if ($movimiento->estado_id != 3) {
            return back()->with('error', 'Solo se pueden confirmar movimientos pendientes.');
        }

        $movimiento->estado_id = 4;
        $movimiento->save();

        return redirect()->route('movimiento_insumos.index')->with('success', 'Llegada confirmada correctamente.');
    }

    public function anular($id)
    {
        $movimiento = MovimientoMaterial::with(['detalles', 'solicitudMaterial.detalles'])->findOrFail($id);

        if ($movimiento->estado_id != 3) {
            return back()->with('error', 'Solo se pueden anular movimientos pendientes.');
        }

        foreach ($movimiento->detalles as $detalle) {
            Inventario::where('deposito_id', $movimiento->origen_deposito_id)
                ->where('insumo_id', $detalle->insumo_id)
                ->increment('cantidad', $detalle->cantidad);

            Inventario::where('deposito_id', $movimiento->destino_deposito_id)
                ->where('obra_id', $movimiento->destino_obra_id)
                ->where('insumo_id', $detalle->insumo_id)
                ->decrement('cantidad', $detalle->cantidad);

            if ($movimiento->solicitudMaterial) {
                $detalleSolicitud = $movimiento->solicitudMaterial->detalles->where('insumo_id', $detalle->insumo_id)->first();

                if ($detalleSolicitud) {
                    $detalleSolicitud->cantidad_entregada = max(0, ($detalleSolicitud->cantidad_entregada ?? 0) - $detalle->cantidad);
                    $detalleSolicitud->terminado = $detalleSolicitud->cantidad_entregada >= $detalleSolicitud->cantidad_solicitada ? 1 : 0;
                    $detalleSolicitud->save();
                }
            }
        }

        if ($movimiento->solicitudMaterial && $movimiento->solicitudMaterial->estado_id == 4) {
            $movimiento->solicitudMaterial->estado_id = 3;
            $movimiento->solicitudMaterial->save();
        }

        $movimiento->estado_id = 5;
        $movimiento->save();

        return redirect()->route('movimiento_insumos.index')->with('success', 'Movimiento anulado y revertido correctamente.');
    }

    public function remision($id)
    {
        $movimiento = MovimientoMaterial::with([
            'usuario',
            'origenDeposito',
            'destinoObra',
            'destinoDeposito',
            'solicitudMaterial',
            'tipoVehiculo',
            'estado',
            'detalles.insumo.marca',
            'detalles.insumo.unidadMedida',
        ])->findOrFail($id);

        return view('movimiento_insumos.remision', compact('movimiento'));
    }
}
