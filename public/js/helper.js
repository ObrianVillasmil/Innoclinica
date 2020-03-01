$(document).ready(function() {
    verificar_rol();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function( jqXHR, textStatus, errorThrown ) {
            if (jqXHR.status === 0) {
                erroPeticionAjax('Not connect: Verify Network.');
            } else if (jqXHR.status == 404) {
                erroPeticionAjax('Página solicitada no encontrada [404].');
            } else if (jqXHR.status == 500) {
                erroPeticionAjax('Error interno del servidor [500].<br/> Error: '+jqXHR.responseJSON.message
                    +'<br/> Mensaje: '+jqXHR.responseJSON.file+'<br/> Línea: '+jqXHR.responseJSON.line);
            } else if (textStatus === 'parsererror') {
                erroPeticionAjax('El JSON solicitado falló.');
            } else if (textStatus === 'timeout') {
                erroPeticionAjax('Error de tiempo de espera.');
            } else if (textStatus === 'abort') {
                erroPeticionAjax('Petición ajax abortada.');
            } else {
                erroPeticionAjax('Error desconcido: ' + jqXHR.responseText);
            }
            load("hide");
        }
    });
    jQuery.extend(jQuery.validator.messages, {
        required: "Este campo es obligatorio.",
        remote: "Por favor, rellena este campo.",
        email: "Por favor, escribe una dirección de correo válida",
        url: "Por favor, escribe una URL válida.",
        date: "Por favor, escribe una fecha válida.",
        dateISO: "Por favor, escribe una fecha (ISO) válida.",
        number: "Por favor, escribe un número entero válido.",
        digits: "Por favor, escribe sólo dígitos.",
        creditcard: "Por favor, escribe un número de tarjeta válido.",
        equalTo: "Por favor, escribe el mismo valor de nuevo.",
        accept: "Por favor, escribe un valor con una extensión aceptada.",
        maxlength: jQuery.validator.format("Sólo se permite hasta {0} caracteres."),
        minlength: jQuery.validator.format("Escribe almenos de {0} caracteres."),
        rangelength: jQuery.validator.format("Por favor, escribe un valor entre {0} y {1} caracteres."),
        range: jQuery.validator.format("Por favor, escribe un valor entre {0} y {1}."),
        max: jQuery.validator.format("Por favor, escribe un valor menor o igual a {0}."),
        min: jQuery.validator.format("Por favor, escribe un valor mayor o igual a {0}.")
    });
});

function load(carga) {
    carga === "show"
        ? $(".loader").fadeIn("slow")
        : $(".loader").fadeOut("slow");
}

function peticion_ajax(data,url,metodo,r) {
    load("show");
    $.ajax({
        method: metodo,
        url: url,
        data: data,
        success: function (response) {
            if(response.success){
                titulo = "<i class='fa fa-check text-success' aria-hidden='true'></i> <span class='text-success'>Acción realizada exitosamente</span>";
                reload = true;
            }else{
                titulo = "<i class='fa fa-exclamation-triangle text-danger' aria-hidden='true'></i> <span class='text-danger'>Hubo un error al realizar la acción</span>";
                reload = false;
            }
            if(r !== undefined) reload = false;

            modal('modal_success', response.msg,titulo ,true, '40%',reload);
        }
    }).always(function () {
        load("hide");
    });
}

function modal(id_modal, contenido, titulo ,draggable, size, reload,accion) {

    $(".modal").modal("show").attr("id","modal_"+id_modal);
    $(".modal-body").html(contenido);
    $(".modal-title").html(titulo);
    $(".btn_store").attr("id","btn_"+id_modal);

    if($(".form").length>0)
        $(".form").attr("id","form_"+id_modal);

    if(draggable === true)
        $("div.modal").draggable({handle: ".modal-header"});

    $(".modal-dialog").css('max-width',size);

    id = $("#modal_"+id_modal+" button.btn_store").attr("id");

    if(accion !== undefined)
        $("#"+id).click(function () { accion(); });

    (reload)
        ? $("#reload").val("true")
        : $("#reload").val("false");

}

