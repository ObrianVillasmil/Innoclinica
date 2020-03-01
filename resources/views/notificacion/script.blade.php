<script>

    $(window).ready(function () {
        if($("#tipo_notificacion").val() == 1){
            if(CKEDITOR.instances['mensaje'] === undefined)
                CKEDITOR.replace( 'mensaje' );
        }
    });

    function partial_otros(input,resert=false) {
        load("show");

        if($("input#otros").is(":checked")){
            datos = {
                tipo_notificacion : $("#tipo_notificacion").val()
            };
            $.get('{{url('notificacion/partials_otros')}}', datos, function (response) {

                if(resert){
                    $("#body_notificacion_otros").empty();
                    $("#body_notificacion_otros").html(response);
                }else{
                    $("#body_notificacion_otros").append(response);
                }
                if(datos.tipo_notificacion == 1) {
                    if (CKEDITOR.instances['mensaje'] !== undefined) {
                        if (CKEDITOR.instances['mensaje'].status !== "ready")
                            CKEDITOR.replace('mensaje');
                    } else {
                        CKEDITOR.replace('mensaje');
                    }

                }else{
                    if(CKEDITOR.instances['mensaje'].status === "ready")
                        CKEDITOR.instances['mensaje'].destroy();
                }
            }).always(function () {
                load("hide");
            });
        }else{

            $("#body_notificacion_otros").empty();

            if($("#tipo_notificacion").val() == 1) {
                if (CKEDITOR.instances['mensaje'] !== undefined) {
                    if (CKEDITOR.instances['mensaje'].status !== "ready")
                        CKEDITOR.replace('mensaje');
                } else {
                    CKEDITOR.replace('mensaje');
                }

            }else{
                if(CKEDITOR.instances['mensaje'].status === "ready")
                    CKEDITOR.instances['mensaje'].destroy();
            }
            load("hide");
        }
    }

    function storeNotificacion(id_notificacion){
        arr_data = [];
        $.each($("input[type=checkbox]"),function(i,j){
            if($(this).is(":checked")){
                if(j.id === "administrador")
                    arr_data.push({administrador:true});
                if(j.id === "representante_legal")
                    arr_data.push({representante_legal:true});
                if(j.id === "paciente")
                    arr_data.push({paciente:true});
                if(j.id === "otros")
                    arr_data.push({otros:true});
            }

        });

        $data_otros = [];
        for (let x=0; x< arr_data.length; x++){
            if(arr_data[x].otros === true){
                $.each($("#body_notificacion_otros div.col-md-6 input"),function (i,j) {
                    $data_otros.push({
                        text : $(j).val()
                    });
                });
            }
        }


        data = {
            arr_data : arr_data,
            nombre : $("#nombre").val(),
            tipo_notificacion : $("#tipo_notificacion").val(),
            mensaje : $("#tipo_notificacion").val() !== "1" ?  $("#mensaje").val() :  CKEDITOR.instances['mensaje'].getData(),
            data_otros : $data_otros,
            id_notificacion : id_notificacion,
            icono : $("#icono_proceso").val()
        };

        peticion_ajax(data,'{{'/notificacion/store_notificacion'}}','POST');
    }

    function agregar_input() {
        html = "<div class='col-md-6'>" + $("#body_notificacion_otros div.col-md-6").html() + "</div>";
        $("#body_notificacion_otros").append(html);
    }

    function quitar_input() {
        cant_input = $("#body_notificacion_otros div.col-md-6").length;

        if(cant_input > 1)
            $("#body_notificacion_otros div.col-md-6:last-child").remove();
    }

    function delete_notificacion(id_notificacion) {
        confirmar = confirm("¿Esta seguro que desea elminar esta notificación?");
        if (confirmar) {
            load("show");
            data = {
                id_notificacion : id_notificacion
            };
            peticion_ajax(data,'{{'/notificacion/delete_notificacion'}}','POST');
        }

    }




</script>