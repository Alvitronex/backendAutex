<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Registro de usuario
    public function register(Request $request)
    {
        // Validar los datos de entrada de forma 
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:users',
            'name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:8',
            'DUI' => 'required|string|max:9|unique:users',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'theme' => 'nullable|string|in:light,dark,auto',
        ]);
        // Validar los datos de entrada si hay errores
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => 'Validation erros',
                'errors' => $validator->errors()
            ], 422);
        }
        // Instanciando un nuevo usuario desde el modelo User
        // y guardando los datos del usuario en la base de datos
        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'DUI' => $request->DUI,
            'email' => $request->email,
            'password' => $request->password,
            'theme' => $request->theme ?? 'light',
        ]);

        $token = $user->createToken('autex-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ], 201);
    }

    // Inicio de sesión de usuario
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Verificar si el usuario está activo
        $token = $user->createToken('autex-token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ], 200);
    }

    // Cierre de sesión de usuario
    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully',
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    }
}
