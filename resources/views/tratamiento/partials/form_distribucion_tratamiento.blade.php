<div class="text-center"><label><b>CLASIFICACIÓN DE ENFERMEDADES</b></label></div>
<div class="col-md-6">
    <div class="form-group">
        <div class="input-group">
            <div class="input-group ui-widget">
              <input type="text" id="enfermedades" name="enfermedades" class="form-control">
                <div class="input-group-append">
                    <button type="button" class="btn btn-primary" onclick="agregar_enfermedad()">
                        <i class="fa fa-plus-circle"></i> Agregar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12" id="div_enfermedades">
    <form id="form_enfermedades">
    <div class="row">
        @isset($cie10Tratamiento)
            @foreach($cie10Tratamiento as $x => $cei10T)
                <div class='col-md-3' id='div_enf_{{$x+1}}'>
                    <input type='hidden' id='enf_{{$x+1}}' class='enf' name='enf_{{$x+1}}' value='{{$cei10T->codigo}}'>
                    <label> {{$cei10T->descripcion}} <i onclick='eliminar_enfermedad({{$x+1}})' class='fa fa-times-circle' style='cursor:pointer;'></i></label>
                </div>
            @endforeach
        @endisset
    </div>
    </form>
</div>
<hr />
<div class="text-center"><label><b>DISTRIBUCIÓN DEL PRODUCTO</b></label></div>
<form class="form form-horizontal" id="form_table_distribucion" name="form_table_distribucion">
    <div class="form-group row">
        <div class="col-sm-12" id="div_estadio_tratamiento">
            <div class="row">
                <div class="col-sm-6">
                    <label>Estadio del tratamiento</label>
                    <button type="button" class="btn btn-primary btn-sm" onclick="agregar_input_fase()" title="Agergar campo">
                        <i class="fa fa-plus"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="borrar_input_fase()" title="Eliminar campo">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                <div class="col-sm-6 text-right">
                <select id="calculo_intervalo" name="calculo_intervalo" style="height: 38px;position: relative;top: 1px;border:1px solid #f3f3f3"
                       title="Seleccione el tipo de cálculo que se usará para las fechas del tratamiento" onchange="error(this.id)">
                    <option value="0" {{isset($detalleTratamiento->calculo_intervalo) ? ($detalleTratamiento->calculo_intervalo ? "" : "selected") : "" }}> Desde la última dosis de cada ciclo </option>
                    <option value="1" {{isset($detalleTratamiento->calculo_intervalo) ? ($detalleTratamiento->calculo_intervalo ? "selected" : "") : "" }}> Desde el comienzo del tratamiento </option>
                </select>
                <button type="button" class="btn btn-success btn_store" onclick="generar_formato();">
                    <i class="fa fa-cog"></i>
                    Generar formato
                </button>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <div class="form-group" style="margin-bottom: 30px">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Fase 1</span></div>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <select id="intevalo_1" name="intevalo_1" required title="Selecciona el intervalo de tiempo de la aplicación"
                                                style="border: none;width: 85px;height: 26px;text-align: center">
                                            <option  disabled selected>Intervalo</option>
                                            <option value="1" {{isset($detalleTratamiento->distribucion_tratamiento[0]->intervalo) ? ($detalleTratamiento->distribucion_tratamiento[0]->intervalo == 1 ? "selected" : "") : ""}}>Días</option>
                                            <option value="2" {{isset($detalleTratamiento->distribucion_tratamiento[0]->intervalo) ? ($detalleTratamiento->distribucion_tratamiento[0]->intervalo == 2 ? "selected" : "") : ""}}>Semana</option>
                                            <option value="3" {{isset($detalleTratamiento->distribucion_tratamiento[0]->intervalo) ? ($detalleTratamiento->distribucion_tratamiento[0]->intervalo == 3 ? "selected" : "") : ""}}>Mes</option>
                                        </select>
                                        <span id="span_error_1"></span>
                                    </span>
                                </div>
                                <div class="input-group-prepend"><span class="input-group-text" > T. inical</span></div>
                                <input title="Escriba la cantidad de las aplicaciones del producto para esta fase" placeholder="Aplicaciones"
                                       value="{{isset($detalleTratamiento->distribucion_tratamiento[0]->cantidad_aplicacion) ? $detalleTratamiento->distribucion_tratamiento[0]->cantidad_aplicacion : ""}}"
                                       type="number" class="form-control cantidad_aplicacion" id="cantidad_aplicacion_1" name="cantidad_aplicacion_1" min="1" style="text-align: center;" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group" style="margin-bottom: 20px">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-prepend"><span class="input-group-text">Fase 2</span></div>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <select id="intevalo_2" name="intevalo_2" required title="Selecciona el intervalo de tiempo de la aplicación"
                                                style="border: none;width: 85px;height: 26px;text-align: center">
                                            <option  disabled selected>Intervalo</option>
                                            <option value="1" {{isset($detalleTratamiento->distribucion_tratamiento[1]->intervalo) ? ($detalleTratamiento->distribucion_tratamiento[1]->intervalo == 1 ? "selected" : "") : ""}}>Días</option>
                                            <option value="2" {{isset($detalleTratamiento->distribucion_tratamiento[1]->intervalo) ? ($detalleTratamiento->distribucion_tratamiento[1]->intervalo == 2 ? "selected" : "") : ""}}>Semana</option>
                                            <option value="3" {{isset($detalleTratamiento->distribucion_tratamiento[1]->intervalo) ? ($detalleTratamiento->distribucion_tratamiento[1]->intervalo == 3 ? "selected" : "") : ""}}>Mes</option>
                                        </select>
                                        <span id="span_error_2"></span>
                                    </span>
                                </div>
                                <input title="Escriba la cantidad del tiempo del intervalo seleccionado" placeholder="Tiempo"
                                       type="number" class="form-control cantidad_intervalo" id="cantidad_intervalo_2"
                                       value="{{isset($detalleTratamiento->distribucion_tratamiento[1]->cantidad_intervalo) ? $detalleTratamiento->distribucion_tratamiento[1]->cantidad_intervalo : ""}}"
                                       name="cantidad_intervalo_2" min="1" style="text-align: center;" required>
                                <input title="Escriba la cantidad de las aplicaciones del producto para esta fase" placeholder="Aplicaciones"
                                       type="number" class="form-control cantidad_aplicacion" id="cantidad_aplicacion_2" name="cantidad_aplicacion_2"
                                       value="{{isset($detalleTratamiento->distribucion_tratamiento[1]->cantidad_aplicacion) ? $detalleTratamiento->distribucion_tratamiento[1]->cantidad_aplicacion : ""}}" min="1" style="text-align: center;" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group" style="margin-bottom: 20px">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-prepend"><span class="input-group-text">Fase 3</span></div>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <select id="intevalo_3" name="intevalo_3" required title="Selecciona el intervalo de tiempo de la aplicación"
                                                style="border: none;width: 85px;height: 26px;text-align: center">
                                            <option  disabled selected>Intervalo</option>
                                            <option value="1" {{isset($detalleTratamiento->distribucion_tratamiento[2]->intervalo) ? ($detalleTratamiento->distribucion_tratamiento[2]->intervalo == 1 ? "selected" : "") : ""}}>Días</option>
                                            <option value="2" {{isset($detalleTratamiento->distribucion_tratamiento[2]->intervalo) ? ($detalleTratamiento->distribucion_tratamiento[2]->intervalo == 2 ? "selected" : "") : ""}}>Semana</option>
                                            <option value="3" {{isset($detalleTratamiento->distribucion_tratamiento[2]->intervalo) ? ($detalleTratamiento->distribucion_tratamiento[2]->intervalo == 3 ? "selected" : "") : ""}}>Mes</option>
                                        </select>
                                        <span id="span_error_3"></span>
                                    </span>
                                </div>
                                <input title="Escriba la cantidad del tiempo del intervalo seleccionado" placeholder="Tiempo"
                                       type="number" class="form-control cantidad_intervalo" id="cantidad_intervalo_3"
                                       value="{{isset($detalleTratamiento->distribucion_tratamiento[2]->cantidad_intervalo) ? $detalleTratamiento->distribucion_tratamiento[2]->cantidad_intervalo : ""}}"
                                       name="cantidad_intervalo_3" min="1" style="text-align: center;" required>
                                <input title="Escriba la cantidad de las aplicaciones del producto para esta fase" placeholder="Productos"
                                       type="number" class="form-control cantidad_aplicacion" id="cantidad_aplicacion_3"
                                       value="{{isset($detalleTratamiento->distribucion_tratamiento[2]->cantidad_aplicacion) ? $detalleTratamiento->distribucion_tratamiento[2]->cantidad_aplicacion : ""}}"
                                       name="cantidad_aplicacion_3" min="1" style="text-align: center;" required>
                            </div>
                        </div>
                    </div>
                </div>
                @isset($detalleTratamiento->distribucion_tratamiento)
                    @for($x=3;$x<count($detalleTratamiento->distribucion_tratamiento);$x++)
                        <div class="col-sm-4">
                            <div class="form-group" style="margin-bottom: 20px">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-prepend"><span class="input-group-text">Fase {{$x+1}}</span></div>
                                        <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <select id="intevalo_{{$x+1}}" name="intevalo_{{$x+1}}" required title="Selecciona el intervalo de tiempo de la aplicación"
                                                style="border: none;width: 85px;height: 26px;text-align: center">
                                            <option value="" disabled selected>Intervalo</option>
                                            <option value="1" {{isset($detalleTratamiento->distribucion_tratamiento[$x]->intervalo) ? ($detalleTratamiento->distribucion_tratamiento[$x]->intervalo == 1 ? "selected" : "") : ""}}>Días</option>
                                            <option value="2" {{isset($detalleTratamiento->distribucion_tratamiento[$x]->intervalo) ? ($detalleTratamiento->distribucion_tratamiento[$x]->intervalo == 2 ? "selected" : "") : ""}}>Semana</option>
                                            <option value="3" {{isset($detalleTratamiento->distribucion_tratamiento[$x]->intervalo) ? ($detalleTratamiento->distribucion_tratamiento[$x]->intervalo == 3 ? "selected" : "") : ""}}>Mes</option>
                                        </select>
                                        <span id="span_error_{{$x+1}}"></span>
                                    </span>
                                        </div>
                                        <input title="Escriba la cantidad del tiempo del intervalo seleccionado" placeholder="Tiempo"
                                               type="number" class="form-control cantidad_intervalo" id="cantidad_intervalo_{{$x+1}}"
                                               value="{{isset($detalleTratamiento->distribucion_tratamiento[$x]->cantidad_intervalo) ? $detalleTratamiento->distribucion_tratamiento[$x]->cantidad_intervalo : ""}}"
                                               name="cantidad_intervalo_{{$x+1}}" min="1"style="text-align: center;" required>
                                        <input title="Escriba la cantidad de las aplicaciones del producto para esta fase" placeholder="Aplicaciones"
                                               type="number" class="form-control cantidad_aplicacion" id="cantidad_aplicacion_{{$x+1}}" name="cantidad_aplicacion_{{$x+1}}"
                                               value="{{isset($detalleTratamiento->distribucion_tratamiento[$x]->cantidad_aplicacion) ? $detalleTratamiento->distribucion_tratamiento[$x]->cantidad_aplicacion : ""}}"
                                               min="1" style="text-align: center;" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                @endisset
            </div>
        </div>
        <div class="col-sm-12" id="formato_distribucion" style="width: 100%;overflow: auto"></div>
        <div class="col-sm-12 botones d-none">
            <div class="col-sm-12 form-group text-center" style="margin-top: 20px">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-ban" aria-hidden="true"></i>
                    Cerrar
                </button>
                <button type="button" class="btn btn-primary" onclick="store_distribucion_tratamiento('{{$id_tratamiento}}')">
                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                    Guardar
                </button>
            </div>
        </div>
    </div>
