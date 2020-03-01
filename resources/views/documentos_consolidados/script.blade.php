<script>


    function addRolDocumentoConsolidado() {
        html = $(".ejecuta_proceso").html();
        $("#otros_roles_documento_consolidado").append(html);
    }
    
    function deleteRolDocumentoConsolidado() {
        $("#otros_roles_documento_consolidado div.form-row").last().remove()
    }
    
    $(window).ready(function () { habilitaCorreos() });

    function habilitaCorreos() {
        $.each($(".enviar_correo"),function (i,j) {
            if(parseInt(j.value) === 1){
                $("#correos").removeClass('d-none');
                return false;
            }else{
                $("#correos").addClass('d-none');
            }
        });
    }
    
    function addCorreoDocumentoConsolidado() {
        html='<div class="col-md-4">'+ $("#input_correo").html() +'</div>';
        //console.log($(html));
        $("#correos_anexos").append(html);

    }
    
    function deleteCorreoDocumentoConsolidado() {

        objDOM = $("#correos_anexos div.col-md-4");

        if(objDOM.length > 1)
            objDOM.last().remove()
    }
    
    function store_documento_consolidado(id_tratamiento) {
        //if($("#form_documento_consolidado").valid()){
            confirmar = confirm("¿Esta seguro que desea guardar esta configuración para consolidar los documentos del tratamiento?");
            if (confirmar) {
                load("show");

                arrRoles= [];
                if(!$("div.ejecuta_proceso").has('d-none')){
                    arrRoles.push({
                        rol: $("div.ejecuta_proceso select#usuario").val(),
                        correo:  $("div.ejecuta_proceso select#enviar_correo").val(),
                        firma: $("div.ejecuta_proceso select#firma_electronica").val(),
                    });
                }


                $.each($("div#otros_roles_documento_consolidado div.form-row"),function (i,j) {
                    objDOM = $(j);
                    arrRoles.push({
                        rol: objDOM.find('select#usuario').val(),
                        correo: objDOM.find('select#enviar_correo').val(),
                        firma: objDOM.find('select#firma_electronica').val(),
                    });
                });

                arrCorreos = [];
                $.each($("div#correos_anexos input#correo"),function (i,j) {
                    arrCorreos.push(j.value);
                });

                data = {
                    id_tratamiento: id_tratamiento,
                    nombre_documento_consolidado : $("#nombre_documento_consolidado").val(),
                    arrRoles : arrRoles,
                    arrCorreos : arrCorreos
                };
                peticion_ajax(data, '{{url('documento_consolidado/store_configuracion')}}', 'POST');
            }
        //}
    }
</script>