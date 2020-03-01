@extends('layouts.partials.dashboard')
@section('title')
    Configuracion documentos consolidados
@endsection

@section('contenido')
    <div class="container p-0">
        <div class="row">
            <div class="col-xl-12">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="subir_documento" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-actions float-right">
                                    <button type="button" class="btn btn-primary btn-sm" title="Agregar otro rol" onclick="addRolDocumentoConsolidado()">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" title="Eliminar rol" onclick="deleteRolDocumentoConsolidado()">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                                <h5 class="card-title mb-0">Configurar consolidado de documentos para el tratamiento {{$tratamiento->nombre_tratamiento}}</h5>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" id="form_documento_consolidado">
                                    <input type="hidden" class="form-control"  id="nombre_documento_consolidado" name="nombre_documento_consolidado" autocomplete="off"
                                           value="{{isset($configuracion->nombre) ? $configuracion->nombre : "Documentos consolidados tratamiento ". $tratamiento->nombre_tratamiento}}" disabled required>
                                    {{--<div class="col-md-5">
                                            <div class="form-group">
                                                <label for="nombre">Nombre de la tarea</label>
                                                <input type="text" class="form-control"  id="nombre_documento_consolidado" name="nombre_documento_consolidado" autocomplete="off"
                                                       value="{{isset($configuracion->nombre) ? $configuracion->nombre : "Documentos consolidados tratamiento ". $tratamiento->nombre_tratamiento}}" disabled required>
                                            </div>
                                        </div>--}}
                                    {{--@if(!isset($configuracion->documento_solicitado_role_type))--}}
                                        <div class="ejecuta_proceso {{isset($configuracion->documento_solicitado_role_type) ? ($configuracion->documento_solicitado_role_type->count() > 0 ? "d-none" : "") : "" }}" >
                                            <div class="form-row">
                                                <div class="col-md-4 ">
                                                        <div class="form-group">
                                                            <label for="usuario">¿Quién ejecuta la tarea?</label>
                                                            <select id="usuario" name="usuario" class="form-control" autocomplete="off" required>
                                                                @foreach($roleType as $rt)
                                                                    <option {{isset($configuracion->role_type_id) ? ($rt->role_type_id == $configuracion->role_type_id ? "selected" : "") : ""}}
                                                                            value="{{$rt->role_type_id}}">{{$rt->description}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="usuario">Correos</label>
                                                            <select id="enviar_correo" name="enviar_correo" class="form-control enviar_correo"
                                                                    onchange="habilitaCorreos()" autocomplete="off" required>
                                                                <option {{ isset($configuracion->envio_mail) ? ($configuracion->envio_mail == true ? 'selected' : '') : "" }} value="1">Envía documentos por correo electrónico</option>
                                                                <option {{ isset($configuracion->envio_mail) ? ($configuracion->envio_mail == false ? 'selected' : '') : ""  }} value="0">No envía documentos por correo electrónico</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="firma_electronica">Firma</label>
                                                            <select id="firma_electronica" name="firma_electronica" class="form-control" autocomplete="off" required>
                                                                <option {{ isset($configuracion->firma  ) ? ($configuracion->firma   == true ? 'selected' : '') : "" }} value="1">Firma documentos electrónicamente</option>
                                                                <option {{ isset($configuracion->firma  ) ? ($configuracion->firma   == false ? 'selected' : '') : ""  }} value="0">No firma documentos electrónicamente</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    {{--@endif--}}
                                    <div id="otros_roles_documento_consolidado">
                                        @if(isset($configuracion->documento_solicitado_role_type) && $configuracion->documento_solicitado_role_type->count() > 0)
                                            @foreach($configuracion->documento_solicitado_role_type as $dsrt)
                                                <div class="form-row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="usuario">¿Quién ejecuta la tarea?</label>
                                                            <select id="usuario" name="usuario" class="form-control" required autocomplete="off">
                                                                @foreach($roleType as $rt)
                                                                    <option {{$rt->role_type_id == $dsrt->role_type_id ? "selected" : ""}}
                                                                            value="{{$rt->role_type_id}}">{{$rt->description}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="usuario">Correos</label>
                                                            <select id="enviar_correo" name="enviar_correo" class="form-control enviar_correo"
                                                                    onchange="habilitaCorreos()" autocomplete="off" required>
                                                                <option {{ isset($dsrt->correo) ? ($dsrt->correo == true ? 'selected' : '') : "" }} value="1">Envía documentos por correo electrónico</option>
                                                                <option {{ isset($dsrt->correo) ? ($dsrt->correo == false ? 'selected' : '') : ""  }} value="0">No envía documentos por correo electrónico</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="firma_electronica">Firma</label>
                                                            <select id="firma_electronica" name="firma_electronica" class="form-control" autocomplete="off" required>
                                                                <option {{isset($dsrt->firma) ? ($dsrt->firma   == true ? 'selected' : '') : "" }} value="1">Firma documentos electrónicamente</option>
                                                                <option {{isset($dsrt->firma) ? ($dsrt->firma   == false ? 'selected' : '') : ""  }} value="0">No firma documentos electrónicamente</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <hr />
                                    <div class="form-row" id="correos" style="margin: 20px 0px">
                                        <div class="col-md-6">
                                            <h5 class="card-title mb-0">
                                                <i class="fa fa-envelope-o" ></i> Agregar correos
                                            </h5>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button type="button" class="btn btn-primary btn-sm" title="Agregar otro correo"
                                                    onclick="addCorreoDocumentoConsolidado()">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" title="Eliminar correo"
                                                    onclick="deleteCorreoDocumentoConsolidado()">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-row" id="correos_anexos" style="margin: 20px 0px">
                                                @if(isset($configuracion->correo_documento_solicitado) && $configuracion->correo_documento_solicitado->count() > 0)
                                                    @foreach($configuracion->correo_documento_solicitado as $correo)
                                                        <div class="col-md-4" id="input_correo">
                                                            <div class="form-group" >
                                                                <input type="mail" id="correo" name="correo" placeholder="Correo" autocomplete="off"
                                                                       class="form-control" value="{{$correo->correo}}" required>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="col-md-4" id="input_correo">
                                                        <div class="form-group" >
                                                            <input type="mail" id="correo" name="correo"  placeholder="Correo"
                                                                   class="form-control" autocomplete="off" required>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary"
                                            onclick="store_documento_consolidado('{{$tratamiento->id_tratamiento}}')">
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
@endsection
@section('custom_page_js')
    @include('documentos_consolidados.script')
@endsection