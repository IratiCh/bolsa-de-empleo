<?php

namespace App\Http\Controllers;

use App\Models\Empresa; // Modelo para interactuar con la tabla "empresa" en la base de datos.
use Illuminate\Http\Request; // Clase para manejar solicitudes HTTP.

class AdminController extends Controller
{
    /**
     * Método para obtener las empresas pendientes de validación.
     * Retorna un listado de empresas con el campo "validado" en 0 (pendiente de validación).
    **/
    public function getEmpresasPendientes()
    {
        try {
            
            // Consultar empresas con validado = 0
            // Seleccionar únicamente las columnas relevantes para esta acción.
            $empresas = Empresa::where('validado', 0) // Filtrar empresas pendientes.
                             ->select(['id', 'nombre', 'cif', 'telefono', 'email']) // Campos que serán devueltos en la respuesta.
                             ->get(); // Obtener el resultado.

            // Devolver la lista de empresas pendientes en formato JSON.
            return response()->json($empresas);
            
        } catch (\Exception $e) {
            // Manejo de errores: devolver un mensaje de error en formato JSON con detalles si ocurre una excepción.
            return response()->json([
                'error' => 'Error al cargar empresas', // Mensaje de error genérico.
                'details' => $e->getMessage() // Detalles específicos del error para depuración.
            ], 500); // Código de estado HTTP 500: Error interno del servidor.
        }
    }

    /**
     * Método para validar o rechazar una empresa.
     * Actualiza el estado de validación de una empresa según la acción proporcionada por el administrador.
     **/

    public function validarEmpresa(Request $request, $id)
    {
        // Validar los datos recibidos.
        // Se espera que "accion" sea obligatorio y contenga uno de los valores "aceptar" o "rechazar".
        $request->validate([
            'accion' => 'required|in:aceptar,rechazar'
        ]);

        try {
            // Buscar la empresa en la base de datos por su ID.
            $empresa = Empresa::find($id);

            // Verificar si la empresa existe.
            if (!$empresa) {
                // Si no se encuentra la empresa, devolver un error 404 (no encontrado).
                return response()->json([
                    'error' => 'Empresa no encontrada' // Mensaje de error indicando que la empresa no existe.
                ], 404);
            }

            // Actualizar el estado de validación de la empresa.
            // Si la acción es "aceptar", se marca como validada (validado = 1).
            // Si la acción es "rechazar", se marca como rechazada (validado = -1).
            $empresa->validado = $request->accion === 'aceptar' ? 1 : -1;
            $empresa->save(); // Guardar los cambios en la base de datos.

            // Devolver una respuesta de éxito en formato JSON.
            return response()->json([
                'success' => true,
                'message' => 'Empresa actualizada correctamente',
                'empresa' => $empresa
            ]);

        } catch (\Exception $e) {
            // Manejo de errores: devolver un mensaje de error en caso de una excepción.
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar empresa',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
