<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificacionTratamientoDoctor extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $partyId;

    public function __construct($partyId)
    {
        $this->partyId = $partyId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.notificacion_doctor')
            ->from(/*getConfiguracionEmpresa()->correo_empresa*/'pruebas-c26453@inbox.mailtrap.io')
            ->subject('Notificacion '.getConfiguracionEmpresa()->nombre_empresa)
            ->with([
                'partyId' =>$this->partyId
            ]);
    }
}
