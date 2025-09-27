<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;

class MarcaController extends Controller
{
    public function index()
    {
        $marcas = Marca::with(['estado', 'usuario'])->get();
        return view('marca.index', compact('marcas'));
    }

    public function create()
    {
        // Obtener todas las descripciones de marcas existentes
        $marcasExistentes = Marca::pluck('descripcion')->toArray();

        return view('marca.create', compact('marcasExistentes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'fecha' => 'required|date',
        ]);

        Marca::create([
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
            'estado_id' => $request->estado_id,
            'usuario_id' => session('user_id')
        ]);

        return redirect()->route('marca.index')->with('success', 'Marca creada exitosamente.');
    }

    public function destroy($id)
    {
        $marca = Marca::findOrFail($id);
        $marca->estado_id = 2; // Cambiar estado a inactivo
        $marca->save();

        return redirect()->route('marca.index')->with('success', 'Marca desactivada exitosamente.');
    }

    public function activate($id)
    {
        $marca = Marca::findOrFail($id);
        $marca->estado_id = 1; // Cambiar estado a activo
        $marca->save();

        return redirect()->route('marca.index')->with('success', 'Marca activada exitosamente.');
    }
}
