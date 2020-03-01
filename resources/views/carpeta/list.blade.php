@extends('layouts.partials.dashboard')
@section('title')
    Documentos
@endsection

@section('contenido')
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <td style="border: none"></td>
                <td style="border: none"></td>
                <td style="vertical-align: middle;border: none" class="text-center" >
                    <a href="{{url('carpeta/add_carpeta')}}" class="btn btn-sm btn-primary" title="Crear carpeta">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
            <tr>
                <th class="text-center">Nombre de la carpeta</th>
                <th class="text-center">Ruta absoluta</th>
                <th class="text-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
                @if(count($carpetas) > 0 )
                    @foreach($carpetas as $x => $c)
                        @if($x > 1)
                            <tr>
                                <td style="vertical-align: middle" class="text-center">{{strtoupper(str_replace("_"," ",$c))}}</td>
                                <td style="vertical-align: middle" class="text-center">{{storage_path('app/public/archivos/').$c}}</td>
                                <td style="vertical-align: middle" class="text-center">
                                    {{--<a href="{{url('/carpeta/add_carpeta',$c)}}" title="Editar carpeta" class="btn btn-sm btn-warning">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    </a>--}}
                                    <button class="btn btn-sm btn-danger" title="Eliminar carga de archivo" onclick="deleteCarpeta('{{$c}}')">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" style="vertical-align: middle">
                            <div class="alert alert-info text-center" role="alert">
                              No se encontraron carpetas creadas
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection

@section('custom_page_js')
    @include('carpeta.script')
@endsection