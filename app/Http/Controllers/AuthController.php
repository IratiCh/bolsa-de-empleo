<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Demandante;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
        $user = Usuario::where('email', $request->email)->first();

        // Si el usuario no existe o la contraseña no coincide
        if (!$user || !Hash::check($request->contrasena_hash, $user->contrasena_hash)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $responseData = [
            'message' => 'Inicio de sesión exitoso',
            'id' => $user->id,
            'nombre' => $user->nombre,
            'email' => $user->email,
            'rol' => $user->rol
        ];

        if ($user->rol === 'empresa') {
            $empresa = Empresa::where('email', $request->email)->first();
            if (!$empresa) {
                return response()->json(['message' => 'Perfil de empresa no encontrado'], 404);
            }
            $responseData['validado'] = $empresa->validado;
            $responseData['id_emp'] = $empresa->id;
        }

        if ($user->rol === 'demandante') {
            $demandante = Demandante::where('email', $request->email)->first();
            if (!$demandante) {
                return response()->json(['message' => 'Perfil de demandante no encontrado'], 404);
            }
            $responseData['id_dem'] = $demandante->id;
        }
    
    
        Auth::login($user);


        return response()->json($responseData);
    }
    public function registerDemandante(Request $request)
    {

        // Validación de campos
        $validator = Validator::make($request->all(), [
            'dni' => [
                'required',
                'regex:/^\d{8}[A-Za-z]$/',
                'unique:demandante,dni'
            ],
            'nombre' => 'required|string|max:45',
            'ape1' => 'required|string|max:45',
            'ape2' => 'required|string|max:45',
            'tel_movil' => 'required|digits:9',
            'email' => [
                'required',
                'regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/',
                'max:45',
                'unique:demandante,email',
                'unique:usuarios,email'
            ],
            'contrasena_hash' => 'required|string|min:6'
        ], [
            // Mensajes personalizados
            'nombre.max' => 'El nombre es demasiado largo',
            'ape1.max' => 'El primer apellido es demasiado largo',
            'ape2.max' => 'El segundo apellido es demasiado largo',
            'dni.regex' => 'El DNI debe tener 8 números seguidos de una letra.',
            'dni.unique' => 'El DNI ya ha sido usado.',
            'email.regex' => 'El email debe tener un formato válido.',
            'email.unique' => 'Este email ya está registrado.',
            'tel_movil.digits' => 'El número de teléfono debe tener exactamente 9 dígitos.',
            'contrasena_hash.min' => 'La contraseña debe tener una longitud mínima de 6.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Crear el demandante
        $demandante = Demandante::create([
            'nombre' => $request->nombre,
            'ape1' => $request->ape1,
            'ape2' => $request->ape2,
            'dni' => $request->dni,
            'tel_movil' => $request->tel_movil,
            'email' => $request->email,
            'situacion' => 0
        ]);

        // Crear el usuario vinculado al demandante
        $usuario = Usuario::create([
            'email' => $request->email,
            'contrasena_hash' => Hash::make($request->contrasena_hash),
            'rol' => 'demandante',
            'id_rol' => 2
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Usuario registrado correctamente',
            'usuario' => $usuario,
            'demandante' => $demandante
        ], 201);
    }

    public function registerEmpresa(Request $request)
    {

        // Validación de campos
        $validator = Validator::make($request->all(), [
            'cif' => 'required|string|size:11|unique:empresa,cif',
            'nombre' => 'required|string|max:45',
            'localidad' => 'required|string|max:45',
            'telefono' => 'required|digits:9',
            'email' => [
                'required',
                'regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/',
                'max:45',
                'unique:empresa,email',
                'unique:usuarios,email'
            ],
            'contrasena_hash' => 'required|string|min:6'
        ], [

            // Mensajes personalizados
            'nombre.max' => 'El nombre es demasiado largo',
            'localidad.max' => 'El nombre de la localidad es demasiado largo',
            'cif.size' => 'El CIF debe tener 11 dígitos.',
            'cif.unique' => 'El CIF ya ha sido usado.',
            'email.regex' => 'El email debe tener un formato válido.',
            'email.unique' => 'Este email ya está registrado.',
            'telefono.digits' => 'El número de teléfono debe tener exactamente 9 dígitos.',
            'contrasena_hash.min' => 'La contraseña debe tener una longitud mínima de 6.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Crear el demandante
        $empresa = Empresa::create([
            'nombre' => $request->nombre,
            'localidad' => $request->localidad,
            'cif' => $request->cif,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'validado' => 0
        ]);

        // Crear el usuario vinculado al demandante
        $usuario = Usuario::create([
            'email' => $request->email,
            'contrasena_hash' => Hash::make($request->contrasena_hash),
            'rol' => 'empresa',
            'id_rol' => 3
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Usuario registrado correctamente',
            'usuario' => $usuario,
            'empresa' => $empresa
        ], 201);
    }
}