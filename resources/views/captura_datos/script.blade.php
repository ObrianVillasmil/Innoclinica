<script>

    function crearInput(input){

        switch (input) {
            case "texto":
                if($("div.div_texto").length > 1) return false;
                cant = $(".div_texto").length+1;
                break;

            case "mail":
                if($("div.div_mail_1").length > 0) return false;
                cant = $(".div_mail").length+1;
                break;

            case "tlf":
                if($("div.div_tlf_1").length > 0) return false;
                cant = $(".div_tlf").length+1;
                break;

            case "doctor":
                if($("div.div_texto_doctor_1").length > 0) return false;
                cant = $(".div_texto_doctor").length+1;
                $(".notificar_doctor").removeClass('d-none');
                $(".descripcion_proceso").removeClass('col-md-12').addClass('col-md-8');
                break;
        }

        load("show");

        data = {
            input : input,
            cant: cant
        };

        $.get('{{url('captura_dato/add_campo')}}',data, function (retorno) {
            $("#body_form_captura_datos").append(retorno);
            $(".btn_store_form_captura_datos").removeClass('d-none');
            $(".alert").addClass('d-none');
            load("hide");
        });

    }
    
    function deleteCampo(div) {

        $cant = $("#body_form_captura_datos div.col-md-6").length;

        if($cant === 1){
            $(".btn_store_form_captura_datos").addClass('d-none');
            $(".alert").removeClass('d-none');
            $(".descripcion_proceso").removeClass('col-md-8').addClass('col-md-12');

        }

        if(div.split("_")[2] === "doctor"){
            $(".notificar_doctor").addClass('d-none');
            $(".descripcion_proceso").removeClass('col-md-8').addClass('col-md-12');
        }

        $("."+div).remove();

    }
    
    function storeCapturaDatos(id_captura_datos) {
        if ($('#form_crear_notificacion').valid()) {
            load("show");

            doctor =[];
            obj_doctor = [];
            mail = [];
            tlf = [];
            texto = [];

            $.each($(".div_texto_doctor"),function (i,j) {
                doctor.push({
                    'id':j.id,
                    'required':$("#text_doctor_requerido_"+j.id.split("_")[3]).is(":checked")
                });
            });

            $.each($(".div_mail"),function (i,j) {
                mail.push({
                    'id':j.id,
                    'required':$("#mail_requerido_"+j.id.split("_")[2]).is(":checked")
                });
            });

            $.each($(".div_tlf"),function (i,j) {
                tlf.push({
                    'id':j.id,
                    'required':$("#tlf_requerido_"+j.id.split("_")[2]).is(":checked")
                });
            });

            $.each($(".div_texto"),function (i,j) {
                texto.push({
                    'id':j.id,
                    'label':$("#campo_texto_"+j.id.split("_")[2]).val(),
                    'required': $("#text_requerido_"+j.id.split("_")[2]).is(":checked")
                });
            });

            data = {
                doctor : doctor,
                mail : mail,
                tlf : tlf,
                texto : texto,
                notifica_doctor : $("#notificar_doctor").val(),
                solcitiud_tratamiento : $("#solcitiud_tratamiento").val(),
                nombre : $("#nombre").val(),
                descripcion : $("#descripcion").val(),
                usuario : $("#usuario").val(),
                id_captura_datos : id_captura_datos,
                icono : $("#icono_proceso").val()
            };
            peticion_ajax(data,'{{url('captura_dato/store_captura_datos')}}','POST');
        }
    }

    function deleteCapturaDatos(id_captura_dato){
        confirmar = confirm("Esta seguro que desea borrar el proceso de Captura de datos?");
        if(confirmar){
            data = {
                id_captura_dato : id_captura_dato
            };
            peticion_ajax(data,'{{url('captura_dato/delete_captura_datos')}}','POST');
        }
    }

</script>