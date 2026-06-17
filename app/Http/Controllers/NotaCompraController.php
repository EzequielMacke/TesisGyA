<?php

namespace App\Http\Controllers;

use App\Models\NotaCompra;
use App\Models\Compra;
use App\Models\CuentaPagar;
use App\Models\Impuesto;
use App\Models\LibroCompra;
use App\Models\Proveedor;
use App\Models\Estado;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotaCompraController extends Controller
{
    public function index(Request $request)
    {
        $proveedores = Proveedor::orderBy('razon_social')->get();
        $tiposDocumento = TipoDocumento::whereIn('id', [2, 3])->get();
        $estados = Estado::whereIn('id', [3, 4, 5])->get();

        $query = NotaCompra::with(['proveedor', 'factura', 'tipoDocumento', 'iva', 'usuario', 'estado']);

        if ($request->filled('proveedor_id')) {
            $query->where('proveedor_id', $request->proveedor_id);
        }
        if ($request->filled('tipo_documento_id')) {
            $query->where('tipo_documento_id', $request->tipo_documento_id);
        }
        if ($request->filled('estado_id')) {
            $query->where('estado_id', $request->estado_id);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_emision', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_emision', '<=', $request->fecha_hasta);
        }

        $notas = $query->orderBy('fecha_emision', 'desc')->paginate(20);

        return view('notas_compra.index', compact('notas', 'proveedores', 'tiposDocumento', 'estados'));
    }

    public function create()
    {
        $proveedores = Proveedor::orderBy('razon_social')->get();
        $tiposDocumento = TipoDocumento::whereIn('id', [2, 3])->get();
        $impuestos = Impuesto::where('estado_id', 1)->get();

        return view('notas_compra.create', compact('proveedores', 'tiposDocumento', 'impuestos'));
    }

    public function facturasPorProveedor($proveedor_id)
    {
        $facturas = Compra::where('proveedor_id', $proveedor_id)
            ->where('estado_id', 4)
            ->orderBy('fecha_emision', 'desc')
            ->get(['id', 'nro_factura', 'fecha_emision', 'monto']);

        return response()->json($facturas);
    }

    public function aprobar($id)
    {
        $nota = NotaCompra::with(['factura', 'iva'])->findOrFail($id);

        if ($nota->estado_id !== 3) {
            return redirect()->route('notas_compra.index')
                ->with('error', 'Solo se pueden aprobar notas en estado Pendiente.');
        }

        DB::transaction(function () use ($nota) {
            $factura  = $nota->factura;
            $esCredito = $nota->tipo_documento_id == 2;

            // --- Calcular IVA sobre el monto de la nota ---
            $monto     = $nota->monto;
            $iva5      = 0;
            $iva10     = 0;
            $ivaExento = 0;

            if ($nota->iva_id == 2) {          // IVA 5%
                $iva5       = round($monto / 21);
            } elseif ($nota->iva_id == 3) {    // IVA 10%
                $iva10      = round($monto / 11);
            } else {                            // Exento
                $ivaExento  = $monto;
            }
            $totalIva    = $iva5 + $iva10;
            $montoSinIva = round($monto - $totalIva);

            // --- Distribuir en cuentas a pagar ---
            $cuotas = CuentaPagar::where('compra_id', $factura->id)->get();

            if ($cuotas->isNotEmpty()) {
                $cantCuotas    = $cuotas->count();
                $montoPorCuota = (int) floor($monto / $cantCuotas);
                $resto         = $monto - ($montoPorCuota * $cantCuotas);

                foreach ($cuotas as $index => $cuota) {
                    $montoEsta = $montoPorCuota + ($index === $cantCuotas - 1 ? $resto : 0);

                    if ($esCredito) {
                        $cuota->descuento += $montoEsta;
                    } else {
                        $cuota->aumento += $montoEsta;
                    }

                    $cuota->saldo_neto = $cuota->saldo - $cuota->descuento + $cuota->aumento;
                    $cuota->save();
                }
            }

            // Nota de Crédito reduce el gasto → negativo. Nota de Débito lo aumenta → positivo.
            $signo = $esCredito ? -1 : 1;

            LibroCompra::create([
                'proveedor_id'     => $nota->proveedor_id,
                'compra_id'        => $factura->id,
                'tipo_documento_id'=> $nota->tipo_documento_id,
                'monto'            => $signo * $montoSinIva,
                'iva5'             => $signo * $iva5,
                'iva10'            => $signo * $iva10,
                'iva_exento'       => $signo * $ivaExento,
                'total_iva'        => $signo * $totalIva,
                'fecha_emision'    => $nota->fecha_emision,
                'condicion_pago_id'=> $factura->condicion_pago_id,
                'estado_id'        => 4,
                'datos_empresa_id' => $factura->datos_empresa_id,
                'timbrado'         => $nota->timbrado,
                'nro_factura'      => $nota->nro_nota,
            ]);

            // --- Aprobar nota ---
            $nota->update(['estado_id' => 4]);
        });

        return redirect()->route('notas_compra.index')
            ->with('success', 'Nota de compra aprobada correctamente.');
    }

    public function anular($id)
    {
        $nota = NotaCompra::findOrFail($id);

        if ($nota->estado_id !== 3) {
            return redirect()->route('notas_compra.index')
                ->with('error', 'Solo se pueden anular notas en estado Pendiente.');
        }

        $nota->update(['estado_id' => 5]);

        return redirect()->route('notas_compra.index')
            ->with('success', 'Nota de compra anulada correctamente.');
    }

    public function edit($id)
    {
        $nota = NotaCompra::findOrFail($id);

        if ($nota->estado_id !== 3) {
            return redirect()->route('notas_compra.index')
                ->with('error', 'Solo se pueden editar notas en estado Pendiente.');
        }

        $proveedores    = Proveedor::orderBy('razon_social')->get();
        $tiposDocumento = TipoDocumento::whereIn('id', [2, 3])->get();
        $impuestos      = Impuesto::where('estado_id', 1)->get();
        $facturas       = Compra::where('proveedor_id', $nota->proveedor_id)
                            ->where('estado_id', 4)
                            ->orderBy('fecha_emision', 'desc')
                            ->get(['id', 'nro_factura', 'fecha_emision', 'monto']);

        return view('notas_compra.edit', compact('nota', 'proveedores', 'tiposDocumento', 'impuestos', 'facturas'));
    }

    public function update(Request $request, $id)
    {
        $nota = NotaCompra::findOrFail($id);

        if ($nota->estado_id !== 3) {
            return redirect()->route('notas_compra.index')
                ->with('error', 'Solo se pueden editar notas en estado Pendiente.');
        }

        $request->validate([
            'proveedor_id'      => 'required|exists:proveedor,id',
            'factura_id'        => 'required|exists:compras,id',
            'tipo_documento_id' => 'required|in:2,3',
            'nro_nota'          => ['required', 'string', 'regex:/^\d{3}-\d{3}-\d{7}$/'],
            'timbrado'          => ['required', 'digits:8'],
            'fecha_emision'     => 'required|date',
            'fecha_vencimiento' => 'required|date|after_or_equal:fecha_emision',
            'monto'             => 'required|numeric|min:1',
            'iva_id'            => 'required|exists:impuestos,id',
            'concepto'          => 'nullable|string|max:255',
        ], [
            'nro_nota.regex'                   => 'El formato del número de nota debe ser XXX-XXX-XXXXXXX.',
            'timbrado.digits'                  => 'El timbrado debe tener exactamente 8 dígitos.',
            'fecha_vencimiento.after_or_equal' => 'La fecha de vencimiento no puede ser anterior a la fecha de emisión.',
            'monto.min'                        => 'El monto debe ser mayor a 0.',
        ]);

        if ($request->tipo_documento_id == 2) {
            $factura = Compra::find($request->factura_id);
            if ($factura && $request->monto > $factura->monto) {
                return back()->withInput()->withErrors([
                    'monto' => 'El monto de la Nota de Crédito no puede superar el monto de la factura (₲ ' . number_format($factura->monto, 0, ',', '.') . ').',
                ]);
            }
        }

        $nota->update([
            'nro_nota'          => $request->nro_nota,
            'timbrado'          => $request->timbrado,
            'fecha_emision'     => $request->fecha_emision,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'proveedor_id'      => $request->proveedor_id,
            'factura_id'        => $request->factura_id,
            'monto'             => $request->monto,
            'iva_id'            => $request->iva_id,
            'tipo_documento_id' => $request->tipo_documento_id,
            'concepto'          => $request->concepto,
        ]);

        return redirect()->route('notas_compra.index')->with('success', 'Nota de compra actualizada correctamente.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'proveedor_id'      => 'required|exists:proveedor,id',
            'factura_id'        => 'required|exists:compras,id',
            'tipo_documento_id' => 'required|in:2,3',
            'nro_nota'          => ['required', 'string', 'regex:/^\d{3}-\d{3}-\d{7}$/'],
            'timbrado'          => ['required', 'digits:8'],
            'fecha_emision'     => 'required|date',
            'fecha_vencimiento' => 'required|date|after_or_equal:fecha_emision',
            'monto'             => 'required|numeric|min:1',
            'iva_id'            => 'required|exists:impuestos,id',
            'concepto'          => 'nullable|string|max:255',
        ], [
            'nro_nota.regex'                   => 'El formato del número de nota debe ser XXX-XXX-XXXXXXX.',
            'timbrado.digits'                  => 'El timbrado debe tener exactamente 8 dígitos.',
            'fecha_vencimiento.after_or_equal' => 'La fecha de vencimiento no puede ser anterior a la fecha de emisión.',
            'monto.min'                        => 'El monto debe ser mayor a 0.',
        ]);

        if ($request->tipo_documento_id == 2) {
            $factura = Compra::find($request->factura_id);
            if ($factura && $request->monto > $factura->monto) {
                return back()->withInput()->withErrors([
                    'monto' => 'El monto de la Nota de Crédito no puede superar el monto de la factura (₲ ' . number_format($factura->monto, 0, ',', '.') . ').',
                ]);
            }
        }

        NotaCompra::create([
            'nro_nota'          => $request->nro_nota,
            'timbrado'          => $request->timbrado,
            'fecha_emision'     => $request->fecha_emision,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'proveedor_id'      => $request->proveedor_id,
            'factura_id'        => $request->factura_id,
            'monto'             => $request->monto,
            'iva_id'            => $request->iva_id,
            'tipo_documento_id' => $request->tipo_documento_id,
            'concepto'          => $request->concepto,
            'usuario_id'        => session('user_id'),
            'estado_id'         => 3,
        ]);

        return redirect()->route('notas_compra.index')->with('success', 'Nota de compra registrada exitosamente.');
    }
}
