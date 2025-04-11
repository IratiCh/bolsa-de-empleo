<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demandante;
use App\Models\Usuario;
use App\Models\Titulo;
use App\Models\TituloDemandante;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DemandanteController extends Controller
{
    public function getPerfil($id)
    {
        try {
            $demandante = Demandante::with(['titulos'])->findOrFail($id);
            
            // Obtener todos los títulos disponibles para los selects
            $allTitulos = Titulo::all();
            
            // Formatear los títulos del demandante
            $titulosDemandante = $demandante->titulos->map(function($titulo) {
            
                $año = ($titulo->pivot->año === '0000-00-00' || empty($titulo->pivot->año)) 
                ? null 
                : $titulo->pivot->año;

                return [
                    'id' => $titulo->pivot->id,
                    'titulo_id' => $titulo->id,
                    'nombre' => $titulo->nombre,
                    'centro' => $titulo->pivot->centro,
                    'año' => $año,
                    'cursando' => $titulo->pivot->cursando
                ];
            });

            return response()->json([
                'success' => true,
                'demandante' => $demandante,
                'titulos' => $titulosDemandante,
                'allTitulos' => $allTitulos
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error al obtener perfil demandante: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al cargar perfil'
            ], 500);
        }
    }

    public function actualizarPerfil(Request $request, $id)
{
    try {
        DB::beginTransaction();
        
        $demandante = Demandante::findOrFail($id);
        $usuario = Usuario::where('email', $demandante->email)
                        ->where('rol', 'demandante')
                        ->firstOrFail();

        // Convertir tel_movil a string para validación
        $request->merge(['tel_movil' => (string)$request->tel_movil]);

        $validated = $request->validate([
            'nombre' => 'required|string|max:45',
            'ape1' => 'required|string|max:45',
            'ape2' => 'required|string|max:45',
            'tel_movil' => [
                'required',
                'regex:/^[67]\d{8}$/' // Número de teléfono obligatorio, debe empezar por 6 o 7 seguido de 8 dígitos.
            ], 
            'contrasena_hash' => 'required|string|min:6'
        ], [
            'nombre.max' => 'El nombre es demasiado largo',
            'ape1.max' => 'El primer apellido es demasiado largo',
            'ape2.max' => 'El segundo apellido es demasiado largo',
            'tel_movil.digits' => 'El número de teléfono debe comenzar con 6 o 7 y tener un total de 9 dígitos.',
            'contrasena_hash.min' => 'La contraseña debe tener una longitud mínima de 6.'
        ]);

        // Convertir tel_movil a integer para la BD
        $validated['tel_movil'] = (int)$validated['tel_movil'];

        // Actualizar demandante
        $demandante->update([
            'nombre' => $validated['nombre'],
            'ape1' => $validated['ape1'],
            'ape2' => $validated['ape2'],
            'tel_movil' => $validated['tel_movil']
        ]);
                    
        // Actualizar usuario
        $usuario->update([
            'contrasena_hash' => Hash::make($validated['contrasena_hash'])
        ]);

        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Perfil actualizado correctamente'
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Error al actualizar perfil: " . $e->getMessage(), [
            'request' => $request->all(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'error' => 'Error al actualizar perfil',
            'details' => env('APP_DEBUG') ? $e->getMessage() : null
        ], 500);
    }
}

    public function guardarTitulo(Request $request, $idDemandante)
    {
        try {
            $validated = $request->validate([
                'titulos' => 'required|array|min:1',
                'titulos.*.id' => 'nullable|exists:titulos_demandante,id', // Para actualizar
                'titulos.*.titulo_id' => 'required|exists:titulos,id',
                'titulos.*.centro' => 'required|string|max:45',
                'titulos.*.año' => 'required|date',
                'titulos.*.cursando' => 'required|string|max:45'
            ],[
                // Mensajes personalizados
                'titulos.*.centro.max' => 'El centro es demasiado largo',
                'titulos.*.cursando.max' => 'La situación del curso es demasiado larga.'
            ]);

            TituloDemandante::where('id_dem', $idDemandante)->delete();

            
            foreach ($validated['titulos'] as $tituloData) {
                TituloDemandante::create([
                    'id_dem' => $idDemandante,
                    'id_titulo' => $tituloData['titulo_id'],
                    'centro' => $tituloData['centro'],
                    'año' => $tituloData['año'],
                    'cursando' => $tituloData['cursando']
                ]);
            }
            
            return response()->json(['success' => 'Título agregado correctamente']);
            
        } catch (\Exception $e) {
            Log::error("Error al agregar título", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Error al agregar título'], 500);
        }
    }
}