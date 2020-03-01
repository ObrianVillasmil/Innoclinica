<script>

    function crear_tema(id_chat_tema) {
        load("show");
        data = {
            id_chat_tema : id_chat_tema,
        };

        $.post("{{url('administracion_bot/crear_tema')}}", data, function (retorno) {
            modal('tema_chat_bot', retorno,'<i class="fa fa-comments"></i> Tema',true,'50%',true);
        }).always(function () {
            load("hide");
        });
    }
    
    function store_tema(id_chat_tema) {

        if($("#form_tema").valid()){
            data = {
                id_chat_tema : id_chat_tema,
                tema : $(".input_tema").val()
            };
            peticion_ajax(data,'{{url('administracion_bot/store_tema')}}','POST',undefined);
        }

    }

    function delete_tema(id_chat_tema){
        data = {
            id_tema : id_chat_tema,
        };
        peticion_ajax(data,'{{url('administracion_bot/delete_tema')}}','POST',undefined);
    }

    function add_etiqueta(id_chat_tema){
        load("show");
        data = {
            id_chat_tema : id_chat_tema
        };
        $.post('{{url('administracion_bot/add_etiqueta')}}',data, function (retorno) {
            modal('tema_chat_bot', retorno, '<i class="fa fa-tags"></i> Etiquetas',true,'50%',undefined);
        }).always(function () {
            load("hide");
        });
    }
    
    function add_input_etiqueta() {

        var cant = $(".input_etiqueta").length;

        html = "<div class='col-md-4 input_etiqueta' id='etiqueta_"+(cant+1)+"'> "+
                    "<div class='form-group'>"+
                        "<div class='input-group'>"+
                            "<input type='text' class='form-control form-control-sm etiqueta'" +
                                " placeholder='Etiqueta' minlength='2' maxlength='100'>"+
                            "<div class='input-group-append'>"+
                                "<button type='button' class='btn btn-danger btn-sm' title='Eliminar etiqueta' " +
                                    "id='etiqueta_"+(cant+1)+"' onclick='delete_etiqueta(this.id)'>" +
                                    "<i class='fa fa-times'></i>" +
                                "</button>"+
                            "</div>"+
                        "</div>"+
                    "</div>"+
               "</div>";

        $("#form_etiqueta div.div_inputs").append(html);
    }

    function delete_etiqueta(id_etiqueta){
        $("div#"+id_etiqueta).remove();
    }

    function store_etiqueta(id_chat_tema) {

        if($("#form_etiqueta").valid()){

            var data =[];

            $.each($("input.etiqueta"),function (i,j) {
                data.push({
                    etiqueta: j.value
                });
            });

            data = {
                data : data,
                id_chat_tema : id_chat_tema
            };
            peticion_ajax(data,'{{url('administracion_bot/store_etiqueta')}}','POST',undefined);
        }

    }
    
    function add_pregunta(id_pregunta_respuesta) {
        load("show");
        data = {
            id_pregunta_respuesta : id_pregunta_respuesta
        };
        $.post('{{url('administracion_bot/add_pregunta')}}',data, function (retorno) {
            modal('pregunta_chat_bot', retorno, '<i class="fa fa-tags"></i> Pregunta y respuesta',true,'60%',undefined);
            if(id_pregunta_respuesta==undefined)
                add_etiqueta_chat_tema();
        }).always(function () {
            load("hide");
        });
    }

    function add_etiqueta_chat_tema() {

        data = {
            id_chat_tema : $("#id_etiqueta_chat_tema").val()
        };
        console.log(data);
        $.post('{{url('administracion_bot/add_etiquetas_tema')}}',data, function (retorno) {
            $(".etiquetas_tema div.row").empty();

            $.each(retorno,function (i,j) {
                html = "<div class='pl-3 pr-3 pb-2'>" +
                            "<div class='btn btn-default btn-sm'>"+
                                "<input type='checkbox' name='check_etiqueta_"+(i+1)+"' checked value='"+j.nombre+"' id='check_etiqueta_"+(i+1)+"'>" +
                                "<label for='check_etiqueta_"+(i+1)+"' class='mb-0'>"+j.nombre+"</label>" +
                            "</div>"+
                        "</div>";
                $(".etiquetas_tema div.row").append(html);
            });

        }).always(function () {
            load("hide");
        });

    }
    
    function ingresar_accion(accion) {

        if(accion==="texto"){
            html = "<label class='label_accion'>Texto</label>" +
                "<div class='input-group'>" +
                "<div class='input-group-prepend'>"+
                    "<span class='input-group-text'>Escriba la respuesta</span>"+
                "</div>" +
                    "<input type='text' id='texto_respuesta' class='form-control'  required>"+
                "</div>";
        }

        if(accion==="link"){
            html = "<label class='label_accion'>Enlace</label>" +
                    "<div class='input-group'>" +
                        "<div class='input-group-prepend'>"+
                            "<input type='text' placeholder='Texto del enlace' id='texto_respuesta' class='form-control' required>" +
                        "</div>" +
                        "<input type='url' placeholder='Enlace' id='texto_enlace' class='form-control' required>" +
                        "<div class='input-group-prepend'>"+
                            "<select placeholder='texto del enlace' id='abrir_en' class='form-control' title='Forma de abrir el enlace' required>" +
                                "<option value='1'>Otra ventana</option>"+
                                "<option value='0'>Misma ventana</option>"+
                            "</select>" +
                        "</div>" +
                    "</div>";
        }

        if(accion === "accion"){
            html = "<label class='label_accion'>Acción</label>" +
                    "<div class='input-group'>" +
                        "<input type='text' placeholder='Texto de la tespuesta' id='texto_respuesta' class='form-control' required>" +
                    "</div>"+
                    "<div class='mt-2 text-center'>"+
                        "<div class='btn btn-default btn-sm'  id='accion_cotizar'>"+
                            "<input type='radio' name='accion_cotizar' value='cotizador' required>" +
                            "<label for='accion_cotizar' class='mb-0'>Cotizar</label>" +
                        "</div>" +
                    "</div>";

        }

        $("#cuerpo_respuesta").html(html);

    }
    
    function store_pregunta(id_pregunta_respuesta) {

        if($("#form_pregunta").valid()){

            etiquetas =[];
            $.each($("div.div_check_etiqueta input[type='checkbox']:checked"),function(i,j){
                etiquetas.push({
                    etiqueta_chat_tema : j.value
                });
            });

            accion = $(".label_accion").html().trim();

            if(accion==="Texto"){
                cuerpo_respuesta = {
                    respuesta: $("#texto_respuesta").val()
                }
            }

            if (accion==="Enlace"){
                cuerpo_respuesta = {
                    respuesta: $("#texto_respuesta").val(),
                    enlace : $("#texto_enlace").val(),
                    abrir_en : $("#abrir_en").val()
                }
            }

            if(accion === "Acción"){
                cuerpo_respuesta = {
                    respuesta: $("#texto_respuesta").val(),
                    btn_accion : $("#cuerpo_respuesta input[type='radio']:checked").val(),
                }
            }

            id_chat_tema = $(".select_chat_tema").val();

            nuevasEtiquetas =[];
            $.each($(".nueva_etiqueta_"+id_chat_tema),function(i,j){
                nuevasEtiquetas.push({
                    nombre : j.value
                });
            });

            data = {
                id_chat_tema : id_chat_tema,
                pregunta : $("input#input_pregunta").val(),
                accion : accion,
                cuerpo_respuesta : cuerpo_respuesta,
                etiquetas : etiquetas,
                id_pregunta_respuesta : id_pregunta_respuesta,
                nuevasEtiquetas : nuevasEtiquetas
            };

            peticion_ajax(data,'{{url('administracion_bot/store_pregunta')}}','POST');
        }

    }

    function eliminar_pregunta_respuesta(id_pregunta_respuesta){

        confirmar = confirm("¿Esta seguro de eliminar esta pregunta?");
        if(confirmar){
            data = {
                id_pregunta_respuesta : id_pregunta_respuesta
            };
            peticion_ajax(data, '{{url('administracion_bot/eliminar_pregunta')}}', 'POST');
        }

    }

    function delete_etiqueta_pregunta_respuesta(id_etiqueta_pregunta_respuesta) {

        confirmar = confirm("¿Esta seguro de eliminar esta etiqueta?");
        if (confirmar) {
            load("show");
            data = {
                id_etiqueta_pregunta_respuesta: id_etiqueta_pregunta_respuesta
            };

            $.post("{{url('administracion_bot/eliminar_etiqueta_pregunta_respuesta')}}", data, function (retorno) {

                if(retorno.success){
                    $("#div_etiqueta_creada_"+id_etiqueta_pregunta_respuesta).remove();
                }

            }).always(function () {
                load("hide");
            });
        }

        /*confirmar = confirm("¿Esta seguro de eliminar esta etiqueta?");
        if(confirmar){
            data = {
                id_etiqueta_pregunta_respuesta : id_etiqueta_pregunta_respuesta
            };
            peticion_ajax(data, '{{url('administracion_bot/eliminar_etiqueta_pregunta_respuesta')}}', 'POST');
        }*/

    }

    function add_etiqueta_pregunta_respuesta(id_chat_tema){

        var cant = $(".etiquetas_tema div.row input[type='checkbox']").length+1;
        html = "<div class='pl-3 pr-3 pb-2' id='input_check_extra_"+(cant)+"'>" +
                    "<div class='btn btn-default btn-sm'>"+
                        "<button type='button' class='close' id='"+(cant)+"' onclick='delete_input_chek_extra(this)'>"+
                                "<span aria-hidden='true'>×</span>"+
                           "</button>"+
                        "<input type='checkbox' name='check_etiqueta_"+(cant)+"' checked value='' id='check_etiqueta_"+(cant)+"'>" +
                        "<label for='check_etiqueta_"+(cant)+"' class='mb-0'>" +
                            "<input type='text' style='width:70px' id='valor_etiqueta_"+(cant)+"' class='nueva_etiqueta_"+id_chat_tema+"' onkeyup='copiar_valor(this)'>" +
                        "</label>" +
                    "</div>"+
                "</div>";

        $(".etiquetas_tema div.row").append(html);
    }

    function copiar_valor(input) {
        console.log(input.value, );
        $("#check_etiqueta_"+input.id.split("_")[2]).val(input.value);
    }

    function delete_input_chek_extra(btn) {
        $("#input_check_extra_"+btn.id).remove()
    }
</script>