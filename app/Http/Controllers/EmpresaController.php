<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Oferta;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    // Listar todas las empresas
    public function index()
    {
        return response()->json(Empresa::all());
    }

    // Obtener una empresa específica por ID
    public function show($id)
    {
        $empresa = Empresa::find($id);
        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }
        return response()->json($empresa);
    }

    // Crear una nueva empresa
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cif' => 'required|string|max:11|unique:empresa,cif',
            'nombre' => 'required|string|max:45',
            'localidad' => 'required|string|max:45',
            'telefono' => 'required|string|max:9',
            'email' => 'required|email|max:50|unique:empresa,email',
        ]);

        $validated['validado'] = 0;

        $empresa = Empresa::create($validated);

        return response()->json(['message' => 'Empresa creada con éxito, a la espera de validación', 'empresa' => $empresa], 201);
    }

    // Actualizar una empresa existente
    public function update(Request $request, $id)
    {
        $empresa = Empresa::find($id);
        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }

        $validated = $request->validate([
            'cif' => 'sometimes|string|max:11|unique:empresa,cif,' . $empresa->id,
            'nombre' => 'sometimes|string|max:45',
            'localidad' => 'sometimes|string|max:45',
            'telefono' => 'sometimes|string|max:9',
            'email' => 'sometimes|email|max:50|unique:empresa,email,' . $empresa->id,
        ]);

        $validated['validado'] = 0;
        
        $empresa->update($validated);

        return response()->json(['message' => 'Empresa actualizada, a la espera de validación', 'empresa' => $empresa]);
    }

    // Eliminar una empresa
    public function destroy($id)
    {
        $empresa = Empresa::find($id);
        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }

        $empresa->delete();

        return response()->json(['message' => 'Empresa eliminada']);
    }

    // Obtener todas las ofertas de una empresa
    public function ofertas($id)
    {
        $empresa = Empresa::find($id);
        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }

        return response()->json($empresa->ofertas);
    }

    // Crear una nueva oferta para una empresa
    public function createOferta(Request $request, $id)
    {
        $empresa = Empresa::find($id);
        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
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
            'id_tipo_cont' => 'required|exists:tipos_contrato,id',
        ]);

        $validated['id_emp'] = $empresa->id;

        $oferta = Oferta::create($validated);

        return response()->json(['message' => 'Oferta creada para empresa', 'oferta' => $oferta], 201);
    }
}
