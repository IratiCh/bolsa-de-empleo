<?php

namespace App\Http\Controllers;

use App\Models\Oferta; // Modelo para la tabla "oferta".
use App\Models\ApuntadosOferta; // Modelo para la tabla "apuntados_oferta".
use Illuminate\Http\Request; // Clase para manejar solicitudes HTTP.
use Illuminate\Support\Facades\DB; // Facade para consultas directas a la base de datos.
use Illuminate\Support\Facades\Log; // Facade para registrar eventos y errores en los logs.

class OfertaDemandanteController extends Controller
{
    /**
     * Obtener la información completa de una oferta.
     **/
    public function show($id)
    {
        try {
            // Buscar la oferta por su ID y cargar sus relaciones (empresa, tipo de contrato, títulos requeridos)
            $oferta = Oferta::with(['empresa', 'tipoContrato', 'titulos'])->findOrFail($id);

            // Formatear la respuesta con la información completa de la oferta.
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
                'abierta' => $oferta->abierta, // Estado de la oferta (abierta o cerrada).
                'tipo_contrato' => $oferta->tipoContrato->nombre ?? null, // Tipo de contrato asociado.
                'empresa_nombre' => $oferta->empresa->nombre ?? null, 
                'empresa_localidad' => $oferta->empresa->localidad ?? null,
                'titulos' => $oferta->titulos->pluck('nombre')->toArray() // Lista de títulos requeridos.
            ]);
            
        } catch (\Exception $e) {
            // Registrar el error en los logs con un mensaje personalizado.
            \Log::error("Error en OfertaDemandanteController: " . $e->getMessage());
            // Devolver un mensaje de error genérico.
            return response()->json([
                'success' => false,
                'error' => 'Error al cargar la oferta',
                'details' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Verificar si un demandante está inscrito en una oferta específica.
     **/
    public function verificarInscripcion($idOferta, Request $request)
    {
        try {
            // Obtener el ID del demandante desde los parámetros de la consulta.
            $idDemandante = $request->query('id_dem');

            // Validar que el ID del demandante haya sido proporcionado.
            if (!$idDemandante) {
                return response()->json([
                    'success' => false,
                    'error' => 'ID de demandante no proporcionado'
                ], 400);
            }

            // Verificar si el demandante está inscrito en la oferta.
            $inscrito = ApuntadosOferta::where('id_oferta', $idOferta)
                ->where('id_demandante', $idDemandante)
                ->exists();

            // Devolver el estado de inscripción.
            return response()->json([
                'success' => true,
                'inscrito' => $inscrito
            ]);
            
        } catch (\Exception $e) {
            // Registrar cualquier error en los logs.
            Log::error("Error verificando inscripción: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al verificar inscripción'
            ], 500);
        }
    }

    /**
     * Inscribir a un demandante en una oferta.
     **/
    public function inscribir(Request $request, $idOferta)
    {
        try {
            // Validar los datos enviados (ID del demandante debe ser válido y existir).
            $validated = $request->validate([
                'id_demandante' => 'required|exists:demandante,id'
            ]);

            // Verificar si la oferta existe.
            $oferta = Oferta::find($idOferta);
            if (!$oferta) {
                return response()->json([
                    'success' => false,
                    'error' => 'Oferta no encontrada'
                ], 404);
            }

            // Verificar si la oferta está abierta.
            if ($oferta->abierta !== 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'La oferta no está abierta'
                ], 400);
            }

            // Verificar si el demandante ya está inscrito en la oferta.
            $yaInscrito = ApuntadosOferta::where('id_oferta', $idOferta)
                ->where('id_demandante', $validated['id_demandante'])
                ->exists();

            if ($yaInscrito) {
                return response()->json([
                    'success' => false,
                    'error' => 'Ya estás inscrito en esta oferta'
                ], 400);
            }

            // Crear una nueva inscripción en la oferta.
            ApuntadosOferta::create([
                'id_oferta' => $idOferta,
                'id_demandante' => $validated['id_demandante'],
                'fecha' => now()
            ]);

            // Devolver un mensaje de éxito.
            return response()->json([
                'success' => true,
                'message' => 'Inscripción realizada con éxito'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Manejar errores de validación.
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Registrar cualquier otro error en los logs.
            Log::error('Error al inscribirse: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al realizar la inscripción',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todas las ofertas a las que un demandante está inscrito.
     **/
    public function ofertasInscritas(Request $request)
    {
        try {
            // Obtener el ID del demandante desde los parámetros de la consulta (?id_dem=).
            $idDemandante = $request->query('id_dem');

            // Validar si el ID del demandante fue proporcionado.
            if (!$idDemandante) {
                // Devolver un error con código HTTP 400 si el ID no está presente.
                return response()->json([
                    'success' => false,
                    'error' => 'ID de demandante no proporcionado'
                ], 400);
            }

            // Realizar la consulta para obtener las ofertas en las que el demandante está inscrito.
            $ofertas = Oferta::select('oferta.*') // Seleccionar todas las columnas de la tabla "oferta".
                ->join('apuntados_oferta', 'oferta.id', '=', 'apuntados_oferta.id_oferta') // Unir la tabla "apuntados_oferta".
                ->where('apuntados_oferta.id_demandante', $idDemandante) // Filtrar por el ID del demandante.
                ->with(['empresa', 'tipoContrato']) // Cargar relaciones con "empresa" y "tipoContrato".
                ->get() // Ejecutar la consulta y obtener los resultados.
                ->map(function($oferta) {
                    // Formatear cada oferta para devolver solo los datos necesarios.
                    return [
                        'id' => $oferta->id,
                        'nombre' => $oferta->nombre,
                        'breve_desc' => $oferta->breve_desc,
                        'abierta' => $oferta->abierta
                    ];
                });

            // Devolver las ofertas en formato JSON con el estado de éxito.
            return response()->json([
                'success' => true,
                'ofertas' => $ofertas
            ]);

        } catch (\Exception $e) {
            // Registrar el error en los logs para depuración.
            Log::error("Error en ofertasInscritas: " . $e->getMessage());
            // Devolver un mensaje de error genérico con código HTTP 500.
            return response()->json([
                'success' => false,
                'error' => 'Error al cargar ofertas inscritas'
            ], 500);
        }
    }

    /**
     * Cancelar la inscripción de un demandante en una oferta.
     **/
    public function cancelarInscripcion(Request $request, $idOferta)
    {
        try {
            // Validar los datos enviados (ID del demandante debe ser válido y existir).
            $validated = $request->validate([
                'id_demandante' => 'required|exists:demandante,id'
            ]);

            // Eliminar la inscripción de la tabla "apuntados_oferta".
            $deleted = ApuntadosOferta::where('id_oferta', $idOferta)
                ->where('id_demandante', $validated['id_demandante'])
                ->delete();

            if ($deleted) {
                // Si la inscripción fue eliminada correctamente, devolver éxito.
                return response()->json([
                    'success' => true,
                    'message' => 'Inscripción cancelada con éxito'
                ]);
            }

            // Si no se encontró la inscripción, devolver un error.
            return response()->json([
                'success' => false,
                'error' => 'No se encontró la inscripción'
            ], 404);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Manejar errores de validación.
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Registrar cualquier otro error en los logs.
            Log::error('Error al cancelar inscripción: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al cancelar la inscripción'
            ], 500);
        }
    }

}