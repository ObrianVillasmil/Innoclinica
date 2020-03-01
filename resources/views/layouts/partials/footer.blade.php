<footer class="main-footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <p>{{strtoupper(isset(getConfiguracionEmpresa()->nombre_empresa) ? getConfiguracionEmpresa()->nombre_empresa : "")}} - {{now()->format('Y')}} </p>
            </div>
            <div class="col-sm-6 text-right">
                <p>Design by <a href="#" >Obrian Villasmil</a></p>
            </div>
        </div>
    </div>
</footer>