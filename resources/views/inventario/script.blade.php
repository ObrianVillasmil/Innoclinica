<script>
    $("#input_productos").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#productos_inventario div.progress-group").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $("#input_lotes").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#lotes_inventario ul.products-list li").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    
    function desglose_entregas(id_tratamiento_solicitado) {

        data ={
            id_tratamiento_solicitado : id_tratamiento_solicitado,
        };
        $.get('{{url('distribucion_inventario/desglose_entrega')}}',data, function (retorno) {
            $('td.product_id').html(data.product_id);
            modal('desglose_entregas', retorno, '<i class="fa fa-medkit"></i> Entregas de la medicación',true,'50%',true);
        });

    }

    function proyeccion_producto_inventario(product_id){

        canvas = "<canvas id='chart'></canvas>";
        modal('proyeccion_producto_inventario', canvas, '<i class="fa fa-line-chart"></i> Proyección de inventario',true,'70%',false);
        data ={
            product_id : product_id,
        };
        id = document.getElementById('chart');
        $.get('{{url('distribucion_inventario/proyeccion_producto_inventario')}}',data, function (retorno) {
            console.log(retorno);

            new Chart(id, {
                type: 'line',
                data: {
                    labels: retorno.labels,
                    datasets: [{
                        label: retorno.producto.toUpperCase(),
                        data: retorno.data,
                        borderColor : '#33b35a',
                        borderWidth : 2,
                        fill : false,
                    },{
                        label: retorno.producto.toUpperCase()+" FALTANTE",
                        borderColor : '#dc3545',
                        borderWidth : 2,
                        data: retorno.negativos,
                        fill : false,
                    }]
                },
                options: {
                    responsive:true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    },
                    fill:false
                }
            });

        });

    }

</script>