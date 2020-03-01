<script>

    function storeCargaArchivo(id_carga_archivo){
        if($("#form_crear_documento").valid()){
            load("show");
            data = {
                id_carga_archivo : id_carga_archivo,
                nombre : $("#nombre").val(),
                descripcion : $("#descripcion").val(),
                carpeta : $("#carpeta").val(),
                usuario : $("#usuario").val(),
                notificacion : $("#notificacion").val(),
                notificacion_doctor : $("#notificacion_doctor").val(),
                solicitud_tratamiento : $("#solicitud_tratamiento").val(),
                icono : $("#icono_proceso").val()
            };
            console.log(data);
            peticion_ajax(data,'{{'/carga_archivo/store_carga_archivo'}}','POST',false);
        }
    }

    function deleteCargaArchivo(id_carga_archivo) {
        confirmar = confirm("Esta seguro que desea borrar el proceso de Carga de archivos?");
        if(confirmar){
            data = {  id_carga_archivo : id_carga_archivo  };
            peticion_ajax(data,'{{'/carga_archivo/delete_carga_archivo'}}','POST');
        }
    }


</script>