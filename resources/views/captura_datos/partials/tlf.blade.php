<div class="form-group col-md-6 div_tlf div_tlf_{{$cant}}" id="div_tlf_{{$cant}}">
    <label>Teléfono</label>
    <div class="input-group">
        <div class="input-group-append">
            <button type="button" class="btn btn-outline-light">
                <input id="tlf_requerido_{{$cant}}" type="checkbox" class="form-control-custom">
                <label for="tlf_requerido_{{$cant}}" title="Hacer que el campo sea obligatorio" style="bottom: 12px;"></label>
            </button>
        </div>
        <input type="tel" id="campo_tlf_{{$cant}}" readonly name="campo_tlf_{{$cant}}" placeholder="Teléfono" class="form-control">
        <div class="input-group-append">
            <button type="button" class="btn btn-danger" title="Eliminar campo" onclick="deleteCampo('div_tlf_{{$cant}}')">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    </div>
</div>
