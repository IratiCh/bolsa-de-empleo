<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getEmpresasPendientes()
    {
        try {
            // Obtener solo empresas con validado = 0
            $empresas = Empresa::where('validado', 0)
                             ->select(['id', 'nombre', 'cif', 'telefono', 'email'])
                             ->get();

            return response()->json($empresas);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al cargar empresas',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function validarEmpresa(Request $request, $id)
    {
        $request->validate([
            'accion' => 'required|in:aceptar,rechazar'
        ]);

        try {
            $empresa = Empresa::findOrFail($id);
            
            $empresa->validado = $request->accion === 'aceptar' ? 1 : -1;
            $empresa->save();

            return response()->json([
                'success' => true,
                'message' => 'Empresa actualizada correctamente',
                'empresa' => $empresa
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar empresa',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}