<script>

    function eliminarFormatoTratamiento(id_tratamiento){
        confirmar = confirm("¿Esta seguro de eliminar la distribución del tratamiento?");
        if(confirmar){
            data = {
                id_tratamiento : id_tratamiento,
            };
            peticion_ajax(data,'{{url('distribucion_tratamiento/delete_distribucion_tratamiento')}}','POST');
        }
    }
</script>