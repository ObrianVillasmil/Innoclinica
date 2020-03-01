@extends('layouts.partials.dashboard')
@section('title')
    Captura de datos
@endsection

@section('contenido')
    <div class="container p-0">
        <div class="row">
            <div class="col-md-3 col-xl-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fa fa-cogs"></i> Opciones
                        </h5>
                    </div>
                    <div class="list-group list-group-flush" role="tablist">
                        <ul style="padding: 0">
                            <li class="list-group-item list-group-item-action pointer" onclick="crearInput('doctor')">
                                <i class="fa fa-user-md"></i>
                                <label class="pointer" style="color: #1b1e21">Nombre del doctor</label>
                            </li>
                            <li class="list-group-item list-group-item-action pointer" onclick="crearInput('texto')">
                                <i class="fa fa-text-width" class="pointer"></i>
                                <label class="pointer" style="color: #1b1e21">Campo de texto</label>
                            </li>
                            <li class="list-group-item list-group-item-action pointer" onclick="crearInput('mail')">
                                <i class="fa fa-envelope-o pointer" ></i>
                                <label style="color: #1b1e21" class="pointer">Campo de correo electrónico</label>
                            </li>
                            <li class="list-group-item list-group-item-action pointer" onclick="crearInput('tlf')">
                                <i class="fa fa-phone-square pointer"></i>
                                <label class="pointer" style="color: #1b1e21">Campo de teléfono</label>
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
                                        <button class="btn-icono btn btn-outline-info btn-sm"
                                                title="Seleccionar un ícono para el proceso" onclick="icono_proceso()">
                                            <i class="{{(isset($dataCapturaDato) && $dataCapturaDato->icono !="") ? $dataCapturaDato->icono : "fa fa-eye"}}"></i>
                                        </button>
                                        <input type="hidden" id="icono_proceso" name="icono_proceso" value="{{isset($dataCapturaDato) ? $dataCapturaDato->icono : ""}}">
                                        {{--<a href="#" data-toggle="dropdown" data-display="static">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle">
                                                <circle cx="12" cy="12" r="1"></circle>
                                                <circle cx="19" cy="12" r="1"></circle>
                                                <circle cx="5" cy="12" r="1"></circle>
                                            </svg>
                                        </a>--}}
                                    </div>
                                </div>
                                <h5 class="card-title mb-0">Crear captura de datos del doctor</h5>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" id="form_crear_notificacion">
                                    <div class="row" id="body_form_captura_datos">
                                        <div class="form-group col-md-4">
                                            <label>Nombre del proceso</label>
                                            <div class="input-group">
                                                <input type="text" id="nombre" name="nombre" class="form-control" value="{{isset($dataCapturaDato) ? $dataCapturaDato->nombre : ""}}" required>
                                            </div>
                                        </div>
                                        {{--<div class="form-group col-md-4">
                                            <label>Comieza la solicutd del tratamiento</label>
                                            <div class="input-group">
                                                <select id="solcitiud_tratamiento" name="solcitiud_tratamiento" class="form-control" required>
                                                    <option value="1" {{isset($dataCapturaDato->solicitud_tratamiento) ? ($dataCapturaDato->solicitud_tratamiento ? "selected" : "") : "" }}>Sí</option>
                                                    <option value="0" {{isset($dataCapturaDato->solicitud_tratamiento) ? ($dataCapturaDato->solicitud_tratamiento ? "" : "selected") : "" }}>No</option>
                                                </select>
                                            </div>
                                        </div>--}}
                                        <div class="form-group col-md-4">
                                            <label for="usuario">¿Quién ejecuta el proceso?</label>
                                            <select id="usuario" name="usuario" class="form-control" required>
                                                <option selected disabled value=""> Seleccione </option>
                                                @foreach($roleType as $rt)
                                                    <option {{isset($dataCapturaDato) ? ($dataCapturaDato->role_type_id == $rt->role_type_id ? "selected" : "") : ""}}
                                                            value="{{$rt->role_type_id}}">{{$rt->description}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @php $issetDoctor = isset($dataCapturaDato) ? $dataCapturaDato->detalle_captura_dato()->where('doctor',true)->first() : null; @endphp
                                        <div class="form-group col-md-4 notificar_doctor {{isset($issetDoctor) ? ($issetDoctor->notifica_doctor) ? "d-none" : "" : "d-none"}}">
                                            <label>Notificar al doctor</label>
                                            <div class="input-group">
                                                <select id="notificar_doctor" name="notificar_doctor" class="form-control" required>
                                                    <option value="0" {{isset($dataCapturaDato->notifica_doctor) ? ($dataCapturaDato->notifica_doctor ? "" : "selected") : "" }}>No</option>
                                                    <option value="1" {{isset($dataCapturaDato->notifica_doctor) ? ($dataCapturaDato->notifica_doctor ? "selected" : "") : "" }}>Sí</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group {{isset($issetDoctor) ? (!$issetDoctor->doctor) ? "col-md-12" : "col-md-8" : "col-md-12"}}  descripcion_proceso">
                                            <label>Descripción del proceso</label>
                                            <div class="input-group">
                                                <input type="text" id="descripcion" name="descripcion" class="form-control" value="{{isset($dataCapturaDato) ? $dataCapturaDato->descripcion : ""}}" required>
                                            </div>
                                        </div>
                                        @if(isset($dataCapturaDato))
                                            @foreach($dataCapturaDato->detalle_captura_dato as $dcd)
                                                @if($dcd->doctor)
                                                    <div class="form-group col-md-6 div_texto_doctor {{$dcd->id_campo}}" id="{{$dcd->id_campo}}" >
                                                        <label>Nombre del doctor</label>
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-light">
                                                                    <input id="text_doctor_requerido_{{explode("_",$dcd->id_campo)[3]}}" {{$dcd->requerido ? "checked" : ""}} type="checkbox" value=""readonly class="form-control-custom">
                                                                    <label for="text_doctor_requerido_{{explode("_",$dcd->id_campo)[3]}}" title="Hacer que el campo sea obligatorio" style="bottom: 12px;"></label>
                                                                </button>
                                                            </div>
                                                            <input type="text" id="campo_texto_doctor_{{explode("_",$dcd->id_campo)[3]}}" readonly name="campo_texto_doctor_{{explode("_",$dcd->id_campo)[2]}}" placeholder="Nombre doctor" class="form-control">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-danger" title="Eliminar campo" onclick="deleteCampo('{{$dcd->id_campo}}')">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($dcd->tlf)
                                                    <div class="form-group col-md-6 div_tlf {{$dcd->id_campo}}" id="{{$dcd->id_campo}}">
                                                        <label>Teléfono</label>
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-light">
                                                                    <input id="tlf_requerido_{{explode("_",$dcd->id_campo)[2]}}" {{$dcd->requerido ? "checked" : ""}} type="checkbox" class="form-control-custom">
                                                                    <label for="tlf_requerido_{{explode("_",$dcd->id_campo)[2]}}" title="Hacer que el campo sea obligatorio" style="bottom: 12px;"></label>
                                                                </button>
                                                            </div>
                                                            <input type="tel" id="campo_tlf_{{explode("_",$dcd->id_campo)[2]}}" readonly name="campo_tlf_{{explode("_",$dcd->id_campo)[2]}}" placeholder="Teléfono" class="form-control">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-danger" title="Eliminar campo" onclick="deleteCampo('{{$dcd->id_campo}}')">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($dcd->mail)
                                                    <div class="form-group col-md-6 div_mail {{$dcd->id_campo}}" id=" {{$dcd->id_campo}}" >
                                                        <label>Correo electrónico</label>
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-light">
                                                                    <input id="mail_requerido_{{explode("_",$dcd->id_campo)[2]}}" type="checkbox"  {{$dcd->requerido ? "checked" : ""}}  value="" readonly class="form-control-custom">
                                                                    <label for="mail_requerido_{{explode("_",$dcd->id_campo)[2]}}" title="Hacer que el campo sea obligatorio" style="bottom: 12px;"></label>
                                                                </button>
                                                            </div>
                                                            <input type="mail" id="campo_mail_{{explode("_",$dcd->id_campo)[2]}}" readonly name="campo_mail_{{explode("_",$dcd->id_campo)[2]}}" placeholder="Correo" class="form-control">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-danger" title="Eliminar campo" onclick="deleteCampo('{{$dcd->id_campo}}')">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($dcd->texto)
                                                    <div class="form-group col-md-6 div_texto {{$dcd->id_campo}}" id="{{$dcd->id_campo}}" >
                                                        <label>{{$dcd->label}}</label>
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-light">
                                                                    <input id="text_requerido_{{explode("_",$dcd->id_campo)[2]}}" type="checkbox" {{$dcd->requerido ? "checked" : ""}} value="" readonly class="form-control-custom">
                                                                    <label for="text_requerido_{{explode("_",$dcd->id_campo)[2]}}" title="Hacer que el campo sea obligatorio" style="bottom: 12px;"></label>
                                                                </button>
                                                            </div>
                                                            <input type="text" id="campo_texto_{{explode("_",$dcd->id_campo)[2]}}" name="campo_texto_{{explode("_",$dcd->id_campo)[2]}}"
                                                                   value="{{$dcd->label}}" placeholder="Label" class="form-control" required>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-danger" title="Eliminar campo" onclick="deleteCampo('{{$dcd->id_campo}}')">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                        <label class="alert alert-info text-center {{isset($dataCapturaDato) ? "d-none" : ""}}" style="width: 100%;">
                                            Seleccione los campos a crear
                                        </label>
                                    </div>
                                    <div class="text-center {{!isset($dataCapturaDato) ? "d-none" : ""}} btn_store_form_captura_datos">
                                        <button type="button" class="btn btn-primary" onclick="storeCapturaDatos('{{isset($dataCapturaDato->id_captura_dato) ? $dataCapturaDato->id_captura_dato : ""}}')">
                                            <i class="fa fa-floppy-o"></i> Guardar
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
    @include('captura_datos.script')
@endsection