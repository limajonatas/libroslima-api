<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationCodeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationCode;

    /**
     * Create a new message instance.
     *
     * @param int $verificationCode
     * @return void
     */
    public function __construct(int $verificationCode)
    {
        $this->verificationCode = $verificationCode;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Código de Verificação',
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Código de Verificação')
            ->view('emails.verification_code')
            ->with([
                'verificationCode' => $this->verificationCode,
            ]);
    }
}
