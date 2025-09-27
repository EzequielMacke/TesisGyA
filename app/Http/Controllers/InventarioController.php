<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\Sucursal;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        // Obtener todas las sucursales para el filtro
        $sucursales = Sucursal::where('estado_id', 1)->orderBy('descripcion')->get();

        // Obtener la sucursal seleccionada del request
        $sucursalSeleccionada = $request->get('sucursal');

        // Consulta base del inventario
        $query = Inventario::with([
            'deposito',
            'insumo.marca',
            'insumo.unidadMedida',
            'estado'
        ]);

        // Filtrar por sucursal si se seleccionó una
        if ($sucursalSeleccionada) {
            // Obtener los depósitos de la sucursal seleccionada
            $sucursal = Sucursal::find($sucursalSeleccionada);
            if ($sucursal && $sucursal->deposito_id) {
                $query->where('deposito_id', $sucursal->deposito_id);
            }
        }

        // Obtener solo inventarios activos y ordenar
        $inventarios = $query->where('estado_id', 1)
                            ->orderBy('deposito_id')
                            ->orderBy('insumo_id')
                            ->get();

        return view('inventario.index', compact(
            'inventarios',
            'sucursales',
            'sucursalSeleccionada'
        ));
    }
}
