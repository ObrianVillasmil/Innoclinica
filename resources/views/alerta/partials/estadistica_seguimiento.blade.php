<div class="@if(count($prodcuto) == 2 || count($prodcuto) == 1) col-lg-6 @else col-lg-4 @endif">
    <div class="card data-usage">
        <h2 class="display h4">Producto: {{getProducto($product)->product_name}} </h2>
        <div class="row d-flex align-items-center">
            <div class="col-sm-6">
                <div id="progress-circle_{{$x+1}}" class="d-flex align-items-center justify-content-center"></div>
            </div>
            <div class="col-sm-6">
                <strong class="text-primary">Usados: {{$usado}}</strong>
                <span>Requeridos: {{$total}}</span>
                <span>Restante: {{$restante}}</span></div>
        </div>
        <p>Distribución de uso del medicamento para el tratamiento del paciente</p>
    </div>
</div>
<script>

    $progress1= $("#progress-circle_1");
    $progress2= $("#progress-circle_2");

    $(function () {
        if($progress1.length===1)
            $progress1.gmpc({
                color: '#33b35a',
                line_width: 5,
                percent: 80
            }).gmpc('animate', 80, 13000);

        if($progress2.length===1)
            $progress2.gmpc({
                color: '#33b35a',
                line_width: 5,
                percent: 80
            }).gmpc('animate', 80, 13000);
    });
</script>