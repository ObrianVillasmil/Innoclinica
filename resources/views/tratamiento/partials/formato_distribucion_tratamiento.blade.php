<hr  style="width: 100%;"/>
@if(!isset($distribucion))
    <table style="width: 100%">
        <thead>
            <th style="border: 1px solid black;width: 80px;text-align: center">Estadio de tratamiento</th>
            @foreach($datosFase as $x => $fase)
                <th style="border: 1px solid black" class="text-center">
                    {{(isset($fase['intervalo']) && isset($fase['cantidad_intervalo'])) ? getIntervalo($fase['intervalo'])." ".$fase['cantidad_intervalo'] : "Tratamiento inicial"}}
                </th>
            @endforeach
            <th></th>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid black;text-align: center">Intervalo</td>
                @foreach($datosFase as $x => $fase)
                    <td>
                        <table style="width: 100%;padding: 0;">
                            <tr>
                                @for($y=1;$y<=$fase['cantidad_aplicacion'];$y++)
                                    <td style="border: 1px solid black" class="text-center">
                                        <input id="check_intervalo_{{$x+1}}_{{$y}}" name="check_intervalo_{{$x+1}}_{{$y}}" type="checkbox" class="form-control-custom">
                                        <label style="position: relative;bottom: 12px;" for="check_intervalo_{{$x+1}}_{{$y}}"></label>
                                    </td>
                                @endfor
                            </tr>
                        </table>
                    </td>
                @endforeach
                <td></td>
            </tr>
          <tr>
              <td style="border: 1px solid black;text-align: center">Aplicación</td>
              @foreach($datosFase as $x => $fase)
                  <td>
                      <table style="width: 100%;padding: 0;" cellpadding="0" cellspacing="0" >
                          <tr class="text-center">
                              @for($y=1;$y<=$fase['cantidad_aplicacion'];$y++)
                                  <td style="border: 1px solid black;">
                                      <select id="intevalo_aplicacion_{{$x+1}}_{{$y}}" name="intevalo_aplicacion_{{$x+1}}_{{$y}}" required
                                              title="Selecciona el intervalo de tiempo de la aplicación"
                                              style="border: none;">
                                          <option value="1">D</option>
                                          <option value="2">S</option>
                                          <option value="3">M</option>
                                      </select>
                                      <input type="number" value="{{$y}}" id="cantidad_intervalo_{{$x+1}}_{{$y}}"
                                             name="cantidad_intervalo_{{$x+1}}_{{$y}}" style="border: none;width: 35px;text-align: center">
                                  </td>
                              @endfor
                          </tr>
                      </table>
                  </td>
              @endforeach
              <td></td>
          </tr>
          @for($i=1;$i<=3;$i++)
                <tr>
                    <td style="border: 1px solid black">
                        <select id="producto_{{$i}}" name="producto_{{$i}}" class="producto" id="producto_{{$i}}" style="border: none;text-align: center;width: 100%" required>
                            <option value="" >Producto</option>
                            @foreach($productos as $p)
                                <option value="{{$p->product_id}}"> {{$p->product_name}} </option>
                            @endforeach
                        </select>
                    </td>
                    @foreach($datosFase as $x => $fase)
                        <td>
                            <table cellpadding="0" cellspacing="0"  style="width: 100%;padding: 0;">
                                <tr class="text-center">
                                    @for($y=1;$y<=$fase['cantidad_aplicacion'];$y++)
                                        <td style="border: 1px solid black">
                                            <input type="number" id="cantidad_producto_{{$x+1}}_{{$i}}_{{$y}}" name="cantidad_producto_{{$x+1}}_{{$i}}_{{$y}}" min="1" class="producto_{{$x+1}}"
                                                   onchange="calcular_producto(this)" value="0" required style="border:none;width: 100%;text-align: center">
                                        </td>
                                    @endfor
                                </tr>
                            </table>
                        </td>
                    @endforeach
                    @if($i==1)
                        <td rowspan="3" style="border:1px solid black;text-align: center"><b>Total</b></td>
                    @endif
                </tr>
          @endfor
            <tr>
                <td style="border: 1px solid black;text-align: center">Dosis en cada fase</td>
                @foreach($datosFase as $x => $fase)
                    <td style="border: 1px solid black" class="text-center div_dosis div_dosis_{{$x+1}}" >0</td>
                @endforeach
                <td style="border:1px solid black;text-align: center" class="total_dosis_fase">0</td>
            </tr>
            <tr>
                <td style="border: 1px solid black;text-align: center">Dosis clinica cálculada para importación</td>
                @foreach($datosFase as $x => $fase)
                    <td style="border: 1px solid black" class="text-center div_dosis_{{$x+1}}">0</td>
                @endforeach
                <td style="border:1px solid black;text-align: center" class="total_dosis_fase">0</td>
            </tr>
        </tbody>
    </table>
