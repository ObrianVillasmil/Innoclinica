<div id="back-to-top" href="#" style="border-radius: 4px;"
     class="card card-prirary cardutline direct-chat direct-chat-primary collapsed-card chat">
    <div class="card-header header-chat header-bot" title="Haga doble clic aquí para abrir y cerrar el chat"
         style="cursor:pointer;background: #33b35a;color: #fff;border-radius: 4px;">
        <h3 class="card-title chat-title d-none" style="margin-top:6px"></h3>
        <div class="card-tools">
            {{--<i class="fa fa-2x fa-comments-o rotated-left i-chat"></i>--}}
            <i style="" class="fa  fa-comment-o i-chat"></i>
        </div>
    </div>
    <div class="card-body chat-body" style="display: none;height: 300px;overflow: auto;">
        <div class="direct-chat-messages" id="chat-bot">
            <div class="direct-chat-msg empresa">
                <div class="direct-chat-infos clearfix">
                    <span class="direct-chat-name float-left">{{getConfiguracionEmpresa()->nombre_empresa}}</span>
                    <span class="direct-chat-timestamp float-right">{{\Carbon\Carbon::parse(now())->format('H:i:s')}}</span>
                </div>
                <i class="fa fa-2x fa-user-circle-o left"></i>
                <div class="direct-chat-text float-right">
                    ¿Hola como podemos ayudarte?
                </div>
            </div>
            @foreach(chatsBotSesion() as $chat_bot)
                @if($chat_bot->party_id != 0)
                    <div class="direct-chat-msg right usuario">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name float-right">{{ucfirst($usuario->person->first_name." ".$usuario->person->last_name)}}</span>
                            <span class="direct-chat-timestamp float-left">{{\Carbon\Carbon::parse($chat_bot->fecha_ingreso)->format('H:i:s')}}</span>
                        </div>
                        <i class="fa fa-2x fa-user right float-right"></i>
                        <div class="direct-chat-text" >
                            <div class="pregunta">{!! $chat_bot->texto !!}</div>
                        </div>
                    </div>
                @else
                    <div class="direct-chat-msg" id="chat-bot">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name float-left">{{getConfiguracionEmpresa()->nombre_empresa}}</span>
                            <span class="direct-chat-timestamp float-right">{{\Carbon\Carbon::parse($chat_bot->fecha_ingreso)->format('H:i:s')}}</span>
                        </div>
                        <i class="fa fa-2x fa-user-circle-o left"></i>
                        <div class="direct-chat-text float-right">
                            <div class="respuesta">{!! $chat_bot->texto !!}</div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <span class="span_repuesta d-none"><i class="fa fa-spinner fa-spin"></i> Respondiendo </span>
    </div>
    <div class="card-footer" style="display: none;">
        <form id="form_chat_bot">
            <div class="input-group">
                <input type="text" name="message" placeholder="Escribe tu mensaje"
                       class="form-control" id="msj_usuario" style="text-transform: lowercase;" required>
                <span class="input-group-append">
                      <button type="button" class="btn btn-primary" id="enviar_pregunta">
                          <i class="fa fa-paper-plane"></i> Enviar
                      </button>
                </span>
            </div>
        </form>
    </div>
    <!-- /.card-footer-->
