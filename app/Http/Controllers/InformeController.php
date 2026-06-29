<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\Impuesto;
use App\Models\PresupuestoCompra;
use App\Models\Proveedor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InformeController extends Controller
{
    public function index()
    {
        return view('informes.select');
    }

    public function presupuestosCompraForm()
    {
        $proveedores = Proveedor::orderBy('razon_social')->get();
        $estados = Estado::whereIn('id', PresupuestoCompra::query()->distinct()->pluck('estado_id'))->get();

        return view('informes.presupuestos_compra', compact('proveedores', 'estados'));
    }

    public function presupuestosCompraPdf(Request $request)
    {
        $validated = $request->validate([
            'proveedor_id' => 'nullable|exists:proveedor,id',
            'estado_id' => 'nullable|exists:estados,id',
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date|after_or_equal:fecha_desde',
        ]);

        $presupuestos = PresupuestoCompra::with([
                'proveedor',
                'estado',
                'pedidoCompra',
                'detalles.impuesto',
            ])
            ->when($validated['proveedor_id'] ?? null, fn ($q, $v) => $q->where('proveedor_id', $v))
            ->when($validated['estado_id'] ?? null, fn ($q, $v) => $q->where('estado_id', $v))
            ->when($validated['fecha_desde'] ?? null, fn ($q, $v) => $q->whereDate('fecha_emision', '>=', $v))
            ->when($validated['fecha_hasta'] ?? null, fn ($q, $v) => $q->whereDate('fecha_emision', '<=', $v))
            ->orderBy('fecha_emision', 'desc')
            ->get();

        $totalGeneral = 0;

        foreach ($presupuestos as $presupuesto) {
            $presupuesto->total_calculado = $presupuesto->detalles->sum(function ($detalle) {
                $subtotal = $detalle->cantidad * $detalle->precio_unitario;
                $impuesto = $detalle->impuesto;

                if ($impuesto && $impuesto->id !== 1) {
                    $subtotal += round($subtotal / $impuesto->calculo);
                }

                return $subtotal;
            });

            $totalGeneral += $presupuesto->total_calculado;
        }

        $proveedor = isset($validated['proveedor_id']) ? Proveedor::find($validated['proveedor_id']) : null;
        $estado = isset($validated['estado_id']) ? Estado::find($validated['estado_id']) : null;

        $pdf = Pdf::loadView('informes.presupuestos_compra_pdf', [
            'presupuestos' => $presupuestos,
            'totalGeneral' => $totalGeneral,
            'filtros' => [
                'proveedor' => $proveedor->razon_social ?? null,
                'estado' => $estado->descripcion ?? null,
                'fecha_desde' => $validated['fecha_desde'] ?? null,
                'fecha_hasta' => $validated['fecha_hasta'] ?? null,
            ],
            'generadoEn' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->stream('informe_presupuestos_compra_' . now()->format('Ymd_His') . '.pdf');
    }
}
