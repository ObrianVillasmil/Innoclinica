@php

//dd($idTratamiento,$partyIdSolicitante);
    $tratamientoSolicitado = getTratamientoSolicitado($idTratamiento,$partyIdSolicitante)->id_tratamiento_solicitado;
    $distribucion = getDetalleTratamientoDoctorByIdTratamientoSolicitado($tratamientoSolicitado);

    if(!isset($distribucion))
        $distribucion = getDetalleTratamiento($idTratamiento);

    $cie10Tratamiento = getCie10Tratamiento($idTratamiento);

    $arrData =[];
    foreach ($distribucion->distribucion_tratamiento as $dist) {
        $detalles = [];
        foreach ($dist->detalle_distribucion_tratamiento as $detalle_distribucion_tratamiento)
            $detalles[$detalle_distribucion_tratamiento->id_distribucion_tratamiento][$detalle_distribucion_tratamiento->product_id][] = $detalle_distribucion_tratamiento;

        $arrData[] = array_values($detalles);
    }

    $filas = count($arrData[0][0]);
    $columnaAplicacion=[];

    foreach ($arrData as $data)
        $columnaAplicacion[] = count(array_values($data[0])[0]);

    $prodcuto = [];
    foreach ($arrData[0][0] as $produc_id => $arrDatum)
        $prodcuto[] = $produc_id;

    $solicitante = getParty($partyIdSolicitante);
@endphp
<div style="width: 100%;">
<table style="width: 100%;">
    <tr>
        <td class="text-center" style="border: 1px solid black">
            <img src="{{asset("/storage/".getConfiguracionEmpresa()->logo_empresa)}}" style="width: 60px;">
        </td>
        <td class="text-center">
            <table style="width: 100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="text-center" style="border: 1px solid black;vertical-align: middle"><h3>TRATAMIENTOS ESPECIALIZADOS</h3></td>
                </tr>
                <tr>
                    <td class="text-center" style="border: 1px solid black;vertical-align: middle"><h4>Informe del Estado Clínico del Paciente</h4></td>
                </tr>
            </table>
        </td>
        <td class="text-center" style="border: 1px solid black"></td>
    </tr>
    <tr>
        <td class="text-center" style="border: 1px solid black">Fecha de elaboración<br /> {{now()->toDateString()}}</td>
        <td class="text-center" style="border: 1px solid black">INNOCLINICA CIA. LTDA <br /> Médico Tratante</td>
        <td class="text-center" style="border: 1px solid black">Página 1 de 1</td>
    </tr>
    <tr><td colspan="3" style="border: 1px solid black" class="text-center">DATOS INFORMATIVOS DEL PACIENTE</td></tr>
    <tr>
        <td style="border: 1px solid black;padding-left: 5px"><b>APELLIDOS:</b> {{$solicitante->person->last_name}}</td>
        <td style="border: 1px solid black;padding-left: 5px"><b>NOMBRES:</b> {{$solicitante->person->first_name}}</td>
        <td style="border: 1px solid black;padding-left: 5px"><b>{{$solicitante->identification->tipo_identificacion->description}}:</b> {{$solicitante->identification->id_value}} </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr><td colspan="3" style="border: 1px solid black" class="text-center">CLASIFICACIÓN DE ENFERMEDADES CIE-10 (SELECCIONAR)</td></tr>
    <tr>
        <td style="background: #f8f9fa;border: 1px solid black; " colspan="3" >
            <ul class="list-unstyled" style="padding-left: 40px">
                @foreach($cie10Tratamiento as $x => $cie10)
                    <li>
                        <input type="radio" id="cie10_{{$x+1}}"
                               {{(isset($distribucion->id_cie10) && $distribucion->id_cie10 == $cie10->id_cie10) ? "checked" : ""}}
                               name="cie10" class="cie10" value="{{$cie10->id_cie10}}">
                        <label for="cei10_{{$x+1}}">{{$cie10->cie10->descripcion}}</label>
                    </li>
                @endforeach
            </ul>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="border: 1px solid black" class="text-center">DESCRIPIÓN PATOLÓGICA</td>
    </tr>
    <tr>
        <td colspan="3" style="border: 1px solid black;padding: 10px" class="text-center">
            <textarea name="descripcion_patologica" cols="150" rows="6" id="descripcion_patologica" class="text-center">
                {{isset($distribucion->descripcion_patologica) ? $distribucion->descripcion_patologica : "" }}
            </textarea>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="border: 1px solid black" class="text-center">JUSTIFICACIÓN DE USO DEL PRODUCTO A IMPORTAR</td>
    </tr>
