<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use App\Models\Demandante;
use App\Models\ApuntadosOferta;
use App\Models\Titulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmpresaController extends Controller
{
    public function getDemandantes($idOferta)
    {
        try {
            // Obtener la oferta con sus titulos requeridos
            $oferta = Oferta::with(['titulos'])->findOrFail($idOferta);
            
            // Obtener IDs de titulos requeridos
            $titulosRequeridos = $oferta->titulos->pluck('id')->toArray();
            
            // Demandantes adjudicados a esta oferta
            $adjudicados = Demandante::whereHas('ofertasInscritas', function($query) use ($idOferta) {
                $query->where('id_oferta', $idOferta)
                    ->whereNotNull('adjudicada');
            })
            ->get();

            if (empty($titulosRequeridos)) {
                return response()->json([
                    'success' => true,
                    'oferta' => [
                        'id' => $oferta->id,
                        'nombre' => $oferta->nombre
                    ],
                    'inscritos' => [],
                    'noInscritos' => [],
                    'message' => 'Esta oferta no tiene titulaciones requeridas definidas'
                ]);
            }
    
            // Demandantes inscritos pero no adjudicados
            $inscritos = Demandante::whereHas('ofertasInscritas', function($query) use ($idOferta) {
                $query->where('id_oferta', $idOferta)
                    ->whereNull('adjudicada');
            })
            ->get();
        
            // Demandantes no inscritos pero con titulación requerida
            $noInscritos = Demandante::whereDoesntHave('ofertasInscritas', function($query) use ($idOferta) {
                    $query->where('id_oferta', $idOferta);
                })
                ->whereHas('titulos', function($query) use ($titulosRequeridos) {
                    $query->whereIn('titulos.id', $titulosRequeridos);
                })
                ->get();
    
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
            \Log::error("Error en getDemandantes: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al cargar demandantes',
                'details' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function asignarDemandante(Request $request, $idOferta)
    {
        try {
            $validated = $request->validate([
                'id_demandante' => 'required|exists:demandante,id'
            ]);
    
            // Obtener la oferta
            $oferta = Oferta::findOrFail($idOferta);
    
            // Verificar si ya hay alguien adjudicado a esta oferta
            $yaAdjudicado = ApuntadosOferta::where('id_oferta', $idOferta)
                ->whereNotNull('adjudicada')
                ->exists();
    
            if ($yaAdjudicado) {
                return response()->json([
                    'success' => false,
                    'error' => 'Ya hay un demandante adjudicado a esta oferta'
                ], 400);
            }
    
            // Buscar la inscripción específica para esta oferta y demandante
            $inscripcion = ApuntadosOferta::where('id_oferta', $idOferta)
                ->where('id_demandante', $validated['id_demandante'])
                ->first();
    
            if ($inscripcion) {
                // Si existe, actualizar
                ApuntadosOferta::where('id_oferta', $idOferta)
                ->where('id_demandante', $validated['id_demandante'])
                ->update([
                    'adjudicada' => 'Adjudicado'
                ]);
            } else {
                // Si no existe, crear
                ApuntadosOferta::create([
                    'id_oferta' => $idOferta,
                    'id_demandante' => $validated['id_demandante'],
                    'adjudicada' => 'Adjudicado',
                    'fecha' => now()
                ]);
            }
    
            // Cerrar la oferta
            $oferta->update([
                'abierta' => 1,
                'fecha_cierre' => now()
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Demandante adjudicado con éxito y oferta cerrada'
            ]);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al adjudicar demandante: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al adjudicar demandante: ' . $e->getMessage()
            ], 500);
        }
    }
}