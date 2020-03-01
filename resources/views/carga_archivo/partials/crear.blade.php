@extends('layouts.partials.dashboard')
@section('title')
    Crear carga de documento
@endsection

@section('contenido')
    <div class="container p-0">
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="crear_documento" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-actions float-right">
                                    <div class="dropdown show">
                                        <button class="btn-icono btn btn-outline-info btn-sm" title="Seleccionar un ícono para el proceso" onclick="icono_proceso()">
                                            <i class="{{(isset($dataCargaArchivo) && $dataCargaArchivo->icono!="" ) ? $dataCargaArchivo->icono : "fa fa-eye"}}"></i>
                                        </button>
                                        <input type="hidden" id="icono_proceso" name="icono_proceso" value="">
                                        {{--<a href="#" data-toggle="dropdown" data-display="static">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle">
                                                <circle cx="12" cy="12" r="1"></circle>
                                                <circle cx="19" cy="12" r="1"></circle>
                                                <circle cx="5" cy="12" r="1"></circle>
                                            </svg>
                                        </a>--}}
                                    </div>
                                </div>
                                <h5 class="card-title mb-0">Carga de archivo</h5>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" id="form_crear_documento">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nombre">Nombre</label>
                                                <input type="text" class="form-control" id="nombre" value="{{isset($dataCargaArchivo) ? $dataCargaArchivo->nombre : ""}}" name="nombre" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="descripcion">Descripción</label>
                                                <input type="text" class="form-control" id="descripcion"  name="descripcion" value="{{isset($dataCargaArchivo) ? $dataCargaArchivo->descripcion : ""}}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="usuario">¿Quién ejecuta el proceso?</label>
                                                <select id="usuario" name="usuario" class="form-control" required>
                                                    <option selected disabled value=""> Seleccione </option>
                                                    @foreach($roleType as $rt)
                                                        <option {{isset($dataCargaArchivo) ? ($dataCargaArchivo->role_type_id == $rt->role_type_id ? "selected" : "") : ""}}
                                                                value="{{$rt->role_type_id}}">{{$rt->description}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="carpeta">¿En que carpeta se guardaran los archivos?</label>
                                                <select id="carpeta" name="carpeta" class="form-control" required>
                                                    <option selected disabled>Seleccione</option>
                                                    @foreach(scandir(storage_path('app/public/archivos/')) as $x => $c)
                                                        @if($x>1)
                                                            <option {{isset($dataCargaArchivo) ? ($dataCargaArchivo->carpeta == $c ? "selected" : "") : ""}} value="{{$c}}">{{$c}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6" style="margin-bottom: 20px">
                                            <label>Sub proceso de notificación</label>
                                            <select name="notificacion" id="notificacion" class="form-control" style="width: 93%;float: left;">
                                                <option value="">Seleccione</option>
                                                @foreach($notificacion as $n)
                                                    <option {{isset($dataCargaArchivo) ? ($dataCargaArchivo->id_notificacion == $n->id_notificacion ? "selected": "") : "" }} value="{{$n->id_notificacion}}">{{$n->nombre}}</option>
                                                @endforeach
                                            </select>
                                            @if(isset($dataCargaArchivo->id_notificacion))
                                                <div class="input-group-append">
                                                    <a href="{{url('/notificacion/add_notificacion',$dataCargaArchivo->id_notificacion)}}" class="btn btn-info" title="Ver sub proceso de notificación" style="float: right;height: 41px;">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                        {{--<div class="col-md-6" style="margin-bottom: 20px">
                                            <label>Notificar al doctor tratante</label>
                                            <select name="notificacion_doctor" id="notificacion_doctor" class="form-control">
                                                <option value="0" {{isset($dataCargaArchivo) ? ($dataCargaArchivo->notificacion_doctor == false ? "selected" : "") : ""}}>No</option>
                                                <option value="1" {{isset($dataCargaArchivo) ? ($dataCargaArchivo->notificacion_doctor == true ? "selected" : "") : ""}}>Si</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6" style="margin-bottom: 20px">
                                            <label>Comenzar solicitud de tratamiento</label>
                                            <select name="solicitud_tratamiento" id="solicitud_tratamiento" class="form-control">
                                                <option value="0" {{isset($dataCargaArchivo) ? ($dataCargaArchivo->solicitud_tratamiento == false ? "selected" : "") : ""}}>No</option>
                                                <option value="1" {{isset($dataCargaArchivo) ? ($dataCargaArchivo->solicitud_tratamiento == true ? "selected" : "") : ""}}>Si</option>
                                            </select>
                                        </div>--}}
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary" onclick="storeCargaArchivo('{{isset($dataCargaArchivo) ? $dataCargaArchivo->id_carga_archivo : null}}')">
                                            <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_page_js')
    @include('carga_archivo.script')
@endsection