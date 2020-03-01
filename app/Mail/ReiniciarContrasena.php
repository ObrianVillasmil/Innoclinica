<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReiniciarContrasena extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $usuario;
    public $pass;

    public function __construct($pass,$usuario)
    {
       $this->pass = $pass;
       $this->usuario = $usuario;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {                           //CORREO EMPRESA
        return $this->from("f710d1281b-5c9cee@inbox.mailtrap.io")
            ->view('mail.reiniciar_contrasena',[
                'pass' => $this->pass,
                'usuario'=> $this->usuario
            ]);
    }
}