function erroPeticionAjax(mensaje) {

    contenido = "<div class='alert alert-danger' role='alert'>" +
                    mensaje +
                "</div>";

    modal('modal_error_peticion_ajax',contenido,
        '<i class="fa fa-exclamation-triangle text-danger" aria-hidden="true"></i> ' +
        '<span class="text-danger">Hubo un error en la petición realizada a servidor</span>',
        true,'50%',false);
}

function cerrarModal(reload) {
    console.log(reload);
    $(".modal").modal("hide");
    if(reload === "true")
        location.reload();
}

function modalQuest(mensaje) {

    return "<div class='alert alert-warning' role='alert' >"
                +"<div class='col-md-12'>"+
                +"hola"+
                "</div>"+
                "<div class='form-group text-right'>" +
                    "<button type='button' class='btn btn-default' data-dismiss='modal' onclick='verificar(false)'>" +
                        " <i class='fa fa-ban' aria-hidden='true'></i> Cancelar" +
                    "</button>" +
                    "<button type='button' class='btn btn-primary btn_store' onclick='verificar(true)'>" +
                        "<i class='fa fa-floppy-o' aria-hidden='true'></i> Guardar" +
                    "</button>" +
                "</div>"+
          "</div>";
}

function verificar(opcion) {
    return opcion;
}

function verificar_rol() {
    $.get('/autenticar/verificar_rol',{}, function (retorno) {
        if(retorno.length === 0){
            add_rol();
            return false;
        }
    });
}

function add_rol() {
    $.get('/autenticar/add_rol',{}, function (retorno) {
        modal('add_rol', retorno, '<i class="fa fa-user-circle"></i> Antes de continuar seleccione su rol por favor',false,'40%',false,function () {
            storeRol();
        });

    })
}

function storeRol() {
    data = {
        rol : $("#rol").val()
    };
    peticion_ajax(data,'/autenticar/store_rol','POST');
}

function calcular_producto(input) {

    if($(input).attr('class')){
        input = $(input).attr('class');
    }

    dosis = 0;
    $.each($("input."+input),function(i,j){
        dosis += parseInt(j.value);
    });

    $("td.div_dosis_"+input.split("_")[1]).html(dosis);

    total_dosis = 0;
    $.each( $("td.div_dosis"),function (i,j) {
        total_dosis += parseInt($(j).html());
    });

    $(".total_dosis_fase").html(total_dosis);

}

function form_distribucion_tratamiento(id_tratamiento,tratamiento){
    datos = {
        id_tratamiento : id_tratamiento
    };
    load("show");
    $.get('/tratamiento/form_distribucion_tratamiento', datos, function (retorno) {
        modal('ditribucion_tratamiento', retorno, '<i class="fa fa-stethoscope"></i> Tratamiento '+tratamiento+'',true,'90%',false,undefined);

    }).always(function () {
        load("hide");
    });
}

function store_distribucion_tratamiento(id_tratamiento) {

    //if($("#form_ditribucion_tratamiento").valid()) {

        if(error('calculo_intervalo') || error('producto_1'))
            return false;

        distribucion_tratamiento = [];
        enfermedades = [];

        $.each($("form#form_enfermedades input.enf"), function (i, j) {
            enfermedades.push({
                codigo_cied10: j.value
            });
        });

        if(enfermedades.length < 1){
            $("#enfermedades").css('border-color','red').focus();
                return false;
        }else{
            $("#enfermedades").css('border-color','#ced4da');
        }

        success = true;

        $.each($("input.cantidad_aplicacion"), function (i, j) {
            if($("#cantidad_intervalo_"+(i+1)).length > 0) {
                if (error(j.id) || error('cantidad_intervalo_' + (i + 1)))
                    success = false;

                if(error('intevalo_' + (i + 1)) )
                    success = false;

            }

            distribucion_tratamiento.push({
                intervalo: $("#intevalo_" + (i + 1)).val(),
                cantidad_intervalo: $("#cantidad_intervalo_" + (i + 1)).val(),
                cantidad_aplicacion: j.value
            });
        });

        if(success){

            total_productos_agrupados = [];
            $.each(distribucion_tratamiento, function (i, j) {
                productos_agrupados = [];
                $.each($("select.producto"), function (k, l) {
                    producto = [];
                    if(l.value!=""){
                        for (let m = 0; m<$("input#cantidad_aplicacion_"+(i+1)).val(); m++) {
                            producto.push({
                                producto: l.value,
                                cantidad: $("#cantidad_producto_"+(i+1)+"_"+(k+1)+"_"+(m+1)).val(),
                                intervalo_aplicacion: $("#intevalo_aplicacion_"+(i+1)+"_"+(m+1)).val(),
                                cantidad_intervalo: $("#cantidad_intervalo_"+(i+1)+"_"+(m+1)).val(),
                            });
                        }
                        productos_agrupados.push(producto)
                    }
                });
                total_productos_agrupados.push(productos_agrupados);
            });

            data = {
                distribucion_tratamiento: distribucion_tratamiento,
                detalle_distribucion_tratamiento : total_productos_agrupados,
                enfermedades: enfermedades,
                id_tratamiento: id_tratamiento,
                calculo_intervalo: $("#calculo_intervalo").val()
            };

            peticion_ajax(data, '/tratamiento/store_distribucion_tratamiento', 'POST', false, false);

        }


    //}

}

