<div class="col-md-12 text-right">
    <div class="btn-group">
        <button type="button" class="btn btn-primary" title="Agregar {{$tipo_notificacion == 1 ? "correo" : "teléfono"}}"
            onclick="agregar_input()">
            <i class="fa fa-plus"></i>
        </button>
        <button type="button" class="btn btn-danger" title="Quitar {{$tipo_notificacion == 1 ? "correo" : "teléfono"}}"
                onclick="quitar_input()">
            <i class="fa fa-trash"></i>
        </button>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>{{$tipo_notificacion == 1 ? "Correo" : "Teléfono"}}</label>
        <input type="{{$tipo_notificacion == 1 ? "mail" : "text"}}" class="form-control {{$tipo_notificacion == 1 ? "mail" : "text"}}"  required>
    </div>
</div>