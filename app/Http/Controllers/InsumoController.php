<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use Illuminate\Http\Request;
use App\Models\Marca;
use App\Models\UnidadMedida;

class InsumoController extends Controller
{
    public function index()
    {
        $insumos = Insumo::with(['marca', 'unidadMedida', 'estado', 'usuario'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('insumo.index', compact('insumos'));
    }

    public function create()
    {
        // Obtener marcas activas
        $marcas = Marca::where('estado_id', 1)->orderBy('descripcion')->get();

        // Obtener unidades de medida activas
        $unidadesMedida = UnidadMedida::where('estado_id', 1)->orderBy('descripcion')->get();

        // Obtener combinaciones existentes de descripción + marca para validación
        $insumosExistentes = Insumo::select('descripcion', 'marca_id')->get()->map(function($insumo) {
            return [
                'descripcion' => $insumo->descripcion,
                'marca_id' => $insumo->marca_id
            ];
        })->toArray();

        return view('insumo.create', compact('marcas', 'unidadesMedida', 'insumosExistentes'));
    }
    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'marca_id' => 'required|exists:marca,id',
            'unidad_medida_id' => 'required|exists:unidad_medidas,id',
            'fecha' => 'required|date',
            'estado_id' => 'required|exists:estados,id'
        ], [
            // Mensajes personalizados
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.max' => 'La descripción no debe exceder los 255 caracteres.',

            'marca_id.required' => 'Debe seleccionar una marca.',
            'marca_id.exists' => 'La marca seleccionada no es válida.',

            'unidad_medida_id.required' => 'Debe seleccionar una unidad de medida.',
            'unidad_medida_id.exists' => 'La unidad de medida seleccionada no es válida.',

            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha debe tener un formato válido.',

            'estado_id.required' => 'El estado es obligatorio.',
            'estado_id.exists' => 'El estado seleccionado no es válido.'
        ]);

        try {
            // Verificar que la marca esté activa
            $marca = Marca::where('id', $request->marca_id)
                        ->where('estado_id', 1)
                        ->first();

            if (!$marca) {
                return back()->withInput()
                        ->with('error', 'La marca seleccionada no está activa o no existe.');
            }

            // Verificar que la unidad de medida esté activa
            $unidadMedida = UnidadMedida::where('id', $request->unidad_medida_id)
                                    ->where('estado_id', 1)
                                    ->first();

            if (!$unidadMedida) {
                return back()->withInput()
                        ->with('error', 'La unidad de medida seleccionada no está activa o no existe.');
            }

            // Verificar que no exista la misma descripción con la misma marca
            $insumoExistente = Insumo::where('descripcion', trim($request->descripcion))
                                    ->where('marca_id', $request->marca_id)
                                    ->first();

            if ($insumoExistente) {
                return back()->withInput()
                        ->with('error', 'Ya existe un insumo con esta descripción y marca. El mismo insumo de la misma marca no puede duplicarse.');
            }

            // Crear el insumo
            $insumo = Insumo::create([
                'descripcion' => trim($request->descripcion),
                'marca_id' => $request->marca_id,
                'unidad_medida_id' => $request->unidad_medida_id,
                'fecha' => $request->fecha,
                'estado_id' => $request->estado_id,
                'usuario_id' => session('user_id'),
            ]);

            return redirect()->route('insumo.index')
                        ->with('success', 'Insumo "' . $insumo->descripcion . '" de la marca "' . $marca->descripcion . '" creado exitosamente.');

        } catch (\Illuminate\Database\QueryException $e) {
            // Error de base de datos
            if ($e->getCode() == 23000) { // Código de violación de integridad
                return back()->withInput()
                        ->with('error', 'Error: Ya existe un insumo con esta descripción y marca.');
            }

            return back()->withInput()
                    ->with('error', 'Error de base de datos: ' . $e->getMessage());

        } catch (\Exception $e) {
            // Error general
            return back()->withInput()
                    ->with('error', 'Error inesperado al crear el insumo: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $insumo = Insumo::findOrFail($id);

            // Verificar que el insumo esté activo
            if ($insumo->estado_id != 1) {
                return redirect()->route('insumo.index')
                               ->with('error', 'El insumo ya está inactivo.');
            }

            // Cambiar estado a inactivo (2) en lugar de eliminar
            $insumo->update([
                'estado_id' => 2
            ]);

            return redirect()->route('insumo.index')
                           ->with('success', 'Insumo "' . $insumo->descripcion . '" desactivado exitosamente.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('insumo.index')
                           ->with('error', 'Insumo no encontrado.');

        } catch (\Exception $e) {
            return redirect()->route('insumo.index')
                           ->with('error', 'Error al desactivar el insumo: ' . $e->getMessage());
        }
    }

    /**
     * Activar un insumo (cambiar estado a activo)
     */
    public function activate($id)
    {
        try {
            $insumo = Insumo::findOrFail($id);

            // Verificar que el insumo esté inactivo
            if ($insumo->estado_id != 2) {
                return redirect()->route('insumo.index')
                            ->with('error', 'El insumo ya está activo.');
            }

            // Verificar que la marca y unidad de medida asociadas estén activas
            $marca = Marca::where('id', $insumo->marca_id)
                        ->where('estado_id', 1)
                        ->first();

            if (!$marca) {
                return redirect()->route('insumo.index')
                            ->with('error', 'No se puede activar el insumo porque la marca asociada está inactiva.');
            }

            $unidadMedida = UnidadMedida::where('id', $insumo->unidad_medida_id)
                                    ->where('estado_id', 1)
                                    ->first();

            if (!$unidadMedida) {
                return redirect()->route('insumo.index')
                            ->with('error', 'No se puede activar el insumo porque la unidad de medida asociada está inactiva.');
            }

            // Verificar que no exista otro insumo activo con la misma descripción Y la misma marca
            $insumoExistente = Insumo::where('descripcion', $insumo->descripcion)
                                ->where('marca_id', $insumo->marca_id)
                                ->where('estado_id', 1)
                                ->where('id', '!=', $insumo->id)
                                ->first();

            if ($insumoExistente) {
                return redirect()->route('insumo.index')
                            ->with('error', 'No se puede activar el insumo porque ya existe otro insumo activo con la misma descripción y marca.');
            }

            // Cambiar estado a activo (1)
            $insumo->update([
                'estado_id' => 1
            ]);

            return redirect()->route('insumo.index')
                        ->with('success', 'Insumo "' . $insumo->descripcion . '" de la marca "' . $marca->descripcion . '" activado exitosamente.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('insumo.index')
                        ->with('error', 'Insumo no encontrado.');

        } catch (\Exception $e) {
            return redirect()->route('insumo.index')
                        ->with('error', 'Error al activar el insumo: ' . $e->getMessage());
        }
    }

}
