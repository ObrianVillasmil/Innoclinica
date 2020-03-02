@extends('layouts.partials.dashboard')
@section('title')
    Empresa
@endsection

@section('contenido')
    <div class="container p-0">
        <div class="row">
            <div class="col-md-5 col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fa fa-cogs" aria-hidden="true"></i> Opciones
                        </h5>
                    </div>
                    <div class="list-group list-group-flush" role="tablist">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account" role="tab">
                            <i class="fa fa-building-o"></i> Datos generales de la empresa
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#representante" role="tab">
                            <i class="fa fa-user-circle"></i> Representante legal de la empresa
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#terminos_condiciones" role="tab">
                            <i class="fa fa-file-text-o" ></i> Terminos y condiciones
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#visualizacion" role="tab">
                            <i class="fa fa-eye"></i> Visualización
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#inventario" role="tab">
                            <i class="fa fa-bell"></i> Notifiaciones del inventario
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-xl-8">
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
                                <h5 class="card-title mb-0">Información general de la empresa</h5>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" id="form_datos_empresa">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nombre">Nombre</label>
                                                <input type="text" class="form-control" id="nombre" value="{{isset($empresa->nombre_empresa) ? $empresa->nombre_empresa : ""}}" name="nombre" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="moneda">Moneda</label>
                                                <select class="form-control" id="moneda" name="moneda">
                                                    <option {{isset($configuracionEmpresa->moneda) ? ($configuracionEmpresa->moneda == "dolar" ? "selected" :"") :"" }} value="dolar">Dolar</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="pais">País</label>
                                                <select class="form-control" id="pais" name="pais">
                                                    @foreach($paises as $p)
                                                        <option {{isset($configuracionEmpresa->pais) ? (($configuracionEmpresa->pais == $p->geo_id) ? "selected" :"") : "" }} value="{{$p->geo_id}}">{{$p->geo_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="ruc">Ruc</label>
                                                <input type="text" class="form-control" id="ruc" name="ruc" value="{{isset($configuracionEmpresa->ruc_empresa) ? $configuracionEmpresa->ruc_empresa : ""}}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="correo_empresa">Correo</label>
                                                <input type="email" class="form-control" id="correo_empresa" name="correo_empresa"
                                                       value="{{isset($configuracionEmpresa->correo_empresa) ? $configuracionEmpresa->correo_empresa : ""}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="direccion">Dirección</label>
                                                <input type="text" class="form-control" id="direccion" name="direccion" value="{{isset($configuracionEmpresa->direccion_empresa) ? $configuracionEmpresa->direccion_empresa : ""}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary" onclick="storeDatosEmpresa()">
                                            <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="representante" role="tabpanel">
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
                                <h5 class="card-title mb-0">Información general del representante legal de la empresa</h5>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" id="form_datos_representante_general">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nombre_representante">Nombres</label>
                                                <input type="text" class="form-control" id="nombre_representante" value="{{isset($empresa->nombre_representante) ? $empresa->nombre_representante : ""}}" name="nombre_representante" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="apellido_representante">Apellidos</label>
                                                <input type="text" class="form-control" id="apellido_representante" value="{{isset($empresa->apellidos_representante) ? $empresa->apellidos_representante : ""}}" name="apellido_representante" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="identificacion_representante">Identificación</label>
                                                <input type="text" class="form-control" id="identificacion_representante" value="{{isset($empresa->identificacion_representante) ? $empresa->identificacion_representante : ""}}" name="identificacion_representante" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="telefono">Teléfono</label>
                                                <input type="text" class="form-control" id="telefono" name="telefono" value="{{isset($empresa->telefono_representante) ? $empresa->telefono_representante : ""}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="correo_representante">Correo</label>
                                                <input type="emai" class="form-control" id="correo_representante" name="correo_representante" value="{{isset($empresa->correo_representante) ? $empresa->correo_representante : ""}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary" onclick="storeDatosRepresentante()">
                                            <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="terminos_condiciones" role="tabpanel">
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
                                <h5 class="card-title mb-0">Terminos y condiciones de la empresa</h5>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" id="form_terminos_condiciones">
                                    <div class="form-row">
                                        <div class="col-md-12">
                                          <textarea id="editor" name="editor">{{getConfiguracionEmpresa()->terminos_condiciones}}</textarea>
                                        </div>
                                    </div>
                                    <div class="text-center" style="margin-top: 20px">
                                        <button type="button" class="btn btn-primary" onclick="storeTerminosCondiciones()">
                                            <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="visualizacion" role="tabpanel">
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
                                <h5 class="card-title mb-0">Visualización</h5>
                            </div>
                            <div class="card-body">
                                <form id="form_visualizacion" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div id="img_login">

                                                @isset($configuracionEmpresa->img_fondo_login)
                                                    <img src="storage/{{$configuracionEmpresa->img_fondo_login}}" style="width: 330px; height: 150px">
                                                @endisset
                                            </div>
                                            <div class="form-group">
                                                <label for="imagen_login">Fondo login 1400px X 900px</label>
                                                <input type="file" class="form-control" id="imagen_login" name="imagen_login" >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div id="img_empresa" class="text-center" style="">
                                                @isset($configuracionEmpresa->logo_empresa)
                                                    <img src="storage/{{$configuracionEmpresa->logo_empresa}}" style="width: 250px; height: 150px">
                                                @endisset
                                            </div>
                                            <div class="form-group">
                                                <label for="logo_empresa">Logo empresa 50px X 50px</label>
                                                <input type="file" class="form-control" id="logo_empresa" name="logo_empresa" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                    <button type="button" class="btn btn-primary" onclick="storeVisualizacion()">
                                        <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                                    </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="inventario" role="tabpanel">
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
                                <h5 class="card-title mb-0">Variables del inventario</h5>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" id="from_configuracion_inventario">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="intervalo">Intervalo</label>
                                                <select id="intervalo_inventario" name="intervalo_inventario" class="form-control" required>
                                                    <option {{$configuracionEmpresa->intervalo_inventario == "D" ? "selected" : ""}} value="D">Dias</option>
                                                    <option {{$configuracionEmpresa->intervalo_inventario == "S" ? "selected" : ""}} value="S">Semanas</option>
                                                    <option {{$configuracionEmpresa->intervalo_inventario == "M" ? "selected" : ""}} value="M">Meses</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cantidad">Cantidad del intervalo</label>
                                                <input type="number" min="1" class="form-control text-center" id="cantidad"
                                                       value="{{$configuracionEmpresa->cantidad_intervalo}}" name="cantidad" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="correo_1">Correo 1 <sup style="color: red">*</sup></label>
                                                <input type="text" id="correo_1" class="form-control" name="correo_1"
                                                       value="{{isset($empresa->correo1_notificacion_intervalo) ? $empresa->correo1_notificacion_intervalo : ""}}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="correo_2">Correo 2</label>
                                                 <input type="text" id="correo_2" class="form-control" name="correo_2"
                                                        value="{{isset($empresa->correo2_notificacion_intervalo) ? $empresa->correo2_notificacion_intervalo : ""}}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="correo_3">Correo 3</label>
                                                <input type="text" id="correo_3" class="form-control" name="correo_3"
                                                       value="{{isset($empresa->correo3_notificacion_intervalo) ? $empresa->correo3_notificacion_intervalo : ""}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary" onclick="store_configuracion_inventario()">
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
    @include('empresa.script')
@endsection