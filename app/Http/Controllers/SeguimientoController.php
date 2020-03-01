<?php

namespace App\Http\Controllers;

use App\Modelos\DetalleTratamientoDoctor;
use App\Modelos\TratamientoSolicitado;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Storage;
//use Dompdf\Dompdf;
//use Illuminate\Support\Facades\Artisan;

class SeguimientoController extends Controller
{
    public function inicio(Request $request){

        return view('seguimiento.inicio',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Tratamientos','sub_titulo'=>'Seleccione el tratamiento'],
            'usuario' => getParty(session('party_id')),
            'tratamientos' => TratamientoSolicitado::where('estado',1)->get()
            ]);
    }

    public function firmaDigital(Request $request){
        set_time_limit(0);
        $party = getParty(session('party_id'));

        $success = false;
        $msg = "<div class='alert alert-danger'> Ha ocurrido un error al intentar firmar el documento, haga clic solo el arcihvo para ver el mensaje </div>";
        $success = false;
        $idTratamiento = $request->idTratamiento;
        $partyId = $request->partyId;
        $idDoctor = $request->idDoctor;
        $seguimiento = true;

        if(!isset($party->firma)){
            $success = false;
            $msg = "<div class='alert alert-danger'> No has cargado aún tu firma digital </div>";
        }else{

            $tratamiento = getTratamiento($request->idTratamiento);
            $tratamientoSolicitado = getTratamientoSolicitado($idTratamiento,$partyId);
            $objTratamientoSolicitado = TratamientoSolicitado::find($tratamientoSolicitado->id_tratamiento_solicitado);

            $rand = rand(5, 2000);
            PDF::loadView('tratamiento.partials.formato_distribucion',compact('idTratamiento','partyId','idDoctor','seguimiento'))
                ->setPaper('a4', 'landscape')->setWarnings(false)->save(env('PATH_ARCHIVOS_PDF_GENERADOS').Str::slug($rand.$tratamiento->nombre_tratamiento).'.pdf');

            exec('java -jar '.env('JAR_FRIMA_DIGITAL_PDF').
                ' -kst PKCS12 -ksf '.env('PATH_ARCHIVO_FIRMA_ELECTRONICA').$party->firma->archivo.' '.
                env('PATH_ARCHIVOS_PDF_GENERADOS').Str::slug($rand.$tratamiento->nombre_tratamiento).'.pdf'.
                ' -ksp '. $party->firma->contrasena.' -l Ecuador --render-mode DESCRIPTION_ONLY -V  -d '.env('PATH_ARCHIVOS_DOCUMENTOS').'',
                $var,$output);

            /*dump($output,env('PATH_ARCHIVOS_PDF_GENERADOS'),'java -jar '.env('JAR_FRIMA_DIGITAL_PDF').
                ' -kst PKCS12 -ksf '.env('PATH_ARCHIVO_FIRMA_ELECTRONICA').$party->firma->archivo.' '.
                env('PATH_ARCHIVOS_PDF_GENERADOS').Str::slug($rand.$tratamiento->nombre_tratamiento).'.pdf'.
                ' -ksp '. $party->firma->contrasena.' -l Ecuador --render-mode DESCRIPTION_ONLY -V  -d '.env('PATH_ARCHIVOS_DOCUMENTOS').'');*/

            if($output == 0){
                $objTratamientoSolicitado->pdf_firmado = Str::slug($rand.$tratamiento->nombre_tratamiento).'_signed.pdf';
                if($objTratamientoSolicitado->save()){
                    unlink(env('PATH_ARCHIVOS_PDF_GENERADOS').Str::slug($rand.$tratamiento->nombre_tratamiento).'.pdf');
                    if(isset($tratamientoSolicitado->pdf_firmado) && $tratamientoSolicitado->pdf_firmado!= ""){
                        unlink(env('PATH_ARCHIVOS_DOCUMENTOS').$tratamientoSolicitado->pdf_firmado);
                    }
                    $success = true;
                    $msg = "<div class='alert alert-success'> Documento firmado </div>";
                }
            }else{
                //Artisan::call('config:cache');
                //Artisan::call('cache:clear');
                //Artisan::call('config:clear');
                $success = false;
                $msg = "<div class='alert alert-danger'> Ha ocurrido un problema al tratar de firmar electrónicamente el archivo, intenta nuevamente </div>";
            }

        }


        return [
            "success" => $success,
            "msg" => $msg
        ];
    }

    public function storeDatoImportacion(Request $request){
        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                          ha ocurrido un inconveniente asignar código de aprobación para la importación, intente nuevamente.!
                </div>';

        $objTratamientoSolicitado = DetalleTratamientoDoctor::where('id_tratamiento_solicitado',$request->id_tratamiento_solicitado);
        $update = $objTratamientoSolicitado->update(['codigo_importacion'=>$request->codigo_importacion]);

        if($update){
            $success = true;
            $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                    Se ha asignado el código de importación éxitosamente.!
                </div>';
        }
        return [
            'success' =>$success,
            "msg" => $msg
        ];
    }
}
