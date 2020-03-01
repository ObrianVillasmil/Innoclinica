<div class="direct-chat-msg right usuario">
    <div class="direct-chat-infos clearfix">
        <span class="direct-chat-name float-right">{{ucfirst($usuario->person->first_name." ".$usuario->person->last_name)}}</span>
        <span class="direct-chat-timestamp float-left">{{\Carbon\Carbon::parse(now())->format('H:i:s')}}</span>
    </div>
    <i class="fa fa-2x fa-user right"></i>
    <div class="direct-chat-text">
        <div class="pregunta">You better believe it!</div>
    </div>
</div>