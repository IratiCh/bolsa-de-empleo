<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use App\Models\TipoContrato;
use App\Models\Titulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OfertaController extends Controller
{
    public function getOfertasAbiertas(Request $request)
    {
        try {
            $idEmpresa = $request->input('id_emp');
        
            if (!$idEmpresa) {
                return response()->json(['error' => 'ID de empresa no proporcionado'], 400);
            }

            $ofertas = Oferta::where('id_emp', $idEmpresa)
                ->get(['id', 'nombre', 'breve_desc', 'abierta']);
            return response()->json($ofertas);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cargar ofertas', 'details' => $e->getMessage()], 500);
        }
    }

    public function cerrarOferta($id)
    {
        try {
            $oferta = Oferta::find($id);

            if (!$oferta) {
                return response()->json(['error' => 'Oferta no encontrada'], 404);
            }

            // Actualizar el estado de la oferta
            $oferta->abierta = -1; // Marcamos la oferta como cerrada con -1
            $oferta->fecha_cierre = now(); // Registrar la fecha de cierre
            $oferta->save();

            return response()->json([
                'success' => true,
                'message' => 'Oferta cerrada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al cerrar la oferta',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function getTiposContrato()
    {
        try {
            $tipos = TipoContrato::all();
            return response()->json([
                'success' => true,
                'tipos' => $tipos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar tipos de contrato'
            ], 500);
        }
    }

    public function getTitulos()
    {
        try {
            $titulos = Titulo::all();
            return response()->json([
                'success' => true,
                'titulos' => $titulos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar títulos'
            ], 500);
        }
    }

    public function crearOferta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:45',
            'breve_desc' => 'required|string|max:45',
            'desc' => 'required|string|max:500',
            'num_puesto' => 'required|integer|min:1',
            'horario' => 'required|string|max:45',
            'obs' => 'nullable|string|max:500',
            'id_tipo_cont' => 'required|exists:tipos_contrato,id',
            'titulos' => 'required|array',
            'titulos.*' => 'required|exists:titulos,id'
        ], [

            // Mensajes personalizados
            'nombre.max' => 'El nombre es demasiado largo',
            'breve_desc.max' => 'La breve descripción es demasiado larga',
            'desc.max' => 'La descripción es demasiado larga.',
            'horario.max' => 'El horario es demasiado largo.',
            'obs.max' => 'Las observaciones son demasiado largas.',
            'titulos.min' => 'Debe seleccionar al menos un título',
            'num_puesto.min' => 'El número de puestos debe ser al menos 1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {

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

            DB::table('ofertas_empresa')->insert([
                'id_empresa' => $request->id_emp,
                'id_oferta' => $oferta->id
            ]);

            foreach ($request->titulos as $idTitulo) {
                DB::table('titulos_oferta')->insert([
                    'id_oferta' => $oferta->id,
                    'id_titulo' => $idTitulo
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Oferta creada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear oferta',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
