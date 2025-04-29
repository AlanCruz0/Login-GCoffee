<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationCode;

    /**
     * Crear una nueva instancia del correo.
     *
     * @param  string  $verificationCode
     * @return void
     */
    public function __construct($verificationCode)
    {
        $this->verificationCode = $verificationCode;
    }

    /**
     * Definir el contenido del mensaje.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Código de Verificación')
                    ->view('emails.verification_code'); // Vista que contiene el cuerpo del correo
    }
}
