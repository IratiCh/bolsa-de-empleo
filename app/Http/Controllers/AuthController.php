<?php

namespace App\Http\Controllers;

use App\Models\Usuario; // Modelo que maneja la tabla "usuarios".
use App\Models\Demandante; // Modelo que maneja la tabla "demandantes".
use App\Models\Empresa; // Modelo que maneja la tabla "empresa".
use Illuminate\Http\Request; // Clase para manejar las solicitudes HTTP.
use Illuminate\support\Facades\Auth; // Facade para manejar la autenticación de usuarios.
use Illuminate\Support\Facades\Hash; // Facade para trabajar con contraseñas hasheadas.
use Illuminate\Support\Facades\Validator; // Facade para validar los datos de entrada.
use Illuminate\Support\Facades\DB; // Facade para interactuar con la base de datos directamente.

class AuthController extends Controller
{
    /**
     * Método para manejar el inicio de sesión.
     * Verifica las credenciales proporcionadas y retorna información sobre el usuario autenticado.
     **/
    public function login(Request $request)
    {
        // Validación de los datos enviados en la solicitud.
        // Se requiere un email válido y una contraseña proporcionada.
        $request->validate([
            'email' => 'required|email', // Verificar que el email sea válido.
            'contrasena_hash' => 'required', // Verificar que la contraseña no esté vacía.
        ]);

        // Buscar al usuario en la tabla "usuarios" por su email.
        $user = Usuario::where('email', $request->email)->first();

        // Verificar si el usuario existe y si la contraseña es válida.
        if (!$user || !Hash::check($request->contrasena_hash, $user->contrasena_hash)) {
            // Si las credenciales son incorrectas, devolver un error con código 401.
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }
        
        // Construir la respuesta con los datos del usuario autenticado.
        $responseData = [
            'message' => 'Inicio de sesión exitoso',
            'id' => $user->id, // ID único del usuario.
            'nombre' => $user->nombre, // Nombre del usuario (si está disponible).
            'email' => $user->email, // Email del usuario.
            'rol' => $user->rol // Rol del usuario (empresa, demandante, etc.).
        ];

        // Si el rol del usuario es "empresa", agregar datos específicos de la empresa.
        if ($user->rol === 'empresa') {
            $empresa = Empresa::where('email', $request->email)->first();
            if (!$empresa) {
                // Si no se encuentra un perfil de empresa asociado, devolver un error.
                return response()->json(['message' => 'Perfil de empresa no encontrado'], 404);
            }
            $responseData['validado'] = $empresa->validado; // Estado de validación de la empresa.
            $responseData['id_emp'] = $empresa->id; // ID único de la empresa.
        }

        // Si el rol del usuario es "demandante", agregar datos específicos del demandante.
        if ($user->rol === 'demandante') {
            $demandante = Demandante::where('email', $request->email)->first();
            if (!$demandante) {
                // Si no se encuentra un perfil de demandante asociado, devolver un error.
                return response()->json(['message' => 'Perfil de demandante no encontrado'], 404);
            }
            $responseData['id_dem'] = $demandante->id; // ID único del demandante.
        }
    
        // Autenticar al usuario con Laravel Auth.
        Auth::login($user);

        // Devolver los datos del usuario autenticado en formato JSON.
        return response()->json($responseData);
    }

    /**
     * Método para registrar un nuevo usuario con rol "demandante".
     * Realiza validaciones sobre los datos proporcionados y guarda la información en las tablas correspondientes.
     **/
    public function registerDemandante(Request $request)
    {

        // Validación de datos de entrada.
        // Se verifican los campos obligatorios, formatos y unicidad en la base de datos.
        $validator = Validator::make($request->all(), [
            'dni' => [
                'required',
                'regex:/^\d{8}[A-Za-z]$/', // Validar el formato del DNI (8 números y 1 letra).
                'unique:demandante,dni' // Asegurar que el DNI no esté registrado previamente.
            ],
            'nombre' => 'required|string|max:45', // Nombre obligatorio, máximo de 45 caracteres.
            'ape1' => 'required|string|max:45', // Primer apellido obligatorio.
            'ape2' => 'required|string|max:45', // Segundo apellido obligatorio.
            'tel_movil' => [
                'required',
                'regex:/^[67]\d{8}$/' // Número de teléfono obligatorio, debe empezar por 6 o 7 seguido de 8 dígitos.
            ],
            'email' => [
                'required',
                'regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/', // Validar el formato del email.
                'max:45', // Máximo de 45 caracteres.
                'unique:demandante,email', // Email único en la tabla "demandante".
                'unique:usuarios,email' // Email único en la tabla "usuarios".
            ],
            'contrasena_hash' => 'required|string|min:6' // Contraseña obligatoria, mínimo de 6 caracteres.
        ], [
            // Mensajes personalizados para cada validación.
            'nombre.max' => 'El nombre es demasiado largo',
            'ape1.max' => 'El primer apellido es demasiado largo',
            'ape2.max' => 'El segundo apellido es demasiado largo',
            'dni.regex' => 'El DNI debe tener 8 números seguidos de una letra.',
            'dni.unique' => 'El DNI ya ha sido usado.',
            'email.regex' => 'El email debe tener un formato válido.',
            'email.unique' => 'Este email ya está registrado.',
            'tel_movil.digits' => 'El número de teléfono debe comenzar con 6 o 7 y tener un total de 9 dígitos.',
            'contrasena_hash.min' => 'La contraseña debe tener una longitud mínima de 6.'
        ]);

        // Si la validación falla, devolver un mensaje de error con código 422.
        if ($validator->fails()) {
            return response()->json([
                'status' => false, // Indica que la operación no fue exitosa.
                'message' => $validator->errors()->first() // Mensaje del primer error encontrado.
            ], 422);
        }

        // Crear un nuevo perfil de demandante en la tabla "demandante".
        $demandante = Demandante::create([
            'nombre' => $request->nombre,
            'ape1' => $request->ape1,
            'ape2' => $request->ape2,
            'dni' => $request->dni,
            'tel_movil' => $request->tel_movil,
            'email' => $request->email,
            'situacion' => 0 // Situación inicial del demandante.
        ]);

        // Crear un nuevo usuario vinculado al demandante en la tabla "usuarios".
        $usuario = Usuario::create([
            'email' => $request->email,
            'contrasena_hash' => Hash::make($request->contrasena_hash), // Hashear la contraseña.
            'rol' => 'demandante', // Asignar el rol "demandante" al usuario.
            'id_rol' => 2 // Asignar el identificador del rol correspondiente (2 = demandante en este caso).
        ]);

        // Devolver los datos del usuario y demandante creados en formato JSON con código 201 (creado).
        return response()->json([
            'status' => true,
            'message' => 'Usuario registrado correctamente',
            'usuario' => $usuario,
            'demandante' => $demandante
        ], 201);
    }

