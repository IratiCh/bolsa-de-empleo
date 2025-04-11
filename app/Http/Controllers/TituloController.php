<?php

namespace App\Http\Controllers;

use App\Models\Titulo; // Modelo que interactúa con la tabla "titulos".
use Illuminate\Http\Request; // Clase para manejar solicitudes HTTP.
use Illuminate\Support\Facades\Validator; // Facade para validar los datos enviados en las solicitudes.

class TituloController extends Controller
{
    /**
     * Obtener la lista de todos los títulos.
     **/
    public function gestionTitulos()
    {
        try {
            // Obtener todos los registros de la tabla "titulos".
            $titulos = Titulo::all();
            // Devolver los títulos en formato JSON.
            return response()->json($titulos);
        } catch (\Exception $e) {
            // Devolver un mensaje de error en caso de que ocurra una excepción.
            return response()->json([
                'error' => 'Error al cargar títulos',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un título específico por su ID.
     **/
    public function eliminar($id)
    {
        try {
            // Buscar el título en la base de datos por su ID.
            $titulo = Titulo::find($id);

            // Si no se encuentra el título, devolver un error 404.
            if (!$titulo) {
                return response()->json(['error' => 'Título no encontrado'], 404);
            }

            // Eliminar el título de la base de datos.
            $titulo->delete();
            
            // Devolver una respuesta de éxito.
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Manejo de errores y devolución de un mensaje genérico.
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Crear un nuevo título.
     **/
    public function crear(Request $request)
    {
        // Validar los datos enviados en la solicitud.
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:45|unique:titulos,nombre'
        ], [
            // Mensajes personalizados para los errores de validación.
            'nombre.max' => 'El nombre es demasiado largo',
        ]);

        // Si la validación falla, devolver los errores con el código HTTP 422.
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Crear un nuevo registro en la tabla "titulos".
            $titulo = Titulo::create([
                'nombre' => $request->nombre
            ]);

            // Devolver un mensaje de éxito con el título creado.
            return response()->json([
                'success' => true,
                'titulo' => $titulo
            ], 201);

        } catch (\Exception $e) {
            // Manejo de errores durante la creación del título.
            return response()->json([
                'success' => false,
                'message' => 'Error al crear título'
            ], 500);
        }
    }

    /**
     * Mostrar un título específico por su ID.
     **/
    public function mostrar($id)
    {
        try {
            // Buscar el título en la base de datos por su ID.
            $titulo = Titulo::find($id);

            // Si no se encuentra el título, devolver un error 404.
            if (!$titulo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Título no encontrado'
                ], 404);
            }

            // Devolver los datos del título encontrado.
            return response()->json([
                'success' => true,
                'titulo' => $titulo // Objeto con los datos del título.
            ]);
        } catch (\Exception $e) {
            // Manejo de errores durante la búsqueda.
            return response()->json([
                'success' => false,
                'message' => 'Título no encontrado',
                'details' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Modificar un título específico por su ID.
    **/
    public function modificar(Request $request, $id)
    {
        // Validar los datos enviados en la solicitud.
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:45|unique:titulos,nombre,'.$id
        ], [
                                     // Mensajes personalizados para los errores de validación.
            'nombre.max' => 'El nombre es demasiado largo',
        ]);

        // Si la validación falla, devolver los errores con el código HTTP 422.
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Buscar el título en la base de datos por su ID.
            $titulo = Titulo::find($id);

            // Si no se encuentra el título, devolver un error 404.
            if (!$titulo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Título no encontrado'
                ], 404);
            }

            // Actualizar el nombre del título.
            $titulo->update([
                'nombre' => $request->nombre
            ]);

            // Devolver un mensaje de éxito con el título actualizado.
            return response()->json([
                'success' => true,
                'titulo' => $titulo
            ], 200);

        } catch (\Exception $e) {
            // Manejo de errores durante la actualización.
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar título'
            ], 500);
        }
    }
}
