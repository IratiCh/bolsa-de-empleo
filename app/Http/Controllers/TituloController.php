<?php

namespace App\Http\Controllers;

use App\Models\Titulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TituloController extends Controller
{
    public function gestionTitulos()
    {
        try {
            $titulos = Titulo::all();
            return response()->json($titulos);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al cargar títulos',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function eliminar($id)
    {
        try {
            $titulo = Titulo::findOrFail($id);
            $titulo->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:45|unique:titulos,nombre'
        ], [
            // Mensajes personalizados
            'nombre.max' => 'El nombre es demasiado largo',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $titulo = Titulo::create([
                'nombre' => $request->nombre
            ]);

            return response()->json([
                'success' => true,
                'titulo' => $titulo
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear título'
            ], 500);
        }
    }

    public function mostrar($id)
    {
        try {
            $titulo = Titulo::findOrFail($id);
            return response()->json([
                'success' => true,
                'titulo' => $titulo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Título no encontrado',
                'details' => $e->getMessage()
            ], 404);
        }
    }

    public function modificar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:45|unique:titulos,nombre,'.$id
        ], [
            'nombre.max' => 'El nombre es demasiado largo',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $titulo = Titulo::findOrFail($id);
            $titulo->update([
                'nombre' => $request->nombre
            ]);

            return response()->json([
                'success' => true,
                'titulo' => $titulo
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar título'
            ], 500);
        }
    }
}