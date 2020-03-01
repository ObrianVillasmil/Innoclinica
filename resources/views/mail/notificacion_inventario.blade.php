<!DOCTYPE html>
<html>
<head>
    <title>Vacaciones</title>
    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }
        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }
        .alert-warning {
            color: #8a6d3b;
            background-color: #fcf8e3;
            border-color: #faebcc;
        }
        .alert-warning hr {
            border-top-color: #f7e1b5;
        }
    </style>
</head>
<body>
<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">{{ucfirst($empresa->nombre_empresa)}}, alerta de inventario</h4>
    <p>
        Se notifica que el inventario para el producto {{$producto}} posee un deficit según su uso futuro, a continuación se procede a explicar
    </p>
    <p> Desde el {{\Carbon\Carbon::parse($fechaTopeInicial)->format('m-Y')}}  hasta el {{\Carbon\Carbon::parse($fechaTopeFinal)->format('m-Y')}} haran falta {{$faltantes}} unidades del producto para poder cubrir el uso del mismo</p>
</div>
</body>
</html>
