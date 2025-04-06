<?php

namespace App\Http\Controllers;

use App\Models\Titulo;
use Illuminate\Http\Request;

class TituloController extends Controller
{
    // Listar todos los títulos
    public function index()
    {
        $titulos = Titulo::all();
        return response()->json($titulos);
    }

    // Mostrar un título específico
    public function show($id)
    {
        $titulo = Titulo::find($id);

        if (!$titulo) {
            return response()->json(['message' => 'Título no encontrado'], 404);
        }

        return response()->json($titulo);
    }

    // Crear un nuevo título
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:45|unique:titulos,nombre',
        ]);

        $titulo = Titulo::create($validated);

        return response()->json(['message' => 'Título creado con éxito', 'titulo' => $titulo], 201);
    }

    // Actualizar un título existente
    public function update(Request $request, $id)
    {
        $titulo = Titulo::find($id);

        if (!$titulo) {
            return response()->json(['message' => 'Título no encontrado'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:45|unique:titulos,nombre,' . $id,
        ]);

        $titulo->update($validated);

        return response()->json(['message' => 'Título actualizado con éxito', 'titulo' => $titulo]);
    }

    // Eliminar un título
    public function destroy($id)
    {
        $titulo = Titulo::find($id);

        if (!$titulo) {
            return response()->json(['message' => 'Título no encontrado'], 404);
        }

        // Se eliminan también relaciones en tablas pivot automáticamente si están configuradas con `onDelete('cascade')`
        $titulo->delete();

        return response()->json(['message' => 'Título eliminado con éxito']);
    }
}