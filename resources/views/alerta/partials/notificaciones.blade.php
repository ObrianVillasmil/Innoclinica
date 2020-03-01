<div class="tab-pane show active" id="notificacion" role="tabpanel">
    <div class="card">
        <div class="card-header">
            <div class="card-actions float-right">
                <div class="dropdown show">
                    <a href="#" data-toggle="dropdown" data-display="static">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle">
                            <circle cx="12" cy="12" r="1"></circle>
                            <circle cx="19" cy="12" r="1"></circle>
                            <circle cx="5" cy="12" r="1"></circle>
                        </svg>
                    </a>
                </div>
            </div>
            <h5 class="card-title mb-0">Notificaciones de tratamientos</h5>
        </div>
        <div class="card-body">
            <div id="table_notificaciones"></div>
        </div>
    </div>
</div>
<script>
    listar_notificaciones();

    function listar_notificaciones() {
        $.get('/alerta/list_notificaciones',{}, function (retorno) {
            console.log(retorno);
            $("#table_notificaciones").html(retorno);
        });
    }

    $(document).on("click", "#pagination_listado_notificaciones .pagination li a", function (e) {
        load("show");
        e.preventDefault();
        $('#table_notificaciones').html($('#notificacion').html());
        $.get($(this).attr("href"), function (resul) {
            $('#table_notificaciones').html(resul);
        }).always(function () {
            load("hide");
        });
    });
</script>