</table>
<table style="width: 100%">
    <thead>
        <th style="border: 1px solid black;width: 80px;text-align: center" >Estadio de tratamiento</th>
        @foreach($distribucion->distribucion_tratamiento as $x => $fase)
            <th style="border: 1px solid black" class="text-center fase_tratamiento">
                {{(isset($fase['intervalo']) && isset($fase['cantidad_intervalo'])) ? getIntervalo($fase['intervalo'])." ".$fase['cantidad_intervalo'] : "Tratamiento inicial"}}
                <input type="hidden" id="intevalo_{{$x+1}}" name="intevalo_{{$x+1}}" value="{{$fase['intervalo']}}">
                <input type="hidden" id="cantidad_intervalo_{{$x+1}}" name="cantidad_intervalo_{{$x+1}}" value="{{$fase['cantidad_intervalo']}}">
                <input type="hidden" id="cantidad_aplicacion_{{$x+1}}" name="cantidad_aplicacion_{{$x+1}}" value="{{$columnaAplicacion[$x]}}">
            </th>
        @endforeach
        <th></th>
    </thead>
    <tbody>
    {{--<tr>
        <td style="border: 1px solid black;text-align: center">Intervalo </td>
        @foreach($distribucion->distribucion_tratamiento as $x => $fase)
            <td>
                <table style="width: 100%;padding: 0;">
                    <tr>
                        @for($y=1;$y<=$columnaAplicacion[$x];$y++)
                            <td style="border: 1px solid black" class="text-center">
                                <input id="check_intervalo_{{$x+1}}_{{$y}}" name="check_intervalo_{{$x+1}}_{{$y}}"
                                       type="checkbox" class="form-control-custom">
                                <label style="position: relative;bottom: 12px;" for="check_intervalo_{{$x+1}}_{{$y}}"></label>
                            </td>
                        @endfor
                    </tr>
                </table>
            </td>
        @endforeach
        <td></td>
    </tr>--}}
    <tr>
        <td style="border: 1px solid black;text-align: center">Aplicación</td>
        @foreach($distribucion->distribucion_tratamiento as $x => $fase)
            <td>
                <table style="width: 100%;padding: 0;" cellpadding="0" cellspacing="0" >
                    <tr class="text-center">
                        @for($y=0;$y<$columnaAplicacion[$x];$y++)
                            <td style="border: 1px solid black;">
                                    <select id="intevalo_aplicacion_{{$x+1}}_{{$y+1}}" name="intevalo_aplicacion_{{$x+1}}_{{$y+1}}" required=""
                                        title="Selecciona el intervalo de tiempo de la aplicación" style="border: none;">
                                    <option value="1" {{$fase->detalle_distribucion_tratamiento[$y]->intervalo == 1 ? "selected" : ""}}>D</option>
                                    <option value="2" {{$fase->detalle_distribucion_tratamiento[$y]->intervalo == 2 ? "selected" : ""}}>S</option>
                                    <option value="3" {{$fase->detalle_distribucion_tratamiento[$y]->intervalo == 3 ? "selected" : ""}}>M</option>
                                </select>
                                <input type="number" id="cantidad_intervalo_{{$x+1}}_{{$y+1}}" name="cantidad_intervalo_{{$x+1}}_{{$y+1}}"
                                       value="{{$fase->detalle_distribucion_tratamiento[$y]->cantidad_intervalo}}"
                                       style="border: none;width: 35px;text-align: center">
                            </td>
                        @endfor
                    </tr>
                </table>
            </td>
        @endforeach
        <td></td>
    </tr>
    @for($i=0;$i<count($prodcuto);$i++)
        <tr>
            <td style="border: 1px solid black;text-align: center;font-size: 12px">
                {{getProducto($prodcuto[$i])->product_name}}
                <input type="hidden" id="producto_{{$i+1}}" name="producto_{{$i+1}}" class="producto" value="{{$prodcuto[$i]}}">
            </td>
            @foreach($distribucion->distribucion_tratamiento as $x => $fase)
                <td>
                    <table cellpadding="0" cellspacing="0"  style="width: 100%;padding: 0;">
                        <tr class="text-center">
                            @for($y=0;$y<$columnaAplicacion[$x];$y++)
                                <td style="border: 1px solid black">
                                    <input type="number" id="cantidad_producto_{{$x+1}}_{{$i+1}}_{{$y+1}}" name="cantidad_producto_{{$x+1}}_{{$i+1}}_{{$y+1}}" min="1" class="producto_{{$x+1}}"
                                           onchange="calcular_producto(this)" required
                                           value="{{isset($prodcuto[$i]) ? $arrData[$x][0][$prodcuto[$i]][$y]->cantidad_aplicacion : 0}}"
                                           style="border:none;width: 100%;text-align: center">
                                </td>
                            @endfor
                        </tr>
                    </table>
                </td>
            @endforeach
            @if($i==0)
                <td rowspan="3" style="border:1px solid black;vertical-align: middle;"><b>Total</b></td>
            @endif
        </tr>
    @endfor
    <tr>
        <td style="border: 1px solid black;text-align: center">Dosis en cada fase</td>
        @foreach($distribucion->distribucion_tratamiento as $x => $fase)
            <td style="border: 1px solid black" class="text-center div_dosis div_dosis_{{$x+1}}" >0</td>
        @endforeach

    </tr>
    <tr>
        <td style="border: 1px solid black;text-align: center">Dosis clinica cálculada para importación</td>
        @foreach($distribucion->distribucion_tratamiento as $x => $fase)
            <td style="border: 1px solid black" class="text-center div_dosis_{{$x+1}}">0</td>
        @endforeach
        <td style="border:1px solid black;text-align: center" class="total_dosis_fase">0</td>
    </tr>
    <tr>
        <td colspan="{{$distribucion->distribucion_tratamiento->count()+1}}" style="border: 1px solid black" class="text-center">JUSTIFICACIONES MÉDICA ADICIONALES</td>
    </tr>
    <tr>
        <td colspan="{{$distribucion->distribucion_tratamiento->count()+1}}" style="border: 1px solid black;padding: 10px" class="text-center">
            <textarea name="justificacion" cols="150" rows="6" id="justificacion" class="text-center">
                {{isset($distribucion->justificacion_medica) ? $distribucion->justificacion_medica : "" }}
            </textarea>
        </td>
    </tr>
    <tr>
        <td colspan="{{$distribucion->distribucion_tratamiento->count()+1}}" class="text-center" style="border: none;padding: 20px 0px">
            <button type="button" class="btn btn-success" onclick="store_distribucion_tratamiento_doctor('{{$partyIdSolicitante}}','{{$idTratamiento}}','')">
                <i class="fa fa-floppy-o"></i> Guardar
            </button>
        </td>
    </tr>
    </tbody>
</table>
</div>
<script>
    //$.each($("input.cantidad_aplicacion"),function (i,j) { calcular_producto('producto_'+(i+1)) });
    function calcular_producto(input) {

        if($(input).attr('class')){
            input = $(input).attr('class');
        }else{
            input = input;
        }

        dosis = 0;
        $.each($("."+input),function(i,j){
            dosis += parseInt(j.value);
        });

        $("td.div_dosis_"+input.split("_")[1]).html(dosis);

        total_dosis = 0;
        $.each( $("td.div_dosis"),function (i,j) {
            total_dosis += parseInt($(j).html());
        });

        $(".total_dosis_fase").html(total_dosis);

    }

    $.each($("td.div_dosis"),function (i,j) { calcular_producto('producto_'+(i+1)) });

</script>