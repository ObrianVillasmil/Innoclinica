<div class="form-group col-md-6 div_mail div_mail_{{$cant}}" id="div_mail_{{$cant}}" >
    <label>Correo electrónico</label>
    <div class="input-group">
        <div class="input-group-append">
            <button type="button" class="btn btn-outline-light">
                <input id="mail_requerido_{{$cant}}" type="checkbox" value=""readonly class="form-control-custom">
                <label for="mail_requerido_{{$cant}}" title="Hacer que el campo sea obligatorio" style="bottom: 12px;"></label>
            </button>
        </div>
        <input type="mail" id="campo_mail_{{$cant}}" readonly name="campo_mail_{{$cant}}" placeholder="Correo" class="form-control">
        <div class="input-group-append">
            <button type="button" class="btn btn-danger" title="Eliminar campo" onclick="deleteCampo('div_mail_{{$cant}}')">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    </div>
</div>
