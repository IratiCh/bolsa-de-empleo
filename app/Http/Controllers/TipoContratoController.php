<?php

namespace App\Http\Controllers;

use App\Models\TipoContrato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TipoContratoController extends Controller
{
    // verificar el rol de empresa
    private function verificarRolEmpresa()
    {
        $usuario = Auth::user();
        if ($usuario->rol !== 'empresa') {
            return response()->json(['message' => 'Accesible para empresas.'], 403);
        }
    }

    public function index()
    {
        $verificacion = $this->verificarRolEmpresa();
        if ($verificacion) {
            return $verificacion;
        }

        // Obtener todos los tipos de contrato
        $tiposContratos = TipoContrato::all();
        
        return response()->json($tiposContratos);
    }

    public function show($id)
    {
        $verificacion = $this->verificarRolEmpresa();
        if ($verificacion) {
            return $verificacion;
        }

        // Buscar el tipo de contrato por su ID
        $tipoContrato = TipoContrato::find($id);

        if (!$tipoContrato) {
            return response()->json(['message' => 'Tipo de contrato no encontrado'], 404);
        }

        return response()->json($tipoContrato);
    }

    public function store(Request $request)
    {
        $verificacion = $this->verificarRolEmpresa();
        if ($verificacion) {
            return $verificacion;
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:45|unique:tipos_contrato,nombre',
        ]);

        $tipoContrato = TipoContrato::create($validated);

        return response()->json(['message' => 'Tipo de contrato creado con éxito', 'tipoContrato' => $tipoContrato], 201);
    }

    public function update(Request $request, $id)
    {
        $verificacion = $this->verificarRolEmpresa();
        if ($verificacion) {
            return $verificacion;
        }

        $tipoContrato = TipoContrato::find($id);

        if (!$tipoContrato) {
            return response()->json(['message' => 'Tipo de contrato no encontrado'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:45|unique:tipos_contrato,nombre,' . $id,
        ]);

        $tipoContrato->update($validated);

        return response()->json(['message' => 'Tipo de contrato actualizado con éxito', 'tipoContrato' => $tipoContrato]);
    }

    public function destroy($id)
    {
        $verificacion = $this->verificarRolEmpresa();
        if ($verificacion) {
            return $verificacion;
        }

        $tipoContrato = TipoContrato::find($id);

        if (!$tipoContrato) {
            return response()->json(['message' => 'Tipo de contrato no encontrado'], 404);
        }

        $tipoContrato->delete();

        return response()->json(['message' => 'Tipo de contrato eliminado con éxito']);
    }
}