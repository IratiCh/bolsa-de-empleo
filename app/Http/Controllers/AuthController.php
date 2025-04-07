<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validación de los datos recibidos
        $request->validate([
            'email' => 'required|email',
            'contrasena_hash' => 'required',
        ]);

        // Intentar encontrar al usuario con el email
        $user = Usuarios::where('email', $request->email)->first();

        // Si el usuario no existe o la contraseña no coincide
        if (!$user || !Hash::check($request->contrasena_hash, $user->contrasena_hash)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        // Autenticación exitosa
        Auth::login($user);

        // Determinar el rol del usuario y redirigir según corresponda
        $role = $user->rol;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'rol' => $role
        ]);
    }
}
