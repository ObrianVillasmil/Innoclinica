@php
    $distribucion = getDistribucionTratamientoDoctor($idTratamiento,session('party_id'),$partyIdSolicitante);
    if($distribucion->count() == 0)
        $distribucion = getDetalleTratamiento($idTratamiento);


    $cie10Tratamiento = getCie10Tratamiento($idTratamiento);
    $datosFase =$distribucion;
    $solicitante = getParty($partyIdSolicitante);

@endphp
<div style="width: 80%;margin: 20px auto;">
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
            <td class="text-center" style="border: 1px solid black">Fecha de elaboración</td>
            <td class="text-center" style="border: 1px solid black">INNOCLINICA CIA. LTDA <br /> Médico Tratante</td>
            <td class="text-center" style="border: 1px solid black">Página 1 de 1</td>
        </tr>

    </table>
</div>
<table style="width: 100%;margin-top: 20px;">
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
                               {{(isset($distribucion[0][0]->id_cie10) && $distribucion[0][0]->id_cie10 == $cie10->id_cie10) ? "checked" : ""}}
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
                {{isset($distribucion[0][0]->descripcion_patologica) ? $distribucion[0][0]->descripcion_patologica : "" }}
            </textarea>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="border: 1px solid black" class="text-center">JUSTIFICACIÓN DE USO DEL PRODUCTO A IMPORTAR</td>
    </tr>
    <tr>
        <td colspan="3" style="border: 1px solid black" class="text-center">
            <table style="width: 100%">
                <thead>
                <th style="border: 1px solid black;width: 80px;text-align: center">Estadio de tratamiento</th>
                @foreach($distribucion as $x => $fase)
                    <th style="border: 1px solid black" class="text-center mes_tratamiento">
                        {{isset($fase[0]['mes']) ? "Mes ".$fase[0]['mes'] : "Tratamiento inicial"}}
                    </th>
                @endforeach
                <th style="border: 1px solid black;width: 80px;text-align: center">
                </th>

                </thead>
                <tbody>
                <tr>
                    <td style="border: 1px solid black;text-align: center">Intervalo</td>
                    @foreach($distribucion as $x => $fase)
                        <td>
                            <table style="width: 100%;padding: 0;">
                                <tr>
                                    @if(isset($fase[0]->descripcion_patologica))
                                        @for($y=1;$y<=count($fase);$y++)
                                            <td style="border: 1px solid black" class="text-center">
                                                <input id="check_intervalo_{{$x+1}}_{{$y}}" name="check_intervalo_{{$x+1}}_{{$y}}" type="checkbox" class="form-control-custom">
                                                <label style="position: relative;bottom: 12px;" for="check_intervalo_{{$x+1}}_{{$y}}"></label>
                                            </td>
                                        @endfor
                                    @else
                                        @for($y=1;$y<=$fase[0]->semana;$y++)
                                            <td style="border: 1px solid black" class="text-center">
                                                <input id="check_intervalo_{{$x+1}}_{{$y}}" name="check_intervalo_{{$x+1}}_{{$y}}" type="checkbox" class="form-control-custom">
                                                <label style="position: relative;bottom: 12px;" for="check_intervalo_{{$x+1}}_{{$y}}"></label>
                                            </td>
                                        @endfor
                                    @endif
                                </tr>
                            </table>
                        </td>
                    @endforeach
                    <td style="border: 1px solid black;width: 80px;text-align: center" rowspan="2"></td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;text-align: center">Instilación</td>
                    @foreach($distribucion as $x => $fase)
                        <td>
                            <table style="width: 100%;padding: 0;" cellpadding="0" cellspacing="0" >
                                <tr class="text-center">
                                    @if(isset($fase[0]->descripcion_patologica))
                                        @for($y=1;$y<=count($fase);$y++)
                                            <td style="border: 1px solid black;" class="sem_{{$x+1}}">
                                                Sem {{$y}}
                                            </td>
                                        @endfor
                                    @else
                                        @for($y=1;$y<=$fase[0]->semana;$y++)
                                            <td style="border: 1px solid black;" class="sem_{{$x+1}}">
                                                Sem {{$y}}
                                            </td>
                                        @endfor
                                    @endif
                                </tr>
                            </table>
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td style="border: 1px solid black">
                        <select id="producto" name="producto" style="border: none;text-align: center;width: 100%;">
                            {{--<option selected disabled> Seleccione </option>--}}
                            <option value="1"> Viales </option>
                        </select>
                    </td>
                    @foreach($distribucion as $x => $fase)
                        <td>
                            <table cellpadding="0" cellspacing="0"  style="width: 100%;padding: 0;">
                                <tr class="text-center">
                                    @if(isset($fase[0]->descripcion_patologica))
                                        @for($y=1;$y<=count($fase);$y++)
                                            <td style="border: 1px solid black">
                                                <input type="number" id="producto_{{$x+1}}_{{$y}}" name="producto_{{$x+1}}_{{$y}}" min="1" class="producto_{{$x+1}}"
                                                       onchange="calcular_producto(this)" value="{{$fase[$y-1]->cantidad_semana}}"  required style="border:none;width: 100%;text-align: center">
                                            </td>
                                        @endfor
                                    @else
                                        @for($y=1;$y<=$fase[0]->semana;$y++)
                                            <td style="border: 1px solid black">
                                                <input type="number" id="producto_{{$x+1}}_{{$y}}" name="producto_{{$x+1}}_{{$y}}" min="1" class="producto_{{$x+1}}"
                                                       onchange="calcular_producto(this)" value="{{$fase[$y-1]->cantidad_semana}}"  required style="border:none;width: 100%;text-align: center">
                                            </td>
                                        @endfor
                                    @endif
                                </tr>
                            </table>
                        </td>
                    @endforeach
                    <td style="border:1px solid black"><b>Total</b></td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;text-align: center">Dosis en cada fase</td>
                    @foreach($distribucion as $x => $fase)
                        <td style="border: 1px solid black" class="text-center div_dosis div_dosis_{{$x+1}}" >0</td>
                    @endforeach
                    <td style="border:1px solid black;text-align: center" class="total_dosis_fase">0</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;text-align: center">Dosis clinica cálculada para importación</td>
                    @foreach($distribucion as $x => $fase)
                        <td style="border: 1px solid black" class="text-center div_dosis_{{$x+1}}">0</td>
                    @endforeach
                    <td style="border:1px solid black;text-align: center" class="total_dosis_fase">0</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="border: 1px solid black" class="text-center">JUSTIFICACIONES MÉDICA ADICIONALES</td>
    </tr>
    <tr>
        <td colspan="3" style="border: 1px solid black;padding: 10px" class="text-center">
            <textarea name="justificacion" cols="150" rows="6" id="justificacion" class="text-center">
                {{isset($distribucion[0][0]->justificacion_medica) ? $distribucion[0][0]->justificacion_medica : "" }}
            </textarea>
        </td>
    </tr>

</table>
<script>
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