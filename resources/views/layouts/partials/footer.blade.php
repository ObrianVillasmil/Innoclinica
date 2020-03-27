<footer class="main-footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-4">
                <p>{{strtoupper(isset(getConfiguracionEmpresa()->nombre_empresa) ? getConfiguracionEmpresa()->nombre_empresa : "")}} - {{now()->format('Y')}} </p>

            </div>
            <div class="col-sm-7 text-right">
                <p>Design by <a href="#" >Obrian Villasmil</a></p>
            </div>
            <div class="col-sm-1 text-right">
                @include('layouts.partials.bot')
            </div>
        </div>
    </div>
</footer>