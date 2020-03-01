@extends('layouts.partials.dashboard')
@section('title')
    Alertas
@endsection

@section('contenido')
    <div class="container p-0">
        <div class="row">
            <div class="col-md-4 col-xl-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fa fa-bell-o"></i> Alertas
                        </h5>
                    </div>
                    <div class="list-group list-group-flush" role="tablist">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#notificacion" role="tab">
                            <i class="fa fa-exclamation-circle"></i> Notificaciones
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#tratamientos_solicitados" role="tab">
                            <i class="fa fa-stethoscope"></i> Tratamientos solicitados
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#sesion_usuario" role="tab">
                            <i class="fa fa-user-circle-o"></i> Sesiones de usuario
                        </a>

                    </div>
                </div>
            </div>
            <div class="col-md-8 col-xl-9">
                <div class="tab-content">
                    @include('alerta.partials.notificaciones')
                    @include('alerta.partials.tratamiento')
                    @include('alerta.partials.sesion_usuario')
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_page_js')
    @include('alerta.script')
@endsection