</div>
<style>
    .fa-comment-o,.fa-commenting-o{
        position: relative;
        bottom: 8px;
        left: 18px;
        font-size: 26px
    }
    .card-header{
        border-bottom: 0;
        height: 28px;
    }
    .chat {
       /* position: fixed;
        left: 50%;
        z-index: 1000;
        bottom: -25px;*/
        width:40px;
        margin: 0;
        padding: 0;
    }
    #back-to-top{
        z-index: 99999999999;
    }
    #msj_usuario-error{
        position: absolute;
        bottom: -26px;
    }
    element.style {
        width: 70%;
    }
    .info-box .progress .progress-bar {
        background-color: #fff;
    }
    .progress-bar {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        -ms-flex-pack: center;
        justify-content: center;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        background-color: #007bff;
        transition: width .6s ease;
    }
    .align-items-center, .info-box .info-box-icon {
        -ms-flex-align: center!important;
        align-items: center!important;
    }
    .info-box .info-box-icon, .justify-content-center {
        -ms-flex-pack: center!important;
        justify-content: center!important;
    }
    .d-flex, .info-box, .info-box .info-box-icon {
        display: -ms-flexbox!important;
        display: flex!important;
    }
    .info-box {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        border-radius: .25rem;
        background: #fff;
        min-height: 80px;
        padding: .5rem;
        position: relative;
    }
    .info-box-icon {
        border-radius: .25rem;
        display: block;
        font-size: 1.875rem;
        text-align: center;
        width: 70px;
    }
    .info-box-content {
        -ms-flex: 1;
        flex: 1;
        padding: 5px 10px;
    }
    .info-box .info-box-text, .info-box .progress-description {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .info-box .info-box-number {
        display: block;
        font-weight: 700;
    }
    .info-box .progress {
        background-color: rgba(0,0,0,.125);
        height: 2px;
        margin: 5px 0;
    }
    .info-box{
        color: #FFFFFF;
    }
    @media (min-width: 1200px){
        .col-lg-3 .info-box .progress-description, .col-md-3 .info-box .progress-description, .col-xl-3 .info-box .progress-description {
            font-size: 1rem;
            display: block;
        }
    }
    @media (min-width: 992px){
        .col-lg-3 .info-box .progress-description, .col-md-3 .info-box .progress-description, .col-xl-3 .info-box .progress-description {
            font-size: .75rem;
            display: block;
        }
    }
    @media (min-width: 768px){
        .col-lg-3 .info-box .progress-description, .col-md-3 .info-box .progress-description, .col-xl-3 .info-box .progress-description {
            display: none;
        }

    }
    @media (max-width: 768px){
        .chat {
            right: 48%;
        }
    }
    .info-box .progress-description {
        margin: 0;
    }
    .info-box .info-box-text, .info-box .progress-description {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .card-title {
        float: left;
        font-size: 1.1rem;
        font-weight: 400;
        margin: 0;
    }
    .direct-chat-infos {
        display: block;
        font-size: .875rem;
        margin-bottom: 2px;
    }
    .direct-chat-msg {
        margin-bottom: 10px;
    }
    .direct-chat-msg, .direct-chat-text {
        display: block;
        background: #f9f8f8;
        padding: 5px;
        border-radius: 10px;
    }
    .card-header>.card-tools {
        float: right;
        margin-right: -.625rem;
        padding: 0px 15px;
    }
    .header-chat:hover{
        transform:scale(1.1);
        -ms-transform:scale(1.1); // IE 9
        -moz-transform:scale(1.1); // Firefox
        -webkit-transform:scale(1.1); // Safari and Chrome
        -o-transform:scale(1.1);
        transition: all ease .4s;

    }
</style>
<script>
    $(".header-bot").click(function (e) {
        $(this).removeClass('collapsed-card');
        $("div.chat-body").css('display','block');
        $("div.card-footer").css('display','block');
        $(".chat-title").html('Escríbenos...!').removeClass('d-none');
        $("#back-to-top").css({'top':'4%','right':'40%','width':'320px','border-radius':'5px'});
        $(".chat").css('position','fixed');
        $(".card-header").css({'border-radius':'5px','height':'48px'});
        e.stopPropagation();
        $(".chat-body").scrollTop($(".chat-body")[0].scrollHeight);
        $(".fa-comment-o,.fa-commenting-o").css({'bottom': '0px','left':'0'});
    });

    $("div.chat-body,div.card-footer,.chat-title").click(function (e) {
        e.stopPropagation();
    });

    $("body").click(function () {
        $(".header-bot").addClass('collapsed-card');
        $("div.chat-body").css('display','none');
        $("div.card-footer").css('display','none');
        $("#back-to-top").css({'width':'40px','right':'5rem','border-radius':'4px','position':'inherit','top':'0','left':'0'});
        $(".card-header").css({'border-radius':'4px','height':'28px'});
        $(".chat").css('position','block');
        $(".chat-title").addClass('d-none').html('');
        $(".fa-comment-o,.fa-commenting-o").css({'bottom':'8px','left':'18px'});
    });

    setInterval(function () {
        element = $(".i-chat");
        if(element.hasClass('fa-comment-o')){
            element.removeClass('fa-comment-o');
            element.addClass('fa-commenting-o');
        }else{
            element.addClass('fa-comment-o');
            element.removeClass('fa-commenting-o');
        }
    },1500);

    $(function() {
        $("#back-to-top").draggable();

        $('.page').droppable({
            accept :"#back-to-top",
            drop: function(ev,ui) {
                ev.stopPropagation();
            }
        });
    });

    $("#enviar_pregunta").click(function () {

        if($("#form_chat_bot").valid()){

            html = "<div class='direct-chat-msg right usuario'>" +
                        "<div class='direct-chat-infos clearfix'>" +
                            "<span class='direct-chat-name float-right'>{{ucfirst($usuario->person->first_name.' '.$usuario->person->last_name)}}</span>" +
                            "<span class='direct-chat-timestamp float-left'>{{Carbon\Carbon::parse(now())->format('H:i:s')}}</span>" +
                        "</div>" +
                        "<i class='fa fa-2x fa-user right float-right'></i>" +
                        "<div class='direct-chat-text'> <div class='respuesta'>"
                            + $("#msj_usuario").val()+
                        "</div></div>" +
                    "</div>";

            $(".span_repuesta").removeClass('d-none');
            data ={
                msj : $("#msj_usuario").val()
            };
            $("#chat-bot").append(html);

            $.ajax({
                method : 'POST',
                url    : '{{url('bot/responder')}}',
                data   : data,
                success:function (response) {
                    $("#msj_usuario").val("");
                    $("#chat-bot").append(response);
                    $(".chat-body").scrollTop($(".chat-body")[0].scrollHeight);
                    $(".span_repuesta").addClass('d-none');
                }
            });

        }

    });

    function cotizar_prodcutos() {
        $.get('/cotizacion/cotizador_productos',{}, function (data) {
            modal('cotizador', data, '<i class="fa fa-money"></i> Generar cotización',false,'70%',false,function () {
                /*enviarCotizacion();*/
            });
        });
    }
    
    function select_producto() {
        data ={
            product_id : $("select#product_id").val()
        };
        $.get('/cotizacion/select_producto',data, function (data) {
            $('td.product_id').html(data.product_id);
        });
    }
</script>