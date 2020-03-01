<div class="direct-chat-msg" id="chat-bot">
    <div class="direct-chat-infos clearfix">
        <span class="direct-chat-name float-left">{{getConfiguracionEmpresa()->nombre_empresa}}</span>
        <span class="direct-chat-timestamp float-right">{{\Carbon\Carbon::parse(now())->format('H:i:s')}}</span>
    </div>
    <i class="fa fa-2x fa-user-circle-o left"></i>
    <div class="direct-chat-text float-right">
        <div class="respuesta">{!! $respuesta !!} </div>
    </div>
</div>
