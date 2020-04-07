<div class="table-responsive" style="margin-bottom: 20px">
    <form id="form_cotizacion">
        <table class="table table-sm">
            @if(isset($cotiza) && $cotiza === "tratamiento_cliente")

            @else
                <tr>
                    <th>
                       Cotización del tratamiento {{$tratamiento->nombre_tratamiento}}
                    </th>
                </tr>
            @endif
        </table>
        <table class="table table-sm">
            <thead class="thead-light">
            <tr>
                <th class="text-center">
                    Código
                    <input type="hidden" id="id_tratamiento_solicitado" value="{{isset($idTratamientoSolicitado) ? $idTratamientoSolicitado :""}}">
                </th>
                <th class="text-center">Producto</th>
                <th class="text-center" style="width: 90px">Cantidad</th>
                <th class="text-center">Unitario.</th>
                <th class="text-center">Total Pvp.</th>
                <th class="text-center">Total Dscto.</th>
                <th class="text-center">Precio total</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($prodcuto) && count($prodcuto) > 0)
                @php $cantidadTotal = 0; @endphp
                @foreach($prodcuto as $x => $p)
                    <tr class="tr_prodcuto">
                        <td style="vertical-align: middle;" class="text-center product_id">{{$p['product_id']}}</td>
                        <td style="vertical-align: middle" class="text-center">{{getProducto($p['product_id'])->product_name}}</td>
                        <td style="vertical-align: middle;width: 90px" class="text-center">
                            <input type="number" class="form-control text-center cantidad" id="cantidad_{{$x+1}}"
                                   value="1{{--$p['cantidad']--}}" name="cantidad_{{$x+1}}" min="1" required>
                        </td>
                        <td style="vertical-align: middle" class="text-center" id="unitario_{{$x+1}}">$0.00</td>
                        <td style="vertical-align: middle" class="text-center" id="total_pvp_{{$x+1}}">$0.00</td>
                        <td style="vertical-align: middle" class="text-center" id="total_dscto_{{$x+1}}">$0.00</td>
                        <td style="vertical-align: middle" class="text-center" id="precio_total_{{$x+1}}">$0.00</td>
                    </tr>
                    @php $cantidadTotal += $p['cantidad']; @endphp
                @endforeach
                <tr style="background: #e9ecef;">
                    <td style="border: 1px solid silver">
                        <div class="row">
                            <label for="forma_pago"  class="col-sm-4 col-form-label text-center"><strong>Forma de pago</strong></label>
                            <div class="col-sm-8">
                            <select id="forma_pago" name="forma_pago" class="form-control" required>
                                <option value="">Seleccione</option>
                                <option value="CASH">Efectivo</option>
                                <option value="CREDIT_CARD"> Tarjeta de crédito corriente</option>
                                <option value="CREDIT_CARD_DIF">Tarjeta de crédito diferido</option>
                                <option value="DEBIT_CARD">Tarjeta de débito</option>
                                <option value="CREDITO">Crédito</option>
                                <option value="EFT_ACCOUNT">Trasnferencia bancaria</option>
                                <option value="PERSONAL_CHECK">Cheque</option>
                            </select>
                            </div>
                        </div>
                    </td>
                    <td colspan="2" style="border: 1px solid silver;vertical-align: center">
                        <div class="row">
                            <label for="tipo_envio"  class="col-sm-2 col-form-label text-center"><strong>Envío</strong></label>
                            <div class="col-sm-10">
                                <select id="tipo_envio" name="tipo_envio" class="form-control" required>
                                    <option value="NO_SHIPPING@_NA_">Sin envío</option>
                                    <option value="PROV@1592">Envío a provincia</option>
                                </select>
                            </div>
                        </div>
                    </td>
                    <td class="text-center" style="vertical-align: middle;border: 1px solid silver"><strong>Totales:</strong></td>
                    <td class="text-center" style="vertical-align: middle;border: 1px solid silver" id="total_general_pvp">$0.00</td>
                    <td class="text-center" style="vertical-align: middle;border: 1px solid silver" id="total_general_descto">$0.00</td>
                    <td class="text-center bg-success" style="vertical-align: middle;border: 1px solid silver;color: #fff" id="total_general">$0.00</td>
                </tr>
                <tr>
                    <td class="text-center" colspan="7">
                        <button type="button" class="btn btn-info" onclick="SolicitarCotizacion();">
                            <i class="fa fa-cog"></i> Cotizar
                        </button>
                        @if(isset($cotiza) && $cotiza !== "procesos")
                            <button type="button" class="btn btn-success" onclick="crearCotizacion()">
                                <i class="fa fa-paper-plane-o"></i> Enviar cotización
                            </button>
                        @endif
                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="6" style="vertical-align: middle">
                        <div class="alert alert-info text-center" role="alert">
                          No se encontraron prodcutos para cotizar en el tratamiento
                        </div>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </form>
</div>