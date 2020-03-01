<div style="padding: 20px;">
    {!! (isset(getConfiguracionEmpresa()->terminos_condiciones) && getConfiguracionEmpresa()->terminos_condiciones != "")  ? getConfiguracionEmpresa()->terminos_condiciones : "no se han configurado los terminos y condiciones" !!}
    <div class="form-group ">
        <input id="register-agree" name="registerAgree" onclick="boton_registro()" type="checkbox" required value="1" data-msg="Acepte los terminos y condiciones" class="form-control-custom">
        <label for="register-agree">Acepto los terminos y condiciones</label>
    </div>
    @if(isset(getConfiguracionEmpresa()->terminos_condiciones) && getConfiguracionEmpresa()->terminos_condiciones != "")
        <div class="form-group text-right">
            <button type="button" class="btn btn-default" data-dismiss="modal">
                <i class="fa fa-ban" aria-hidden="true"></i>
                Cerrar
            </button>
        </div>
    @endif
</div>