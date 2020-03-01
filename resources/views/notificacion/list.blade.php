@extends('layouts.partials.dashboard')
@section('title')
    Notificaciones
@endsection

@section('contenido')
    @extends('layouts.partials.dashboard')
@section('title')
    Cargar archivo
@endsection

@section('contenido')
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <td style="border: none"></td>
                <td style="border: none"></td>
                <td style="border: none"></td>
                <td style="vertical-align: middle;border: none" class="text-center" >
                    <a href="{{url('/notificacion/add_notificacion')}}" class="btn btn-sm btn-primary" title="Crear carga de archivo">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
            <tr>
                <th class="text-center">Nombre</th>
                <th class="text-center">Tipo notificación</th>
                <th class="text-center" style="cursor: pointer"> Notificacion a:</th>
                <th class="text-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @if(count($notificaciones) >0)
                @foreach($notificaciones as $cA)
                    <tr>
                        <td style="vertical-align: middle" class="text-center">{{$cA->nombre}}</td>
                        <td style="vertical-align: middle" class="text-center">{{$cA->tipo_notificacion->nombre}}</td>
                        <td style="vertical-align: middle" class="text-center">
                            {{$cA->administrador == true ? "Administrador | " : ""}}
                            {{$cA->representante_legal == true ? "Representante legal | " : ""}}
                            {{$cA->paciente == true ? "Paciente | " : ""}}
                            {!! $cA->otros == true ? "Otros" : "" !!}
                        </td>
                        <td style="vertical-align: middle" class="text-center">
                            <div class="btn-group">
                                <a href="{{url('/notificacion/add_notificacion',$cA->id_notificacion)}}" title="Editar carga de archivo" class="btn btn-sm btn-warning"
                                onclick="">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" title="Eliminar carga de archivo"
                                    onclick="delete_notificacion('{{$cA->id_notificacion}}')">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" style="vertical-align: middle">
                        <div class="alert alert-info text-center" role="alert">
                          No se encontraron notificaciones
                        </div>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
@endsection

@section('custom_page_js')
    @include('notificacion.script')
@endsection
