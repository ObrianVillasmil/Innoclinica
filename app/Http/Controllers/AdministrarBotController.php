<?php

namespace App\Http\Controllers;

use App\Modelos\EtiquetaChatTema;
use App\Modelos\EtiquetaPreguntaRespuesta;
use Illuminate\Http\Request;
use App\Modelos\ChatTema;
use App\Modelos\PreguntaRespuesta;
use Validator;

class AdministrarBotController extends Controller
{
    public function inicio(Request $request){

        $preguntasRespuestas = PreguntaRespuesta::orderBy('id_pregunta_respuesta','desc');

        if(isset($request['pregunta']))
          $preguntasRespuestas->where('pregunta','ilike',"%".$request['pregunta']."%");

        if(isset($request['tema']) && $request['tema'] != null)
            $preguntasRespuestas->where('id_chat_tema',$request['tema']);

        return view('admin_bot.inicio',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Bot','sub_titulo'=>'Administrar inteligencia del bot'],
            'usuario' => getParty(session('party_id')),
            'temas' => ChatTema::get(),
            'preguntasRespuestas' => $preguntasRespuestas->get(),
            'pregunta'=>$request['pregunta'],
            'tema_selected' => $request['tema']

        ]);
    }

    public function addTema(Request $request){
        return view('admin_bot.partials.add_tema',[
            'tema' => ChatTema::where('id_chat_tema',$request->id_chat_tema)->first()
        ]);
    }

    public function storeTema(Request $request){

        $validar = Validator::make($request->all(), [
            'tema' => 'required',
        ]);
        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                        ha ocurrido un inconveniente al guardar el tema, intente nuevamente.!
                    </div>';

        if (!$validar->fails()) {

            $objChatTema = isset($request->id_chat_tema) ? ChatTema::find($request->id_chat_tema) : new ChatTema;
            $objChatTema->nombre = $request->tema;
            if ($objChatTema->save()) {
                $success = true;
                $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                             Se ha guardado el tema con éxito.!
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
            'success' => $success,
            "msg" => $msg
        ];

    }

    public function deleteTema(Request $request){

        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                        ha ocurrido un inconveniente al eliminar el tema, intente nuevamente.!
                    </div>';

        if (ChatTema::destroy($request->id_tema)) {
            $success = true;
            $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                             Se ha eliminado el tema con éxito.!
                        </div>';
        }

        return [
            'success' => $success,
            "msg" => $msg
        ];

    }

    public function addEtiqueta(Request $request){

        return view('admin_bot.partials.add_etiqueta',[
            'idChatTema' => $request->id_chat_tema,
            'etiquetaChatTema' => EtiquetaChatTema::where('id_chat_tema',$request->id_chat_tema)->get()
        ]);

    }

    public function storeEtiqueta(Request $request){

        $x = 0;
        $dataDelete = EtiquetaChatTema::where('id_chat_tema',$request->id_chat_tema)->get();

        foreach ($request->data as $item) {
           $objEtiquetaChatema = new EtiquetaChatTema;
           $objEtiquetaChatema->id_chat_tema = $request->id_chat_tema;
           $objEtiquetaChatema->nombre = $item['etiqueta'];
           if($objEtiquetaChatema->save()) $x++;
        }

        if($x == count($request->data)){
            $success = true;
            $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                    Se han guardado las etiquetas con éxito.!
                </div>';
            foreach ($dataDelete as $item)
                EtiquetaChatTema::destroy($item->id_etiqueta_chat_tema);

        }else{
            $success = false;
            $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                    No se ha guardado las etiquetas, intente nuevamente.!
                </div>';
        }
        return [
            'success' => $success,
            "msg" => $msg
        ];

    }

    public function addPregunta(Request $request){

        return view('admin_bot.partials.add_pregunta',[
            'temas' => ChatTema::get(),
            'preguntaRespuesta'=> getPreguntaRespuesta($request->id_pregunta_respuesta)
        ]);

    }

    public function addEtiquetasTema(Request $request){

        return EtiquetaChatTema::where('id_chat_tema',$request->id_chat_tema)
            ->select('id_etiqueta_chat_tema','nombre')->get();

    }

    public function storePregunta(Request $request){

        $validar = Validator::make($request->all(), [
            'id_chat_tema' => 'required',
            'accion' => 'required',
            'cuerpo_respuesta.respuesta'=> 'required'
        ],[
            'id_chat_tema.required'=> 'No se seleccionó un tema',
            'accion.required' => 'Debe seleccionar el tipo de cuerpo de la respuesta e ingresar su contenido',
            'cuerpo_respuesta.respuesta.required' => 'Debe escribir la respuesta de la pregunta'
        ]);

        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                        ha ocurrido un inconveniente al guardar el tema, intente nuevamente.!
                    </div>';

        if (!$validar->fails()) {

            $dataEtiquetaPreguntaRespuesta = EtiquetaPreguntaRespuesta::where('id_pregunta_respuesta',$request->id_pregunta_respuesta)->get();
            $objPreguntaRespuesta = isset($request->id_pregunta_respuesta) ? PreguntaRespuesta::find($request->id_pregunta_respuesta) : new PreguntaRespuesta;
            $objPreguntaRespuesta->id_chat_tema = $request->id_chat_tema;
            $objPreguntaRespuesta->pregunta = $request->pregunta;
            $objPreguntaRespuesta->respuesta = $request->cuerpo_respuesta['respuesta'];
            if(isset($request->cuerpo_respuesta['enlace'])) $objPreguntaRespuesta->enlace = $request->cuerpo_respuesta['enlace'];
            if(isset($request->cuerpo_respuesta['abrir_en'])) $objPreguntaRespuesta->abrir_en = $request->cuerpo_respuesta['abrir_en'];
            if(isset($request->cuerpo_respuesta['btn_accion'])) $objPreguntaRespuesta->accion = $request->cuerpo_respuesta['btn_accion'];

            if($objPreguntaRespuesta->save()){

                $x=0;
                $modelPreguntaRespuesta = PreguntaRespuesta::all()->last();

                foreach ($request->etiquetas as $etiqueta) {

                    $objEtiquetaPreguntaRespuesta = new EtiquetaPreguntaRespuesta;
                    $objEtiquetaPreguntaRespuesta->id_pregunta_respuesta =isset($objPreguntaRespuesta->id_pregunta_respuesta) ? $objPreguntaRespuesta->id_pregunta_respuesta : $modelPreguntaRespuesta->id_pregunta_respuesta;
                    $objEtiquetaPreguntaRespuesta->etiqueta = $etiqueta['etiqueta_chat_tema'];
                    if($objEtiquetaPreguntaRespuesta->save()) $x++;

                }

                if($x === count($request->etiquetas)){

                    foreach ($dataEtiquetaPreguntaRespuesta as $etiquetaPreguntaRespuesta)
                        EtiquetaPreguntaRespuesta::destroy($etiquetaPreguntaRespuesta->id_etiqueta_pregunta_respuesta);

                    if(isset($request->nuevasEtiquetas)){
                        foreach ($request->nuevasEtiquetas as $nuevaEtiqueta) {
                            $objEtiquetaChatTema = new EtiquetaChatTema;
                            $objEtiquetaChatTema->nombre = $nuevaEtiqueta['nombre'];
                            $objEtiquetaChatTema->id_chat_tema = $request->id_chat_tema;
                            $objEtiquetaChatTema->save();
                        }
                    }

                    $success = true;
                    $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                        Se ha guardado la pregunta con éxito.!
                    </div>';
                }

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
            'success' => $success,
            "msg" => $msg
        ];

    }

    public function deletePregunta(Request $request){

        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                        ha ocurrido un inconveniente al eliminar la pregunta, intente nuevamente.!
                    </div>';

        if (PreguntaRespuesta::destroy($request->id_pregunta_respuesta)) {
            $success = true;
            $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                             Se ha eliminado la pregunta con éxito.!
                        </div>';
        }

        return [
            'success' => $success,
            "msg" => $msg
        ];
    }

    public function deleteEtiquetaPreguntaRespuesta(Request $request){

        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                        ha ocurrido un inconveniente al eliminar la etiqueta, intente nuevamente.!
                    </div>';

        if (EtiquetaPreguntaRespuesta::destroy($request->id_etiqueta_pregunta_respuesta)) {
            $success = true;
            $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                             Se ha eliminado la etiqueta con éxito.!
                        </div>';
        }

        return [
            'success' => $success,
            "msg" => $msg
        ];
    }
}