function actualizaDatosUsuario(party_id) {
    verificar_rol();
    if($("#form_datos_usuario").valid()) {
        confirmar = confirm("¿Esta seguro de actualizar sus datos?");
        if(confirmar){
            load("show");
            data = {
                party_id: party_id,
                correo: $("#correo").val(),
                nombre: $("#nombres").val(),
                apellido: $("#apellidos").val(),
                tipo_identificacion: $("#tipo_identificacion").val(),
                identificacion: $("#identificacion").val(),
                pais: $("#pais").val(),
                direccion: $("#direccion").val(),
                nacionalidad: $("#nacionalidad").val(),
                fecha_nacimiento: $("#fecha_nacimiento").val(),
                telefono: $("#telefono").val(),
                cuenta_habilitada : $("#cuenta_habilitada").val()
            };
            peticion_ajax(data, '/usuario/actualizar_datos_usuario', 'POST');
        }
    }
}

function actualizarContrasena(party_id) {
    verificar_rol();
    if($("#form_contrasena").valid()) {
        confirmar = confirm("¿Esta seguro de actualizar la contraseña?");
        if (confirmar) {
            load("show");
            data = {
                party_id: party_id,
                contrasena: $("#contrasena").val(),
                contrasena_confirmation: $("#contrasena_contrasena").val()
            };
            peticion_ajax(data, '/usuario/actualizar_contrasena_usuario', 'POST');
        }
    }
}

function error(idInput) {

    input = $("#"+idInput);
    if(input.val() === null || input.val() === "" || input.val() === undefined){
        input.addClass('text-danger').focus().css('border-color','red');
        //$("#span_error_"+idInput.split("_")[1]).html('<span style="position: absolute;color: red;">Falta este campo</span>');
        return true;
    }else{
        input.removeClass('text-danger').css('border','1px solid #ced4da');
        return false;
    }
}

function store_distribucion_tratamiento_doctor(partyIdSolicitante,idTratamiento,idDistribucionTratamientoDoctor) {

    cie10 = "";
    $.each($('input.cie10'),function (i,j) {
        if($(j).is(":checked")) cie10 = j.value;
    });

    if(cie10 === ""){
        modal('error_cie-10','<div class="alert alert-danger text-center">Debe selecionar una enfermedad del CIE-10</div>',
            '<span class="text-danger"><i class="fa fa-exclamation-triangle" ></i> Alerta</span>',false,'40%',false);
        return false;
    }


    distribucion_tratamiento=[];
    $.each($("th.fase_tratamiento"), function (i, j) {
        distribucion_tratamiento.push({
            intervalo: $("#intevalo_" + (i + 1)).val(),
            cantidad_intervalo: $("#cantidad_intervalo_" + (i + 1)).val(),
            cantidad_aplicacion:$("#cantidad_aplicacion_" + (i + 1)).val(),
        });
    });

    distribucionTratamientoDoctor = [];
    $.each(distribucion_tratamiento, function (i, j) {
        productos_agrupados = [];
        $.each($("input.producto"), function (k, l) {
            producto = [];
                for (let m = 0; m<$("input#cantidad_aplicacion_"+(i+1)).val(); m++) {
                    producto.push({
                        producto: l.value,
                        cantidad: $("#cantidad_producto_"+(i+1)+"_"+(k+1)+"_"+(m+1)).val(),
                        intervalo_aplicacion: $("#intevalo_aplicacion_"+(i+1)+"_"+(m+1)).val(),
                        cantidad_intervalo: $("#cantidad_intervalo_"+(i+1)+"_"+(m+1)).val(),
                    });
                }
                productos_agrupados.push(producto)
        });
        distribucionTratamientoDoctor.push(productos_agrupados);
    });


    data = {
        partyIdSolicitante : partyIdSolicitante,
        distribucion_tratamiento: distribucion_tratamiento,
        idTratamiento : idTratamiento,
        distribucionTratamientoDoctor : distribucionTratamientoDoctor,
        cie10 : cie10,
        descripcion_patologica : $("#descripcion_patologica").val(),
        justificacion : $("#justificacion").val(),
        idDistribucionTratamientoDoctor : idDistribucionTratamientoDoctor,
        paso : $('a.active').attr('data-step'),
    };
    peticion_ajax(data,'/tratamientos_clientes/store_distribucion_tratamiento_doctor','POST');

}

