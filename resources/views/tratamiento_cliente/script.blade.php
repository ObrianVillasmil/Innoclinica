<script>

    $('.next').click(function(){
        var nextId = $(this).parents('.tab-pane').next().attr("id");
        if(!$("#leido_"+(parseInt(nextId.substring(5,4))-1)).is(":checked")){
            $("#label_leido_"+(parseInt(nextId.substring(5,4))-1)).css('color','red').html('<i class="fa fa-exclamation-triangle"></i> Debe hacer clic en esta casilla para continuar');
            return false;
        } else{
            $("#label_leido_"+(parseInt(nextId.substring(5,4))-1)).css('color','black').html('He completado este paso');
        }
        $('[href="#'+nextId+'"]').tab('show');
        $(this).attr('checked',false);

        if(nextId === "step2" ){
            @if(!isset($pasoActual->tratamiento->id_tratamiento) && (getParty(session('party_id'))->party_role->role_type->role_type_id === "REPRESENTANTE_LEGAL" || getParty(session('party_id'))->party_role->role_type->role_type_id === "END_USER_CUSTOMER"))
                data = {
                    id_tratamiento : '{{$idTratamiento}}'
                };
                $.post("{{url('/tratamientos_clientes/store_notificacion_tratamiento')}}",data,function(response){
                    if(parseInt(response) === 1){
                        /*Push.create("Tienes una alerta!", {
                            body: "Un usuario a mostrado interés en un tratamiento!",
                            icon: '',
                            timeout: 15000,
                            onClick: function () {
                                window.focus();
                                this.close();
                            }
                        });*/
                    }
                });
            @endif
        }
    });

    $('.back').click(function(){

        var prevId = $(this).parents('.tab-pane').prev().attr("id");
        console.log(prevId);
        $('[href="#'+prevId+'"]').tab('show');
        return false;

    });

    function storeArchivoCliente(carpeta,id_notificacion,id_carga_archivo,id_tratamiento,form,partyIdSolicitante) {
        if($("#"+form).valid()){
            confirmar = confirm("Esta seguro de cargar el archivo?");
            if(confirmar){
                load("show");
                formData = new FormData($("#"+form)[0]);
                formData.append('carpeta',carpeta);
                formData.append('id_carga_archivo',id_carga_archivo);
                formData.append('id_tratamiento',id_tratamiento);
                formData.append('paso',$('a.active').attr('data-step'));
                formData.append('correo_doctor',$("#doctor_mail").val());
                formData.append('tlf_doctor',$("#doctor_tlf").val());
                formData.append('partyIdSolicitante',partyIdSolicitante);

                if(id_notificacion !== undefined)
                    formData.append('id_notificacion', id_notificacion);

                $.ajax({
                    method : 'POST',
                    url    : '{{url('/tratamientos_clientes/store_archivo_cliente')}}',
                    data   : formData,
                    processData: false,
                    contentType: false,
                    success:function (response) {
                        modal('store_archivo_cliente',response.msg,'Acción',false,'50%',true);
                        load("hide");
                    }
                }).always(function () {
                    load("hide");
                });
            }
        }
    }

    function eliminar_archivo(carpeta,archivo) {
        confirmar = confirm("Esta seguro que desea eliminar es archivo?");
        if(confirmar){
            data = {
                carpeta : carpeta,
                archivo : archivo
            };
            peticion_ajax(data,'{{'/tratamientos_clientes/eliminar_archivo'}}','POST');
        }
    }

    function storeDatosCliente(form_captura_dato,id_proceso,id_tratamiento,id_datos_cliente,notifica_doctor) {

        if($("#"+form_captura_dato).valid()){

            load("show");
            var formData = new FormData($("#"+form_captura_dato)[0]);
            formData.append('id_captura_dato',id_proceso);
            formData.append('id_tratamiento',id_tratamiento);
            formData.append('paso',$('a.active').attr('data-step'));
            formData.append('notifica_doctor',notifica_doctor);
            if(id_datos_cliente !== undefined)
                formData.append('id_datos_cliente',id_datos_cliente);

            $.ajax({
                method : 'POST',
                url    : '{{url('tratamientos_clientes/store_datos_doctor')}}',
                data   : formData,
                processData: false,
                contentType: false,
                success:function (response) {
                    modal('store_datos_doctor',response.msg,'Acción',false,'50%',true);
                    load("hide");
                }
            }).always(function () {
                load("hide");
            });

        }

    }

    function deleteTratamiento(id_distribucion_tratamiento_doctor) {

        confirmar = confirm("Esta seguro de eliminar esta fase del tratamiento.?");
        if (confirmar) {
            load("show");
            data = {
                id_distribucion_tratamiento_doctor : id_distribucion_tratamiento_doctor
            };
            peticion_ajax(data, '{{url('tratamientos_clientes/delete_fase_distribucion_tratamiento_seguimiento')}}', 'POST');
        }

    }

    @isset($pasoActual->id_tratamiento_solicitado)
        step = '{{$pasoActual->proceso_actual}}';
        percent = (parseInt(step) / '{{$procesos->count()}}') * 100;
        $('.progress-bar').css({width: percent + '%'});
        $('.progress-bar').text("Paso " + step + " de "+ '{{$procesos->count()}}');
    @endisset

    $('.first').click(function(){ $('#myWizard a:first').tab('show') });

    $(function(){
        $('.btn-circle').on('click',function(){
            $('.btn-circle.btn-info').removeClass('btn-info').addClass('btn-default').addClass('next-step');
            $(this).addClass('btn-info').removeClass('btn-default').blur();
            id = $(this).attr('href').split("#")[1];
            $("div.aplicaciones div").removeClass('active show');
            $("div#"+id).addClass('active show');
        });
    });

    function storeAplicacionTratamiento(id_detalle_distribucion_tratamiento_doctor) {

        data = {
            id_detalle_distribucion_tratamiento_doctor: id_detalle_distribucion_tratamiento_doctor,
            sitio_aplicacion: $("#sitio_aplicacion_"+id_detalle_distribucion_tratamiento_doctor).val(),
            comentario : $("#comentario_"+id_detalle_distribucion_tratamiento_doctor).val(),
            cumplido : $("#cumplido_"+id_detalle_distribucion_tratamiento_doctor).is(":checked"),
            fecha_aplicacion : $("#fecha_aplicacion_"+id_detalle_distribucion_tratamiento_doctor).val(),
            fecha_aplicacion_real : $("#fecha_aplicacion_real_"+id_detalle_distribucion_tratamiento_doctor).val(),
            cantidad_aplicacion : $("#cantidad_aplicacion_"+id_detalle_distribucion_tratamiento_doctor).val()

        };

        peticion_ajax(data, '{{url('seguimiento/store_detalle_distribucion_tratamiento_doctor')}}', 'POST',true);

    }


    function firmar_pdf(id_tratamiento,party_id,id_doctor) {

        data = {
            idTratamiento : id_tratamiento,
            partyId : party_id,
            idDoctor : id_doctor
        };

        peticion_ajax(data, '{{url('seguimiento/firma_pdf')}}', 'POST');
        /*setTimeout(function () {
         load("hide");
        },4000);*/
    }

    
    function storeCodigoImportacion(id_tratamiento_solicitado) {

        if(id_tratamiento_solicitado === undefined){
            modal('error_tratamiento_doctor','<div class="alert alert-danger text-center">No se ha guardado la distribución del tratamiento por parte del doctor</div>',
                '<span class="text-danger"><i class="fa fa-exclamation-triangle" ></i> Alerta</span>',false,'40%',false);
            return false;
        }

        data = {
            codigo_importacion: $("#codigo_importacion").val(),
            id_tratamiento_solicitado: id_tratamiento_solicitado
        };

        peticion_ajax(data, '{{url('seguimiento/store_dato_importacion')}}', 'POST',true);
    }
</script>