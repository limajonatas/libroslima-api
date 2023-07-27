<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckCodeEmailRequest;
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


        // Gerar um token aleatório para o e-mail de verificação até que seja único
        $verificationCode = null;
        do {
            $verificationCode = rand(100000, 999999);
        } while (EmailVerification::where('code_verification', $verificationCode)->exists());


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

    public function verifyEmail(CheckCodeEmailRequest $request)
    {
        try {

            if (User::where('email', $request->input('email'))->exists()) {
                return response()->json([
                    'message' => 'Usuário já cadastrado',
                    'status' => 'error'
                ], 400);
            }

            //verifica se o email está no banco
            if (EmailVerification::where('email_user', $request->input('email'))->exists()) {
                //verifica se o código está correto
                try {
                    if (EmailVerification::where('email_user', $request->input('email'))
                        ->where('code_verification', $request->input('code'))->exists()
                    ) {
                        //se estiver, cria o usuário
                        $emailVerification = EmailVerification::where('email_user', $request->input('email'))
                            ->where('code_verification', $request->input('code'))->first();

                        $user = User::create([
                            'name' => $emailVerification->name_user,
                            'nickname' => $emailVerification->nickname_user,
                            'email' => $emailVerification->email_user,
                            'password' => $emailVerification->password,
                            'email_verified_at' => now(),
                        ]);

                        //deleta o registro de verificação de email
                        EmailVerification::where('email_user', $request->input('email'))->delete();

                        $token = $user->createToken('api-token')->plainTextToken;

                        return response()->json([
                            'message' => 'Usuário criado com sucesso.',
                            'status' => 'success',
                            'user' => $user,
                            'token' => $token
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'Código de verificação inválido.',
                            'status' => 'error'
                        ], 400);
                    }
                } catch (ModelNotFoundException $e) {
                    return response()->json([
                        'message' => 'Ocorreu um erro ao verificar o código. Tente novamente!',
                        'status' => 'error'
                    ], 400);
                }
            } else {
                return response()->json([
                    'message' => 'E-mail não encontrado.',
                    'status' => 'error'
                ], 400);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao verificar o e-mail. Tente novamente!',
                'status' => 'error'
            ], 400);
        }
    }

    public function resendEmail(Request $request)
    {
        try {
            $verification = EmailVerification::where('email_user', $request->email)->first();

            if ($verification) {
                // Gerar um token aleatório para o e-mail de verificação até que seja único
                $verificationCode = null;
                do {
                    $verificationCode = rand(100000, 999999);
                } while (EmailVerification::where('code_verification', $verificationCode)->exists());

                $verification->code_verification = $verificationCode;
                $verification->save();

                try {
                    // Enviar o email
                    Mail::to($request->email)
                        ->send(new VerificationCodeEmail($verificationCode));
                } catch (TransportException $e) {
                    // Capturar a exceção de envio de e-mail
                    $code = $e->getCode();
                    $message = $e->getMessage();

                    // Excluir o registro da tabela de e-mails a serem verificados
                    EmailVerification::where('email_user', $request->email)->delete();
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
            }else{
                return response()->json([
                    'message' => 'E-mail não encontrado.',
                    'status' => 'error'
                ], 400);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao verificar o e-mail. Tente novamente!',
                'status' => 'error'
            ], 400);
        }
    }
}
