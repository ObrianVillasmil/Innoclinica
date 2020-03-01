<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ErrorMailDoctor extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $doctor;

    public function __construct($doctor)
    {
        $this->doctor = $doctor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.error_mail_doctor')
            ->subject('Error en mail de doctor ('.getConfiguracionEmpresa()->nombre_empresa.')')
            ->with([
                'doctor' => $this->doctor
            ]);
    }
}
