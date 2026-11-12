<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Час життя токена (секунди)
     */
    public const TOKEN_TTL = 3600;

    /**
     * Реєстрація - новий користувач завжди отримує роль Client
     *
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request): mixed
    {
        if (!$request->filled('email') || !$request->filled('password')) {
            return response()->json(['data' => ['error' => 'Email and password are required']], Response::HTTP_BAD_REQUEST);
        }

        if (User::where('email', $request->get('email'))->exists()) {
            return response()->json(['data' => ['error' => 'User already exists']], Response::HTTP_CONFLICT);
        }

        $user = User::create([
            'email'    => $request->get('email'),
            'password' => $request->get('password'),
            'role'     => User::ROLE_CLIENT,
        ]);

        return response()->json(['data' => $user], Response::HTTP_CREATED);
    }

    /**
     * Логін - перевіряє email/пароль і повертає JWT токен
     *
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request): mixed
    {
        $user = User::where('email', $request->get('email'))->first();

        if (!$user || !Hash::check((string) $request->get('password'), $user->password)) {
            return response()->json(['data' => ['error' => 'Invalid credentials']], Response::HTTP_UNAUTHORIZED);
        }

        $token = JWT::encode([
            'sub'  => $user->id,
            'role' => $user->role,
            'iat'  => time(),
            'exp'  => time() + self::TOKEN_TTL,
        ], config('app.key'), 'HS256');

        return response()->json(['token' => $token], Response::HTTP_OK);
    }

    /**
     * Поточний користувач за токеном
     *
     * @param Request $request
     * @return mixed
     */
    public function me(Request $request): mixed
    {
        return response()->json(['data' => $request->user()], Response::HTTP_OK);
    }
}
