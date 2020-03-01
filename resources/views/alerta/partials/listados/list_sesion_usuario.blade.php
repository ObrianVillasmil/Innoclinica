<table class="table table-striped table-sm" id="table_sesion_usuario">
    <thead>
    <tr>
        <th class="text-center">Usuario</th>
        <th class="text-center">Fecha</th>
        <th class="text-center">Hora</th>
        <th class="text-center">Mesaje</th>
    </tr>
    </thead>
    <tbody>
    @if(count($sesion) > 0 )
        @foreach($sesion as $x => $s)
            <tr>
                <td style="vertical-align: middle" class="text-center">{{getParty($s->id_usuario)->person->first_name." ".getParty($s->id_usuario)->person->last_name}}</td>
                <td style="vertical-align: middle" class="text-center">{{\Carbon\Carbon::parse($s->fecha_registro)->format('d-m-Y')}}</td>
                <td style="vertical-align: middle" class="text-center">{{\Carbon\Carbon::parse($s->fecha_registro)->format('H:i:s')}}</td>
                <td style="vertical-align: middle" class="text-center">{{$s->accion}}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="6" style="vertical-align: middle">
                <div class="alert alert-info text-center" role="alert">
                     No se encontraron carpetas creadas
                </div>
            </td>
        </tr>
    @endif
    </tbody>
</table>
<div id="pagination_listado_sesiones">
    {!! str_replace('/?','?',$sesion->render()) !!}
</div>