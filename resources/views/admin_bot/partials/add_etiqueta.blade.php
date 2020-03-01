<div class="row">
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-primary btn-sm" title="Agregar etiqueta" onclick="add_input_etiqueta()">
            <i class="fa fa-plus-square"></i>
        </button>
    </div>
</div>
<div class="form-group pt-2">
    <form id="form_etiqueta">
        <div class="row div_inputs">
            @if($etiquetaChatTema->count() > 0)
                @foreach($etiquetaChatTema as $x => $eCt)
                    <div class="col-md-4 input_etiqueta" id="etiqueta_{{$x+1}}">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm etiqueta" minlength="2" maxlength="100"
                                       value="{{$eCt->nombre}}" placeholder="Etiqueta" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger btn-sm" title='Eliminar etiqueta'
                                            id="etiqueta_{{$x+1}}" onclick="delete_etiqueta(this.id)">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-md-4 input_etiqueta" id="etiqueta_1">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm etiqueta" minlength="2" maxlength="100"
                                   value="" placeholder="Etiqueta" required>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-danger btn-sm" title='Eliminar etiqueta'
                                        id="etiqueta_1" onclick="delete_etiqueta(this.id)">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="row text-center">
            <div class="col-md-12">
            <button type="button" class="btn btn-primary" title='Guardar etiquetas'
                    id="etiqueta_1" onclick="store_etiqueta('{{$idChatTema}}')">
                <i class="fa fa-floppy-o"></i> Guardar
            </button>
            </div>
        </div>
    </form>
</div>
