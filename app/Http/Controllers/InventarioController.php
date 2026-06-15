<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\Obra;
use App\Models\Sucursal;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        // Obtener todas las sucursales para el filtro
        $sucursales = Sucursal::where('estado_id', 1)->orderBy('descripcion')->get();

        // Obtener solo las obras que tienen inventario registrado
        $obraIds = Inventario::whereNotNull('obra_id')->distinct()->pluck('obra_id');
        $obras = Obra::where('estado_id', 1)->whereIn('id', $obraIds)->orderBy('descripcion')->get();

        // Obtener la sucursal/obra seleccionada del request
        $sucursalSeleccionada = $request->get('sucursal');
        $obraSeleccionada = $request->get('obra');

        // Consulta base del inventario
        $query = Inventario::with([
            'deposito',
            'obra',
            'insumo.marca',
            'insumo.unidadMedida',
            'estado'
        ]);

        // Filtrar por sucursal o por obra si se seleccionó alguna
        if ($sucursalSeleccionada) {
            // Obtener el depósito de la sucursal seleccionada
            $sucursal = Sucursal::find($sucursalSeleccionada);
            if ($sucursal && $sucursal->deposito_id) {
                $query->where('deposito_id', $sucursal->deposito_id);
            }
        } elseif ($obraSeleccionada) {
            $query->where('obra_id', $obraSeleccionada);
        }

        // Obtener solo inventarios activos y ordenar
        $inventarios = $query->where('estado_id', 1)
                            ->orderBy('deposito_id')
                            ->orderBy('obra_id')
                            ->orderBy('insumo_id')
                            ->get();

        return view('inventario.index', compact(
            'inventarios',
            'sucursales',
            'sucursalSeleccionada',
            'obras',
            'obraSeleccionada'
        ));
    }
}
