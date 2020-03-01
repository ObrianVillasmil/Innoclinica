@php

    if(!isset($idDoctor)){
        $html ="<div style='color:#00000;width:70%;margin:0 auto;text-align:center;background-color: #ffc107;border-radius:3px;padding:20px '>
                      No se ha elegido un doctor para este tratamiento
                 </div>";
        echo $html;
        return false;
    }

       $distribucion = getDetalleTratamiento($idTratamiento);

       if(isset($seguimiento)){

          $tratamientoSolicitado = getTratamientoSolicitado($idTratamiento,$partyId)->id_tratamiento_solicitado;
          $distribucion = getDetalleTratamientoDoctorByIdTratamientoSolicitado($tratamientoSolicitado);
          $solicitante = getParty($partyId);
          $doctor = getParty($idDoctor);
          $firma = getParty(session('party_id'))->firma;

       }

       $cie10Tratamiento = getCie10Tratamiento($idTratamiento);

       if(!isset($distribucion)){
           $html ="<div style='color:#00000;width:70%;margin:0 auto;text-align:center;background-color: #ffc107;border-radius:3px;padding:20px '>
                       No se ha guardado el tratamiento por parte del doctor
                  </div>";
           echo $html;
           return false;
       }

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

   foreach ($arrData as $arrDatum){
       $a= 0;
       foreach($arrDatum as $arrDat){
           foreach($arrDat as $arrDa)
               foreach ($arrDa as $item)
                   $a+= $item->cantidad_aplicacion;
       }
   }

@endphp
<style>
        html { margin-top: 0px;margin-bottom: 0px;margin-right: 72px}
</style>
<div style="width: 80%;margin: 20px auto;">
    <table style="width: 100%;" cellpadding="0" cellspacing="0">
        <tr>
            <td class="" style="border: 1px solid black;text-align: center">
                <img src="./storage/{{getConfiguracionEmpresa()->logo_empresa}}" style="width: 60px;">
            </td>
            <td style="text-align: center">
                <table style="width: 100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="border: 1px solid black;vertical-align: middle;text-align: center;height: 30px">TRATAMIENTOS ESPECIALIZADOS</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black;vertical-align: middle;text-align: center;height: 30px">Informe del Estado Clínico del Paciente</td>
                    </tr>
                </table>
            </td>
            <td  style="border: 1px solid black;text-align: center"></td>
        </tr>
        <tr>
            <td  style="border: 1px solid black;text-align: center">Fecha de elaboración
            <br /> {{now()->toDateString()}}
            </td>
            <td  style="border: 1px solid black;text-align: center">
                {{strtoupper(getConfiguracionEmpresa()->nombre_empresa)}}<br />
                @if(isset($doctor))
                    Dr. {{$doctor->person->first_name." ".$doctor->person->last_name}}
                @else
                    Médico Tratante
                @endif
            </td>
            <td  style="border: 1px solid black;text-align: center">Página 1 de 1</td>
        </tr>
    </table>
