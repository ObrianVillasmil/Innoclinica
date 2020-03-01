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
                <td style="border: none"></td>
                <td style="vertical-align: middle;border: none" class="text-center" >
                    <a href="{{url('documento/add_documento')}}" class="btn btn-sm btn-primary" title="Crear documento">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
            <tr>
                <th class="text-center">Nombre</th>
                <th class="text-center">Descripción</th>
                <th class="text-center">Tratmiento</th>
                <th class="text-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @if($documentos->count() >0)
                @foreach($documentos as $d)
                    <tr>
                        <td style="vertical-align: middle" class="text-center">{{$d->nombre}}</td>
                        <td style="vertical-align: middle" class="text-center">{{$d->descripcion}}</td>
                        <td style="vertical-align: middle" class="text-center">
                            @php
                                $tratamientos = "";
                                foreach ($d->tratamiento() as $item)
                                    $tratamientos .= "<a href='/tratamiento/add_procesos_tratamiento/".$item->id_tratamiento."'>".getTratamiento($item->id_tratamiento)->nombre_tratamiento."</a> / ";
                            @endphp
                            {!! substr($tratamientos,0,-2) !!}
                        </td>
                        <td style="vertical-align: middle" class="text-center">
                            <div class="btn-group">
                                @if($d->cuerpo != "")
                                    <a target="_blank" href="{{url('documento/ver_documento',$d->id_documento)}}" title="Ver documento" class="btn btn-sm btn-default">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                @else
                                    <a target="_blank" href="{{Storage::disk('documentos')->url($d->archivo)}}" title="Ver documento" class="btn btn-sm btn-default">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                @endif

                                <a href="{{url('documento/editar_documento',[$d->id_documento,$d->cuerpo != "" ? 1 : 0])}}" title="Editar documento" class="btn btn-sm btn-warning">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" title="Eliminar documento" onclick="eliminarDocumento('{{$d->id_documento}}')">
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
                          No se encontraron documentos creados
                        </div>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
@endsection

@section('custom_page_js')
    @include('documento.script')
@endsection