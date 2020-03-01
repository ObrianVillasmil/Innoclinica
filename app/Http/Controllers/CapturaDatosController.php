<?php

namespace App\Http\Controllers;

use App\Modelos\CapturaDato;
use App\Modelos\DetalleCapturaDato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Modelos\RoleType;

class CapturaDatosController extends Controller
{
    public function inicio(Request $request){
        return view('captura_datos.list',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Proceso','sub_titulo'=>'Captura de datos'],
            'usuario' => getParty((int)session::get('party_id')),
            'capturaDatos' => CapturaDato::all()

        ]);
    }

    public function addCapturaDatos(Request $request, $idCapturaDato = null){
        return view('captura_datos.partials.crear',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Proceso','sub_titulo'=>'Captura de datos'],
            'usuario' => getParty((int)session::get('party_id')),
            'roleType' => RoleType::All(),
            'dataCapturaDato' => $idCapturaDato === null ? $idCapturaDato : CapturaDato::find($idCapturaDato)
        ]);
    }

    public function addCampo(Request $request){

        switch ($request->input){
            case "texto":
                return view('captura_datos.partials.text',[
                    'cant' => $request->cant
                ]);
                break;
            case "mail":
                return view('captura_datos.partials.mail',[
                    'cant' => $request->cant
                ]);
                break;
            case "tlf":
                return view('captura_datos.partials.tlf',[
                    'cant' => $request->cant
                ]);
                break;
            case "doctor":
                return view('captura_datos.partials.nombre_doctor',[
                    'cant' => $request->cant
                ]);
                break;
        }
    }

    public function storeCapturaDato(Request $request){
        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                      ha ocurrido un inconveniente al guardar el proceso de captura de datos, intente nuevamente.!
                </div>';


        if(!empty($request->id_captura_datos)){
            $objCapturaDato = CapturaDato::find($request->id_captura_datos);
            $objDetalleCapturaDato = DetalleCapturaDato::where('id_captura_dato',$request->id_captura_datos);
            $objDetalleCapturaDato->delete();
        }else{
            $objCapturaDato = new CapturaDato;
        }


        $objCapturaDato->nombre = $request->nombre;
        $objCapturaDato->descripcion = $request->descripcion;
        $objCapturaDato->role_type_id = $request->usuario;
        $objCapturaDato->solicitud_tratamiento = $request->solcitiud_tratamiento;
        $objCapturaDato->notifica_doctor = $request->notifica_doctor;
        $objCapturaDato->icono = $request->icono;

        if($objCapturaDato->save()){
            $modelCapturaDato = CapturaDato::all()->last();

                if(isset($request->doctor)){
                    foreach ($request->doctor as $x => $data) {
                        $objDetalleCapturaDato = new DetalleCapturaDato;
                        $objDetalleCapturaDato->id_captura_dato = $modelCapturaDato->id_captura_dato;
                        $objDetalleCapturaDato->doctor = true;
                        $objDetalleCapturaDato->id_campo = $data['id'];
                        $objDetalleCapturaDato->label = "campo_doctor_".($x+1)."";
                        $objDetalleCapturaDato->requerido = $data['required'];
                        $objDetalleCapturaDato->save();
                    }
                }

                if(isset($request->tlf)){
                    foreach ($request->tlf as $x => $data) {
                        $objDetalleCapturaDato = new DetalleCapturaDato;
                        $objDetalleCapturaDato->tlf = true;
                        $objDetalleCapturaDato->id_captura_dato = $modelCapturaDato->id_captura_dato;
                        $objDetalleCapturaDato->id_campo = $data['id'];
                        $objDetalleCapturaDato->label = "campo_tlf_".($x+1)."";
                        $objDetalleCapturaDato->requerido = $data['required'];
                        $objDetalleCapturaDato->save();
                    }
                }

                if(isset($request->mail)){
                    foreach ($request->mail as $x => $data) {
                        $objDetalleCapturaDato = new DetalleCapturaDato;
                        $objDetalleCapturaDato->mail = true;
                        $objDetalleCapturaDato->id_captura_dato = $modelCapturaDato->id_captura_dato;
                        $objDetalleCapturaDato->id_campo = $data['id'];
                        $objDetalleCapturaDato->requerido = $data['required'];
                        $objDetalleCapturaDato->label = "campo_mail_".($x+1)."";
                        $objDetalleCapturaDato->save();
                    }
                }

                if(isset($request->texto)){
                    foreach ($request->texto as $data) {
                        $objDetalleCapturaDato = new DetalleCapturaDato;
                        $objDetalleCapturaDato->label = $data['label'];
                        $objDetalleCapturaDato->texto = true;
                        $objDetalleCapturaDato->id_captura_dato = $modelCapturaDato->id_captura_dato;
                        $objDetalleCapturaDato->id_campo = $data['id'];
                        $objDetalleCapturaDato->requerido = $data['required'];
                        $objDetalleCapturaDato->save();
                    }
                }


            $success = true;
            $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                     Se ha guardado la configuración del formulario con éxito.!
            </div>';
        }

        return [
            'success' =>$success,
            "msg" => $msg
        ];
    }

    public function deleteCapturaDato(Request $request){
        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                    Ha ocurrido un error al intentar eliminar el proceso de Captura de datos, intente nuevamente.!
                </div>';

        if(CapturaDato::destroy($request->id_captura_dato)){
            $success = true;
            $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                    Se ha eliminado el proceso de Carga de archivo, éxitosamente.!
                </div>';
        }

        return [
            'success' =>$success,
            "msg" => $msg
        ];
    }
}
