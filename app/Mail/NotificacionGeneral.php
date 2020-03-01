<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificacionGeneral extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $mensaje;

    public function __construct($mensaje)
    {
        $this->mensaje = $mensaje;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(getConfiguracionEmpresa()->correo_empresa)
            ->view('mail.notificacion_mail')
            ->subject('Notificación de '.getConfiguracionEmpresa()->nombre_empresa)
            ->with(['mensaje'=> $this->mensaje]);
    }
}
