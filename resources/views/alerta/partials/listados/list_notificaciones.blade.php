<table class="table table-striped table-sm" id="table_notificacion">
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
        @if(count($notificacion) > 0 )
            @foreach($notificacion as $x => $n)
                <tr>
                    <td style="vertical-align: middle;width: 100px" class="text-center">{{getParty($n->id_usuario)->person->first_name." ".getParty($n->id_usuario)->person->last_name}}</td>
                    <td style="vertical-align: middle" class="text-center">{{\Carbon\Carbon::parse($n->fecha_registro)->format('d-m-Y')}}</td>
                    <td style="vertical-align: middle" class="text-center">{{\Carbon\Carbon::parse($n->fecha_registro)->format('H:i:s')}}</td>
                    <td style="vertical-align: middle" class="text-center"><a href="#" title="Clic para ver el tratamiento">{{isset(getTratamiento($n->id_registro_tabla)->nombre_tratamiento) ? getTratamiento($n->id_registro_tabla)->nombre_tratamiento : ""}}</a></td>
                    <td style="vertical-align: middle" class="text-center">{{$n->accion}}</td>
                    <td style="vertical-align: middle" class="text-center">
                        <div class="btn-group">
                            <button class="btn btn-success btn-sm" title="Ver datos del usuario" onclick="datos_usuario('{{$n->id_usuario}}')">
                                <i class="fa fa-user-circle-o"></i>
                            </button>
                            @if(!$n->estado_notificacion)
                                <button class="btn btn-default btn-sm notificacion_activa_{{$x+1}}" title="Marcar la notificación como vista" onclick="notificacion_visto('{{$n->id_log_administrador}}','{{$x+1}}')">
                                    <i class="fa fa-eye"></i>
                                </button>
                            @endif
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
<div id="pagination_listado_notificaciones">
    {!! str_replace('/?','?',$notificacion->render()) !!}
</div>