</div>
<table style="width: 100%;margin-top: 20px;font-size: 13px" cellpadding="0" cellspacing="0">
    <tr>
        <td  colspan="{{array_sum($columnaAplicacion)+2}}">
        <table style="width: 100%;margin-top: 20px;font-size: 13px" cellpadding="0" cellspacing="0"> >
            <tr>
                <td  colspan="{{array_sum($columnaAplicacion)+2}}" style="border: 1px solid black;text-align: center" >DATOS INFORMATIVOS DEL PACIENTE</td>
            </tr>
            <tr>
                <td style="border: 1px solid black;padding-left:10px;width:33.33%"><b>APELLIDOS:</b>{{isset($solicitante->person->last_name) ? $solicitante->person->last_name : ""}}</td>
                <td style="border: 1px solid black;padding-left:10px;width:33.33%" ><b>NOMBRES:</b> {{isset($solicitante->person->first_name) ? $solicitante->person->first_name : ""}}</td>
                <td style="border: 1px solid black;padding-left:10px;width:33.33%">
                    <b>{{isset($solicitante->identification->tipo_identificacion->description) ? $solicitante->identification->tipo_identificacion->description :"Cédula/Ruc"}}:</b>
                    {{isset($solicitante->identification->id_value) ? $solicitante->identification->id_value : ""}}
                </td>
            </tr>
        </table>
        </td>
    </tr>
    <tr>
        <td  colspan="{{array_sum($columnaAplicacion)+2}}" style="border: 1px solid black;text-align: center" >CLASIFICACIÓN DE ENFERMEDADES CIE-10 @if(!isset($seguimiento))(SELECCIONAR)@endif</td>
    </tr>
    <tr>
        <td style="background: #f8f9fa;border: 1px solid black;vertical-align: middle"  colspan="{{array_sum($columnaAplicacion)+2}}" >
            <ul class="list-unstyled" style="padding-left: 40px">
                @if(isset($seguimiento))
                    {{isset($distribucion->id_cie10) ? $distribucion->cie10->descripcion : ""}}
                @else
                    @foreach($cie10Tratamiento as $x => $cie10)
                        <li>
                            <input type="radio" id="cie10_{{$x+1}}"
                                   {{(isset($distribucion->id_cie10) && $distribucion->id_cie10 == $cie10->id_cie10) ? "checked" : ""}}
                                   name="cie10" class="cie10" value="{{$cie10->id_cie10}}">
                            <label for="cei10_{{$x+1}}">{{$cie10->cie10->descripcion}}</label>
                        </li>
                    @endforeach
                @endif
            </ul>
        </td>
    </tr>
    <tr>
        <td  colspan="{{array_sum($columnaAplicacion)+2}}" style="border: 1px solid black;text-align: center" >DESCRIPIÓN PATOLÓGICA</td>
    </tr>
    <tr>
        <td  colspan="{{array_sum($columnaAplicacion)+2}}" style="border: 1px solid black;padding: 10px;text-align: center" >
            {{isset($seguimiento) ? $distribucion->descripcion_patologica : "Aquí irá la descripción patológica del paciente escrita por el doctor"}}
        </td>
    </tr>
    <tr>
        <td  colspan="{{array_sum($columnaAplicacion)+2}}" style="border: 1px solid black;text-align: center" >JUSTIFICACIÓN DE USO DEL PRODUCTO A IMPORTAR</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;width: 80px;text-align: center">Estadio de tratamiento</td>
        @foreach($distribucion->distribucion_tratamiento as $x => $fase)
            <td style="border: 1px solid black;text-align: center" class="text-center fase_tratamiento" colspan="{{$columnaAplicacion[$x]}}">
                {{(isset($fase['intervalo']) && isset($fase['cantidad_intervalo'])) ? getIntervalo($fase['intervalo'])." ".$fase['cantidad_intervalo'] : "Tratamiento inicial"}}
            </td>
        @endforeach
        <td style="border: 1px solid black;width: 80px;text-align: center"></td>
    </tr>
    <tr>
        <td style="border: 1px solid black;text-align: center">Aplicación</td>
        @foreach($distribucion->distribucion_tratamiento as $x => $fase)
            @for($y=0;$y<$columnaAplicacion[$x];$y++)
                <td style="border: 1px solid black;text-align: center">
                    {{getIntervalo($fase->detalle_distribucion_tratamiento[$y]->intervalo)." ".$fase->detalle_distribucion_tratamiento[$y]->cantidad_intervalo}}
                </td>
            @endfor
        @endforeach
        <td style="border: 1px solid black;text-align: center"></td>
    </tr>
    @for($i=0;$i<count($prodcuto);$i++)
        <tr>
            <td style="border: 1px solid black;text-align: center;font-size: 11px">
                {{getProducto($prodcuto[$i])->product_name}}
            </td>
            @foreach($distribucion->distribucion_tratamiento as $x => $fase)
                @for($y=0;$y<$columnaAplicacion[$x];$y++)
                    <td style="border: 1px solid black;text-align: center">
                        {{$arrData[$x][0][$prodcuto[$i]][$y]->cantidad_aplicacion}}

                    </td>
                @endfor
            @endforeach
            @if($i==0)
                <td rowspan="{{count($prodcuto)+1}}" style="border:1px solid black;vertical-align: middle;"><b>Total</b></td>
            @endif
        </tr>
    @endfor
    <tr>
        <td style="border: 1px solid black;text-align: center" >Dosis en cada fase</td>
        @foreach($distribucion->distribucion_tratamiento as $x => $fase)
           @php $cantidadFase = 0; @endphp
            @foreach($fase->detalle_distribucion_tratamiento as $detTrat)
                @php $cantidadFase += $detTrat->cantidad_aplicacion;@endphp
            @endforeach
            <td style="border: 1px solid black;text-align: center" class="text-center div_dosis div_dosis_{{$x+1}}" colspan="{{$columnaAplicacion[$x]}}">
                {{$cantidadFase}}
            </td>
        @endforeach
    </tr>
    <tr>
        <td style="border: 1px solid black;text-align: center">Dosis clinica cálculada para importación</td>
        @php $cantidadTotal = 0; @endphp
        @foreach($distribucion->distribucion_tratamiento as $x => $fase)
            @php $cantidadFase = 0; @endphp
            @foreach($fase->detalle_distribucion_tratamiento as $detTrat)
                @php $cantidadFase += $detTrat->cantidad_aplicacion;@endphp
            @endforeach
            <td style="border: 1px solid black;text-align: center" colspan="{{$columnaAplicacion[$x]}}" class="text-center div_dosis_{{$x+1}}">
                {{$cantidadFase}}
                @php $cantidadTotal+= $cantidadFase @endphp
            </td>
        @endforeach
        <td style="border:1px solid black;text-align: center" class="total_dosis_fase">{{$cantidadTotal}}</td>
    </tr>
    <tr>
        <td colspan="{{array_sum($columnaAplicacion)+2}}" style="border: 1px solid black;text-align: center" class="text-center">JUSTIFICACIONES MÉDICA ADICIONALES</td>
    </tr>
    <tr>
        <td colspan="{{array_sum($columnaAplicacion)+2}}" style="border: 1px solid black;text-align: center;" class="text-center">
        {{isset($distribucion->justificacion_medica) ? $distribucion->justificacion_medica : "------------------------------------" }}
        </td>
    </tr>
</table>
@if(isset($seguimiento))
    <table style="width:100%;margin-top: 20px">
        <tr>
            <td colspan="{{array_sum($columnaAplicacion)+2}}" style="text-align: center;width:100%">
                @if(isset($firma))
                    <img style="width: 300px;height: 100px;" src="./storage/firmas_digital/{{$firma->imagen}}">
                @else
                    No se ha cargado una firma digital
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="{{array_sum($columnaAplicacion)+2}}" style="text-align: center;width:100%">
                <hr style="width: 30%"/>
            </td>
        </tr>
        <tr>
            <td colspan="{{array_sum($columnaAplicacion)+2}}" style="text-align: center;width:100%">
                FIRMA Y SELLO
            </td>
        </tr>
    </table>
@endif
