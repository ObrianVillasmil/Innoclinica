<script>

    function store_documento(id_documento){

        verificar_rol();
        if($("#form_crear_documento").valid()) {
            load("show");
            var formData = new FormData($("#form_crear_documento")[0]);
            formData.append('cuerpo_documento', CKEDITOR.instances['editor'].getData());
            formData.append('icono', $("#icono_proceso").val());
            if(id_documento !== "") formData.append('id_documento', id_documento);
            $.ajax({
                method : 'POST',
                url    : '{{url('documento/store_documento')}}',
                data   : formData,
                processData: false,
                contentType: false,

                success:function (response) {
                    load("hide");
                    modal('store_documento',response.msg,'Acción',false,'50%',false);
                }
            });
        }
    }

    function eliminarDocumento(id_documento) {
        confirmar = confirm("¿Esta seguro que desea elminar el documento?");
        if (confirmar) {
            load("show");
            data = {
                id_documento: id_documento
            };
            peticion_ajax(data, '{{url('documento/eliminar_documento')}}', 'POST');
        }
    }

    function uploadDocumento(id_documento) {
        verificar_rol();
        if($("#form_crear_documento").valid()) {
            load("show");
            var formData = new FormData($("#form_cargar_documento")[0]);
            formData.append('id_documento',id_documento);
            formData.append('icono',$("#icono_proceso").val());
            $.ajax({
                method : 'POST',
                url    : '{{url('documento/subir_documento')}}',
                data   : formData,
                processData: false,
                contentType: false,
                success:function (response) {
                    load("hide");
                    modal('upload_documento',response.msg,'Acción',false,'50%',true);
                }
            });
        }
    }


    CKEDITOR.replace( 'editor' );
</script>