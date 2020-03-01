<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class DocumentosTratamientoSolicitado extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $archivos;
    public $asunto;
    public $mensaje;

    public function __construct($archivos,$asunto,$mensaje)
    {
        $this->archivos = $archivos;
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $correo = $this->from(getConfiguracionEmpresa()->correo_empresa)
            ->subject($this->asunto)
            ->view('mail.documentos_solicitud_tratamiento',[
                'mensaje' => $this->mensaje
            ]);

        foreach ($this->archivos as $archivo) {

            if($archivo['ingreso'] ==="distribucion_tratamiento"){

                $correo->attach(env("PATH_ARCHIVOS_DOCUMENTOS").$archivo['data'],[
                    'as' => $archivo['data']]);

            }
            if($archivo['ingreso'] ==="cliente"){
                /*
                 *  $arch = Storage::disk('archivos')->get($archivo->carpeta."/".$archivo->archivo);
                    $correo->attach($arch,['as' => $archivo->archivo]);
                */
                $correo->attach(public_path()."/storage/archivos/".$archivo['data']->carpeta."/".$archivo['data']->archivo,[
                    'as' => $archivo['data']->archivo]);

            }
            elseif($archivo['ingreso'] === "admin"){

                if($archivo['data']->archivo != ""){

                    $correo->attach(public_path()."/storage/archivos/documentos/".$archivo['data']->archivo,[
                        'as' => $archivo['data']->archivo]);

                }else{

                    $pdf = \App::make('dompdf.wrapper');
                    $configuracion = getConfiguracionEmpresa();
                    $datos = [
                        'NOMBRE_EMPRESA' => $configuracion->nombre_empresa,
                        'PAIS_EMPRESA' => $configuracion->pais,
                        'ID_EMPRESA' => $configuracion->ruc_empresa,
                        'DIREC_EMPRESA' => $configuracion->direccion_empresa,
                        'NOMBRE_REP_EMPRESA' => $configuracion->nombre_representante,
                        'ID_REP_EMPRESA' => $configuracion->identificacion_representante,
                        'TLF_REP_EMPRESA' => $configuracion->telefono_representante,
                        'CORREO_REP_EMPRESA' => $configuracion->correo_representante,
                        'TEXTO_DOCUMENTO' =>$archivo['data']->cuerpo
                    ];
                    $pdf->loadHTML(crearDocumento($datos,false));
                    $pdf->save(public_path().'/'.Str::slug($archivo['data']->nombre).'.pdf');

                    $correo->attach(public_path().'/'.Str::slug($archivo['data']->nombre).'.pdf',[
                        'as' => $archivo['data']->archivo]);

                }

            }elseif($archivo['ingreso'] === "otros"){

                $correo->attach(public_path()."/storage/archivos/documentos/".$archivo['data']->nombre,[
                    'as' => $archivo['data']->nombre]);

            }

        }

    }
}
