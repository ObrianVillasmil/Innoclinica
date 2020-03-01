<div class="col-sm-4">
    <div class="form-group">
        <div class="form-group" style="margin-bottom: 30px">
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">Fase {{$x}}</span></div>
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <select id="intevalo_{{$x}}" name="intevalo_{{$x}}" required title="Selecciona el intervalo de tiempo de la aplicación"
                                style="border: none;width: 85px;height: 26px;text-align: center">
                            <option value="" disabled selected>Intervalo</option>
                            <option value="1">Días</option>
                            <option value="2">Semana</option>
                            <option value="3">Mes</option>
                        </select>
                        <span id="span_error_{{$x}}"></span>
                    </span>
                </div>
                <input title="Escriba la cantidad del tiempo del intervalo seleccionado" placeholder="Tiempo"
                       type="number" class="form-control cantidad_intervalo" id="cantidad_intervalo_{{$x}}" name="cantidad_intervalo_{{$x}}" min="1" value="" style="text-align: center;" required>
                <input title="Escriba la cantidad de las aplicaciones del producto para esta fase" placeholder="Aplicaciones"
                       type="number" class="form-control cantidad_aplicacion" id="cantidad_aplicacion_{{$x}}" name="cantidad_aplicacion_{{$x}}" min="1" value="" style="text-align: center;" required>
            </div>
        </div>
    </div>
</div>