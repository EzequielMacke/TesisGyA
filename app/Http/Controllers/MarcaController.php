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
            'fecha'       => 'required|date',
        ]);

        $usuarioId = session('user_id');

        if (!$usuarioId) {
            return redirect()->route('marca.index')->with('error', 'Sesión expirada. Volvé a iniciar sesión.');
        }

        Marca::create([
            'descripcion' => $request->descripcion,
            'fecha'       => $request->fecha,
            'estado_id'   => 1,
            'usuario_id'  => $usuarioId,
        ]);

        return redirect()->route('marca.index')->with('success', 'Marca creada exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'fecha'       => 'required|date',
        ]);

        $marca = Marca::findOrFail($id);
        $marca->descripcion = $request->descripcion;
        $marca->fecha       = $request->fecha;
        $marca->save();

        return redirect()->route('marca.index')->with('success', 'Marca actualizada exitosamente.');
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
