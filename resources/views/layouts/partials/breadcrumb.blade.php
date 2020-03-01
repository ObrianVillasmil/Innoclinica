<div class="breadcrumb" style="padding: 0.35rem 1rem;margin-bottom: 0rem;">
    <div class="col-md-8">
        <h3 style="margin-bottom: 0">
                @isset($titulo['titulo']) {{$titulo['titulo']}}@endisset
            <small>
                @isset($titulo['sub_titulo']) {{$titulo['sub_titulo']}}@endisset
            </small>
        </h3>
    </div>
    <div class="col-md-4">
        <span style="width: 100%;text-align: right">
            <a href="{{url('/')}}" style="text-decoration:none;">
                <i class="fa fa-home" aria-hidden="true"></i>
                    Inicio
                </a>
            @php $path = explode("/",$url) @endphp
            @foreach($path as $x => $p)
                @if($x < 1)
                    <a href="{{url($p)}}" style="text-decoration:none;">
                       / {{ucfirst(str_replace("_"," ",$p))}}
                    </a>
                @endif
            @endforeach
            <span onclick="window.location.reload()" title="Actualizar página"> <a href="#" style="text-decoration:none;">/ <i class="fa fa-retweet" aria-hidden="true"></i> </a></span>
        </span>
    </div>
</div>