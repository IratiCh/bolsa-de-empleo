<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demandante;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DemandanteController extends Controller
{
    public function getPerfil($id)
    {
        try {
            $usuario = User::where('id', $userId)
                          ->where('rol', 'demandante')
                          ->first();

            if (!$usuario) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            // Buscar el demandante por email
            $demandante = Demandante::where('email', $usuario->email)->first();

            if (!$demandante) {
                return response()->json(['error' => 'Demandante no encontrado'], 404);
            }

            if (empty($usuario->id_dem)) {
                $usuario->update(['id_dem' => $demandante->id]);
            }

            return response()->json([
                'demandante' => [
                    'id' => $demandante->id,
                    'nombre' => $demandante->nombre,
                    'ape1' => $demandante->ape1,
                    'ape2' => $demandante->ape2,
                    'tel_movil' => $demandante->tel_movil,
                    'email' => $demandante->email,
                    'user_id' => $usuario->id
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error al obtener perfil demandante: " . $e->getMessage());
            return response()->json(['error' => 'Error al cargar perfil'], 500);
        }
    }

    public function actualizarPerfil(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $usuario = User::where('id', $userId)
                          ->where('rol', 'demandante')
                          ->first();

            if (!$usuario) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }
            
            $demandante = Demandante::where('email', $usuario->email)->first();
            if (!$demandante) {
                return response()->json(['error' => 'Demandante no encontrado'], 404);
            }

            // Validación
            $validated = $request->validate([
                'nombre' => 'required|string|max:45',
                'ape1' => 'required|string|max:45',
                'ape2' => 'nullable|string|max:45',
                'tel_movil' => 'required|string|max:9|regex:/^[0-9]{9}$/',
                'email' => [
                    'required',
                    'email',
                    'max:45',
                    Rule::unique('demandante', 'email')->ignore($demandante->id),
                    Rule::unique('usuarios', 'email')->ignore($usuario->id)
                ],
                'contrasena_hash' => 'nullable|string|min:6'
            ], [
                'tel_movil.regex' => 'El teléfono debe tener 9 dígitos numéricos',
                'email.unique' => 'Este email ya está registrado'
            ]);

            // Actualizar tabla demandante
            $demandante->update([
                'nombre' => $validated['nombre'],
                'ape1' => $validated['ape1'],
                'ape2' => $validated['ape2'],
                'tel_movil' => $validated['tel_movil'],
                'email' => $validated['email']
            ]);

            // Actualizar tabla usuarios
            $updateData = [
                'email' => $validated['email']
            ];
    
            if (!empty($validated['contrasena_hash'])) {
                $updateData['contrasena_hash'] = Hash::make($validated['contrasena_hash']);
            }
    
            $usuario->update($updateData);

            DB::commit();

            return response()->json([
                'message' => 'Perfil actualizado correctamente',
                'email_updated' => $usuario->email !== $validated['email']
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al actualizar perfil del demandante: ' . $e->getMessage());
            return response()->json(['error' => 'Error al actualizar el perfil'], 500);
        }
    }

}