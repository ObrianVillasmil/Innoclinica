@extends('layouts.partials.dashboard')
@section('title')
    Distribución del tratamiento
@endsection
@section('contenido')
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th class="text-center">Tratamiento</th>
                <th class="text-center">Especialidad</th>
                <th class="text-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($tratamiento))
                @foreach($tratamiento as $t)
                    <tr>
                        <td style="vertical-align: middle" class="text-center">{{$t->nombre_tratamiento}}</td>
                        <td style="vertical-align: middle" class="text-center">{{$t->especialidad->nombre}}</td>
                        <td style="vertical-align: middle" class="text-center">
                            <div class="btn-group">
                            <a href="{{url('tratamiento/visualizarTratamiento',$t->id_tratamiento)}}" target="_blank" class="btn btn-sm btn-default" title="Ver formato de distribución del tratamiento">
                                <i class="fa fa-eye"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" title="Eliminar formato" onclick="eliminarFormatoTratamiento('{{$t->id_tratamiento}}')">
                                <i class="fa fa-trash"></i>
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
    @include('distribucion_tratamiento.script')
@endsection