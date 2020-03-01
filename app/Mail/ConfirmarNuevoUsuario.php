<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmarNuevoUsuario extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $token;
    public $url;
    public function __construct($token,$party_id)
    {
        $this->token = $token;
        $this->party_id = $party_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $empresa = getConfiguracionEmpresa();//CORREO EMPRESA
        return $this->from($empresa->correo_empresa)
            ->view('mail.confirmar_usuario',[
                'token' => $this->token,
                'party_id'=> $this->party_id,
                'url'   => url('/')
            ]);
    }
}
