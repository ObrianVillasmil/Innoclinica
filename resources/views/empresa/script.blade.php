<script>

    verificar_rol();

    function storeDatosEmpresa(){
        verificar_rol();
        if($("#form_datos_empresa").valid()) {
            load("show");
            data = {
                nombre: $("#nombre").val(),
                moneda : $("#moneda").val(),
                pais: $("#pais").val(),
                ruc: $("#ruc").val(),
                direccion: $("#direccion").val(),
            };
            peticion_ajax(data, '{{url('empresa/actualizar_datos_empresa')}}', 'POST');
        }
    }

    function storeDatosRepresentante(){
        if($("#form_datos_representante_general").valid()) {
            load("show");
            data = {
                nombre_representante: $("#nombre_representante").val(),
                apellido_representante : $("#apellido_representante").val(),
                identificacion_representante: $("#identificacion_representante").val(),
                telefono: $("#telefono").val(),
                correo_representante: $("#correo_representante").val(),
            };
            peticion_ajax(data, '{{url('empresa/actualizar_datos_representante')}}', 'POST');
        }
    }
    
    function storeVisualizacion() {
        verificar_rol();
        if($("#form_visualizacion").valid()) {
            load("show");
            var formData = new FormData($("#form_visualizacion")[0]);
            $.ajax({
                method : 'POST',
                url    : '{{url('empresa/actualizar_vizualizacion')}}',
                data   : formData,
                processData: false,
                contentType: false,

                success:function (response) {
                    modal('store_visualizacion',response.msg,'Acción',false,'50%',true);
                    load("hide");
                }
            });
        }
    }
    
    function storeTerminosCondiciones() {
        verificar_rol();
        if($("#form_terminos_condiciones").valid()) {
            load("show");
            data = { terminos_condiciones : CKEDITOR.instances['editor'].getData() };
            peticion_ajax(data, '{{url('empresa/store_terminos_condiciones')}}', 'POST');
        }
    }

    function store_configuracion_inventario(){

        if($("#from_configuracion_inventario").valid()) {
            load("show");
            data = {
                intervalo_inventario: $("#intervalo_inventario").val(),
                cantidad : $("#cantidad").val(),
                correo_1 : $("#correo_1").val(),
                correo_2 : $("#correo_2").val(),
                correo_3 : $("#correo_3").val(),
            };
            peticion_ajax(data, '{{url('empresa/store_variables_inventario')}}', 'POST');
        }

    }

    CKEDITOR.replace( 'editor' );
</script>