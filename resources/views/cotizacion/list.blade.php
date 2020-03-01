@extends('layouts.partials.dashboard')
@section('title')
    Cotización de tratamiento
@endsection

@section('contenido')
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th class="text-center">Tratamiento</th>
                <th class="text-center">Especialidad</th>
                <th class="text-center">Estado</th>
                <th class="text-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @if($tratamientos->count() > 0)
                @foreach($tratamientos as $tratamiento)
                    <tr>
                        <td style="vertical-align: middle" class="text-center">{{$tratamiento->nombre_tratamiento}}</td>
                        <td style="vertical-align: middle" class="text-center">{{$tratamiento->especialidad->nombre}}</td>
                        <td style="vertical-align: middle" class="text-center">{{$tratamiento->estado ? "Activo" : "Inactivo"}}</td>
                        <td style="vertical-align: middle" class="text-center">
                            <button class="btn btn-sm btn-success" title="Cotizar tratamiento" onclick="cotizar('{{$tratamiento->id_tratamiento}}')">
                                <i class="fa fa-money"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" style="vertical-align: middle">
                        <div class="alert alert-info text-center" role="alert">
                          No se encontraron tratamientos para cotizar
                        </div>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>

@endsection
@section('custom_page_js')
    @include('cotizacion.script')
@endsection