@extends('layouts.partials.dashboard')
@section('title')
    Inicio
@endsection

@section('contenido')

@if(in_array('ADMIN',$usuario->roles()))
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <a style="color:#fff;width:100%;text-decoration: none;" href="{{url('tratamiento')}}">
                <div class="info-box bg-info">
                    <span class="info-box-icon" style="border: 1px solid;border-radius: 40px">
                        <i class="fa fa-medkit"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">
                            Tratamientos </span>
                        <span class="info-box-number">
                            {{getTratamiento(null,true)->count()}}
                        </span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">

                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </a>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
            <a style="color:#fff;width:100%;text-decoration: none;" href="{{url('seguimiento')}}">
                <div class="info-box bg-success">
                    <span class="info-box-icon" style="border: 1px solid;border-radius: 40px">
                        <i class="fa fa-stethoscope" ></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">
                            Solicitados
                        </span>
                        <span class="info-box-number">
                            {{getTratamientoSolicitado(null,null,true)->count()}}
                        </span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">

                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </a>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
            <a style="color:#fff;width:100%;text-decoration: none;" href="{{url('distribucion_inventario')}}">
            <div class="info-box bg-warning">
                <span class="info-box-icon" style="border: 1px solid;border-radius: 40px">
                    <i class="fa fa-cubes" ></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Ver inventario</span>
                    {{--<span class="info-box-number"></span>--}}
                    <div class="progress" style="margin-top: 29px">
                        <div class="progress-bar" style="width: 70%"></div>
                    </div>
                    <span class="progress-description">

                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            </a>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
            <a style="color:#fff;width:100%;text-decoration: none;" href="{{url('alerta')}}">
            <div class="info-box bg-danger">
                <span class="info-box-icon" style="border: 1px solid;border-radius: 40px">
                    <i class="fa fa-bell" ></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Alertas</span>
                    <span class="info-box-number">{{getNotifiacionesActivas()->count()}}</span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            </a>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
@else
    <div class="col-md-12">
        <div class="row">
            @if($tratamientos->count() > 0)
                @foreach($tratamientos as $t)
                    @php
                        $tratamientoSolicitado = getTratamientoSolicitado($t->id_tratamiento,isset($t->party_id) ? $t->party_id : session('party_id'));
                    @endphp
                    <div class="col-md-3 col-lg-3 text-center" style="height: 300px;{{$t->imagen === null ? "border:1px solid" : "" }}" title="{{$t->nombre_tratamiento}}">
                        <a href="{{url('tratamientos_clientes/proceso_tratamiento',[$t->id_tratamiento,isset($tratamientoSolicitado->id_tratamiento_solicitado) ? $tratamientoSolicitado->id_tratamiento_solicitado : null,$t->party_id])}}" style="{{ $t->imagen === null ? "margin: 133px auto" : ""}};text-align: center;width: 100%;">
                            <div>
                                {!! $t->imagen === null ? $t->nombre_tratamiento : "<img class='img-fluid' src='/storage/img_tratamientos/".$t->imagen."'>" !!}
                            </div>
                        </a>
                        <div class="text-success" style="margin-top: 10px">
                            @if((getParty(session('party_id'))->party_role->role_type->role_type_id === "END_USER_CUSTOMER" || getParty(session('party_id'))->party_role->role_type->role_type_id === "ADMIN" || getParty(session('party_id'))->party_role->role_type->role_type_id == "REPRESENTANTE_LEGAL") &&  getTratamientoSolicitado($t->id_tratamiento,session('party_id')) != null)
                                <i class="fa fa-check-square-o"></i> Solicitado
                            @endif
                            @if(getParty(session('party_id'))->party_role->role_type->role_type_id === "MEDICO_USUARIO")
                                Solicitado por {{getParty($t->party_id)->person->first_name ." ". getParty($t->party_id)->person->last_name}}
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-md-12">
                    <div class="alert alert-info text-center" role="alert">
                        @if(getParty(session('party_id'))->party_role->role_type->role_type_id === "ADMIN")
                            No hay tratamientos solcitados por el momento
                        @else
                            No hay tratamientos disponibles por el momento
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

@endif
@endsection
{{--@section('custom_page_js')
    <script>
        render_chat();
    </script>
@endsection--}}