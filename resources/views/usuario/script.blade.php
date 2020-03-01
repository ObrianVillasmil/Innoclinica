<script>
    function actualizarEstadoUsuario(party_id,estado) {
        accion = estado === "Y" ? "deshabilitar" : "habilitar";
        confirmar = confirm("Esta seguro de "+accion+" el usuario?");
        if(confirmar){
            load("show");
            data = {
                party_id : party_id,
                estado: estado
            };
            peticion_ajax(data,'{{url('usuario/actualizar_estado')}}','POST');
        }
    }

    function actualizaDatosPaciente(party_id) {
        verificar_rol();
        if($("#form_datos_paciente").valid()) {
            confirmar = confirm("¿Esta seguro de actualizar los datos de paciente?");
            if (confirmar) {
                load("show");
                data = {
                    party_id: party_id,
                    nombres_paciente: $("#nombres_paciente").val(),
                    apellidos_paciente: $("#apellidos_paciente").val(),
                    tipo_identificacion_paciente: $("#tipo_identificacion_paciente").val(),
                    identificacion_paciente: $("#identificacion_paciente").val(),
                    pais_paciente: $("#pais_paciente").val(),
                    nacionalidad_paciente: $("#nacionalidad_paciente").val(),
                    telefono_paciente: $("#telefono_paciente").val(),
                    fecha_nacimiento_paciente: $("#fecha_nacimiento_paciente").val(),
                    direccion_paciente: $("#direccion_paciente").val(),
                };

                (party_id === "")
                    ? peticion_ajax(data, '{{url('usuario/guardar_datos_paciente')}}', 'POST')
                    : peticion_ajax(data, '{{url('usuario/actualizar_datos_paciente')}}', 'POST');

            }
        }
    }

    function crear_usuario(){
        $.get('usuario/nuevo',{}, function (retorno) {
            modal('modal_success', retorno, "<i class='fa fa-user-plus'></i> Creación de nuevo usuario",true, '60%',false,function () {
                storeUsuario();
            });
        });
    }
    
    function storeDatosUsuario() {

        if($("#form_datos_usuario").valid()){
            confirmar = confirm("Esta seguro de guardar el usuario?");
            if(confirmar){
                load("show");
                data = {
                    usuario : $("#correo").val(),
                    nombres: $("#nombres").val(),
                    apellidos : $("#apellidos").val(),
                    direccion : $("#direccion").val(),
                    correo : $("#correo").val(),
                    tipo_identificacion : $("#tipo_identificacion").val(),
                    identificacion : $("#identificacion").val(),
                    pais : $("#pais").val(),
                    nacionalidad: $("#nacionalidad").val(),
                    telefono : $("#telefono").val(),
                    fecha_nacimiento : $("#fecha_nacimiento").val(),
                    contrasena : $("#contrasena").val(),
                    rol : $("#rol").val()
                };
                peticion_ajax(data,'{{url('usuario/store')}}','POST',false);
            }
        }

    }

    function guardarFirma(party_id) {
        verificar_rol();
        form = $("#form_firma_digital");
        if(form.valid()) {
            load('show');
            formData = new FormData(form[0]);
            formData.append('party_id',party_id);
            $.ajax({
                url: '{{url('usuario/store_firma')}}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    modal('store_firma_digital',response.msg,'Acción',false,'50%',true);
                }
            }).always(function () {
                load('hide');
            });
        }
    }

</script>