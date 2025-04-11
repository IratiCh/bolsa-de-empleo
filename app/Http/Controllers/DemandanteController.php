<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Clase para manejar solicitudes HTTP.
use App\Models\Demandante; // Modelo que interactúa con la tabla "demandante".
use App\Models\Usuario; // Modelo que interactúa con la tabla "usuarios".
use Illuminate\Support\Facades\DB; // Facade para ejecutar transacciones y consultas directas en la base de datos.
use Illuminate\Support\Facades\Hash; // Facade para trabajar con contraseñas hasheadas.
use Illuminate\Support\Facades\Log; // Facade para registrar errores y eventos importantes en el sistema.
use Illuminate\Validation\Rule; // Clase para crear reglas avanzadas de validación.

class DemandanteController extends Controller
{
    
    /**
     * Método para obtener el perfil del demandante.
     * Realiza la búsqueda del perfil de un usuario con rol "demandante" y devuelve los datos en formato JSON.
     */
    public function getPerfil($id)
    {
        try {
            // Buscar el usuario con el ID proporcionado y verificar que su rol sea "demandante".
            $usuario = User::where('id', $userId) // Buscar el usuario por ID.
                          ->where('rol', 'demandante') // Asegurar que el rol sea "demandante".
                          ->first(); // Obtener el primer resultado.

            // Si no se encuentra el usuario, devolver un error 404.
            if (!$usuario) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            // Buscar el demandante en la tabla "demandante" utilizando su email.
            $demandante = Demandante::where('email', $usuario->email)->first();

            // Si no se encuentra el demandante, devolver un error 404.
            if (!$demandante) {
                return response()->json(['error' => 'Demandante no encontrado'], 404);
            }

            // Si el campo "id_dem" del usuario está vacío, actualizarlo con el ID del demandante encontrado.
            if (empty($usuario->id_dem)) {
                $usuario->update(['id_dem' => $demandante->id]); // Actualizar el ID del demandante.
            }

            // Devolver los datos del demandante y usuario en formato JSON.
            return response()->json([
                'demandante' => [
                    'id' => $demandante->id, // ID único del demandante.
                    'nombre' => $demandante->nombre, // Nombre del demandante.
                    'ape1' => $demandante->ape1, // Primer apellido.
                    'ape2' => $demandante->ape2, // Segundo apellido.
                    'tel_movil' => $demandante->tel_movil, // Teléfono móvil.
                    'email' => $demandante->email, // Email del demandante.
                    'user_id' => $usuario->id // ID del usuario relacionado.
                ]
            ]);
            
        } catch (\Exception $e) {
            // Registrar el error en los logs para su análisis.
            Log::error("Error al obtener perfil demandante: " . $e->getMessage());
            // Devolver una respuesta de error genérica con código 500.
            return response()->json(['error' => 'Error al cargar perfil'], 500);
        }
    }

    /**
     * Método para actualizar el perfil del demandante.
     * Valida los datos enviados por el usuario y actualiza tanto la tabla "demandante" como "usuarios".
     **/
    public function actualizarPerfil(Request $request, $id)
    {
        try {
            // Iniciar una transacción para garantizar la consistencia de los datos.
            DB::beginTransaction();

            // Buscar el usuario con el ID proporcionado y verificar que su rol sea "demandante"
            $usuario = User::where('id', $userId) // Buscar el usuario por ID.
                          ->where('rol', 'demandante') // Asegurar que el rol sea "demandante".
                          ->first();

            // Si no se encuentra el usuario, devolver un error 404.
            if (!$usuario) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            // Buscar el demandante relacionado en la tabla "demandante" por su email.
            $demandante = Demandante::where('email', $usuario->email)->first();

            // Si no se encuentra el demandante, devolver un error 404.
            if (!$demandante) {
                return response()->json(['error' => 'Demandante no encontrado'], 404);
            }

            // Validar los datos enviados en la solicitud.
            $validated = $request->validate([
                'nombre' => 'required|string|max:45', // Nombre obligatorio, máximo de 45 caracteres.
                'ape1' => 'required|string|max:45', // Primer apellido obligatorio, máximo de 45 caracteres.
                'ape2' => 'nullable|string|max:45', // Segundo apellido opcional, máximo de 45 caracteres.
                'tel_movil' => 'required|string|max:9|regex:/^[0-9]{9}$/', // Teléfono móvil obligatorio con regex para formato.
                'email' => [
                    'required',
                    'email', // Verificar que sea un email válido.
                    'max:45',
                    Rule::unique('demandante', 'email')->ignore($demandante->id), // Email único en "demandante", excepto el actual.
                    Rule::unique('usuarios', 'email')->ignore($usuario->id) // Email único en "usuarios", excepto el actual.
                ],
                'contrasena_hash' => 'nullable|string|min:6' // Contraseña opcional, mínimo de 6 caracteres.
            ], [
                'tel_movil.regex' => 'El teléfono debe tener 9 dígitos numéricos', // Mensaje personalizado para el teléfono.
                'email.unique' => 'Este email ya está registrado' // Mensaje personalizado para emails duplicados.
            ]);

            // Actualizar la información en la tabla "demandante".
            $demandante->update([
                'nombre' => $validated['nombre'],
                'ape1' => $validated['ape1'],
                'ape2' => $validated['ape2'],
                'tel_movil' => $validated['tel_movil'],
                'email' => $validated['email']
            ]);

            // Actualizar la información en la tabla "usuarios".
            $updateData = [
                'email' => $validated['email']
            ];

            // Si se proporciona una contraseña, hashearla y agregarla a la actualización.
            if (!empty($validated['contrasena_hash'])) {
                $updateData['contrasena_hash'] = Hash::make($validated['contrasena_hash']); // Hashear la contraseña.
            }
    
            $usuario->update($updateData); // Actualizar los datos del usuario.

            DB::commit(); // Confirmar los cambios en la base de datos.

            // Devolver una respuesta de éxito en formato JSON.
            return response()->json([
                'message' => 'Perfil actualizado correctamente',
                'email_updated' => $usuario->email !== $validated['email'] // Indicar si el email fue modificado.
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si la validación falla, revertir los cambios realizados.
            DB::rollBack();
            return response()->json(['error' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Si ocurre otro error, revertir los cambios realizados.
            DB::rollBack();
            \Log::error('Error al actualizar perfil del demandante: ' . $e->getMessage());
            return response()->json(['error' => 'Error al actualizar el perfil'], 500);
        }
    }

}