@else
    @php
        $arrData =[];
        foreach ($distribucion as $dist) {
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
    @endphp
    <table style="width: 100%">
        <thead>
            <th style="border: 1px solid black;width: 80px;text-align: center">Estadio de tratamiento</th>
            @foreach($distribucion as $x => $fase)
                <th style="border: 1px solid black" class="text-center">
                    {{(isset($fase['intervalo']) && isset($fase['cantidad_intervalo'])) ? getIntervalo($fase['intervalo'])." ".$fase['cantidad_intervalo'] : "Tratamiento inicial"}}
                </th>
            @endforeach
            <th></th>
        </thead>
        <tbody>
        <tr>
            <td style="border: 1px solid black;text-align: center">Intervalo</td>
            @foreach($distribucion as $x => $fase)
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
        </tr>
        <tr>
             <td style="border: 1px solid black;text-align: center">Aplicación</td>
             @foreach($distribucion as $x => $fase)
                 <td>
                     <table style="width: 100%;padding: 0;" cellpadding="0" cellspacing="0" >
                         <tr class="text-center">
                             @for($y=0;$y<$columnaAplicacion[$x];$y++)
                                 <td style="border: 1px solid black;">
                                     <select id="intevalo_aplicacion_{{$x+1}}_{{$y+1}}" name="intevalo_aplicacion_{{$x+1}}_{{$y+1}}" required="" title="Selecciona el intervalo de tiempo de la aplicación" style="border: none;">
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
        @for($i=0;$i<2;$i++)
            <tr>
                <td style="border: 1px solid black">
                    <select id="producto_{{$i+1}}" name="producto_{{$i+1}}" class="producto producto_{{$i+1}}" style="border: none;text-align: center;width: 100%" required>
                        <option value="" >Producto</option>
                        @foreach($productos as $p)
                            <option {{isset($prodcuto[$i]) ? ($prodcuto[$i] == $p->product_id ? 'selected' : '' ) : "" }}
                               value="{{$p->product_id}}"> {{$p->product_name}}
                            </option>
                        @endforeach
                    </select>
                </td>
                @foreach($distribucion as $x => $fase)
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
                    <td rowspan="2" style="border:1px solid black;vertical-align: middle;"><b>Total</b></td>
                @endif
            </tr>
        @endfor
        <tr>
            <td style="border: 1px solid black;text-align: center">Dosis en cada fase</td>
            @foreach($distribucion as $x => $fase)
                <td style="border: 1px solid black" class="text-center div_dosis div_dosis_{{$x+1}}" >0</td>
            @endforeach
            <td style="border:1px solid black;text-align: center" class="total_dosis_fase">0</td>
        </tr>
        <<tr>
            <td style="border: 1px solid black;text-align: center">Dosis clinica cálculada para importación</td>
            @foreach($datosFase as $x => $fase)
                <td style="border: 1px solid black" class="text-center div_dosis_{{$x+1}}">0</td>
            @endforeach
            <td style="border:1px solid black;text-align: center" class="total_dosis_fase">0</td>
        </tr>
    </tbody>
    </table>
@endif
<script>
        $.each($("input.cantidad_aplicacion"),function (i,j) { calcular_producto('producto_'+(i+1)) });
</script>