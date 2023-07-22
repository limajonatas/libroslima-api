<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserValidateRequest;
use App\Mail\VerificationCodeEmail;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;

class RegisterController extends Controller
{
    public function store(UserValidateRequest $request)
    {
        if (User::where('email', $request->input('email'))->exists()) {
            return response()->json([
                'message' => 'Este email está em uso',
                'status' => 'error'
            ], 400);
        }


        // Gerar um token aleatório para o e-mail de verificação
        $verificationCode = mt_rand(1000, 999999);

        try {
            // Procurar por um registro com o e-mail fornecido
            $emailVerification = EmailVerification::updateOrCreate(
                ['email_user' => $request->input('email')],
                [
                    'name_user' => $request->input('name'),
                    'nickname_user' => $request->input('nickname'),
                    'password' => bcrypt($request->input('password')),
                    'code_verification' => $verificationCode,
                ]
            );
        } catch (ModelNotFoundException $e) {
            EmailVerification::where('email_user', $request->input('email'))->delete();

            return response()->json([
                'error' => 'Erro ao verificar o e-mail de verificação. Tente Novamente!',
                'status' => 'error'
            ], 500);
        }

        if ($emailVerification->wasRecentlyCreated) {
            try {
                // Enviar o email
                Mail::to($request->input('email'))
                    ->send(new VerificationCodeEmail($verificationCode));
            } catch (TransportException $e) {
                // Capturar a exceção de envio de e-mail
                $code = $e->getCode();
                $message = $e->getMessage();

                // Excluir o registro da tabela de e-mails a serem verificados
                EmailVerification::where('email_user', $request->input('email'))->delete();
                return response()->json([
                    'message' => 'Erro ao enviar o e-mail de verificação.',
                    'status' => 'error',
                    'errors' => [
                        'code' => $code,
                        'message' => $message
                    ]
                ], 500);
            }

            return response()->json([
                'message' => 'Verifique seu e-mail para concluir o cadastro.',
                'status' => 'success'
            ]);
        } else {
            // Se o registro não foi criado recentemente, significa que o e-mail já existe
            return response()->json([
                'error' => 'Um e-mail de verificação já foi enviado para este endereço de e-mail.',
                'status' => 'success'
            ], 400);
        }
    }
}
