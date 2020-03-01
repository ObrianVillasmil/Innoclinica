<script>

    function datos_usuario(id_usuario) {
        data = {
            partyId : id_usuario
        };
        $.get('/usuario/perfil/'+id_usuario,data, function (retorno) {
            modal('Datos_usuarios', retorno, '<i class="fa fa-file-text-o" aria-hidden="true"></i> Datos del usuario',true,'80%',false,'');
        });
    }

    function notificacion_visto(id_log_administrador,id) {

        confirmar = confirm("Se marcará la notificación como vista y no se alertará mas sobre ella");
        if (confirmar) {
            load("show");
            data = {
                id_log_administrador : id_log_administrador,
            };
            $.post('{{url('alerta/desactivar_notificacion')}}', data, function (retorno) {
                if(retorno.success)
                    $(".notificacion_activa_"+id).addClass('d-none');

                modal('modal_success', retorno.msg,'Acción' ,true, '40%',false);
            }).always(function () {
                load("hide");

            });

        }

    }

</script>