@php
    $capturaDato = getCapturaDato($p->id_proceso); $capturaDatoCliente = isset($pasoActual->id_tratamiento_solicitado) ? getCapturaDatoCliente($pasoActual->id_tratamiento_solicitado,$p->id_proceso) : null;
    if(isset($capturaDatoCliente->party_id)){
        foreach (getParty($capturaDatoCliente->party_id)->party_contact_mech as $ctm){
            if(isset($ctm->contact_mech->telecom_number))
                $phone = $ctm->contact_mech->telecom_number;
            if($ctm->contact_mech->info_string != "")
                $mail = $ctm->contact_mech->info_string;
        }
    }
    $x = 0;
@endphp
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<form class="form-horizontal" id="form_captura_datos_{{$p->id_proceso}}">
    <div class="row" id="body_form_captura_datos">
        @foreach($capturaDato->detalle_captura_dato as $dcd)
            @if($dcd->doctor)
                <div class="form-group col-md-4 div_texto_doctor {{$dcd->id_campo}}"  id="{{$dcd->id_campo}}" >
                    <label>Nombre del doctor</label>
                    <div class="ui-widget">
                        <input type="text" id="campo_texto_doctor" {{$dcd->requerido ? "required" : ""}}
                                value="{{isset($capturaDatoCliente) ? $capturaDatoCliente->party_id.") ".getParty($capturaDatoCliente->party_id)->person->first_name." ".getParty($capturaDatoCliente->party_id)->person->last_name : ""}}"
                                name="campo_texto_doctor" class="form-control">
                    </div>
                </div>
            @endif
            @if($dcd->tlf)
                <div class="form-group col-md-4 div_tlf {{$dcd->id_campo}}" id="{{$dcd->id_campo}}">
                    <label>Teléfono</label>

                    <div>
                        <input type="tel" id="campo_tlf_{{explode("_",$dcd->id_campo)[2]}}" {{$dcd->requerido ? "required" : ""}} name="campo_tlf_{{explode("_",$dcd->id_campo)[2]}}"
                               value="{{isset($phone) ? $phone->contact_number : ""}}" placeholder="(xxx) xxxxxxx " class="form-control">
                        <script>$('#campo_tlf_'+'{{explode("_",$dcd->id_campo)[2]}}').mask('(000) 000-0000');</script>
                    </div>
                </div>
            @endif
            @if($dcd->mail)
                <div class="form-group col-md-4 div_mail {{$dcd->id_campo}}" id=" {{$dcd->id_campo}}" >
                    <label>Correo electrónico</label>
                    <div>
                        <input type="email" id="campo_mail_{{explode("_",$dcd->id_campo)[2]}}" {{$dcd->requerido ? "required" : ""}} name="campo_mail_{{explode("_",$dcd->id_campo)[2]}}"
                            value="{{isset($mail) ? $mail : ""}}"   placeholder="ejemplo@gmail.com"  class="form-control">
                    </div>
                </div>
            @endif
            @if($dcd->texto)
                <div class="form-group col-md-12 div_texto {{$dcd->id_campo}}" id="{{$dcd->id_campo}}" >
                    <label>{{$dcd->label}}</label>
                    <div>
                        <input type="text" id="campo_texto_{{explode("_",$dcd->id_campo)[2]}}" {{$dcd->requerido ? "required" : ""}} name="campo_texto_{{explode("_",$dcd->id_campo)[2]}}"
                             value="{{isset($capturaDatoCliente) ? ($x == 0 ? $capturaDatoCliente->texto1 : $capturaDatoCliente->texto2) : "" }}"  placeholder="Label" class="form-control">
                    </div>
                </div>
              @php $x++ @endphp
            @endif
        @endforeach
        <div class="form-group col-md-12 text-center">
            <button type="button" class="btn btn-primary" onclick="storeDatosCliente('form_captura_datos_{{$p->id_proceso}}','{{$p->id_proceso}}','{{$idTratamiento}}','{{isset($capturaDatoCliente) ? $capturaDatoCliente->id_captura_dato_cliente : ""}}','{{$capturaDato->notifica_doctor}}')">
                <i class="fa fa-floppy-o"></i>
                Enviar datos
            </button>
        </div>
    </div>
</form>
<style>
    #ui-id-1{
        width: 60%;
        background: white;
    }
</style>
<script>
    $( function() {
        $.get('{{url('tratamientos_clientes/doctores')}}',{}, function (retorno) {
            console.log(retorno);
            $("#campo_texto_doctor").autocomplete({
                source: retorno
            });
        });

    });
</script>