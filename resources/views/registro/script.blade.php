<script>

    function terminos_condiciones() {
        load("show");
        $.get('{{url('terminos_condiciones')}}', {}, function (retorno) {
            modal('terminos_condiciones', retorno, '<i class="fa fa-file-text-o" aria-hidden="true"></i> Terminos y condiciones',true,'80%',false,'');
            $("#msg_regitrar").hasClass('d-none')
                ? $("#register-agree").attr('checked',true)
                : $("#register-agree").removeAttr('checked');
        }).always(function () {
            load("hide");
        });
    }

    function boton_registro() {
        if($("#register-agree").is(":checked")){
            $("#register").removeAttr('disabled');
            $("#msg_regitrar").addClass('d-none');
        }else{
            $("#register").attr('disabled',true) ;
            $("#msg_regitrar").removeClass('d-none');
        }
    }

</script>