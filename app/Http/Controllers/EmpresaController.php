<?php

namespace App\Http\Controllers;

use App\Models\Oferta; // Modelo para interactuar con la tabla "oferta".
use App\Models\Demandante; // Modelo para interactuar con la tabla "demandante".
use App\Models\ApuntadosOferta; // Modelo para interactuar con la tabla "apuntados_oferta".
use App\Models\Titulo; // Modelo para interactuar con la tabla "titulo".
use Illuminate\Http\Request; // Clase para manejar solicitudes HTTP.
use Illuminate\Support\Facades\Log; // Facade para registrar errores en los logs del sistema.

class EmpresaController extends Controller
{
    /**
     * Método para obtener demandantes relacionados con una oferta.
     * Devuelve tres grupos:
     * 1. Demandantes adjudicados a la oferta.
     * 2. Demandantes inscritos pero no adjudicados.
     * 3. Demandantes no inscritos pero que cumplen con la titulación requerida.
     **/
    public function getDemandantes($idOferta)
    {
        try {
            // Buscar la oferta y cargar sus títulos requeridos mediante la relación "titulos".
            $oferta = Oferta::with(['titulos'])->findOrFail($idOferta);
            
            // Obtener los IDs de los títulos requeridos por la oferta.
            $titulosRequeridos = $oferta->titulos->pluck('id')->toArray();
            
            // Buscar demandantes adjudicados a esta oferta (ya seleccionados).
            $adjudicados = Demandante::whereHas('ofertasInscritas', function($query) use ($idOferta) {
                $query->where('id_oferta', $idOferta)
                    ->whereNotNull('adjudicada'); // Demandantes adjudicados.
            })
            ->get();

            // Si no hay titulaciones requeridas para la oferta, devolver un mensaje específico.
            if (empty($titulosRequeridos)) {
                return response()->json([
                    'success' => true,
                    'oferta' => [
                        'id' => $oferta->id, // ID único de la oferta.
                        'nombre' => $oferta->nombre // Nombre de la oferta.
                    ],
                    'inscritos' => [], // No hay demandantes inscritos.
                    'noInscritos' => [], // No hay demandantes con titulaciones requeridas.
                    'message' => 'Esta oferta no tiene titulaciones requeridas definidas' // Mensaje informativo.
                ]);
            }
    
            // Buscar demandantes inscritos en la oferta pero que aún no están adjudicados.
            $inscritos = Demandante::whereHas('ofertasInscritas', function($query) use ($idOferta) {
                $query->where('id_oferta', $idOferta)
                    ->whereNull('adjudicada'); // Demandantes no adjudicados.
            })
            ->get();
        
            // Buscar demandantes no inscritos en la oferta pero que tienen títulos requeridos por la oferta.
            $noInscritos = Demandante::whereDoesntHave('ofertasInscritas', function($query) use ($idOferta) {
                    $query->where('id_oferta', $idOferta); // Demandantes que no tienen inscripción en esta oferta.
                })
                ->whereHas('titulos', function($query) use ($titulosRequeridos) {
                    $query->whereIn('titulos.id', $titulosRequeridos); // Demandantes con títulos requeridos.
                })
                ->get();

            // Devolver los datos agrupados en formato JSON.
            return response()->json([
                'success' => true,
                'oferta' => [
                    'id' => $oferta->id,
                    'nombre' => $oferta->nombre,
                    'abierta' => $oferta->abierta
                ],'adjudicados' => $adjudicados->map(function($demandante) {
                    return [
                        'id' => $demandante->id,
                        'nombre_completo' => $demandante->nombre . ' ' . $demandante->apellidos,
                        'email' => $demandante->email,
                        'tel_movil' => $demandante->tel_movil
                    ];
                }),
                'inscritos' => $inscritos->map(function($demandante) {
                    return [
                        'id' => $demandante->id,
                        'nombre_completo' => $demandante->nombre . ' ' . $demandante->apellidos,
                        'email' => $demandante->email,
                        'tel_movil' => $demandante->tel_movil
                    ];
                }),
                'noInscritos' => $noInscritos->map(function($demandante) {
                    return [
                        'id' => $demandante->id,
                        'nombre_completo' => $demandante->nombre . ' ' . $demandante->apellidos,
                        'email' => $demandante->email,
                        'tel_movil' => $demandante->tel_movil
                    ];
                })
            ]);
    
        } catch (\Exception $e) {
            // Registrar el error en los logs del sistema para depuración.
            \Log::error("Error en getDemandantes: " . $e->getMessage());
            // Devolver una respuesta de error genérica en formato JSON con código HTTP 500.
            return response()->json([
                'success' => false,
                'error' => 'Error al cargar demandantes',
                'details' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * Método para adjudicar un demandante a una oferta.
     * Actualiza el estado de la inscripción y cierra la oferta.
     **/
    public function asignarDemandante(Request $request, $idOferta)
    {
        // Validar que el ID del demandante sea válido y exista en la tabla "demandante".
        try {
            $validated = $request->validate([
                'id_demandante' => 'required|exists:demandante,id'
            ]);
    
            // Obtener la oferta por su ID.
            $oferta = Oferta::findOrFail($idOferta);
    
            // Verificar si ya existe un demandante adjudicado a esta oferta.
            $yaAdjudicado = ApuntadosOferta::where('id_oferta', $idOferta)
                ->whereNotNull('adjudicada') // Verificar si hay adjudicados.
                ->exists(); // Retorna true si existe, false si no.
    
            if ($yaAdjudicado) {
                // Si ya hay un adjudicado, devolver un error con código HTTP 400.
                return response()->json([
                    'success' => false,
                    'error' => 'Ya hay un demandante adjudicado a esta oferta'
                ], 400);
            }
    
            // Buscar la inscripción del demandante en esta oferta.
            $inscripcion = ApuntadosOferta::where('id_oferta', $idOferta)
                ->where('id_demandante', $validated['id_demandante'])
                ->first();
    
            if ($inscripcion) {
                // Si la inscripción existe, actualizar el estado a "Adjudicado".
                ApuntadosOferta::where('id_oferta', $idOferta)
                ->where('id_demandante', $validated['id_demandante'])
                ->update([
                    'adjudicada' => 'Adjudicado'
                ]);
            } else {
                // Si no existe inscripción, crear una nueva.
                ApuntadosOferta::create([
                    'id_oferta' => $idOferta,
                    'id_demandante' => $validated['id_demandante'],
                    'adjudicada' => 'Adjudicado',
                    'fecha' => now()
                ]);
            }
    
            // Actualizar el estado de la oferta a "cerrada" y asignar la fecha de cierre.
            $oferta->update([
                'abierta' => 1,
                'fecha_cierre' => now()
            ]);

            // Devolver una respuesta de éxito en formato JSON.
            return response()->json([
                'success' => true,
                'message' => 'Demandante adjudicado con éxito y oferta cerrada'
            ]);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Capturar errores de validación y devolver una respuesta con código HTTP 422.
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Registrar el error en los logs del sistema para depuración.
            Log::error('Error al adjudicar demandante: ' . $e->getMessage());
            // Devolver una respuesta de error genérica en formato JSON con código HTTP 
            return response()->json([
                'success' => false,
                'error' => 'Error al adjudicar demandante: ' . $e->getMessage()
            ], 500);
        }
    }
}