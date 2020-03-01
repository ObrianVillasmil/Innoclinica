<div class="container p-0">
    <div class="row">
        <form id="form_pregunta" style="width: 100%">
            <div class="col-md-12 col-xl-12">
                <label>Seleccione un tema</label>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-md-6 col-lg-6">
                            <div class="row">
                                <select class="form-control select_chat_tema" {{isset($preguntaRespuesta->id_chat_tema) ? "" : "onchange=add_etiqueta_chat_tema()"}}  id="id_etiqueta_chat_tema">
                                    @foreach($temas as $tema)
                                        <option {{isset($preguntaRespuesta->id_chat_tema) ? ($preguntaRespuesta->id_chat_tema == $tema->id_chat_tema ? "selected" : "") : ""}} value="{{$tema->id_chat_tema}}">{{$tema->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if(isset($preguntaRespuesta->id_pregunta_respuesta))
                            <div class="col-xs-12 col-md-6 col-lg-6">
                                <div class="row">
                                    <button title="Agregar etiqueta" onclick="add_etiqueta_pregunta_respuesta('{{$preguntaRespuesta->id_chat_tema}}')" type="button" class="btn btn-warning">
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 pt-2">
                    <div class="row">
                        <div class="card card-body bg-light etiquetas_tema font-weight-bold text-center p-1">
                            <label>Etiquetas</label>
                            <div class="row div_check_etiqueta">
                                @if(isset($preguntaRespuesta->etiqueta_pregunta_respuesta))
                                    @foreach($preguntaRespuesta->etiqueta_pregunta_respuesta as $x => $etiqueta)
                                        <div class='pl-3 pr-3 pb-2' id="div_etiqueta_creada_{{$etiqueta->id_etiqueta_pregunta_respuesta}}">
                                            <div class='btn btn-default btn-sm'>
                                                <button type="button" class="close" onclick="delete_etiqueta_pregunta_respuesta('{{$etiqueta->id_etiqueta_pregunta_respuesta}}')">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                <input type='checkbox' name='check_etiqueta_{{$x+1}}' checked value='{{$etiqueta->etiqueta}}' id='check_etiqueta_{{$x+1}}'>
                                                <label for='check_etiqueta_{{$x+1}}'  class='mb-0'>{{$etiqueta->etiqueta}}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 pt-2">
                    <div class="row">
                        <div class="card card-body bg-light font-weight-bold text-center p-1">
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <label class="font-weight-bold text-center p-1">Pregunta</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button data-toggle="dropdown" type="button" class="btn btn-outline-secondary dropdown-toggle" aria-expanded="false">
                                                Cuerpo de la respuesta <span class="caret"></span>
                                            </button>
                                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 40px, 0px);">
                                                <a href="#" class="dropdown-item" onclick="ingresar_accion('texto')">Texto</a>
                                                <a href="#" class="dropdown-item" onclick="ingresar_accion('link')">Link</a>
                                                <a href="#" class="dropdown-item" onclick="ingresar_accion('accion')">Acción</a>
                                                {{--<div class="dropdown-divider"></div><a href="#" class="dropdown-item">Separated link</a>--}}
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" id="input_pregunta" name="input_pregunta" value="{{isset($preguntaRespuesta->pregunta) ? $preguntaRespuesta->pregunta : "" }}" required>
                                    </div>
                                    <div id="cuerpo_respuesta" class="pt-2">
                                        @if(isset($preguntaRespuesta->pregunta))
                                            @if(isset($preguntaRespuesta->enlace))
                                                <label class='label_accion'>Enlace</label>
                                                <div class='input-group'>
                                                    <div class='input-group-prepend'>
                                                        <input type='text' placeholder='Texto del enlace' id='texto_respuesta' class='form-control'
                                                               value="{{$preguntaRespuesta->respuesta}}" required>
                                                    </div>
                                                    <input type='url' placeholder='Enlace' id='texto_enlace' class='form-control'
                                                           value="{{$preguntaRespuesta->enlace}}" required>
                                                    <div class='input-group-prepend'>
                                                        <select placeholder='texto del enlace' id='abrir_en' class='form-control' title='Forma de abrir el enlace' required>
                                                            <option value='1' {{$preguntaRespuesta->abrir_en == true ?"selected" : ""}}>Otra ventana</option>
                                                            <option value='0' {{$preguntaRespuesta->abrir_en == false ? "selected" : ""}}>Misma ventana</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            @elseif(isset($preguntaRespuesta->accion))
                                                <label class='label_accion'>Acción</label>
                                                <div class='input-group'>
                                                    <input type='text' placeholder='Texto de la tespuesta' value="{{$preguntaRespuesta->respuesta}}" id='texto_respuesta' class='form-control' required>
                                                </div>
                                                <div class='mt-2 text-center'>
                                                    <div class='btn btn-default btn-sm'  id='accion_cotizar'>
                                                        <input {{$preguntaRespuesta->accion == "cotizador" ? "checked" : ""}} type='radio' name='accion_cotizar' value='cotizador' required>
                                                        <label for='accion_cotizar' class='mb-0'>Cotizar</label>
                                                        {{--ACCIONES DE COTIZACION--}}
                                                    </div>
                                               </div>
                                            @else
                                                <label class='label_accion'>Texto</label>
                                                <div class='input-group'>
                                                    <div class='input-group-prepend'>
                                                        <span class='input-group-text'>Escriba la respuesta</span>
                                                    </div>
                                                    <input type='text' id='texto_respuesta' class='form-control' value="{{$preguntaRespuesta->respuesta}}" required>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group text-center mt-2" style="margin-top: 10px">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-ban" aria-hidden="true"></i>
                        Cerrar
                    </button>
                    <button type="button" class="btn btn-primary" onclick="store_pregunta('{{isset($preguntaRespuesta->id_pregunta_respuesta) ? $preguntaRespuesta->id_pregunta_respuesta : ""}}')">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                        Guardar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<style>
    #texto_enlace-error{
        position: absolute;
        bottom: -28px;
        left: 210px;
    }

    #input_pregunta-error{
        position: absolute;
        top: 39px;
    }

    #texto_respuesta-error{
        position: absolute;
        top: 38px;
    }

</style>