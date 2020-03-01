@extends('layouts.partials.dashboard')
@section('title')
    Tratamientos
@endsection

@section('contenido')
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <td style="border: none"></td>
                    <td style="border: none"></td>
                    <td style="vertical-align: middle;border: none" class="text-center" >
                        <form method="GET" id="form_tratamiento" action="{{action('TratamientoController@inicio')}}">
                            <label for="esatdo">Estado</label>
                            <select id="estado" name="estado" style="height: 31px;width: 100px;position: relative;top: 1px;" onchange="document.getElementById('form_tratamiento').submit()">
                                <option value="1" {{$estado == 1 ? "selected" : ""}}>Activo</option>
                                <option value="0" {{$estado == 0 ? "selected" : ""}}>Inactivo</option>
                            </select>
                            <a href="{{url('/tratamiento/add_tratamiento')}}" class="btn btn-sm btn-primary" title="Crear tratamiento">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            </a>
                        </form>
                    </td>
                </tr>
                <tr>
                    <th class="text-center">Nombre del tratamiento</th>
                    <th class="text-center">Especialidad</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if(count($trataminetos) >0)
                    @foreach($trataminetos as $t)
                        <tr>
                            <td style="vertical-align: middle" class="text-center">{{$t->nombre_tratamiento}}</td>
                            <td style="vertical-align: middle" class="text-center"> {{$t->especialidad->nombre}}</td>
                            <td style="vertical-align: middle" class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-warning" title="Distribución del tratamiento"
                                            onclick="form_distribucion_tratamiento('{{$t->id_tratamiento}}','{{$t->nombre_tratamiento}}')">
                                        <i class="fa fa-file-text-o"></i>
                                    </button>
                                    <a href="{{url('/tratamiento/add_procesos_tratamiento',$t->id_tratamiento)}}" title="Procesos del tratamiento" class="btn btn-sm btn-primary"
                                       onclick="">
                                        <i class="fa fa-cogs"></i>
                                    </a>
                                    <a href="{{url('/tratamiento/add_tratamiento',$t->id_tratamiento)}}" title="Editar tratamiento" class="btn btn-sm btn-warning"
                                       onclick="">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    </a>
                                    <button class="btn btn-sm btn-{{$t->estado == 1 ? "default" : "primary"}}" title="{{$t->estado == 1 ? "Descativar" : "Activar"}} el tratamiento"
                                            onclick="update_estado_trtamiento('{{$t->id_tratamiento}}','{{$t->estado}}')">
                                        <i class="fa fa-{{$t->estado == 1 ? "ban" : "check"}}" aria-hidden="true"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" title="Eliminar tratamiento"
                                            onclick="delete_trtamiento('{{$t->id_tratamiento}}')">
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
                                  No se encontraron tratamientos creados
                                </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
@section('custom_page_js')
    @include('tratamiento.script')
@endsection