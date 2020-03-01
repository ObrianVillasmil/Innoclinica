    <div class="form-group row">
        <div class="col-sm-12">
            <form id="form_tema">
                <div class="form-group">
                    <div class="form-group" style="margin-bottom: 30px">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Nombre del tema</span></div>
                            <input type="text" class="form-control input_tema" minlength="2" maxlength="100" id="tema"
                                   value="{{isset($tema->nombre) ? $tema->nombre : "" }}" required>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" onclick="store_tema('{{isset($tema->id_chat_tema) ? $tema->id_chat_tema : "" }}')">
                                    <i class="fa fa-floppy-o"></i>
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<style>
    #tema-error{
        position: absolute;
        top: 40px
    }
</style>
