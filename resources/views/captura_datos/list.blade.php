@extends('layouts.partials.dashboard')
@section('title')
    Captura de datos
@endsection

@section('contenido')
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <td style="border: none"></td>
                <td style="border: none"></td>
                <td style="border: none"></td>
                <td style="border: none"></td>
                <td style="vertical-align: middle;border: none" class="text-center" >
                    <a href="{{url('captura_dato/add_captura_dato')}}" class="btn btn-sm btn-primary" title="Crear captura de datos">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
            <tr>
                <th class="text-center">Nombre proceso</th>
                <th class="text-center">Descripción del proceso</th>
                <th class="text-center">Usuario</th>
                <th class="text-center">Tratamientos</th>
                <th class="text-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @if($capturaDatos->count() > 0)
                @foreach($capturaDatos as $cD)
                    <tr>
                        <td style="vertical-align: middle" class="text-center">{{$cD->nombre}}</td>
                        <td style="vertical-align: middle" class="text-center">{{$cD->descripcion}}</td>
                        <td style="vertical-align: middle" class="text-center">{{$cD->party_role->description}}</td>
                        <td style="vertical-align: middle" class="text-center">
                            @php
                                $tratamientos = "";
                                foreach ($cD->tratamiento() as $item)
                                    $tratamientos .= "<a href='/tratamiento/add_procesos_tratamiento/".$item->id_tratamiento."'>".getTratamiento($item->id_tratamiento)->nombre_tratamiento."</a> / ";
                            @endphp
                            {!! substr($tratamientos,0,-2) !!}
                        </td>
                        <td style="vertical-align: middle" class="text-center">
                            <div class="btn-group">
                                <a href="{{url('captura_dato/add_captura_dato',$cD->id_captura_dato)}}" title="Editar captura de datos" class="btn btn-sm btn-warning">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" title="Eliminar captura de datos" onclick="deleteCapturaDatos('{{$cD->id_captura_dato}}')">
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
                          No se encontraron procesos de captura de datos
                        </div>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>

@endsection
@section('custom_page_js')
    @include('captura_datos.script')
@endsection