    /**
     * Método para registrar un nuevo usuario con rol "empresa".
     * Realiza validaciones sobre los datos proporcionados y guarda la información en las tablas correspondientes.
     **/
    public function registerEmpresa(Request $request)
    {

        // Validación de datos de entrada.
        // Se verifican los campos obligatorios, formatos y unicidad en la base de datos.
        $validator = Validator::make($request->all(), [
            'cif' => 'required|string|size:11|unique:empresa,cif', // Validar que el CIF tenga exactamente 11 caracteres y sea único.
            'nombre' => 'required|string|max:45', // Nombre obligatorio, máximo de 45 caracteres.
            'localidad' => 'required|string|max:45', // Localidad obligatoria.
            'telefono' => 'required|digits:9', // Número de teléfono obligatorio.
            'email' => [
                'required',
                'regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/', // Validar el formato del email.
                'max:45', // Máximo de 45 caracteres.
                'unique:empresa,email', // Email único en la tabla "empresa".
                'unique:usuarios,email' // Email único en la tabla "usuarios".
            ],
            'contrasena_hash' => 'required|string|min:6' // La contraseña es obligatoria, debe ser una cadena y tener al menos 6 caracteres.
        ], [

            // Mensajes personalizados para cada regla de validación.
            'nombre.max' => 'El nombre es demasiado largo',
            'localidad.max' => 'El nombre de la localidad es demasiado largo',
            'cif.size' => 'El CIF debe tener 11 dígitos.',
            'cif.unique' => 'El CIF ya ha sido usado.',
            'email.regex' => 'El email debe tener un formato válido.',
            'email.unique' => 'Este email ya está registrado.',
            'telefono.digits' => 'El número de teléfono debe tener exactamente 9 dígitos.',
            'contrasena_hash.min' => 'La contraseña debe tener una longitud mínima de 6.'
        ]);

        // Validar los datos enviados en la solicitud.
        if ($validator->fails()) {
            // Si la validación falla, devolver un JSON con el estado de error y el primer mensaje de error.
            return response()->json([
                'status' => false, // Indica que la validación falló.
                'message' => $validator->errors()->first() // Devuelve el primer error encontrado por el validador.
            ], 422);
        }

        // Crear el registro de la empresa en la base de datos.
        $empresa = Empresa::create([
            'nombre' => $request->nombre, // Asignar el nombre enviado en la solicitud.
            'localidad' => $request->localidad, // Asignar la localidad enviada en la solicitud.
            'cif' => $request->cif, // Asignar el CIF enviado en la solicitud.
            'telefono' => $request->telefono, // Asignar el teléfono enviado en la solicitud.
            'email' => $request->email, // Asignar el email enviado en la solicitud.
            'validado' => 0 // Por defecto, se asigna "0" para marcar como "pendiente de validación".
        ]);

        // Crear un usuario asociado a la empresa en la tabla "usuarios".
        $usuario = Usuario::create([
            'email' => $request->email, // Asociar el mismo email que el registrado en la empresa.
            'contrasena_hash' => Hash::make($request->contrasena_hash), // Hashear la contraseña antes de almacenarla para seguridad.
            'rol' => 'empresa', // Asignar el rol "empresa" al usuario.
            'id_rol' => 3 // Asignar el identificador del rol correspondiente (3 = empresa en este caso).
        ]);

        // Devolver una respuesta JSON indicando que la operación fue exitosa.
        return response()->json([
            'status' => true,
            'message' => 'Usuario registrado correctamente',
            'usuario' => $usuario,
            'empresa' => $empresa
        ], 201);
    }
}