function store_distribucion_tratamiento_seguimiento(partyIdSolicitante,idTratamiento) {

    cie10 = "";
    $.each($('input.cie10'),function (i,j) {
        if($(j).is(":checked")) cie10 = j.value;
    });

    if(cie10 === ""){
        modal('error_cie-10','<div class="alert alert-danger text-center">Debe selecionar una enfermedad del CIE-10</div>',
            '<span class="text-danger"><i class="fa fa-exclamation-triangle" ></i> Alerta</span>',false,'40%',false);
        return false;
    }


    distribucion_tratamiento=[];
    $.each($("th.fase_tratamiento"), function (i, j) {
        distribucion_tratamiento.push({
            intervalo: $("#intervalo_" + (i + 1)).val(),
            cantidad_intervalo: $("#cantidad_intervalo_" + (i + 1)).val(),
            cantidad_aplicacion:$("#cantidad_aplicacion_" + (i + 1)).val(),
        });
    });

    distribucionTratamientoDoctor = [];
    $.each(distribucion_tratamiento, function (i, j) {
        productos_agrupados = [];
        $.each($("input.producto"), function (k, l) {
            producto = [];
            for (let m = 0; m<$("input#cantidad_aplicacion_"+(i+1)).val(); m++) {
                producto.push({
                    producto: l.value,
                    cantidad: $("#cantidad_producto_"+(i+1)+"_"+(k+1)+"_"+(m+1)).val(),
                    intervalo_aplicacion: $("#intevalo_aplicacion_"+(i+1)+"_"+(m+1)).val(),
                    cantidad_intervalo: $("#cantidad_intervalo_"+(i+1)+"_"+(m+1)).val(),
                });
            }
            productos_agrupados.push(producto)
        });
        distribucionTratamientoDoctor.push(productos_agrupados);
    });


    data = {
        partyIdSolicitante : partyIdSolicitante,
        distribucion_tratamiento: distribucion_tratamiento,
        idTratamiento : idTratamiento,
        distribucionTratamientoDoctor : distribucionTratamientoDoctor,
        cie10 : cie10,
        descripcion_patologica : $("#descripcion_patologica").val(),
        justificacion : $("#justificacion").val(),
        //idDistribucionTratamientoDoctor : idDistribucionTratamientoDoctor,
    };
    console.log(data);
    peticion_ajax(data,'/seguimiento/store_distribucion_tratamiento_seguimiento','POST');

}

function aumentar_face(partyIdSolicitante,idTratamiento) {

    if($("#form_nueva_fase").valid()) {
        producto=[];
        $.each($(".producto"),function (i,j) {
            producto.push({
                producto : j.value
            });
        });

        confirmar = confirm("Esta seguro de agregar otra fase al tratamiento.?");
        if (confirmar) {
            load("show");
            data = {
                intervalo: $("#intevalo").val(),
                cantidad_intervalo : $("#cantidad_intervalo").val(),
                cantidad_aplicacion : $("#cantidad_aplicacion").val(),
                producto : producto,
                idTratamiento : idTratamiento,
                partyIdSolicitante : partyIdSolicitante,
            };
            peticion_ajax(data, '/seguimiento/add_fase_distribucion_tratamiento_seguimiento', 'POST');
        }
    }

}

