<form class="form form-horizontal cargar_archivos_tramiento_solicitado" enctype="multipart/form-data">
    <div class="form-group row">
        <div class="col-sm-12">
            <div class="form-group">
                <div class="form-group">
                    <label for="documento">Seleccione los documentos a cargar</label>
                    <input type="file" name="documento[]" id="documento" class="form-control"
                           accept="application/pdf" multiple required>
                </div>
                <div class="form-group text-right">
                    <button type="button" class="btn btn-primary btn_store">
                        <i class="fa fa-floppy-o"></i>
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>