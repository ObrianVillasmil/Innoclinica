@extends('layouts.partials.dashboard')
@section('title')
    Tratamiento
@endsection

@section('contenido')
    <div class="container p-0">
        <div class="row">
            <div class="container p-0">
                <div class="row">
                    <div class="col-md-12 col-xl-12">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tratamiento" role="tabpanel">
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
                                                    <i class="{{(isset($dataTratamiento->icono) && $dataTratamiento->icono!="" ) ? $dataTratamiento->icono : "fa fa-eye"}}"></i>
                                                </button>
                                                <input type="hidden" id="icono_proceso" name="icono_proceso" value="{{isset($dataTratamiento->icono) ? $dataTratamiento->icono : "fa fa-eye"}}">
                                            </div>
                                        </div>
                                        <h5 class="card-title mb-0">{{isset($dataTratamiento) ? 'Editar' : 'Crear'}} tratamiento</h5>
                                    </div>
                                    <div class="card-body">
                                        <form class="form-horizontal" id="form_tratamiento">
                                            <div class="form-row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="nombre">Nombre</label>
                                                        <input type="text" class="form-control" maxlength="100" id="nombre" value="{{isset($dataTratamiento->nombre_tratamiento) ? $dataTratamiento->nombre_tratamiento : ""}}" name="nombre" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="especialidad">Especialidad</label>
                                                        <select id="especialidad" name="especialidad" class="form-control" required>
                                                            <option selected disabled>Seleccione</option>
                                                            @foreach($especialidades as $e)
                                                                <option {{isset($dataTratamiento->id_especialidad) ? ($dataTratamiento->id_especialidad === $e->id_especialidad) ? "selected" : "" : ""}} value="{{$e->id_especialidad}}">{{$e->nombre}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="imagen">Imagen del tratamiento (250px por 300px)</label>
                                                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <button type="button" class="btn btn-primary" onclick="storeTratamiento('{{isset($dataTratamiento->id_tratamiento) ? $dataTratamiento->id_tratamiento : null}}')">
                                                    <i class="fa fa-floppy-o"></i> Guardar
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
        </div>
    </div>
@endsection

@section('custom_page_js')
    @include('tratamiento.script')
@endsection