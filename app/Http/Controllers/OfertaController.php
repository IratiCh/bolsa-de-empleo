<?php

namespace App\Http\Controllers;

use App\Models\Oferta; // Modelo para interactuar con la tabla "oferta".
use App\Models\TipoContrato; // Modelo para interactuar con la tabla "tipos_contrato".
use App\Models\Titulo; // Modelo para interactuar con la tabla "titulos".
use App\Models\Demandante; // Modelo para interactuar con la tabla "demandante".
use Illuminate\Http\Request; // Clase para manejar solicitudes HTTP.
use Illuminate\Support\Facades\Validator; // Facade para validar datos de entrada.
use Illuminate\Support\Facades\DB; // Facade para realizar consultas directas en la base de datos.
use Illuminate\Support\Facades\Log;

class OfertaController extends Controller
{
    /**
     * Obtener las ofertas abiertas de una empresa específica.
     **/
    public function getOfertasAbiertas(Request $request)
    {
        try {
            // Obtener el ID de la empresa desde los parámetros de la solicitud.
            $idEmpresa = $request->input('id_emp');

            // Validar que el ID de la empresa fue proporcionado.
            if (!$idEmpresa) {
                return response()->json(['error' => 'ID de empresa no proporcionado'], 400);
            }

            // Consultar las ofertas relacionadas con la empresa que estén abiertas.
            $ofertas = Oferta::where('id_emp', $idEmpresa)
                ->where('abierta', 0) // Solo ofertas abiertas (abierta = 0).
                ->get(['id', 'nombre', 'breve_desc', 'abierta']); // Selección específica de columnas.
            return response()->json($ofertas); // Devolver las ofertas encontradas en formato JSON.
        } catch (\Exception $e) {
            // Manejo de errores y devolución de un mensaje genérico.
            return response()->json(['error' => 'Error al cargar ofertas', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Cerrar una oferta específica.
     **/
    public function cerrarOferta($id)
    {
        try {
            // Buscar la oferta por su ID.
            $oferta = Oferta::find($id);

            // Si la oferta no existe, devolver un error.
            if (!$oferta) {
                return response()->json(['error' => 'Oferta no encontrada'], 404);
            }

            // Marcar la oferta como cerrada y registrar la fecha de cierre.
            $oferta->abierta = 1; // Marcamos la oferta como cerrada con -1
            $oferta->fecha_cierre = now(); // Registrar la fecha de cierre
            $oferta->save();

            // Devolver un mensaje de éxito.
            return response()->json([
                'success' => true,
                'message' => 'Oferta cerrada correctamente'
            ]);
        } catch (\Exception $e) {
            // Manejo de errores.
            return response()->json([
                'error' => 'Error al cerrar la oferta',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todos los tipos de contrato disponibles.
     **/
    public function getTiposContrato()
    {
        try {
            // Obtener todos los registros de la tabla "tipos_contrato".
            $tipos = TipoContrato::all();
            // Devolver los tipos en formato JSON.
            return response()->json([
                'success' => true,
                'tipos' => $tipos
            ]);
        } catch (\Exception $e) {
            // Manejo de errores.
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar tipos de contrato'
            ], 500);
        }
    }

    /**
     * Obtener todos los títulos disponibles.
     **/
    public function getTitulos()
    {
        try {
            // Obtener todos los registros de la tabla "titulos".
            $titulos = Titulo::all();
            // Devolver los títulos en formato JSON.
            return response()->json([
                'success' => true,
                'titulos' => $titulos
            ]);
        } catch (\Exception $e) {
            // Manejo de errores.
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar títulos'
            ], 500);
        }
    }

    /**
     * Crear una nueva oferta.
     **/
    public function crearOferta(Request $request)
    {
        // Validar los datos proporcionados.
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:45', // Nombre obligatorio, máximo 45 caracteres.
            'breve_desc' => 'required|string|max:45', // Breve descripción obligatoria, máximo 45 caracteres.
            'desc' => 'required|string|max:500', // Descripción detallada obligatoria, máximo 500 caracteres.
            'num_puesto' => 'required|integer|min:1', // Número de puestos, debe ser al menos 1.
            'horario' => 'required|string|max:45', // Horario laboral, máximo 45 caracteres.
            'obs' => 'nullable|string|max:500', // Observaciones opcionales, máximo 500 caracteres.
            'id_emp' => 'required|exists:empresa,id', // Empresa válida debe existir en la base de datos.
            'id_tipo_cont' => 'required|exists:tipos_contrato,id', // ID de tipo de contrato debe existir en la base de datos.
            'titulos' => 'required|array', // Títulos requeridos para la oferta.
            'titulos.*' => 'required|exists:titulos,id' // Cada título debe existir en la base de datos.
        ], [

            // Mensajes personalizados para los errores de validación.
            'nombre.max' => 'El nombre es demasiado largo',
            'breve_desc.max' => 'La breve descripción es demasiado larga',
            'desc.max' => 'La descripción es demasiado larga.',
            'horario.max' => 'El horario es demasiado largo.',
            'obs.max' => 'Las observaciones son demasiado largas.',
            'titulos.min' => 'Debe seleccionar al menos un título',
            'num_puesto.min' => 'El número de puestos debe ser al menos 1'
        ]);

        // Si la validación falla, devolver errores.
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::transaction(function () use ($request, &$ofertaId) {
                // Crear la oferta en la base de datos.
                $oferta = Oferta::create([
                    'nombre' => $request->nombre,
                    'breve_desc' => $request->breve_desc,
                    'desc' => $request->desc,
                    'fecha_pub' => now(),
                    'num_puesto' => $request->num_puesto,
                    'horario' => $request->horario,
                    'obs' => $request->obs,
                    'abierta' => 0, // Oferta abierta por defecto
                    'id_emp' => $request->id_emp,
                    'id_tipo_cont' => $request->id_tipo_cont
                ]);

                // Relacionar la oferta con los títulos requeridos en la tabla pivot.
                foreach ($request->titulos as $idTitulo) {
                    DB::table('titulos_oferta')->insert([
                        'id_oferta' => $oferta->id,
                        'id_titulo' => $idTitulo
                    ]);
                }

                // Relacionar la oferta con la empresa en la tabla pivot.
                DB::table('ofertas_empresa')->insert([
                    'id_empresa' => $request->id_emp,
                    'id_oferta' => $oferta->id
                ]);

            });

            // Devolver un mensaje de éxito.
            return response()->json([
                'success' => true,
                'message' => 'Oferta creada correctamente'
            ]);
        } catch (\Exception $e) {
            // Manejo de errores.
            return response()->json([
                'success' => false,
                'message' => 'Error al crear oferta',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener las ofertas disponibles para un demandante según sus títulos.
     **/
    public function getOfertasDemandante(Request $request)
    {
        try {
            // ID del demandante desde la solicitud.
            $idDemandante = $request->input('id_dem');

            // Obtener los títulos del demandante desde la tabla pivot.
            $titulosDemandante = DB::table('titulos_demandante')
                ->where('id_dem', $idDemandante)
                ->pluck('id_titulo')
                ->toArray();

            // Consulta consulta base para ofertas abiertas
            $query = Oferta::where('abierta', 0)
                ->with(['empresa', 'tipoContrato'])
                ->select('oferta.*')
                ->join('empresa', 'oferta.id_emp', '=', 'empresa.id')
                ->addSelect('empresa.nombre as empresa_nombre');

            // Si el demandante tiene títulos, filtrar ofertas que coincidan con ellos.
            if (!empty($titulosDemandante)) {
                $query->whereHas('titulos', function ($q) use ($titulosDemandante) {
                    $q->whereIn('titulos.id', $titulosDemandante);
                });
            }

            // Obtener y mapear los resultados
            $ofertas = $query->get()
                ->map(function ($oferta) {
                    // Transformar cada oferta en un formato personalizado para la respuesta JSON.
                    return [
                        'id' => $oferta->id, // ID único de la oferta.
                        'nombre' => $oferta->nombre, // Nombre de la oferta.
                        'breve_desc' => $oferta->breve_desc, // Breve descripción de la oferta.
                        'fecha_pub' => $oferta->fecha_pub, // Fecha de publicación de la oferta.
                        'tipo_contrato' => $oferta->tipoContrato->nombre, // Nombre del tipo de contrato asociado a la oferta.
                        'empresa' => $oferta->empresa_nombre // Nombre de la empresa que publicó la oferta.
                    ];
                });

            // Registrar información en los logs sobre las ofertas encontradas.
            // Esto es útil para depuración y para saber cuántas ofertas se cargaron correctamente.
            Log::info("Ofertas encontradas:", [
                'count' => count($ofertas),
                'con_filtro_titulos' => !empty($titulosDemandante),
                'titulos_demandante' => $titulosDemandante
            ]);

            // Devolver las ofertas en formato JSON como respuesta al cliente.
            return response()->json($ofertas);
        } catch (\Exception $e) {
            // Registrar el error en los logs para depuración, incluyendo el mensaje de error y el stack trace.
            Log::error("Error en getOfertasDemandante", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Devolver una respuesta de error en formato JSON con código HTTP 500.
            return response()->json([
                'error' => 'Error al cargar ofertas',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