</form>
<style>
    ul.ui-menu{
        background: white;
        z-index: 999999999;
        width: 500px;
        height: 400px;
        overflow: auto;
    }
</style>
<script>
    function agregar_input_fase() {

        cant_input = $("div#div_estadio_tratamiento input.cantidad_aplicacion").length;
        datos = {
            x : parseInt(cant_input)+1
        };
        load("show");
        $.get('{{url('tratamiento/input_distribucion_tratamiento')}}', datos, function (retorno) {
            $("#div_estadio_tratamiento div.row").append(retorno);
        }).always(function () {
            load("hide");
        });


    }

    function borrar_input_fase() {
        cant_input = $("div#div_estadio_tratamiento input.cantidad_aplicacion").length;
        if(cant_input > 1){
            $("div#div_estadio_tratamiento div.col-sm-4:last-child").remove()
        }
    }

    function generar_formato(id_tratamiento) {
        load("show");
        data = [];
        $.each($("div#div_estadio_tratamiento input.cantidad_aplicacion"), function (i,j) {
            data.push({
                intervalo : $("#intevalo_"+(i+1)).val(),
                cantidad_intervalo : $("#cantidad_intervalo_"+(i+1)).val(),
                cantidad_aplicacion : j.value
            });
        });

        data = {
            data :data,
            id_tratamiento:id_tratamiento
        };

        $.get('{{url('tratamiento/formato_distribucion_tratamiento')}}', data, function (retorno) {
            $("#formato_distribucion").html(retorno);
            $(".botones").removeClass('d-none');
        }).always(function () {
            load("hide");
        });
    }

    $(function() {
        $.get('{{url('tratamiento/listado_enfermedades')}}',{}, function (retorno) {

            $("#enfermedades").autocomplete({
                source: retorno
            });
        });
    });

    function agregar_enfermedad(){

        cant_enf =parseInt($("#div_enfermedades div.row input").length)+1;

        if($('#enfermedades').val() != ""){
            html = "<div class='col-md-3' id='div_enf_"+cant_enf+"'>" +
                "<input type='hidden' id='enf_"+cant_enf+"' class='enf' name='enf_"+cant_enf+"' value='"+$('#enfermedades').val().split(")")[0]+"'>" +
                "<label>"+ $('#enfermedades').val() +" <i onclick='eliminar_enfermedad("+cant_enf+")' class='fa fa-times-circle' style='cursor:pointer;'></i></label>"+
                "</div>";

            $("#div_enfermedades div.row").append(html);
            $('#enfermedades').val(" ");
        }

    }

    function eliminar_enfermedad(id) {
        $("#div_enf_"+id).remove();
    }

    generar_formato('{{$id_tratamiento}}');

</script>