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
                <td style="border: none"></td>
                <td style="border: none"></td>
                <td style="vertical-align: middle;border: none" class="text-center" >
                    <a href="{{url('carga_archivo/add_carga_archivo')}}" class="btn btn-sm btn-primary" title="Crear carga de archivo">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
            <tr>
                <th class="text-center">Nombre proceso</th>
                <th class="text-center">Descripción del proceso</th>
                <th class="text-center" style="cursor: pointer" title="Ruta: {{storage_path('app/public/archivos/')}}">Carpeta <i class="fa fa-question" ></i></th>
                <th class="text-center">Usuario</th>
                <th class="text-center">Tratamientos</th>
                <th class="text-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @if($cargaArchivo->count() >0)
                @foreach($cargaArchivo as $cA)
                    <tr>
                        <td style="vertical-align: middle" class="text-center">{{$cA->nombre}}</td>
                        <td style="vertical-align: middle" class="text-center">{{$cA->descripcion}}</td>
                        <td style="vertical-align: middle" class="text-center">{{$cA->carpeta}}</td>
                        <td style="vertical-align: middle" class="text-center">{{$cA->party_role->description}}</td>
                        <td style="vertical-align: middle" class="text-center">
                            @php
                                $tratamientos = "";
                                foreach ($cA->tratamiento() as $item)
                                    $tratamientos .= "<a href='/tratamiento/add_procesos_tratamiento/".$item->id_tratamiento."'>".getTratamiento($item->id_tratamiento)->nombre_tratamiento."</a> / ";
                            @endphp
                            {!! substr($tratamientos,0,-2) !!}
                        </td>
                        <td style="vertical-align: middle" class="text-center">
                            <div class="btn-group">
                                <a href="{{url('carga_archivo/add_carga_archivo',$cA->id_carga_archivo)}}" title="Editar carga de archivo" class="btn btn-sm btn-warning">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" title="Eliminar carga de archivo" onclick="deleteCargaArchivo('{{$cA->id_carga_archivo}}')">
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
                          No se encontraron micro procesos de carga de archivos
                        </div>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
@endsection

@section('custom_page_js')
    @include('carga_archivo.script')
@endsection