function inicioTratamiento(id_tratamineto_solicitado) {
    fecha = $("#inicio_tratamiento").val();

    if(fecha === ""){
        alert("Debe seleccionar una fecha para iniciar el tratamiento");
        return false;
    }

    confirmar = confirm("El tratamiento comenzará en fecha "+fecha);
    if (confirmar) {
        load("show");
        data = {
            id_tratamineto_solicitado : id_tratamineto_solicitado,
            fecha_inicio: fecha
        };
        peticion_ajax(data, '/seguimiento/update_fecha_tratamiento_solicitado', 'POST');
    }
}

function agregarArchivoTratamiento(id_tratamiento_solicitado) {
    load("show");
    $.get('/seguimiento/agregar_archivo', {}, function (retorno) {
        modal('add_permisos', retorno, '<i class="fa fa-files-o"></i> Agregar archivos',true,'40%',false,function () {
            storeDocumento(id_tratamiento_solicitado);
        });
    }).always(function () {
        load("hide");
    });
}

function storeDocumento(id_tratamiento_solicitado) {
    form = $(".cargar_archivos_tramiento_solicitado");
    if(form.valid()) {
        load('show');
        formData = new FormData(form[0]);
        formData.append('id_tratamiento_solicitado',id_tratamiento_solicitado);
        formData.append('icono',$("#icono_proceso").val());
        $.ajax({
            url: '/seguimiento/carga_documento_solicitud_tratamiento',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
            console.log(response);
            modal('archivos_tratamiento_solicitado',response.msg,'Acción',false,'50%',true);
            //$("a[href='#documentos_tratamiento']").addClass("active").attr('aria-selected',true);
        }
    }).always(function () {
            load('hide');
        });
    }
}

function eliminar_archivo_documento_tratamiento_solicitado(id_documento_tratamiento_solicitado) {
    confirmar = confirm("¿Esta seguro que desea elminar el documento?");
    if (confirmar) {
        load("show");
        data = {
            id_documento_tratamiento_solicitado : id_documento_tratamiento_solicitado
        };
        peticion_ajax(data, '/seguimiento/eliminar_archivo_documento_tratamiento_solicitado', 'POST');
    }
}

function enviar_documentos(id_tratamiento) {

    if($("#form_correo_documentos").valid()){

        arr_archivos=[];

        $.each($("div#multiCollapseExample1 input[type='checkbox']:checked"),function(i,j){
            arr_archivos.push({
                ingreso : 'cliente',
                id:$(j).val()
            });
        });

        $.each($("div#multiCollapseExample2 input[type='checkbox']:checked"),function(i,j){
            arr_archivos.push({
                ingreso : 'admin',
                id:$(j).val()
            });
        });

        $.each($("div#multiCollapseExample3 input[type='checkbox']:checked"),function(i,j){
            arr_archivos.push({
                ingreso : 'distribucion_tratamiento',
                id: $(j).val()
            });
        });

        $.each($("div#multiCollapseExample4 input[type='checkbox']:checked"),function(i,j){
            arr_archivos.push({
                ingreso : 'otros',
                id:$(j).val()
            });
        });

        confirmar = confirm("¿Esta seguro que deseaenviar el correo electrónico?");
        if (confirmar) {
            load("show");
            data = {
                arr_archivos: arr_archivos,
                id_tratamiento : id_tratamiento,
                asunto : $("#asunto").val(),
                mensaje : $("#mensaje").val(),
            };
            peticion_ajax(data,'/seguimiento/enviar_correo_documentos','POST',true);
        }

    }

}

function icono_proceso() {
    load("show");
    $.get('/iconos', {}, function (retorno) {
        modal('iconos', retorno, '<i class="fa fa-files-o"></i> Agregar un ícono al proceso',true,'50%',false,function () {});
    }).always(function () {
        load("hide");
    });
}

function seleccionar_icono(button) {
    clase = button.id;
    $("#icono_proceso").val(clase);
    $(".btn-icono i").removeClass($(".btn-icono i").attr('class'));
    $(".btn-icono i").addClass(clase);
    $(".modal").modal('hide');
}

