<div class="col-md-12">
    <div class="row ">
        @foreach($iconos as $icono)
            <div style="padding: 5px;">
                <button type="button" class="btn btn-outline-info" id="{{$icono->clase}}" onclick="seleccionar_icono(this)">
                    {{--<input type="radio" value="{{$icono->clase}}" id="icono" name="icono">--}}
                    <i class="{{$icono->clase}}"></i>
                </button>
            </div>
        @endforeach
    </div>
</div>