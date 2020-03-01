@extends('layouts.partials.dashboard')
@section('title')
    Procesos tratamiento
@endsection

@section('contenido')
    <div class="container" id="myWizard">
        <h3>Pasos</h3>
        @if($procesos->count() > 0)
            <div class="progress">
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="5" style="width: 20%;">

                    <span>Paso 1 de {{$procesosByUser}}</span>
                </div>
            </div>
            <div class="navbar bg-light" style="border-bottom: 1px solid #def0f8;padding:0.5rem 0rem;">
              <hr style="top: 26px;bottom: 0;position: absolute; width: 100%; height: 0.2px; background-color: #eaeaea;"/>
                <div class="navbar-inner">
                    <ul class="nav nav-pills">
                        @php $autx = 0; $auty =  0; @endphp
                        @foreach($procesos as $x => $p)
                            @php
                                $dataProceso = dataProcesoTratamiento($p);
                                $pasoActual = getTratamientoSolicitado($idTratamiento,$usuario->party_id);

                                if(!isset($pasoActual))
                                    $pasoActual = getIntervinienteTratamientoSolicitado($idTratamiento,$usuario->party_id);

                                if(!isset($dataProceso['role_type_id']) && !isset($dataProceso['id_especialidad']))
                                    $dataProceso->put('role_type_id',"MEDICO_USUARIO");

                                if($usuario->party_role->role_type->role_type_id === "REPRESENTANTE_LEGAL" || $usuario->party_role->role_type->role_type_id === "END_USER_CUSTOMER"){
                                    $arrRol = ["REPRESENTANTE_LEGAL","END_USER_CUSTOMER"];
                                }else{
                                    $arrRol = [$usuario->party_role->role_type->role_type_id];
                                }
                            @endphp
                            @if(isset($dataProceso['role_type_id']))
                                @if(in_array($dataProceso['role_type_id'],$arrRol))
                                    @php $x = $autx; @endphp
                                    <li style="padding: 0px 10px;margin-top: 5px;" class="{{$x == 0 ? "active" : ""}} text-center" title="{{isset($dataProceso['descripcion']) ? $dataProceso['descripcion'] : ""}}">
                                        <a {{--data-toggle="tab"--}} href="#step{{$x+1}}" data-step="{{$x+1}}"
                                            style="text-decoration: none;width: 60px;height: 60px;text-align: center;border-radius: 50%;padding: 15px;position: relative;z-index: 1000;"
                                            class="btn btn-info tab @if($pasoActual==null) {{$x == 0 ? "active show" : ""}} @else {{($x+1) == $pasoActual->proceso_actual ? "active show" : ""}} @endif  ">
                                            <i class="{{(isset($dataProceso['icono']) && $dataProceso['icono'] != "") ? "fa-2x ".$dataProceso['icono']  : "fa fa-2x fa-cog"}}"></i>
                                        </a>
                                        <div style="font-size: 14px">{{isset($dataProceso['nombre']) ? $dataProceso['nombre'] : "Distribución del tratamiento". getTratamiento($idTratamiento)->nombre_tratamiento}}</div>
                                    </li>
                                    @php $autx++; @endphp
                                @endif
                            @else
                                @php $x = $autx; @endphp
                                <li style="padding: 0px 10px;margin-top: 5px;" class="{{$x == 0 ? "active" : ""}} text-center" title="Cotización del tratamiento">
                                    <a href="#step{{$x+1}}" data-step="{{$x+1}}"
                                       style="text-decoration: none;width: 60px;height: 60px;text-align: center;border-radius: 50%;padding: 15px 0px;position: relative;z-index: 1000;"
                                       class="btn btn-info tab @if($pasoActual==null) {{$x == 0 ? "active show" : ""}} @else {{($x+1) == $pasoActual->proceso_actual ? "active show" : ""}} @endif  ">
                                        <i class="fa fa-2x fa fa-money"></i>
                                    </a>
                                    <div style="font-size: 14px">{{"Cotización". getTratamiento($idTratamiento)->nombre_tratamiento}}</div>
                                </li>
                                @php $autx++; @endphp
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="tab-content">
                @foreach($procesos as $y => $p)
                    @php
                        $a=0;
                        $dataProceso = collect(dataProcesoTratamiento($p));
                        $pasoActual = getTratamientoSolicitado($idTratamiento,$usuario->party_id);
                        if(!isset($pasoActual) && isset(getTratamientoSolicitado($idTratamiento,$partyIdSolicitante)->id_tratamiento_solicitado))
                            $pasoActual = getIntervinienteTratamientoSolicitado(getTratamientoSolicitado($idTratamiento,$partyIdSolicitante)->id_tratamiento_solicitado,$usuario->party_id);

                        if(!isset($dataProceso['role_type_id']) && !isset($dataProceso['id_especialidad']))
                            $dataProceso->put('role_type_id',"MEDICO_USUARIO");

                        if($usuario->party_role->role_type->role_type_id === "REPRESENTANTE_LEGAL" || $usuario->party_role->role_type->role_type_id === "END_USER_CUSTOMER"){
                            $arrRol = ["REPRESENTANTE_LEGAL","END_USER_CUSTOMER"];
                        }else{
                            $arrRol = [$usuario->party_role->role_type->role_type_id];
                        }
                    @endphp


                    @if((!isset($dataProceso['role_type_id']) && isset($dataProceso['id_especialidad'])) || (in_array($dataProceso['role_type_id'],$arrRol)))
                        @php $y = $auty; @endphp
                        <div id="step{{$y+1}}" class="tab-pane
                             @if($pasoActual==null)
                                {{$y == 0 ? "fade in active show" : ""}}
                                @php $checked = null @endphp
                             @else
                                @php
                                    if(($y+1) <= $pasoActual->proceso_actual)
                                        $checked = "checked";
                                    else
                                        $checked = "";
                                @endphp
                                {{ isset($pasoActual->proceso_actual) ? (($y+1) == $pasoActual->proceso_actual ? "active show" : "") : ""}}
                             @endif
                                ">
                            @if(!isset($dataProceso['id_especialidad']))
                                <div>
                                    {{isset($dataProceso['descripcion']) ? "Descripción: ".$dataProceso['descripcion'] :  "Distribución del tratamiento ".getTratamiento($idTratamiento)->nombre_tratamiento}}
                                </div>
                            @endif
                            @if($p->sub_menu->path === 'documento' && $dataProceso['cuerpo'] != "")
                                @include('tratamiento_cliente.partials.documento.descarga_formato_documento')
                            @elseif($p->sub_menu->path === 'documento' && $dataProceso['archivo'] != "")
                                @include('tratamiento_cliente.partials.documento.documento_pdf')
                            @elseif($p->sub_menu->path === 'carga_archivo')
                                @include('tratamiento_cliente.partials.carga_archivo.cargar_archivo')
                            @elseif($p->sub_menu->path === 'detalle_tratamiento')
                                @include('tratamiento_cliente.partials.distribucion_tratamiento.distribucion')
                            @elseif($p->sub_menu->path === 'captura_dato')
                                @include('tratamiento_cliente.partials.captura_dato.captura_dato')
                            @elseif(isset($dataProceso['id_especialidad']))
                                @if(isset($prodcuto))
                                    @include('cotizacion.partials.cotizador')
                                @else
                                    <div class="alert alert-info text-center">
                                      No se ha guardado la distribución del tratamiento para poder cotizarlo
                                    </div>
                                @endif
                            @endif
                            <form id="form_leido_{{$y+1}}">
                                    <div class="row">
                                <div class="col-md-6" style="margin-top: 15px">
                                    <input id="leido_{{$y+1}}" {{$checked}} name="leido_{{$y+1}}" type="checkbox" required
                                           value="1" data-msg="Haga click en esta casilla para verificar completó este paso"
                                           class="form-control-custom check_completo">
                                    <label for="leido_{{$y+1}}" id="label_leido_{{$y+1}}">He completado este paso</label>
                                </div>
                                <div class="col-md-6 text-right">
                                    @if($y > 0)
                                        <a class="btn btn-primary btn-lg back " href="#" disabled="true"> <i class="fa fa-arrow-left" ></i> Atras</a>
                                    @endif
                                    <a class="btn btn-primary btn-lg next" id="btn_siguiente" href="#" disabled="true">Siguiente <i class="fa fa-arrow-right" ></i></a>
                                </div>
                                    </div>
                            </form>
                        </div>
                        @php $auty++; @endphp
                    @endif

                @endforeach
            </div>

        @else
            <div class="alert alert-danger" role="alert">
                El tratamiento no está disponible por el momento
            </div>
        @endif
    </div>
<script>

    @isset($pasoActual->id_tratamiento_solicitado)
        $(function () {
            step = '{{$pasoActual->proceso_actual}}';
            percent = (parseInt(step) / '{{$procesosByUser}}') * 100;
            $('.progress-bar').css({width: percent + '%'});
            $('.progress-bar').text("Paso " + step + " de "+ '{{$procesosByUser}}');
        });
    @endisset

    $('a.tab').on('shown.bs.tab', function (e) {

        console.log('{{$procesosByUser}}');
        step = $(e.target).data('step');
        percent = (parseInt(step) / '{{$procesosByUser}}') * 100;
        console.log(percent );
        $('.progress-bar').css({width: percent + '%'});
        console.log('{{$procesosByUser}}');
        $('.progress-bar').text("Paso " + step + " de "+ '{{$procesosByUser}}');

        //e.relatedTarget // previous tab

    });

    $('.first').click(function(){ $('#myWizard a:first').tab('show') });
</script>
@endsection
@section('custom_page_js')
    @include('tratamiento_cliente.script')
@endsection