function cotizar(id_tratamiento,id_tratamiento_solicitado) {
    data= {
        id_tratamiento : id_tratamiento,
        id_tratamiento_solicitado : id_tratamiento_solicitado
    };
    $.get('/cotizacion/cotizador',data, function (data) {
        modal('cotizador', data, '<i class="fa fa-money"></i> Generar cotización',false,'70%',false,function () {
            /*enviarCotizacion();*/
        });

    });
}

function SolicitarCotizacion() {

    if($("#form_cotizacion").valid()){
        let total_general=0;
        let total_general_pvp=0;
        let total_general_dscto=0;
        $.each($(".tr_prodcuto"),function (i,j) {
            load("show");
            datos = {
                product_id : $(j).find('td.product_id').html(),
                cantidad : $(j).find('input.cantidad').val(),
                forma_pago : $("select#forma_pago").val()
            };

            $.get('/cotizacion/solicitar_cotizacion', datos, function (retorno) {
                if(!retorno.success){
                    if(retorno.codigo!==0){
                        titulo = "<span class='text-danger'><i class='fa fa-exclamation-triangle'></i> Ha ocurrido un error al intentar solicitar la cotización</span>";
                        mensaje = "<div class='alert alert-danger'>"+retorno.mensaje+"</div>";
                        modal('modal_alert', mensaje,titulo ,true, '40%',false);
                    }else{
                       forma_pago = $("#forma_pago").val();
                       total = retorno.valor.total.toFixed(2);
                       total_pvp = retorno.valor.totalPvp.toFixed(2);
                       desceunto = retorno.valor.totalDescto.toFixed(2);

                       $("#total_pvp_"+(i+1)).html("$"+total_pvp);
                       $("#total_dscto_"+(i+1)).html("$"+desceunto);
                       $("#precio_total_"+(i+1)).html("$"+total);
                       
                       total_general+=parseFloat(total);
                       total_general_pvp+= parseFloat(total_pvp);
                       total_general_dscto+=parseFloat(desceunto);

                       if($(".tr_prodcuto").length === (i+1)){
                           $("#total_general_pvp").html("$"+total_general_pvp.toFixed(2));
                           $("#total_general_descto").html("$"+total_general_dscto.toFixed(2));
                           $("#total_general").html("$"+total_general.toFixed(2));
                       }

                    }
                }else{
                    modal('error_sistema',retorno.msg,
                        '<span class="text-danger"><i class="fa fa-exclamation-triangle" ></i> Alerta</span>',false,'40%',false);
                }
            }).always(function () {
                load("hide");
            });

        });


    }
}

function crearCotizacion() {
    product=[];
    $.each($("td.product_id"),function(i,j){
        product.push({
            product_id: $(j).html(),
            cantidad : $("#cantidad_"+(i+1)).val()
        });
    });
    data={
        product : product,
        id_tratamiento_solicitado : $("#id_tratamiento_solicitado").val(),
        forma_pago : $("#forma_pago").val(),
        tipo_envio : $("#tipo_envio").val()
    };
    $.post('/cotizacion/crear_cotizacion', data, function (retorno) {

        if(retorno.success){

            msg = "";
            $.each(retorno.msg,function (i,j) {
                console.log(i);
                msg+=j.original.mensaje+" ";
            });

            titulo = "<span class='text-success'><i class='fa fa-exclamation-triangle'></i> Alerta</span>";
            mensaje = "<div class='alert alert-danger'>"+msg+"</div>";
            modal('modal_alert', mensaje,titulo ,true, '40%',false);

        }else{
            titulo = "<span class='text-danger'><i class='fa fa-exclamation-triangle'></i> Ha ocurrido un error al intentar crear la cotización</span>";
            mensaje = "<div class='alert alert-danger'>"+retorno.msg+"</div>";
            modal('modal_alert', mensaje,titulo ,true, '40%',false);
        }

    },'json');
    //peticion_ajax(data, '/cotizacion/crear_cotizacion', 'POST',false);
}

/*function render_chat() {
    $.get('/bot/render', {}, function (retorno) {

        $("#chat-bot").html(retorno);
    });

}*/

function notificacionLogin(nombre,sistema){
    console.log("hola");
    Push.create("HOLA "+nombre.toUpperCase(), {
        body: "Bienvenido al sistema "+sistema+" !",
        icon: '/imagenes/user-icon.png',
        timeout: 10000,
        onClick: function () {
            window.focus();
            this.close();
        }
    });
}

