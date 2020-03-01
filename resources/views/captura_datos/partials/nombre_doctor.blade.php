<div class="form-group col-md-6 div_texto_doctor div_texto_doctor_{{$cant}}" id="div_texto_doctor_{{$cant}}" >
    <label>Nombre del doctor</label>
    <div class="input-group">
        <div class="input-group-append">
            <button type="button" class="btn btn-outline-light">
                <input id="text_doctor_requerido_{{$cant}}" type="checkbox" value=""readonly class="form-control-custom">
                <label for="text_doctor_requerido_{{$cant}}" title="Hacer que el campo sea obligatorio" style="bottom: 12px;"></label>
            </button>
        </div>
        <input type="text" id="campo_texto_doctor_{{$cant}}" readonly name="campo_texto_doctor_{{$cant}}" placeholder="Nombre doctor" class="form-control">
        <div class="input-group-append">
            <button type="button" class="btn btn-danger" title="Eliminar campo" onclick="deleteCampo('div_texto_doctor_{{$cant}}')">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    </div>
</div>
