<?php

namespace App\Http\Controllers;

use App\Models\Demandante;
use App\Models\Oferta;
use App\Models\Titulo;
use Illuminate\Http\Request;

class DemandanteController extends Controller
{
    // Crear un nuevo demandante
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dni' => 'required|string|max:9|unique:demandante,dni',
            'nombre' => 'required|string|max:45',
            'ape1' => 'required|string|max:45',
            'ape2' => 'required|string|max:45',
            'tel_movil' => 'required|string|max:9',
            'email' => 'required|email|max:45|unique:demandante,email',
            'situacion' => 'required|boolean',
        ]);

        $demandante = Demandante::create($validated);

        return response()->json(['message' => 'Demandante creado correctamente', 'demandante' => $demandante], 201);
    }

    // Listar todos los demandantes
    public function index()
    {
        return response()->json(Demandante::all());
    }

    // Mostrar un demandante por ID
    public function show($id)
    {
        $demandante = Demandante::with('titulos')->find($id);
        if (!$demandante) {
            return response()->json(['message' => 'Demandante no encontrado'], 404);
        }

        return response()->json($demandante);
    }

    // Actualizar un demandante
    public function update(Request $request, $id)
    {
        $demandante = Demandante::find($id);
        if (!$demandante) {
            return response()->json(['message' => 'Demandante no encontrado'], 404);
        }

        $validated = $request->validate([
            'dni' => 'sometimes|string|max:9|unique:demandante,dni,' . $demandante->id,
            'nombre' => 'sometimes|string|max:45',
            'ape1' => 'sometimes|string|max:45',
            'ape2' => 'sometimes|string|max:45',
            'tel_movil' => 'sometimes|string|max:9',
            'email' => 'sometimes|email|max:45|unique:demandante,email,' . $demandante->id,
            'situacion' => 'sometimes|boolean',
        ]);

        $demandante->update($validated);

        return response()->json(['message' => 'Demandante actualizado', 'demandante' => $demandante]);
    }

    // Eliminar un demandante
    public function destroy($id)
    {
        $demandante = Demandante::find($id);
        if (!$demandante) {
            return response()->json(['message' => 'Demandante no encontrado'], 404);
        }

        $demandante->delete();

        return response()->json(['message' => 'Demandante eliminado']);
    }

    // Actualizar los títulos de un demandante
    public function updateTitulos(Request $request, $id)
    {
        $demandante = Demandante::find($id);
        if (!$demandante) {
            return response()->json(['message' => 'Demandante no encontrado'], 404);
        }

        $validated = $request->validate([
            'titulos' => 'required|array',
            'titulos.*.id' => 'required|exists:titulos,id',
            'titulos.*.centro' => 'nullable|string|max:45',
            'titulos.*.año' => 'nullable|string|max:45',
            'titulos.*.cursando' => 'nullable|string|max:45',
        ]);

        $syncData = [];
        foreach ($validated['titulos'] as $titulo) {
            $syncData[$titulo['id']] = [
                'centro' => $titulo['centro'] ?? null,
                'año' => $titulo['año'] ?? null,
                'cursando' => $titulo['cursando'] ?? null,
            ];
        }

        $demandante->titulos()->sync($syncData);

        return response()->json(['message' => 'Títulos actualizados correctamente']);
    }

    // Para futuras mejoras en la experiencia del usuario
    // Inscripciones del demandante
    public function inscripciones($id)
    {
        $demandante = Demandante::with('inscripciones')->find($id);
        if (!$demandante) {
            return response()->json(['message' => 'Demandante no encontrado'], 404);
        }

        return response()->json($demandante->inscripciones);
    }

    // Ofertas abiertas
    public function ofertas()
    {
        $ofertas = Oferta::where('abierta', true)->get();
        return response()->json($ofertas);
    }

    // Ofertas filtradas por título
    public function ofertasPorTitulacion($tituloId)
    {
        $titulo = Titulo::find($tituloId);
        if (!$titulo) {
            return response()->json(['message' => 'Título no encontrado'], 404);
        }

        $ofertas = $titulo->ofertas()->where('abierta', true)->get();

        return response()->json($ofertas);
    }
}
