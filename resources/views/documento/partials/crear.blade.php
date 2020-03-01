@extends('layouts.partials.dashboard')
@section('title')
    Documentos
@endsection

@section('contenido')
    <div class="container p-0">
        <div class="row">
            <div class="col-md-4 col-xl-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fa fa-cogs" aria-hidden="true"></i> Opciones
                        </h5>
                    </div>
                    <div class="list-group list-group-flush" role="tablist">
                            <a class="list-group-item list-group-item-action {{isset($active) ? ($active=="1" ? "active" : "") : "active"}}" data-toggle="list" {{isset($active) ? ($active=="1" ? "href=#crear_documento" : "") : "href=#crear_documento"}} role="tab">
                            <i class="fa fa-pencil-square-o"></i> Crear documento
                        </a>
                        <a class="list-group-item list-group-item-action {{isset($active) ? ($active=="1" ? "" : "active") : ""}}" data-toggle="list" {{isset($active) ? ($active=="1" ? "" : "href=#subir_documento") : "href=#subir_documento"}}  role="tab">
                            <i class="fa fa-file-pdf-o"></i> Subir documento
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-xl-9">
                <div class="tab-content">
                    <div class="tab-pane fade show {{isset($active) ? ($active=="1" ? "active" : "") : "active"}}" id="crear_documento" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-actions float-right">
                                    <div class="dropdown show">
                                        {{--<a href="#" data-toggle="dropdown" data-display="static">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle">
                                                <circle cx="12" cy="12" r="1"></circle>
                                                <circle cx="19" cy="12" r="1"></circle>
                                                <circle cx="5" cy="12" r="1"></circle>
                                            </svg>
                                        </a>--}}
                                        <button class="btn-icono btn btn-outline-info btn-sm"
                                                title="Seleccionar un ícono para el proceso" onclick="icono_proceso()">
                                            <i class="{{(isset($documento) && isset($documento->icono) != "") ? $documento->icono : "fa fa-eye"}}"></i>
                                        </button>
                                        <input type="hidden" id="icono_proceso" name="icono_proceso" value="{{isset($documento) ? $documento->icono : "fa fa-eye"}}">
                                    </div>
                                </div>
                                <h5 class="card-title mb-0">Creación del documento</h5>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" id="form_crear_documento">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nombre">Nombre del documento</label>
                                                <input type="text" class="form-control" id="nombre" value="{{isset($documento) ? $documento->nombre : ""}}" name="nombre" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="usuario">¿Quién ejecuta el proceso?</label>
                                                <select id="usuario" name="usuario" class="form-control" required>
                                                    <option selected disabled> Seleccione </option>
                                                    @foreach($roleType as $rt)
                                                        <option {{isset($documento) ? ($rt->role_type_id == $documento->role_type_id ? "selected" : "") : ""}}
                                                                value="{{$rt->role_type_id}}">{{$rt->description}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="descripcion">Descripción</label>
                                                <input type="text" class="form-control" id="descripcion"  name="descripcion" value="{{isset($documento) ? $documento->descripcion : ""}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="formato">Tags para agregar información personalizada:</label>
                                            <ul style="font-size: 14px;">
                                                <li><b>Datos Empresa:</b>  [NOMBRE_EMPRESA], [PAIS_EMPRESA], [ID_EMPRESA], [DIREC_EMPRESA]</li>
                                                <li><b>Datos Representante de la empresa:</b> [NOMBRE_REP_EMPRESA], [ID_REP_EMPRESA], [TLF_REP_EMPRESA] , [CORREO_REP_EMPRESA]</li>
                                                <li><b>Datos de fehca:</b>  [DIA], [MES], [ANNO]</li>
                                                <li><b>Salto de página:</b>  [SALTO_DE_PAGINA]</li>
                                                <li><b>Datos del usuario:</b>  [NOMBRE_USUARIO], [APELLIDO_USUARIO], [ID_USUARIO], [DIREC_USUARIO] </li>
                                            </ul>
                                            <textarea name="editor" id="editor" rows="90" cols="180">{{isset($documento) ? $documento->cuerpo : ""}}</textarea>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary" onclick="store_documento('{{isset($documento) ? $documento->id_documento : null}}')">
                                            <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show {{isset($active) ? ($active=="1" ? "" : "active") : ""}}" id="subir_documento" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-actions float-right">
                                    <div class="dropdown show">
                                        {{--<a href="#" data-toggle="dropdown" data-display="static">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle">
                                                <circle cx="12" cy="12" r="1"></circle>
                                                <circle cx="19" cy="12" r="1"></circle>
                                                <circle cx="5" cy="12" r="1"></circle>
                                            </svg>
                                        </a>--}}
                                        <button class="btn-icono btn btn-outline-info btn-sm"
                                                title="Seleccionar un ícono para el proceso" onclick="icono_proceso()">
                                            <i class="{{(isset($documento) && isset($documento->icono) != "") ? $documento->icono : "fa fa-eye"}}"></i>
                                        </button>
                                        <input type="hidden" id="icono_proceso" name="icono_proceso" value="{{isset($documento) ? $documento->icono : "fa fa-eye"}}">
                                    </div>
                                </div>
                                <h5 class="card-title mb-0">Cargar un documento</h5>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" id="form_cargar_documento">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nombre">Nombre del documento</label>
                                                <input type="text" class="form-control"  id="nombre_documento" name="nombre_documento" value="{{isset($documento) ? $documento->nombre : ""}}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="usuario">¿Quién ejecuta el proceso?</label>
                                                <select id="usuario" name="usuario" class="form-control" required>
                                                    <option selected disabled value="">Seleccione</option>
                                                    @foreach($roleType as $rt)
                                                        <option {{isset($documento) ? ($rt->role_type_id == $documento->role_type_id ? "selected" : "") : ""}}
                                                                value="{{$rt->role_type_id}}">{{$rt->description}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="descripcion">Descripción</label>
                                                <input type="text" class="form-control" id="descripcion_documento" name="descripcion_documento" value="{{isset($documento) ? $documento->descripcion : ""}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="documento">Documento</label>
                                            <input type="file" id="documento" accept="application/pdf" name="documento" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            {!! isset($documento) ? "<i class='fa fa-file-pdf-o'></i> Archivo actual: <a target='_blank' href='".url('/storage/archivos/documentos/').'/'.$documento->archivo."'>".$documento->archivo."</a>" : ""!!}
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary" onclick="uploadDocumento('{{isset($documento->id_documento) ? $documento->id_documento : "" }}')">
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
    @include('documento.script')
@endsection