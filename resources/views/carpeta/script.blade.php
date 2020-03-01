<script>


    /*$("#nombre_carpeta").keyup(function () {
        console.log("hola");
        a = $("#nombre_carpeta").val().toLowerCase();
        $("#nombre_carpeta").val(a)
    });*/

    function storeCarpeta() {
        if($("#form_crear_carpeta").valid()){
            load("show");
            data = {  nombre_carpeta : $("#nombre_carpeta").val().trim() };
            peticion_ajax(data,'{{'/carpeta/store_carpeta'}}','POST');
        }
    }

    function deleteCarpeta(carpeta) {
        confirmar = confirm("¿Esta seguro de eliminar esta carpeta.?");
        if(confirmar){
            data = {  nombre_carpeta : carpeta.trim() };
            peticion_ajax(data,'{{'/carpeta/delete_carpeta'}}','POST');
        }
    }

</script>