<?php

namespace App\Http\Controllers;

use App\Modelos\ChatTema;
use App\Modelos\PreguntaRespuesta;
use App\Modelos\Productos;
use Illuminate\Http\Request;
use App\Modelos\BotConversacion;

class BotController extends Controller
{

    public function responder(Request $request){

        /*PREGUNTA*/

        $arrPregunta = explode(" ",$request->msj);


        $objBotConversacion = new BotConversacion;
        $objBotConversacion->texto = $request->msj;
        $objBotConversacion->id_sesion = session('id_sesion');
        $objBotConversacion->party_id  = session('party_id');
        if($objBotConversacion->save()){

            $temas = ChatTema::join('etiqueta_chat_tema as ect','chat_tema.id_chat_tema','ect.id_chat_tema')->get();

            $coincidencias = [];
            foreach ($arrPregunta as $pregunta) {
                foreach ($temas as $tema) {
                    similar_text($pregunta ,$tema->nombre,$porcentaje);

                    //Info($pregunta." ".$tema->nombre." ".$porcentaje);

                    if($porcentaje >= 50 && $porcentaje < 70){

                        //usar lavenstein cuando la coincidencia de la palabra no es muy exacta

                    }elseif($porcentaje >= 70){

                        $coincidencias[] = [
                            'porcentaje_coincidencia' => $porcentaje,
                            'palabra_pregunta' => $pregunta,
                            'palabra_tema' => $tema->nombre,
                            'tema' => $tema
                        ];

                    }
                }
            }

            if(count($coincidencias)>0){
                $etiquetasValidas = [];
                foreach ($coincidencias as $coincidencia) {
                    foreach ($coincidencia['tema']->etiqueta_chat_tema as $etiqueta){
                        foreach ($arrPregunta as $pregunta) {
                            similar_text($pregunta ,$etiqueta->nombre,$porcentaje);
                            //Info($pregunta ." ".$etiqueta->nombre." ".$porcentaje);
                            if($porcentaje >= 70){
                                $etiquetasValidas[]= $etiqueta->nombre;
                            }
                        }
                    }
                }

                //dd($coincidencias,$etiquetasValidas);
                $preg = [];
                foreach ($coincidencias as $coincidencia) {
                    foreach ($etiquetasValidas as $etiqueta){
                        foreach ($coincidencia['tema']->pregunta_respuesta as $pregunta) {
                            foreach (explode(" ",$pregunta->pregunta) as $palabraPregunta){
                                similar_text($palabraPregunta, $etiqueta, $porcentaje);                                
                                if ($porcentaje>=70) {
                                    //Info($porcentaje." ".$etiqueta." ".$pregunta->pregunta);
                                    $preg[$pregunta->id_pregunta_respuesta][] = $etiqueta;
                                }
                            }
                        }
                    }
                }


                $cantidad = 0;
                $preguntaDefinitiva=[];
                foreach ($preg as $idPregunta => $arr) {

                    if(count($arr) > $cantidad)
                        $preguntaDefinitiva['idPregunta']= $idPregunta;

                    $cantidad = count($arr);
                }


                if(isset($preguntaDefinitiva['idPregunta'])){
                  
                  $objPreguntaRespuesta = PreguntaRespuesta::where('id_pregunta_respuesta', $preguntaDefinitiva['idPregunta'])->first();

                  if($objPreguntaRespuesta->enlace){

                      $respuesta = "<a target='".($objPreguntaRespuesta->abrir_en ? '_blank' : '_self')."' href='".$objPreguntaRespuesta->enlace."'>".$objPreguntaRespuesta->respuesta."<a/>";

                  }elseif($objPreguntaRespuesta->accion){
                      $html="";
                      if($objPreguntaRespuesta->accion === "cotizador"){
                          $html .= "<p>".$objPreguntaRespuesta->respuesta."</p>";
                          $html .= "<p class='text-center'><buttom type='buttom' class='btn btn-default btn-sm' onclick='cotizar_prodcutos()'>
                                        <i class='fa fa-money'></i> Cotizador</buttom>
                                    </p>";
                      }
                      $respuesta = $html;
                  }else{
                      $respuesta = $objPreguntaRespuesta->respuesta;
                  }


                }else{
                  $respuesta =  "<span class='alert-warning'>No fue encontrada ninguna coincidencia con el texto ingresado</span>";
                }
               // dd($preguntaDefinitiva['idPregunta']);
                
            }else{
                $respuesta = "<span class='alert-warning'>Lo siento no entiendo tu pregunta, ¿puedes formularla de una forma más especifica y verificar que no posea errores ortográficos para comprenderte mejor?</span>";
            }

            /*RESPUESTA*/
            $objBotConversacion = new BotConversacion;
            $objBotConversacion->texto = $respuesta;
            $objBotConversacion->id_sesion =session('id_sesion');
            $objBotConversacion->party_id  = 0;
            $objBotConversacion->save();

        }

        return view('layouts.partials.bot_msg_left',[
            'respuesta' =>$respuesta
        ]);

    }

    public function selectProducto(Request $request){
        return Productos::where('product_id',$request->product_id)->first();
    }



}
