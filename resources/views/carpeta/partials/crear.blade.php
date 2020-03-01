@extends('layouts.partials.dashboard')
@section('title')
    Crear carga de documento
@endsection

@section('contenido')
    <div class="container p-0">
        <div class="row">
            {{--<div class="col-md-4 col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fa fa-cogs" aria-hidden="true"></i> Opciones
                        </h5>
                    </div>
                    <div class="list-group list-group-flush" role="tablist">
                        <ul style="padding: 0">
                            <li class="list-group-item list-group-item-action">
                                <input id="register-agree" name="registerAgree" type="checkbox" required value="1" data-msg="Acepte los terminos y condiciones" class="form-control-custom">
                                <label for="register-agree">Crear Documento</label>
                            </li>
                            <li class="list-group-item list-group-item-action">
                                <input id="register-agree" name="registerAgree" type="checkbox" required value="1" data-msg="Acepte los terminos y condiciones" class="form-control-custom">
                                <label for="register-agree">Crear Documento</label>
                            </li>
                            <li class="list-group-item list-group-item-action">
                                <input id="register-agree" name="registerAgree" type="checkbox" required value="1" data-msg="Acepte los terminos y condiciones" class="form-control-custom">
                                <label for="register-agree">Crear Documento</label>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>--}}
            <div class="col-md-12 col-xl-12">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="crear_carpeta" role="tabpanel">
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
                                <h5 class="card-title mb-0">Crear carpeta</h5>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" id="form_crear_carpeta">
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="nombre_carpeta">Nombre de la carpeta</label>
                                                <input type="text" class="form-control" id="nombre_carpeta" value="{{isset($carpeta) ? str_replace("_"," ",$carpeta) : "" }}" name="nombre_carpeta" required>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary" onclick="storeCarpeta('{{isset($carpeta) ? $carpeta : "" }}')">
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
    @include('carpeta.script')
@endsection