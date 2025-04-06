<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use App\Models\Demandante;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    // Mostrar una oferta específica
    public function show($id)
    {
        $oferta = Oferta::with(['empresa', 'tipoContrato', 'titulos'])->find($id);
        if (!$oferta) {
            return response()->json(['message' => 'Oferta no encontrada'], 404);
        }
        return response()->json($oferta);
    }

    // Actualizar una oferta
    public function update(Request $request, $id)
    {
        $oferta = Oferta::find($id);
        if (!$oferta) {
            return response()->json(['message' => 'Oferta no encontrada'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'nullable|string|max:45',
            'breve_desc' => 'nullable|string|max:45',
            'desc' => 'nullable|string|max:500',
            'fecha_pub' => 'nullable|date',
            'num_puesto' => 'nullable|integer',
            'horario' => 'nullable|string|max:45',
            'obs' => 'nullable|string|max:500',
            'abierta' => 'nullable|boolean',
            'fecha_cierre' => 'nullable|date',
            'id_tipo_cont' => 'nullable|exists:tipos_contrato,id',
        ]);

        $oferta->update($validated);

        return response()->json(['message' => 'Oferta actualizada', 'oferta' => $oferta]);
    }

    // Eliminar una oferta
    public function destroy($id)
    {
        $oferta = Oferta::find($id);
        if (!$oferta) {
            return response()->json(['message' => 'Oferta no encontrada'], 404);
        }

        $oferta->delete();

        return response()->json(['message' => 'Oferta eliminada']);
    }

    // Obtener demandantes inscritos en una oferta
    public function inscripciones($id)
    {
        $oferta = Oferta::with('candidatos')->find($id);
        if (!$oferta) {
            return response()->json(['message' => 'Oferta no encontrada'], 404);
        }

        return response()->json($oferta->candidatos);
    }

    // Inscribir un demandante a una oferta
    public function inscribirDemandante(Request $request, $id)
    {
        $oferta = Oferta::find($id);
        if (!$oferta) {
            return response()->json(['message' => 'Oferta no encontrada'], 404);
        }

        $validated = $request->validate([
            'id_demandante' => 'required|exists:demandante,id',
        ]);

        // Inscribir con fecha actual y sin adjudicar
        $oferta->candidatos()->attach($validated['id_demandante'], [
            'fecha' => now(),
            'adjudicada' => false,
        ]);

        return response()->json(['message' => 'Demandante inscrito correctamente']);
    }

    // Desinscribir un demandante de una oferta
    public function desinscribirDemandante(Request $request, $id)
    {
        $oferta = Oferta::find($id);
        if (!$oferta) {
            return response()->json(['message' => 'Oferta no encontrada'], 404);
        }

        $validated = $request->validate([
            'id_demandante' => 'required|exists:demandante,id',
        ]);

        $oferta->candidatos()->detach($validated['id_demandante']);

        return response()->json(['message' => 'Demandante desinscrito correctamente']);
    }

    // Candidatos que cumplen con al menos un título de la oferta
    public function candidatosPerfil($id)
    {
        $oferta = Oferta::with('titulos')->find($id);
        if (!$oferta) {
            return response()->json(['message' => 'Oferta no encontrada'], 404);
        }

        $tituloIds = $oferta->titulos->pluck('id');

        // Demandantes con al menos uno de esos títulos
        $candidatos = Demandante::whereHas('titulos', function ($q) use ($tituloIds) {
            $q->whereIn('titulos.id', $tituloIds);
        })->get();

        return response()->json($candidatos);
    }

    // Adjudicar un demandante a una oferta
    public function adjudicarCandidato(Request $request, $id)
    {
        $oferta = Oferta::find($id);
        if (!$oferta) {
            return response()->json(['message' => 'Oferta no encontrada'], 404);
        }

        $validated = $request->validate([
            'id_demandante' => 'required|exists:demandante,id',
        ]);

        $oferta->candidatos()->updateExistingPivot($validated['id_demandante'], [
            'adjudicada' => true,
        ]);

        return response()->json(['message' => 'Candidato adjudicado correctamente']);
    }

    // Cerrar una oferta
    public function cerrarOferta($id)
    {
        $oferta = Oferta::find($id);
        if (!$oferta) {
            return response()->json(['message' => 'Oferta no encontrada'], 404);
        }

        $oferta->abierta = false;
        $oferta->fecha_cierre = now();
        $oferta->save();

        return response()->json(['message' => 'Oferta cerrada correctamente']);
    }
}
