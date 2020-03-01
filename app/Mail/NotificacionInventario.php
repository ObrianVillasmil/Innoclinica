<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificacionInventario extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $fechaTopeInicial;
    public $fechaTopeFinal;
    public $faltantes;
    public $product_id;

    public function __construct($fechaTopeInicial,$fechaTopeFinal,$faltantes,$product_id)
    {
        $this->fechaTopeInicial = $fechaTopeInicial;
        $this->fechaTopeFinal = $fechaTopeFinal;
        $this->faltantes = $faltantes;
        $this->product_id = $product_id;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(getConfiguracionEmpresa()->correo_empresa)
            ->subject(ucfirst(getConfiguracionEmpresa()->nombre_empresa). ', alerta de inventario')
            ->view('mail.notificacion_inventario',[
                'fechaTopeInicial' =>$this->fechaTopeInicial,
                'fechaTopeFinal' =>$this->fechaTopeFinal,
                'faltantes' =>$this->faltantes,
                'producto' =>getProducto($this->product_id)->product_name,
                'empresa' => getConfiguracionEmpresa()
            ]);
    }
}
