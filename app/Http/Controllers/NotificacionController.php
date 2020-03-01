<?php

namespace App\Http\Controllers;

use App\Modelos\CargaArchivo;
use App\Modelos\Notificacion;
use App\Modelos\OtrosNotificacion;
use App\Modelos\TipoNotificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;


class NotificacionController extends Controller
{
    public function inicio(Request $request){
        return view('notificacion.list',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Proceso','sub_titulo'=>'NotificacionesInvetario'],
            'usuario' => getParty((int)session::get('party_id')),
            'notificaciones' => Notificacion::all()
        ]);
    }

    public function addNotificacion(Request $request, $idNotificacion = null){
        return view('notificacion.partials.crear',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Proceso','sub_titulo'=>'Crear notificación'],
            'usuario' => getParty((int)session::get('party_id')),
            'tipoNotificacion' => TipoNotificacion::all(),
            'dataNotificacion' => $idNotificacion != null ? getNotificacion($idNotificacion) : null
        ]);
    }

    public function partialsOtros(Request $request){
        return view('notificacion.partials.otros',[
            'tipo_notificacion' => $request->tipo_notificacion
        ]);
    }

    public function storeNotificacion(Request $request){

       $validar = Validator::make($request->all(), [
            'nombre' => 'required',
            'tipo_notificacion' => 'required',
            'mensaje' => 'required',
        ]);


       $success = false;
       $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                    Hubo un error guardando la notificación.!
                </div>';

       if (!$validar->fails()) {

           if($request->arr_data == null)
               return [
                   'success' =>false,
                   'msg' => '<div class="alert alert-danger" role="alert" style="margin: 0">
                                Debe seleccionar al menos una persona para crear la notificación.!
                             </div>'
               ];


           $objNotificacion = new Notificacion;
           $objNotificacion->id_tipo_notificacion = $request->tipo_notificacion;
           $objNotificacion->nombre = $request->nombre;
           $objNotificacion->mensaje = $request->mensaje;
           $objNotificacion->icono = $request->icono;

           foreach ($request->arr_data as $x => $item) {

               if(isset($item['administrador']) && $item['administrador'] == "true")
                  $objNotificacion->administrador = 1;

               if(isset($item['representante_legal']) && $item['representante_legal'] == "true")
                    $objNotificacion->representante_legal = 1;

               if(isset($item['paciente']) && $item['paciente'] == "true")
                   $objNotificacion->paciente = 1;

               if(isset($item['otros']) && $item['otros'] == "true")
                   $objNotificacion->otros = 1;

           }


           if($objNotificacion->save()){

               if(isset($objNotificacion->otros) && $objNotificacion->otros){
                   $modelNotificacion = Notificacion::all()->last();
                   if(!empty($request->id_notificacion)){

                       $objCargaArchivo = CargaArchivo::where('id_notificacion',$request->id_notificacion);
                       $objCargaArchivo->update([
                           'id_notificacion' => $modelNotificacion->id_notificacion
                        ]);
                       Notificacion::destroy($request->id_notificacion);
                   }

                   $success = false;
                   foreach ($request->data_otros as $data_otros) {
                       $objOtrosNotificacion = new OtrosNotificacion;
                       $objOtrosNotificacion->id_notificacion = $modelNotificacion->id_notificacion;
                       $objOtrosNotificacion->texto = $data_otros['text'];
                       $objOtrosNotificacion->save();
                   }

               }
               $success = true;
               $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                            Se ha guardado la notificación con éxito.!
                        </div>';

           }

       }else{

           $errores = '';
           foreach ($validar->errors()->all() as $error) {
               if ($errores == '') {
                   $errores = '<li>' . $error . '</li>';
               } else {
                   $errores .= '<li>' . $error . '</li>';
               }
           }
           $msg = '<div class="alert alert-danger">' .
               '<p class="text-center">¡Por favor corrija los siguientes errores!</p>' .
               '<ul>' .
               $errores .
               '</ul>' .
               '</div>';
       }
        return [
            'success' =>$success,
            "msg" => $msg
        ];

    }

    public function deleteNotificacion(Request $request){
        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                    Hubo un error guardando la notificación.!
                </div>';

        if(Notificacion::destroy($request->id_notificacion)){
            $success = true;
            $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                        Se ha eliminado la notificación con éxito.!
                    </div>';
        }
        return [
            'success' =>$success,
            "msg" => $msg
        ];

    }
}