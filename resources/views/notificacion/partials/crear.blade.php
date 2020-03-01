@extends('layouts.partials.dashboard')
@section('title')
    Crear carga de documento
@endsection

@section('contenido')
    <div class="container p-0">
        <div class="row">
            <div class="col-md-3 col-xl-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fa fa-bell" ></i> Notificación
                        </h5>
                    </div>
                    <div class="list-group list-group-flush" role="tablist">
                        <ul style="padding: 0">
                            <li class="list-group-item list-group-item-action">
                                <input id="administrador" name="administrador" {{isset($dataNotificacion) ? ($dataNotificacion->administrador ? "checked" : "") : ""}}
                                type="checkbox" class="form-control-custom">
                                <label for="administrador" style="color: #1b1e21">Al administrador</label>
                            </li>
                            <li class="list-group-item list-group-item-action">
                                <input id="representante_legal" name="representante_legal" {{isset($dataNotificacion) ? ($dataNotificacion->representante_legal ? "checked" : "") : ""}}
                                type="checkbox" class="form-control-custom" >
                                <label for="representante_legal" style="color: #1b1e21">Al representante legal</label>
                            </li>
                            <li class="list-group-item list-group-item-action">
                                <input id="paciente" name="paciente" {{isset($dataNotificacion) ? ($dataNotificacion->paciente ? "checked" : "") : ""}}
                                type="checkbox" class="form-control-custom" >
                                <label for="paciente" style="color: #1b1e21">Al paciente</label>
                            </li>
                            <li class="list-group-item list-group-item-action">
                                <input id="otros" name="otros" type="checkbox" {{isset($dataNotificacion) ? ($dataNotificacion->otros ? "checked" : "") : ""}}
                                class="form-control-custom" onclick="partial_otros(this)">
                                <label for="otros" style="color: #1b1e21">A otros</label>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
            <div class="col-md-9 col-xl-9">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="crear_documento" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-actions float-right">
                                    <div class="dropdown show">
                                        {{--<a href="#" data-toggle="dropdown" data-display="static">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle">
                                                <circle cx="12" cy="12" r="1"></circle>
                                                <circle cx="19" cy="12" r="1"></circle>
                                                <circle cx="5" cy="12" r="1"></circle>
                                            </svg>
                                        </a>--}}
                                        <button class="btn-icono btn btn-outline-info btn-sm"
                                                title="Seleccionar un ícono para el proceso" onclick="icono_proceso()">
                                            <i class="{{(isset($dataNotificacion->icono) && $dataNotificacion->icono!="" ) ? $dataNotificacion->icono : "fa fa-eye"}}"></i>
                                        </button>
                                        <input type="hidden" id="icono_proceso" name="icono_proceso" value="{{isset($dataNotificacion->icono) ? $dataNotificacion->icono : "fa fa-eye"}}">
                                    </div>
                                </div>
                                <h5 class="card-title mb-0">Crear notificación</h5>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" id="form_crear_notificacion">
                                    <div class="form-row">
                                        {{--<div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nombre">Nombre</label>
                                                <input type="text" class="form-control" id="nombre" value="" name="nombre" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="descripcion">Descripción</label>
                                                <input type="text" class="form-control" id="descripcion"  name="descripcion" value="" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="carpeta">Carpeta</label>

                                            </div>
                                        </div>--}}
                                    </div>
                                    <div class="form-row" id="body_notificacion">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nombre">Nombre</label>
                                                <input type="text" class="form-control" id="nombre" value="{{isset($dataNotificacion) ? $dataNotificacion->nombre : ""}}" name="nombre" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tipo_notificacion">Tipo de notificación</label>
                                                <select id="tipo_notificacion" name="tipo_notificacion" class="form-control" onchange="partial_otros(this,true)" required>
                                                    @foreach($tipoNotificacion as $tN)
                                                        <option {{isset($dataNotificacion) ? ($dataNotificacion->tipo_notificacion->id_tipo_notificacion === $tN->id_tipo_notificacion ? "selected" : "") : ""}}
                                                                value="{{$tN->id_tipo_notificacion}}">
                                                            {{$tN->nombre}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row col-md-12" id="body_notificacion_otros">
                                        @if($dataNotificacion != null && $dataNotificacion->otros_notificacion->count() > 0)
                                            <div class="text-right" style="width: 100%">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary" title="Agregar {{$dataNotificacion->otros_notificacion[0]->notificacion->tipo_notificacion->id_tipo_notificacion == 1 ? "correo" : "teléfono"}}" onclick="agregar_input()">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger" title="Quitar {{$dataNotificacion->otros_notificacion[0]->notificacion->tipo_notificacion->id_tipo_notificacion == 1 ? "correo" : "teléfono"}}"  onclick="quitar_input()">
                                                            <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @foreach($dataNotificacion->otros_notificacion as $otrosN)
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{$otrosN->notificacion->tipo_notificacion->id_tipo_notificacion == 1 ? "Correo" : "Teléfono"}}</label>
                                                        <input type="{{$otrosN->notificacion->tipo_notificacion->id_tipo_notificacion == 1 ? "mail" : "text"}}"
                                                               class="form-control {{$otrosN->notificacion->tipo_notificacion->id_tipo_notificacion == 1 ? "mail" : "text"}}"
                                                               value="{{$otrosN->texto}}" required>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="tipo_notificacion">Mensaje</label>
                                                <textarea name="mensaje" rows="5" class="form-control" id="mensaje">{{isset($dataNotificacion) ? $dataNotificacion->mensaje : ""}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary" onclick="storeNotificacion('{{isset($dataNotificacion) ? $dataNotificacion->id_notificacion : null}}')">
                                            <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{--<div class="tab-pane fade show" id="subir_documento" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-actions float-right">
                                    <div class="dropdown show">
                                        <a href="#" data-toggle="dropdown" data-display="static">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle">
                                                <circle cx="12" cy="12" r="1"></circle>
                                                <circle cx="19" cy="12" r="1"></circle>
                                                <circle cx="5" cy="12" r="1"></circle>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <h5 class="card-title mb-0">Cargar un documento</h5>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" id="form_cargar_documento">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nombre">Nombre del documento</label>
                                                <input type="text" class="form-control"  id="nombre_documento" value="" name="nombre_documento" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="descripcion">Descripción</label>
                                                <input type="text" class="form-control" id="descripcion_documento"  name="descripcion_documento" value="" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="documento">Documento</label>
                                            <input type="file" id="documento" accept="application/pdf" name="documento" class="form-control">
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary" onclick="uploadDocumento()">
                                            <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>--}}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_page_js')
    @include('notificacion.script')
@endsection