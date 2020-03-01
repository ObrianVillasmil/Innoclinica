@extends('layouts.partials.dashboard')
@section('title')
    Tratamiento
@endsection

@section('contenido')
    <div class="container p-0">
        <div class="row">
            <div class="col-md-4 col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fa fa-cogs" aria-hidden="true"></i> Procesos
                        </h5>
                    </div>
                    <div class="list-group list-group-flush" role="tablist">
                        <ul id="menu_procesos" class="side-menu list-unstyled " style="margin-bottom: 0;">
                            <li>
                                @foreach($procesos->subMenu as $x => $p)
                                        @if($p->path !== 'notificacion' && $p->path !== 'documento_consolidado')
                                            <a href="#dropdownProcesos_{{$x+1}}" class="list-group-item list-group-item-action"
                                               aria-expanded="false" data-toggle="collapse">
                                                {!! $icono[$x] !!} {{$p->nombre}}
                                            </a>
                                            <ul id="dropdownProcesos_{{$x+1}}" class="collapse list-unstyled">
                                                <li style="padding: 10px 20px;">
                                                    <div id="item_{{$x+1}}">
                                                        @if($p->path === 'carga_archivo')
                                                            @foreach($cargaArchivo as $c)
                                                                <div style="cursor: pointer; padding: 5px 0px;width: 100%;" class="item">
                                                                    <input id="id_sub_menu" type="hidden" value="{{$p->id_sub_menu}}">
                                                                    <input id="id_proceso" type="hidden" value="{{$c->id_carga_archivo}}">
                                                                    {{$c->nombre}}
                                                                </div>
                                                            @endforeach
                                                        @elseif($p->path === 'documento')
                                                            @foreach($documentos as $d)
                                                                <div style="cursor: pointer; padding: 5px 0px;width: 100%;" class="item">
                                                                    <input id="id_sub_menu" type="hidden" value="{{$p->id_sub_menu}}">
                                                                    <input id="id_proceso" type="hidden" value="{{$d->id_documento}}">
                                                                    {{$d->nombre}}
                                                                </div>
                                                            @endforeach
                                                        @elseif($p->path === 'distribucion_tratamiento')
                                                            @foreach($distribucion_tratamiento as $dist)
                                                                <div style="cursor: pointer; padding: 5px 0px;width: 100%;" class="item">
                                                                    <input id="id_sub_menu" type="hidden" value="{{$p->id_sub_menu}}">
                                                                    <input id="id_proceso" type="hidden" value="{{$dist->id_tratamiento}}">
                                                                    {{$dist->nombre_tratamiento}}
                                                                </div>
                                                            @endforeach
                                                        @elseif($p->path === 'captura_dato')
                                                            @foreach($capturaDatos as $cap_dat)
                                                                <div style="cursor: pointer; padding: 5px 0px;width: 100%;" class="item">
                                                                    <input id="id_sub_menu" type="hidden" value="{{$p->id_sub_menu}}">
                                                                    <input id="id_proceso" type="hidden" value="{{$cap_dat->id_captura_dato}}">
                                                                    {{$cap_dat->nombre}}
                                                                </div>
                                                            @endforeach
                                                        @elseif($p->path === 'cotizacion')
                                                            <div style="cursor: pointer; padding: 5px 0px;width: 100%;" class="item">
                                                                <input id="id_sub_menu" type="hidden" value="{{$p->id_sub_menu}}">
                                                                <input id="id_proceso" type="hidden" value="{{$idTratamiento}}">
                                                                Cotización
                                                            </div>
                                                        @endif
                                                    </div>
                                                </li>
                                            </ul>
                                        @endif
                                @endforeach
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-xl-8">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="account" role="tabpanel">
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
                                <h5 class="card-title mb-0">Administrar procesos del tratamiento {{strtoupper(getTratamiento($idTratamiento)->nombre_tratamiento)}}</h5>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" id="form_datos_empresa">
                                    <input type="hidden" id="id_tratamiento" name="id_tratamiento" value="{{isset($procesosTratamientos) ? $procesosTratamientos->id_tratamiento : null}}">
                                    <div class="form-row linea_tratamiento" style="margin-left: 0px;">
                                        @if(isset($procesosTratamientos) && $procesosTratamientos->procesos->count() > 0)
                                            @foreach($procesosTratamientos->procesos as $x => $proceso)
                                                <div style="cursor: pointer; padding: 10px; width: 47%; position: initial; z-index: 1000; left: auto; top: 111px; background: rgb(245, 244, 244); margin: 10px 5px;" id="item_{{$x+1}}" class="item_{{$x+1}} div_procesos ui-draggable ui-draggable-handle ui-draggable-dragging">
                                                    <input id="id_sub_menu" type="hidden" value="{{$proceso->sub_menu->id_sub_menu}}">
                                                    <input id="id_proceso" type="hidden" value="{{$proceso->id_proceso}}">
                                                    @if($proceso->sub_menu->path === "carga_archivo")
                                                        <span class="span_text">{{$x+1}}</span>) <a target="_blank" href="{{url('carga_archivo/add_carga_archivo',$proceso->id_proceso)}}"> {{getCargaArchivo($proceso->id_proceso)->nombre}}</a>
                                                    @elseif($proceso->sub_menu->path === "documento")
                                                        <span class="span_text">{{$x+1}}</span>) <a  target="_blank" href="{{url('/documento/editar_documento',[$proceso->id_proceso,getDocumento($proceso->id_proceso)->cuerpo != "" ? 1 : 0])}}"> {{getDocumento($proceso->id_proceso)->nombre}}</a>
                                                    @elseif($proceso->sub_menu->path === "distribucion_tratamiento")
                                                        <span class="span_text">{{$x+1}}</span>) <a href="#" onclick="form_distribucion_tratamiento('{{$proceso->id_tratamiento}}','{{getTratamiento($proceso->id_tratamiento)->nombre_tratamiento}}')"> Distribución {{getTratamiento($proceso->id_tratamiento)->nombre_tratamiento}}</a>
                                                    @elseif($proceso->sub_menu->path === "captura_dato")
                                                        <span class="span_text">{{$x+1}}</span>) <a  target="_blank" href="{{url('captura_dato/add_captura_dato',[$proceso->id_proceso])}}"> {{getCapturaDato($proceso->id_proceso)->nombre}}</a>
                                                    @elseif($proceso->sub_menu->path === "cotizacion")
                                                        <span class="span_text">{{$x+1}}</span>) <a href="#" onclick="cotizar('{{$proceso->id_tratamiento}}')">Cotización {{getTratamiento($proceso->id_tratamiento)->nombre_tratamiento}}</a>
                                                    @endif
                                                    <a href="#" style="border:1px solid #33b35a ;border-radius: 30px;padding: 0px 7px;" onclick="eliminar_proceso('{{$x+1}}')">x</a>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="text-center" style="margin-top: 20px">
                                        <button type="button" class="btn btn-default" onclick="reiniciarProcesosTratamiento()">
                                            <i class="fa fa-refresh" aria-hidden="true"></i> Reiniciar
                                        </button>
                                        <button type="button" class="btn btn-primary" onclick="storeProcesosTratamiento()">
                                            <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        #menu_procesos li a::before{
            content: '\f107';
            display: inline-block;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            font-family: 'FontAwesome';
            position: absolute;
            top: 50%;
            right: 20px;
        }

        .linea_tratamiento {
            border: 5px dashed transparent;
            /*background: #ddd;*/
            /*margin: 1em;*/
            min-height: 8em;
            /*padding: 1em;*/
            width: 100%;
            border: 1px solid #276cbc;
        }

        .linea_tratamiento.hovering {
           /* background: #b6d6fb;*/
            border-color: #276cbc;
        }

        .item {
            background: white;
            cursor: pointer;
            display: inline-block;
            padding: 1em;
        }
    </style>
@endsection
@section('custom_page_js')
    @include('tratamiento.script')
@endsection