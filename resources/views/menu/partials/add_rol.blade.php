<form class="form form-horizontal">
    <input type="hidden" id="id_rol" value="{{isset($rol->id_rol) ? $rol->id_rol : ""}}">
    <div class="form-group row">
        <div class="col-sm-12">
            <div class="form-group">
                <div class="form-group">
                    <input type="text" class="form-control" minlength="2" maxlength="100" id="nombre_rol"
                           placeholder="Ingrese el ID del rol separados por '_'" value="{{isset($rol->role_type_id) ? $rol->role_type_id : ""}}" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" minlength="2" maxlength="100" id="descripcion"
                           placeholder="Ingrese la descripción" value="{{isset($rol->descripcion) ? $rol->descripcion : ""}}" required>
                </div>
                <div class="form-group text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-ban" aria-hidden="true"></i>
                        Cerrar
                    </button>
                    <button type="button" class="btn btn-primary btn_store">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>