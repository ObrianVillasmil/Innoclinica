@extends('layouts.partials.dashboard')
@section('title')
    Tratamientos
@endsection
@section('contenido')
    <div class="col-md-12">
        <div class="row">
            @if($tratamientos->count() > 0)
                @foreach($tratamientos as $t)
                    <div class="col-md-3 col-lg-3 text-center" style="height: 300px;{{$t->tratamiento->imagen === null ? "border:1px solid" : "" }}" title="{{$t->tratamiento->nombre_tratamiento}}">
                        <a href="{{url('seguimiento/seguimineto_tratamiento',[$t->id_tratamiento,$t->party_id])}}" style="{{ $t->tratamiento->imagen === null ? "margin: 133px auto" : ""}};text-align: center;width: 100%;">
                            <div>
                                {!! $t->tratamiento->imagen === null ? $t->tratamiento->nombre_tratamiento : "<img class='img-fluid' src='/storage/img_tratamientos/".$t->tratamiento->imagen."'>" !!}
                            </div>
                        </a>
                        <span style="margin-top: 10px">Solicitado por {{getParty($t->party_id)->person->first_name ." ". getParty($t->party_id)->person->last_name}}</span>
                    </div>
                @endforeach
            @else
                <div class="col-md-12">
                    <div class="alert alert-info text-center" role="alert">
                        No hay tratamientos solcitados por el momento
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('custom_page_js')
    @include('seguimiento.script')
@endsection