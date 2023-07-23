<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginValidateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginValidateRequest $request)
    {
        $userField = $request->input('user');
        $password = $request->input('password');

        // Verifica se o usuário existe por email ou nickname
        $user = User::where('email', $userField)->orWhere('nickname', $userField)->first();

        if (!$user) {
            return response()->json([
                'error' => 'Usuário não encontrado!',
                'status' => 'error'
            ], 404);
        }

        // Verifica se a senha está correta
        if (!Auth::attempt(['email' => $user->email, 'password' => $password])) {
            return response()->json([
                'error' => 'Senha inválida!',
                'status' => 'error'
            ], 401);
        }

        // Verifica se o usuário escolheu "lembrar-me"
        $rememberMe = $request->input('remember_me');

        // Define o prazo de validade com base na escolha do usuário
        $expiration = $rememberMe ? now()->addDays(30) : null;

        // Gera o token de acesso
        $token = $user->createToken('token-name', ['server:update'], $expiration);

        return response()->json([
            'message' => 'Login efetuado com sucesso!',
            'status' => 'success',
            'user' => $user,
            'token' => [
                'token' => $token->plainTextToken,
                'token_type' => 'Bearer',
                'expires_at' => $token->accessToken->expires_at,
            ],
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout efetuado com sucesso.']);
    }
}