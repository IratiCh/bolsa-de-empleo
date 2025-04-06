<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // verificar rol centro
    private function verificarRolCentro()
    {
        $usuario = Auth::user();
        if ($usuario->rol !== 'centro') {
            return response()->json(['message' => 'Acceso permitido solo para el administrador.'], 403);
        }
    }

    // Consultar las empresas que están pendientes de validación
    public function empresasPendientes()
    {
        $verificacion = $this->verificarRolCentro();
        if ($verificacion) {
            return $verificacion;
        }

        // Obtener las empresas que están pendientes de validación (validado = 0)
        $empresasPendientes = Empresa::where('validado', 0)->get();

        return response()->json($empresasPendientes);
    }

    // Validar una empresa
    public function validarEmpresa($id)
    {
        $verificacion = $this->verificarRolCentro();
        if ($verificacion) {
            return $verificacion;
        }

        // Buscar la empresa
        $empresa = Empresa::find($id);
        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }

        // Validar la empresa
        $empresa->validado = 1;
        $empresa->save();

        return response()->json(['message' => 'Empresa validada correctamente', 'empresa' => $empresa]);
    }

    // Rechazar una empresa
    public function rechazarEmpresa($id)
    {
        // Verificar que el usuario tenga el rol 'centro'
        $verificacion = $this->verificarRolCentro();
        if ($verificacion) {
            return $verificacion;
        }

        // Buscar la empresa
        $empresa = Empresa::find($id);
        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }

        // Rechazar la empresa (cambiar estado validado a 2)
        $empresa->validado = 2;
        $empresa->save();

        return response()->json(['message' => 'Empresa rechazada correctamente', 'empresa' => $empresa]);
    }
}

