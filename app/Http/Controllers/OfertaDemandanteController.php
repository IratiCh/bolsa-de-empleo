<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use App\Models\ApuntadosOferta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OfertaDemandanteController extends Controller
{
    public function show($id)
    {
        try {
            $oferta = Oferta::with(['empresa', 'tipoContrato', 'titulos'])->findOrFail($id);
    
            return response()->json([
                'id' => $oferta->id,
                'nombre' => $oferta->nombre,
                'breve_desc' => $oferta->breve_desc,
                'desc' => $oferta->desc,
                'obs' => $oferta->obs,
                'fecha_pub' => $oferta->fecha_pub,
                'fecha_limite' => $oferta->fecha_limite,
                'fecha_cierre' => $oferta->fecha_cierre,
                'num_puesto' => $oferta->num_puesto,
                'horario' => $oferta->horario,
                'abierta' => $oferta->abierta,
                'tipo_contrato' => $oferta->tipoContrato->nombre ?? null,
                'empresa_nombre' => $oferta->empresa->nombre ?? null,
                'empresa_localidad' => $oferta->empresa->localidad ?? null,
                'titulos' => $oferta->titulos->pluck('nombre')->toArray()
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Error en OfertaDemandanteController: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al cargar la oferta',
                'details' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Verificar si un demandante está inscrito en una oferta
     */
    public function verificarInscripcion($idOferta, Request $request)
    {
        try {
            $idDemandante = $request->query('id_dem');
            
            if (!$idDemandante) {
                return response()->json([
                    'success' => false,
                    'error' => 'ID de demandante no proporcionado'
                ], 400);
            }

            $inscrito = ApuntadosOferta::where('id_oferta', $idOferta)
                ->where('id_demandante', $idDemandante)
                ->exists();

            return response()->json([
                'success' => true,
                'inscrito' => $inscrito
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error verificando inscripción: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al verificar inscripción'
            ], 500);
        }
    }

    /**
     * Inscribir un demandante en una oferta
     */
    public function inscribir(Request $request, $idOferta)
    {
        try {
            $validated = $request->validate([
                'id_demandante' => 'required|exists:demandante,id'
            ]);

            // Verificar si la oferta existe y está abierta
            $oferta = Oferta::find($idOferta);
            if (!$oferta) {
                return response()->json([
                    'success' => false,
                    'error' => 'Oferta no encontrada'
                ], 404);
            }

            if ($oferta->abierta !== 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'La oferta no está abierta'
                ], 400);
            }

            // Verificar si ya está inscrito
            $yaInscrito = ApuntadosOferta::where('id_oferta', $idOferta)
                ->where('id_demandante', $validated['id_demandante'])
                ->exists();

            if ($yaInscrito) {
                return response()->json([
                    'success' => false,
                    'error' => 'Ya estás inscrito en esta oferta'
                ], 400);
            }

            // Crear la inscripción
            ApuntadosOferta::create([
                'id_oferta' => $idOferta,
                'id_demandante' => $validated['id_demandante'],
                'fecha' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Inscripción realizada con éxito'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al inscribirse: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al realizar la inscripción',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todas las ofertas a las que está inscrito un demandante
     */
    public function ofertasInscritas(Request $request)
    {
        try {
            $idDemandante = $request->query('id_dem');
            
            if (!$idDemandante) {
                return response()->json([
                    'success' => false,
                    'error' => 'ID de demandante no proporcionado'
                ], 400);
            }

            $ofertas = Oferta::select('oferta.*')
                ->join('apuntados_oferta', 'oferta.id', '=', 'apuntados_oferta.id_oferta')
                ->where('apuntados_oferta.id_demandante', $idDemandante)
                ->with(['empresa', 'tipoContrato'])
                ->get()
                ->map(function($oferta) {
                    return [
                        'id' => $oferta->id,
                        'nombre' => $oferta->nombre,
                        'breve_desc' => $oferta->breve_desc
                    ];
                });

            return response()->json([
                'success' => true,
                'ofertas' => $ofertas
            ]);

        } catch (\Exception $e) {
            Log::error("Error en ofertasInscritas: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al cargar ofertas inscritas'
            ], 500);
        }
    }

    /**
     * Cancelar inscripción a una oferta
     */
    public function cancelarInscripcion(Request $request, $idOferta)
    {
        try {
            $validated = $request->validate([
                'id_demandante' => 'required|exists:demandante,id'
            ]);

            $deleted = ApuntadosOferta::where('id_oferta', $idOferta)
                ->where('id_demandante', $validated['id_demandante'])
                ->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Inscripción cancelada con éxito'
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'No se encontró la inscripción'
            ], 404);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al cancelar inscripción: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al cancelar la inscripción'
            ], 500);
        }
    }

}