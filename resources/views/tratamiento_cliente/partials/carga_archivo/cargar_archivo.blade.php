@php $cargaArchivoCliente = isset($pasoActual->id_tratamiento_solicitado) ? getCargaArchivoCliente($pasoActual->id_tratamiento_solicitado,$p->id_proceso) : null;  @endphp
<div class="" style="margin: 20px 0px 40px">
    <form id="form_archivo_cliente_{{$y+1}}">
        <div class="row">
            @if($dataProceso['notificacion_doctor'])
                <div class="col-md-6">
                    <label for="doctor"> Escriba el nombre de su doctor tratante </label>
                    <div class="form-group">
                        <div class="input-group ui-widget">
                            <input type="text" id="doctor" name="doctor" {{isset($cargaArchivoCliente) ? "disabled" : ""}} class="form-control" required>
                        </div>
                    </div>
                    <div class="row inputs-doctor">
                        <div class="col-md-6">
                            <label><i class="fa fa-envelope-o"></i> Correo</label>
                            <input type="mail" id="doctor_mail" {{isset($cargaArchivoCliente) ? "disabled" : ""}} name="doctor_mail" class="form-control" >
                        </div>
                        <div class="col-md-6">
                            <label><i class="fa fa-phone"></i> Teléfono</label>
                            <input type="number" min="1" id="doctor_tlf" {{isset($cargaArchivoCliente) ? "disabled" : ""}} placeholder="Ej 980000000" name="doctor_tlf" class="form-control" >
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-md-{{$dataProceso['notificacion_doctor'] ? "6": "12"}}">
                <label for="archivo"> Cargue su archivo, tamaño máximo 1MB en formato PDF, JPG ó PNG</label>
                <div class="form-group">
                    <div class="input-group">
                        <input type="file" multiple  {{--count($cargaArchivoCliente)>0 ? "disabled" : ""--}} name="archivo[]" id="archivo"
                                class="form-control" accept="application/pdf,image/*" required>
                        <div class="input-group-append">

                            <button type="button" class="btn btn-primary" {{isset($cargaArchivoCliente) && count($cargaArchivoCliente)>0 ? "disabled" : ""}} onclick="storeArchivoCliente('{{$dataProceso['carpeta']}}','{{$dataProceso['id_notificacion']}}','{{$dataProceso['id_carga_archivo']}}','{{$idTratamiento}}','form_archivo_cliente_{{$y+1}}','{{$partyIdSolicitante}}')">
                                <i class="fa fa-cloud-upload"></i>
                                Cargar archivo
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    @if($cargaArchivoCliente != null)
                        <i class="fa fa-file-text-o"></i> Archivo cargado:
                        @foreach($cargaArchivoCliente as $ca)
                             <a target="_blank" href="{{url('/storage/archivos/'.$ca->carpeta."/".$ca->archivo)}}"> {{$ca->archivo}}</a>
                            <a style="cursor: pointer" class="text-danger" onclick="eliminar_archivo('{{$ca->carpeta}}','{{$ca->archivo}}')" title="Eliminar archivo">
                                <i class="fa fa-times-circle"></i>
                            </a>
                        @endforeach
                    @else
                        <i class="fa fa-file-text-o"></i> Archivo cargado: No se ha cargado el archivo
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>
<style>
    label#archivo-error,
    label#doctor-error{
        position: absolute;
        top: 40px;
    }
    ul#ui-id-1{
        background: white;
        width: 100px;
        height: 150px;
        overflow: auto;
    }
    li.ui-menu-item{
        cursor: pointer;
    }
    li.ui-menu-item:hover{
        background: #E8E8E8;
    }
</style>
<script>
    /*$("#doctor").on('keyup',function () {
        if($("div[aria-relevant='additions'] div:last")[0].outerText === "No search results."){
            $("#doctor_tlf").remove('required');
            $(".inputs-doctor").removeClass('d-none');
            return false;
        }else if($("div[aria-relevant='additions'] div:last")[0].outerText === "1 result is available, use up and down arrow keys to navigate."){
            $("#doctor_tlf").attr('required',true);
            $(".inputs-doctor").addClass('d-none');
            return false;
        }else if($("div[aria-relevant='additions'] div:last")[0].outerText !== "No search results."){
            $("#doctor_tlf").remove('required');
            $(".inputs-doctor").removeClass('d-none');
            return false;
        }else if($("div[aria-relevant='additions'] div:last")[0].outerText == ""){
            $("#doctor_tlf").remove('required');
            $(".inputs-doctor").removeClass('d-none');
            return false;
        }
    });

    $( function() {
        $.get('/tratamientos_clientes/doctores',{}, function (retorno) {
            $("#doctor").autocomplete({
                source: retorno
            });
        });

    });*/
</script>



