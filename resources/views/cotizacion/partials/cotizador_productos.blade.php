<div class="table-responsive" style="margin-bottom: 20px">
    <form id="form_cotizacion">
        <table class="table table-sm">
            <tr>
                <th>
                    Cotización de productos
                </th>
            </tr>
        </table>
        <table class="table table-sm">
            <thead class="thead-light">
            <tr>
                {{--<th class="text-center">Código</th>--}}
                <th class="text-center">Producto</th>
                <th class="text-center" style="width: 90px">Cantidad</th>
                <th class="text-center">Total Pvp.</th>
                <th class="text-center">Total Dscto.</th>
                <th class="text-center">Precio total</th>
            </tr>
            </thead>
            <tbody>
                <tr class="tr_prodcuto">
                    {{--<td style="vertical-align: middle;width:250px" class="text-center codigo_producto"></td>--}}
                    <td style="vertical-align: middle" class="text-center product_id d-none"></td>
                    <td style="vertical-align: middle" class="text-center">
                        <select id="product_id" name="product_id" class="form-control" onchange="select_producto()" >
                            <option value="">Seleccione</option>
                            @foreach($prodcutos as $producto)
                                <option value="{{$producto->product_id}}">{{$producto->product_name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="vertical-align: middle;width: 90px" class="text-center">
                        <input type="number" class="form-control text-center cantidad" id="cantidad"
                               value="" name="cantidad" min="1" required>
                    </td>
                    <td style="vertical-align: middle" class="text-center" id="total_pvp_1">$0.00</td>
                    <td style="vertical-align: middle" class="text-center" id="total_dscto_1">$0.00</td>
                    <td style="vertical-align: middle" class="text-center" id="precio_total_1">$0.00</td>
                </tr>
                <tr style="background: #e9ecef;">
                    <td colspan="1" style="border: 1px solid silver">
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
                    <td class="text-center" colspan="1" style="vertical-align: middle;border: 1px solid silver"><strong>Totales:</strong></td>
                    <td class="text-center" style="vertical-align: middle;border: 1px solid silver" id="total_general_pvp">$0.00</td>
                    <td class="text-center" style="vertical-align: middle;border: 1px solid silver" id="total_general_descto">$0.00</td>
                    <td class="text-center bg-success" style="vertical-align: middle;border: 1px solid silver;color: #fff" id="total_general">$0.00</td>
                </tr>
                <tr>
                    <td class="text-center" colspan="6">
                        <button type="button" class="btn btn-info" onclick="SolicitarCotizacion();">
                            <i class="fa fa-cog"></i> Cotizar
                        </button>
                        <button type="button" class="btn btn-success" onclick="enviar">
                            <i class="fa fa-paper-plane-o"></i> Enviar cotización
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>