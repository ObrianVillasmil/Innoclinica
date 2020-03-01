<table class="table table-striped table-sm" id="table_tratamiento_solicitado">
    <thead>
    <tr>
        <th class="text-center">Usuario</th>
        <th class="text-center">Fecha solicitud</th>
        <th class="text-center">Hora solicitud</th>
        <th class="text-center">Tratamiento</th>
        <th class="text-center">Mesaje</th>
        <th class="text-center">Acciones</th>
    </tr>
    </thead>
    <tbody>
    @if(count($solicitudTratamiento) > 0 )
        @foreach($solicitudTratamiento as $x => $st)
            <tr>
                <td style="vertical-align: middle;width: 100px" class="text-center">{{getParty($st->id_usuario)->person->first_name." ".getParty($st->id_usuario)->person->last_name}}</td>
                <td style="vertical-align: middle" class="text-center">{{\Carbon\Carbon::parse($st->fecha_registro)->format('d-m-Y')}}</td>
                <td style="vertical-align: middle" class="text-center">{{\Carbon\Carbon::parse($st->fecha_registro)->format('H:i:s')}}</td>
                <td style="vertical-align: middle" class="text-center"><a href="#" title="Clic para ver el tratamiento">{{getTratamientoSolicitadoById($st->id_registro_tabla)->tratamiento->nombre_tratamiento}}</a></td>
                <td style="vertical-align: middle" class="text-center">{{$st->accion}}</td>
                <td style="vertical-align: middle" class="text-center">
                    <div class="btn-group">
                    <a target="_blank" href="{{url('seguimiento/seguimineto_tratamiento',[getTratamientoSolicitadoById($st->id_registro_tabla)->tratamiento->id_tratamiento,$st->id_usuario])}}" class="btn btn-success btn-sm" title="Segumiento del tratamiento">
                        <i class="fa fa-stethoscope" ></i>
                    </a>
                    {{--@if(empty(getTratamientoSolicitadoById($st->id_registro_tabla)->id_doctor))--}}
                    <button class="btn btn-default btn-sm" title="Asignar un doctor al tratamiento" onclick="asignar_doctor_tratamiento('{{getTratamientoSolicitadoById($st->id_registro_tabla)->id_tratamiento_solicitado}}')">
                        <i class="fa fa-user-md"></i>
                    </button>
                    {{--@endif--}}
                    </div>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="6" style="vertical-align: middle">
                <div class="alert alert-info text-center" role="alert">
                        No se encontraron resultados
                    </div>
            </td>
        </tr>
    @endif
    </tbody>
</table>
<div id="pagination_listado_tratamiento_solicitado">
    {!! str_replace('/?','?',$solicitudTratamiento->render()) !!}
</div>
<script>
    function asignar_doctor_tratamiento(id_tratamiento_solicitado){
        datos = {
            id_tratamiento_solicitado : id_tratamiento_solicitado
        };
        load("show");
        $.get('{{url('alerta/asignar_asignar_doctor')}}', datos, function (retorno) {
            modal('asignar_doctor_tratamiento', retorno, '<i class="fa fa-user-md"></i> Asignar doctor al tratamiento',true,'35%',false,undefined);
        }).always(function () {
            load("hide");
        });
    }

    function storedoctorAsignado(id_tratamiento_solicitado) {
        data = {
            party_id : $("#party_id").val(),
            id_tratamiento_solicitado : id_tratamiento_solicitado
        };
        peticion_ajax(data,'{{url('alerta/store_asignar_doctor')}}','POST');
    }
</script>