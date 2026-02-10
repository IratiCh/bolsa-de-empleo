<?php

namespace App\Http\Controllers;

use App\Models\Oferta; // Modelo para interactuar con la tabla "oferta".
use App\Models\Demandante; // Modelo para interactuar con la tabla "demandante".
use App\Models\ApuntadosOferta; // Modelo para interactuar con la tabla "apuntados_oferta".
use App\Models\Titulo; // Modelo para interactuar con la tabla "titulo".
use App\Models\NotificacionCentro; // Modelo para notificaciones del centro.
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
            $adjudicados = Demandante::whereHas('ofertasInscritas', function ($query) use ($idOferta) {
                $query->where('id_oferta', $idOferta)
                    ->where('adjudicada_estado', 1); // Demandantes adjudicados.
            })
                ->get();

            // Buscar demandantes inscritos en la oferta pero que aún no están adjudicados.
            $inscritos = Demandante::whereHas('ofertasInscritas', function ($query) use ($idOferta) {
                $query->where('id_oferta', $idOferta)
                    ->where('adjudicada_estado', 0); // Demandantes no adjudicados.
            })
                ->get();

            // Buscar demandantes no inscritos en la oferta pero que tienen títulos requeridos por la oferta.
            // Si la oferta no tiene titulaciones requeridas, no hay candidatos "no inscritos" por criterio de título.
            $noInscritos = collect();
            if (!empty($titulosRequeridos)) {
                $noInscritos = Demandante::whereDoesntHave('ofertasInscritas', function ($query) use ($idOferta) {
                    $query->where('id_oferta', $idOferta); // Demandantes que no tienen inscripción en esta oferta.
                })
                    ->whereHas('titulos', function ($query) use ($titulosRequeridos) {
                        $query->whereIn('titulos.id', $titulosRequeridos); // Demandantes con títulos requeridos.
                    })
                    ->get();
            }

            // Devolver los datos agrupados en formato JSON.
            $response = [
                'success' => true,
                'oferta' => [
                    'id' => $oferta->id,
                    'nombre' => $oferta->nombre,
                    'abierta' => $oferta->abierta
                ],
                'adjudicados' => $adjudicados->map(function ($demandante) {
                    return [
                        'id' => $demandante->id,
                        'nombre_completo' => trim($demandante->nombre . ' ' . $demandante->ape1 . ' ' . $demandante->ape2),
                        'email' => $demandante->email,
                        'tel_movil' => $demandante->tel_movil
                    ];
                }),
                'inscritos' => $inscritos->map(function ($demandante) {
                    return [
                        'id' => $demandante->id,
                        'nombre_completo' => trim($demandante->nombre . ' ' . $demandante->ape1 . ' ' . $demandante->ape2),
                        'email' => $demandante->email,
                        'tel_movil' => $demandante->tel_movil
                    ];
                }),
                'noInscritos' => $noInscritos->map(function ($demandante) {
                    return [
                        'id' => $demandante->id,
                        'nombre_completo' => trim($demandante->nombre . ' ' . $demandante->ape1 . ' ' . $demandante->ape2),
                        'email' => $demandante->email,
                        'tel_movil' => $demandante->tel_movil
                    ];
                })
            ];

            if (empty($titulosRequeridos)) {
                $response['message'] = 'Esta oferta no tiene titulaciones requeridas definidas';
            }

            return response()->json($response);
        } catch (\Exception $e) {
            // Registrar el error en los logs del sistema para depuración.
            Log::error("Error en getDemandantes: " . $e->getMessage());
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
                'id_demandante' => 'nullable|exists:demandante,id',
                'externo_nombre' => 'nullable|string|max:120'
            ]);

            // Obtener la oferta por su ID.
            $oferta = Oferta::findOrFail($idOferta);

            if (empty($validated['id_demandante']) && empty($validated['externo_nombre'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Debes indicar un demandante interno o un candidato externo'
                ], 422);
            }

            // Verificar si ya existe un demandante adjudicado a esta oferta.
            $yaAdjudicado = ApuntadosOferta::where('id_oferta', $idOferta)
                ->where('adjudicada_estado', 1) // Verificar si hay adjudicados.
                ->exists(); // Retorna true si existe, false si no.

            if ($yaAdjudicado) {
                // Si ya hay un adjudicado, devolver un error con código HTTP 400.
                return response()->json([
                    'success' => false,
                    'error' => 'Ya hay un demandante adjudicado a esta oferta'
                ], 400);
            }

            $idDemandante = $validated['id_demandante'] ?? null;
            $externoNombre = $validated['externo_nombre'] ?? null;

            if (!empty($idDemandante)) {
                // Buscar la inscripción del demandante en esta oferta.
                $inscripcion = ApuntadosOferta::where('id_oferta', $idOferta)
                    ->where('id_demandante', $idDemandante)
                    ->first();

                if ($inscripcion) {
                    // Si la inscripción existe, actualizar el estado a "Adjudicado".
                    ApuntadosOferta::where('id_oferta', $idOferta)
                        ->where('id_demandante', $idDemandante)
                    ->update([
                        'adjudicada_estado' => 1
                    ]);
            } else {
                // Si no existe inscripción, crear una nueva.
                ApuntadosOferta::create([
                    'id_oferta' => $idOferta,
                    'id_demandante' => $idDemandante,
                    'adjudicada_estado' => 1,
                    'fecha' => now()
                ]);
            }
            }

            // Actualizar el estado de la oferta a "cerrada" y asignar la fecha de cierre.
            $oferta->update([
                'abierta' => 1,
                'fecha_cierre' => now()
            ]);

            $mensaje = 'Oferta adjudicada';
            if (!empty($idDemandante)) {
                $demandante = Demandante::find($idDemandante);
                $nombre = $demandante ? trim($demandante->nombre . ' ' . $demandante->ape1 . ' ' . $demandante->ape2) : 'Demandante';
                $mensaje = "Oferta {$oferta->nombre} adjudicada a {$nombre}";
            } else {
                $mensaje = "Oferta {$oferta->nombre} adjudicada a candidato externo: {$externoNombre}";
            }

            NotificacionCentro::create([
                'tipo' => 'adjudicacion',
                'mensaje' => $mensaje,
                'id_oferta' => $oferta->id,
                'id_empresa' => $oferta->id_emp,
                'id_demandante' => $idDemandante,
                'externo_nombre' => $externoNombre,
                'fecha' => now()
            ]);

            // Devolver una respuesta de éxito en formato JSON.
            return response()->json([
                'success' => true,
                'message' => 'Adjudicación registrada y oferta cerrada'
            ]);
        } catch (\Exception $e) {
            // Registrar el error en los logs del sistema para depuración.
            Log::error("Error en getDemandantes: " . $e->getMessage());
            // Devolver una respuesta de error genérica en formato JSON con código HTTP 500.
            return response()->json([
                'success' => false,
                'error' => 'Error al cargar demandantes',
                'details' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Histórico de solicitudes por oferta (inscritos y adjudicados).
     **/
    public function getSolicitudesOferta($idOferta)
    {
        try {
            $oferta = Oferta::with(['demandantesInscritos'])->findOrFail($idOferta);

            $inscritos = $oferta->demandantesInscritos->map(function ($demandante) {
                return [
                    'id' => $demandante->id,
                    'nombre_completo' => trim($demandante->nombre . ' ' . $demandante->ape1 . ' ' . $demandante->ape2),
                    'email' => $demandante->email,
                    'tel_movil' => $demandante->tel_movil,
                    'adjudicada' => (int) ($demandante->pivot->adjudicada_estado ?? 0)
                ];
            });

            $adjudicados = $inscritos->where('adjudicada', 1)->values();

            return response()->json([
                'success' => true,
                'oferta' => [
                    'id' => $oferta->id,
                    'nombre' => $oferta->nombre,
                    'abierta' => $oferta->abierta
                ],
                'inscritos' => $inscritos,
                'adjudicados' => $adjudicados
            ]);
        } catch (\Exception $e) {
            Log::error("Error en getSolicitudesOferta: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al cargar solicitudes'
            ], 500);
        }
    }
}
