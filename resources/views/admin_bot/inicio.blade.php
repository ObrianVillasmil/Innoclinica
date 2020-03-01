@extends('layouts.partials.dashboard')
@section('title')
    Adminstarción del bot
@endsection

@section('contenido')
    <div class="container">

        <div class="p-3 bg-white rounded shadow mb-5">
            <!-- Rounded tabs -->
            <ul id="myTab" role="tablist" class="nav nav-tabs nav-pills flex-column flex-sm-row text-center bg-light border-0 rounded-nav">
                <li class="nav-item flex-sm-fill">
                    <a id="profile-tab" data-toggle="tab" href="#preguntas_respuestas" role="tab" aria-controls="preguntas_respuestas" aria-selected="false" class="nav-link border-0 font-weight-bold active"><i class="fa fa-comments-o"></i> PREGUNTAS Y RESPUESTAS</a>
                </li>
                <li class="nav-item flex-sm-fill">
                    <a id="home-tab" data-toggle="tab" href="#temas_etiquetas" role="tab" aria-controls="temas_etiquetas" aria-selected="true" class="nav-link border-0 font-weight-bold "><i class="fa fa-tags"></i> TEMAS Y ETIQUETAS</a>
                </li>
            </ul>
            <div id="myTabContent" class="tab-content">
                <div id="preguntas_respuestas" role="tabpanel" aria-labelledby="profile-tab" class="tab-pane fade px-1 py-3 show active">
                    <div class="row">
                        <div class="col-md-8">
                            <form class="form-inline"  action="{{url('administracion_bot')}}">
                                <div class="form-group">
                                    <label for="pregunta" class="sr-only">Pregunta</label>
                                    <input id="pregunta" name="pregunta" type="text" placeholder="Escriba una pregunta"
                                           value="{{isset($pregunta) ? $pregunta: ""}}" class="form-control form-control-sm">
                                </div>
                                <div class="form-group">
                                    <label for="tema" class="sr-only">Tema</label>
                                    <select class="form-control form-control-sm" id="tema" name="tema">
                                        <option value="">Selecione un tema</option>
                                        @foreach($temas as $tema)
                                            <option {{isset($tema_selected) ? ($tema_selected == $tema->id_chat_tema ? 'selected' : '' ) : ""}} value="{{$tema->id_chat_tema}}">{{$tema->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4 text-right">
                            <button class="btn btn-success btn-sm" onclick="add_pregunta()">
                                <i class="fa fa-plus-circle"></i> Crear nueva pregunta
                            </button>
                        </div>
                    </div>
                    <div id="body_preguntas" class="mt-2">
                        @foreach($preguntasRespuestas as $preguntaRespuesta)
                            <div class="card card-body bg-light shadow" style="border-radius: 5px" >
                                <p class="mb-0" style="color:#000000"><i class="fa fa-question-circle"></i> Pregunta: <span style="color: #929191">{{$preguntaRespuesta->pregunta}}</span>
                                    <button title="Eliminar pregunta" onclick="eliminar_pregunta_respuesta('{{$preguntaRespuesta->id_pregunta_respuesta}}')" type="button" class="btn btn-danger btn-sm pull-right"><i class="fa fa-trash"></i></button>
                                    <button title="Editar pregunta" onclick="add_pregunta('{{$preguntaRespuesta->id_pregunta_respuesta}}')" type="button" class="btn btn-warning btn-sm pull-right"><i class="fa fa-pencil"></i></button>
                                </p>
                                <p class="mb-0" style="color:#000000"><i class="fa fa-commenting"></i> Respuesta:
                                    <span style="color: #929191">
                                        @if(isset($preguntaRespuesta->enlace))
                                            <a target="{{isset($preguntaRespuesta->abrir_en) ? "_blank" : "_self"}}" href="{{$preguntaRespuesta->enlace}}"> {{$preguntaRespuesta->respuesta}}</a>
                                        @elseif(isset($preguntaRespuesta->accion))

                                        @else
                                            {{$preguntaRespuesta->respuesta}}
                                        @endif
                                        </span>
                                </p>
                                <p class="mb-0" style="color:#000000"><i class="fa fa-book"></i> Tema:
                                    {{ucfirst($preguntaRespuesta->tema->nombre)}}
                                </p>
                                <p class="mb-0" style="color:#000000"><i class="fa fa-tags"></i> Etiquetas:
                                    @foreach ($preguntaRespuesta->etiqueta_pregunta_respuesta as $etiqueta_pregunta_respuesta)
                                        <span class="pt-1 pb-1 pl-2 pr-2 alert-info" style="border-radius: 10px">
                                            {{$etiqueta_pregunta_respuesta->etiqueta}}
                                        </span>
                                    @endforeach
                                </p>
                            </div>
                        @endforeach
                    </div>

                </div>
                <div id="temas_etiquetas" role="tabpanel" aria-labelledby="home-tab" class="tab-pane fade px-1 py-3">
                    <div class="">
                        <div class="card">
                            <div class="card-header" style="padding: 0.3rem 1.3rem;">
                                <div class="row">
                                    <div class="col-md-11" style="margin-top: 6px;">
                                        <h4>Información</h4>
                                    </div>
                                    <div class="col-md-1 text-right">
                                        <button type="button" class="btn  btn-sm btn-success" title="Crear usuario" onclick="crear_tema()">
                                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Tema</th>
                                            <th class="text-center">Tags</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($temas->count()>0)
                                            @foreach($temas as $tema)
                                                <tr>
                                                    <td class="text-center" style="vertical-align: middle">{{$tema->nombre}}</td>
                                                    <td class="text-center" style="vertical-align: middle">
                                                        @if($tema->etiqueta_chat_tema->count())
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    @foreach($tema->etiqueta_chat_tema as $etiqueta)
                                                                        <div class="bg-primary p-1" style="color: #fff;border: 1px solid #fff;border-radius: 3px">
                                                                            {{$etiqueta->nombre}}
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @else
                                                            No se han guardado etiquetas
                                                        @endif
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn  btn-sm btn-warning"
                                                                    title="Administrar etiquetas" onclick="crear_tema('{{$tema->id_chat_tema}}')">
                                                                <i class="fa fa-pencil"></i>
                                                            </button>
                                                            <button type="button" class="btn  btn-sm btn-default"
                                                                    title="Administrar etiquetas" onclick="add_etiqueta('{{$tema->id_chat_tema}}')">
                                                                <i class="fa fa-comments-o"></i>
                                                            </button>
                                                            <button type="button" class="btn  btn-sm btn-danger"
                                                                    title="Eliminar etiquetas" onclick="delete_tema('{{$tema->id_chat_tema}}')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="text-center alert alert-info" colspan="3">No se ha registrado ningún tema</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End rounded tabs -->
        </div>
    </div>
@endsection
@section('custom_page_js')
    @include('admin_bot.script')
@endsection