@extends('layouts.partials.dashboard')
@section('title')
    Documentos consolidados
@endsection

@section('contenido')
    <div class="col-md-12">
        <div class="row">
            @if($tratamientos->count() > 0)
                @foreach($tratamientos as $t)
                    <div class="col-md-3 col-lg-3 text-center" style="height: 300px;{{$t->imagen === null ? "border:1px solid" : "" }}" title="{{$t->nombre_tratamiento}}">
                        <a href="{{url('documento_consolidado/configuracion',$t->id_tratamiento)}}" style="{{ $t->imagen === null ? "margin: 133px auto" : ""}};text-align: center;width: 100%;">
                            <div>
                                {!! $t->imagen === null ? $t->nombre_tratamiento : "<img class='img-fluid' src='/storage/img_tratamientos/".$t->imagen."'>" !!}
                            </div>
                        </a>
                    </div>
                @endforeach
            @else
                <div class="col-md-12">
                    <div class="alert alert-info text-center" role="alert">
                        No hay tratamientos disponibles por el momento
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('custom_page_js')
    @include('documentos_consolidados.script')
@endsection
