<?php

namespace App\Http\Controllers;

use APP\Models\Usuario;
use Illuminate\Http\Request;
use illuminate\support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:usuarios,email',
            'contrasena_hash' => 'required|string|min:6',
            'rol' => 'required|in:centro,empresa,demandante',
            'id_rol' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $usuario = Usuario::create([
            'email' => $request->email,
            'contrasena_hash' => Hash::make($request->contrasena_hash),
            'rol' => $request->rol,
            'id_rol' => $request->id_rol
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Usuario registrado correctamente',
            'data' => $usuario
        ], 201);
    }

    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->contrasena_hash;

        if ($email === 'centro@centro.com') { // email fijo para centro
            $usuario = Usuario::where('rol', 'centro')->first();

            if ($usuario && Hash::check($password, $usuario->contrasena_hash)) {
                // Autenticación exitosa del centro
                return response()->json([
                    'status' => true,
                    'message' => 'Login correcto',
                    'data' => $usuario,
                ]);
            }
        } else {
            // Lógica para otros usuarios (empresa, demandante)
            $credentials = $request->only('email', 'contrasena_hash');

            if (Auth::attempt($credentials)) {
                // Autenticación exitosa
                $usuario = Auth::user();
                return response()->json([
                    'status' => true,
                    'message' => 'Login correcto',
                    'data' => $usuario,
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Credenciales inválidas',
        ], 401);
    }

    public function me(Request $request)
    {
        if (Auth::check()) {
            // Usuario autenticado
            $usuario = Auth::user();
            return response()->json([
                'status' => true,
                'message' => 'Perfil del usuario',
                'data' => $usuario,
            ]);
        }

        // Usuario no autenticado
        return response()->json([
            'status' => false,
            'message' => 'Usuario no autenticado',
        ], 401);
    }

    public function updateMe(Request $request)
    {
        if (Auth::check()) {
            // Usuario autenticado
            $usuario = Auth::user();
    
            if ($usuario instanceof \App\Models\Usuario) {
                $usuario->update($request->all());
    
                return response()->json([
                    'status' => true,
                    'message' => 'Perfil actualizado',
                    'data' => $usuario,
                ]);
            } 
        }
    
        // Usuario no autenticado
        return response()->json([
            'status' => false,
            'message' => 'Usuario no autenticado',
        ], 401);
    }
}
