@extends('layouts.partials.dashboard')
@section('title')
    Procesos tratamiento
@endsection
@php
    $solicitante = getParty($partyIdSolicitante);
    $tratamientoSolicitado = getTratamientoSolicitado($idTratamiento,$partyIdSolicitante)->id_tratamiento_solicitado;
    $doctor = $solicitante->tratamiento_solicitado() != null ? getParty($solicitante->tratamiento_solicitado()->id_doctor) : null;
    $distribucion = getDetalleTratamientoDoctorByIdTratamientoSolicitado($tratamientoSolicitado);

    $distribucionRegistrada = true;

    if(!isset($distribucion)){
        $distribucion = getDetalleTratamiento($idTratamiento);
        $distribucionRegistrada=false;
    }

    $documentosConsolidados = getDocumentoConsolidadoByIdTratamiento($idTratamiento);

    $cie10Tratamiento = getCie10Tratamiento($idTratamiento);
    $datosFase = $distribucion;

    $arrData =[];

    foreach ($distribucion->distribucion_tratamiento as $dist) {
        $detalles = [];
        foreach ($dist->detalle_distribucion_tratamiento as $detalle_distribucion_tratamiento)
            $detalles[$detalle_distribucion_tratamiento->id_distribucion_tratamiento][$detalle_distribucion_tratamiento->product_id][] = $detalle_distribucion_tratamiento;

        $arrData[] = array_values($detalles);

    }

    $filas = count($arrData[0][0]);
    $columnaAplicacion=[];

    foreach ($arrData as $data)
        $columnaAplicacion[] = count(array_values($data[0])[0]);

    $prodcuto = [];
    foreach ($arrData[0][0] as $produc_id => $arrDatum)
        $prodcuto[] = $produc_id;

    $dataAplicacionMedicacion = [];
    foreach($distribucion->distribucion_tratamiento as $x => $fase)
        foreach($prodcuto as $y => $prdocut)
            for($z=0;$z<$columnaAplicacion[$x];$z++)
                $dataAplicacionMedicacion[$x][$arrData[$x][0][$prodcuto[$y]][$z]->fecha_aplicacion][] = [
                    'producto' => getProducto($prodcuto[$y])->product_name,
                    'cantidad' => $arrData[$x][0][$prodcuto[$y]][$z]->cantidad_aplicacion,
                    'cumplido' => $arrData[$x][0][$prodcuto[$y]][$z]->cumplido,
                    'id_detalle_distribucion_tratamiento_doctor' =>$arrData[$x][0][$prodcuto[$y]][$z]->id_detalle_distribucion_tratamiento_doctor,
                    'sitio_aplicacion' =>$arrData[$x][0][$prodcuto[$y]][$z]->sitio_aplicacion,
                    'fecha_aplicacion_real' =>$arrData[$x][0][$prodcuto[$y]][$z]->fecha_aplicacion_real,
                    'comentarios' =>$arrData[$x][0][$prodcuto[$y]][$z]->comentarios,
                ];


    $estadisticaUso =[];
    foreach($arrData as $dat)
        foreach ($dat as $product)
            foreach ($product as $item)
                foreach ($item as $p => $it)
                    $estadisticaUso[$it->product_id][] = $it;

@endphp
@section('contenido')
    <section id="tabs">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 ">
                    <nav style="background: #f1f1f1">
                        <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#distribucion_tratamiento" role="tab" aria-controls="distribucion_tratamiento" aria-selected="true">
                                <i class="fa fa-user-md"></i> Distribución del tratamiento
                            </a>
                            {{--@if(in_array($usuario->party_role->role_type->role_type_id,$roles))--}}
                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#documentos_tratamiento" role="tab" aria-controls="documentos_tratamiento" aria-selected="false">
                                    <i class="fa fa-files-o"></i> Documentos del tratamiento
                                </a>
                            {{--@endif--}}
                            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#aplicacion_medicacion" role="tab" aria-controls="aplicacion_medicacion" aria-selected="false">
                                <i class="fa fa-medkit"></i> Aplicación de la medicación
                            </a>
                            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#distribucion_medicacion" role="tab" aria-controls="distribucion_medicacion" aria-selected="false">
                                <i class="fa fa-cubes"></i> Uso de la medicación
                            </a>
                        </div>
                    </nav>
                    <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="distribucion_tratamiento" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div style="width: 80%;margin: 20px auto;">
                                <table style="width: 100%;">
                                    <tr>
                                        <td class="text-center" style="border: 1px solid black">
                                            <img src="/stoage/{{getConfiguracionEmpresa()->logo_empresa}}" style="width: 60px;">
                                        </td>
                                        <td class="text-center">
                                            <table style="width: 100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td class="text-center" style="border: 1px solid black;vertical-align: middle"><h3>TRATAMIENTOS ESPECIALIZADOS</h3></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center" style="border: 1px solid black;vertical-align: middle"><h4>Informe del Estado Clínico del Paciente</h4></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="text-center" style="border: 1px solid black"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" style="border: 1px solid black">Fecha de elaboración<br /> {{now()->toDateString()}}</td>
                                        <td class="text-center" style="border: 1px solid black">INNOCLINICA CIA. LTDA <br /> {{isset($doctor) ? "Dr. ".$doctor->person->first_name." ".$doctor->person->last_name : "Médico Tratante"}}</td>
                                        <td class="text-center" style="border: 1px solid black">Página 1 de 1</td>
                                    </tr>
                                </table>
                            </div>
                            <table style="width: 100%;margin-top: 20px;">
                                <tr><td colspan="4" style="border: 1px solid black" class="text-center">DATOS INFORMATIVOS DEL PACIENTE</td></tr>
                                <tr>
                                    <td style="border: 1px solid black;padding-left: 5px"><b>APELLIDOS:</b> {{$solicitante->person->last_name}}</td>
                                    <td style="border: 1px solid black;padding-left: 5px"><b>NOMBRES:</b> {{$solicitante->person->first_name}}</td>
                                    <td style="border: 1px solid black;padding-left: 5px"><b>{{$solicitante->identification->tipo_identificacion->description}}:</b> {{$solicitante->identification->id_value}} </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr><td colspan="3" style="border: 1px solid black" class="text-center">CLASIFICACIÓN DE ENFERMEDADES CIE-10 (SELECCIONAR)</td></tr>
                                <tr>
                                    <td style="background: #f8f9fa;border: 1px solid black; " colspan="3" >
                                        <ul class="list-unstyled" style="padding-left: 40px">
                                            @foreach($cie10Tratamiento as $x => $cie10)
                                                <li>
                                                    <input type="radio" id="cie10_{{$x+1}}"
                                                           {{(isset($distribucion->id_cie10) && $distribucion->id_cie10 == $cie10->id_cie10) ? "checked" : ""}}
                                                           name="cie10" class="cie10" value="{{$cie10->id_cie10}}">
                                                    <label for="cei10_{{$x+1}}">{{$cie10->cie10->descripcion}}</label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="border: 1px solid black" class="text-center">DESCRIPIÓN PATOLÓGICA</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="border: 1px solid black;padding: 10px" class="text-center">
                                        <textarea name="descripcion_patologica" cols="150" rows="6" id="descripcion_patologica" class="text-center">
                                            {{isset($distribucion->descripcion_patologica) ? trim($distribucion->descripcion_patologica) : "" }}
                                        </textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="border: 1px solid black" class="text-center">JUSTIFICACIÓN DE USO DEL PRODUCTO A IMPORTAR</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="border: 1px solid black;padding-left: 5px" class="text-center">
                                        CÓDIGO DE APROBACIÓN DE IMPORTACIÓN:
                                        <input type="text" id="codigo_importacion" value="{{isset($distribucion->codigo_importacion) ? $distribucion->codigo_importacion : ""}}" name="codigo_importacion" style="text-align: center;">
                                        <button class="btn btn-success btn-sm" onclick="storeCodigoImportacion('{{$solicitante->tratamiento_solicitado() != null ? $solicitante->tratamiento_solicitado()->id_tratamiento_solicitado : ""}}')"><i class="fa fa-floppy-o"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="border: 1px solid black;">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div class="input-group">
                                                    <form id="form_nueva_fase" name="form_nueva_fase">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-prepend"><span class="input-group-text">Nueva fase</span></div>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <select id="intevalo" name="intevalo" required title="Selecciona el intervalo de tiempo de la aplicación"
                                                                            style="border: none;width: 85px;height: 26px;text-align: center">
                                                                        <option value="" disabled selected>Intervalo</option>
                                                                        <option value="1">Días</option>
                                                                        <option value="2">Semana</option>
                                                                        <option value="3">Mes</option>
                                                                    </select>
                                                                </span>
                                                            </div>
                                                            <input title="Escriba la cantidad del tiempo del intervalo seleccionado" placeholder="Tiempo"
                                                                   type="number" class="form-control cantidad_intervalo" id="cantidad_intervalo"
                                                                   value="" name="cantidad_intervalo" min="1" style="text-align: center;width: 100px;" required>
                                                            <input title="Escriba la cantidad de las aplicaciones del producto para esta fase" placeholder="Aplicaciones"
                                                                   type="number" class="form-control cantidad_aplicacion" id="cantidad_aplicacion"
                                                                   value="" name="cantidad_aplicacion" min="1" style="text-align: center;width: 115px;" required>
                                                            <div class="input-group-prepend">
                                                                <button type="button" class="btn btn-primary" title="Aumentar fase" onclick="aumentar_face('{{$partyIdSolicitante}}','{{$idTratamiento}}')">
                                                                    <i class="fa fa-plus-circle"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-calendar"></i> Inicio del tratamiento
                                                            </span>
                                                        </div>
                                                        <input type="date" class="form-control" id="inicio_tratamiento" value="{{$solicitante->tratamiento_solicitado() != null ? $solicitante->tratamiento_solicitado()->fecha_inicio : ""}}"
                                                               name="inicio_tratamiento" min="1" style="text-align: center;" required>
                                                    </div>
                                                    <div class="input-group-prepend">
                                                        <button type="button" class="btn btn-primary" title="Fecha de inicio del tratamiento" onclick="inicioTratamiento('{{$solicitante->tratamiento_solicitado() != null ? $solicitante->tratamiento_solicitado()->id_tratamiento_solicitado : ""}}')">
                                                            <i class="fa fa-floppy-o"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr >
                                    <td colspan="3" style="border: 1px solid black" class="text-center">
                                        <table style="width:100%;overflow:auto" >
                                            <thead >
                                                <tr id="tr_cabecera">
                                                    <th style="border: 1px solid black;width: 80px;text-align: center">Estadio de tratamiento</th>
                                                    @foreach($distribucion->distribucion_tratamiento as $x => $fase)
                                                        <th style="border: 1px solid black" class="text-center fase_tratamiento fase_tratamiento_{{$x+1}}" colspan="{{$columnaAplicacion[$x]}}">
                                                            {{(isset($fase['intervalo']) && isset($fase['cantidad_intervalo'])) ? getIntervalo($fase['intervalo'])." ".$fase['cantidad_intervalo'] : "Tratamiento inicial"}}
                                                            <input type="hidden" id="intervalo_{{$x+1}}" name="intervalo_{{$x+1}}" value="{{$fase['intervalo']}}">
                                                            <input type="hidden" id="cantidad_intervalo_{{$x+1}}" name="cantidad_intervalo_{{$x+1}}" value="{{$fase['cantidad_intervalo']}}">
                                                            <input type="hidden" id="cantidad_aplicacion_{{$x+1}}" name="cantidad_aplicacion_{{$x+1}}" value="{{$columnaAplicacion[$x]}}">
                                                            {{--@if($fase->nuevo)
                                                                <button class="btn btn-danger btn-sm" title="Eliminar fase" onclick="deleteTratamiento('{{$fase->id_distribucion_tratamiento_doctor}}')">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                                </button>
                                                            @endif--}}
                                                        </th>
                                                    @endforeach
                                                    <th style="border: 1px solid black;width: 80px;text-align: center" ></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            {{--<tr>
                                                <td style="border: 1px solid black;text-align: center">Intervalo</td>
                                                @foreach($distribucion->distribucion_tratamiento as $x => $fase)
                                                    @for($y=0;$y<$columnaAplicacion[$x];$y++)
                                                        <td style="border: 1px solid black;text-align: center">
                                                            //AQUÍ
                                                            <input id="check_intervalo_{{$x+1}}_{{$y+1}}" name="check_intervalo_{{$x+1}}_{{$y+1}}" {{$fase->detalle_distribucion_tratamiento[$x]->cumplido ? "checked" : ""}}
                                                            type="checkbox" class="form-control-custom">
                                                            <label style="position: relative;bottom: 12px;" for="check_intervalo_{{$x+1}}_{{$y+1}}"></label>
                                                        </td>
                                                    @endfor
                                                @endforeach
                                                <td style="border: 1px solid black;width: 80px;text-align: center" rowspan="2"></td>
                                            </tr>--}}
                                            <tr>
                                                <td style="border: 1px solid black;text-align: center">Aplicación</td>
                                                @foreach($distribucion->distribucion_tratamiento as $x => $fase)
                                                    @for($y=0;$y<$columnaAplicacion[$x];$y++)
                                                        <td style="border: 1px solid black;text-align: center" class="sem_{{$x+1}}">
                                                            <select id="intevalo_aplicacion_{{$x+1}}_{{$y+1}}" name="intevalo_aplicacion_{{$x+1}}_{{$y+1}}" required=""
                                                                    class="intevalo_aplicacion_{{$x+1}}" title="Selecciona el intervalo de tiempo de la aplicación" style="border: none;">
                                                                <option value="1" {{$fase->detalle_distribucion_tratamiento[$y]->intervalo == 1 ? "selected" : ""}}>D</option>
                                                                <option value="2" {{$fase->detalle_distribucion_tratamiento[$y]->intervalo == 2 ? "selected" : ""}}>S</option>
                                                                <option value="3" {{$fase->detalle_distribucion_tratamiento[$y]->intervalo == 3 ? "selected" : ""}}>M</option>
                                                            </select>
                                                            <input type="number" id="cantidad_intervalo_{{$x+1}}_{{$y+1}}" name="cantidad_intervalo_{{$x+1}}_{{$y+1}}"
                                                                   value="{{$fase->detalle_distribucion_tratamiento[$y]->cantidad_intervalo}}"
                                                                   style="border: none;width: 35px;text-align: center;background: transparent;">
                                                        </td>
                                                    @endfor
                                                @endforeach
                                                <td style="border: 1px solid black;text-align: center;font-size: 12px"></td>
                                            </tr>
                                            @for($i=0;$i<count($prodcuto);$i++)
                                                <tr>
                                                    <td style="border: 1px solid black;text-align: center;font-size: 11px">
                                                        {{getProducto($prodcuto[$i])->product_name}}
                                                        <input type="hidden" id="producto_{{$i+1}}" name="producto_{{$i+1}}" class="producto" value="{{$prodcuto[$i]}}">
                                                    </td>
                                                    @foreach($distribucion->distribucion_tratamiento as $x => $fase)
                                                        @for($y=0;$y<$columnaAplicacion[$x];$y++)
                                                            <td style="border: 1px solid black">
                                                                <input type="number" id="cantidad_producto_{{$x+1}}_{{$i+1}}_{{$y+1}}" name="cantidad_producto_{{$x+1}}_{{$i+1}}_{{$y+1}}" min="1" class="producto_{{$x+1}}"
                                                                       onchange="calcular_producto(this)" required
                                                                       value="{{isset($prodcuto[$i]) ? $arrData[$x][0][$prodcuto[$i]][$y]->cantidad_aplicacion : 0}}"
                                                                       style="border:none;width: 100%;text-align: center;background: transparent">
                                                            </td>
                                                        @endfor
                                                    @endforeach
                                                    @if($i==0)
                                                        <td rowspan="3" style="border:1px solid black;vertical-align: middle;text-align: center"><b>Total</b></td>
                                                    @endif
                                                </tr>
                                            @endfor
                                            <tr>
                                                <td style="border: 1px solid black;text-align: center" >Dosis en cada fase</td>
                                                @foreach($distribucion->distribucion_tratamiento as $x => $fase)
                                                    <td style="border: 1px solid black;text-align: center;" class="div_dosis div_dosis_{{$x+1}}" colspan="{{$columnaAplicacion[$x]}}">0</td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black;text-align: center">Dosis clinica cálculada para importación</td>
                                                @php $cols = 0; @endphp
                                                @foreach($distribucion->distribucion_tratamiento as $x => $fase)
                                                    <td style="border: 1px solid black;text-align: center" class="div_dosis_{{$x+1}}" colspan="{{$columnaAplicacion[$x]}}">0</td>
                                                    @php
                                                        $cols+=$columnaAplicacion[$x];
                                                    @endphp
                                                @endforeach
                                                <td style="border:1px solid black;text-align: center" class="total_dosis_fase">0</td>

                                            </tr>
                                            <tr>
                                                <td colspan="{{$cols+2}}" style="border: 1px solid black;text-align: center">JUSTIFICACIONES MÉDICA ADICIONALES</td>
                                            </tr>
                                            <tr>
                                                <td colspan="{{$cols+2}}" style="border: 1px solid black;padding: 10px;text-align: center">
                                                    <textarea name="justificacion" cols="150" rows="6" id="justificacion" class="text-center" style="width: 100%">
                                                        {{isset($distribucion->justificacion_medica) ? $distribucion->justificacion_medica : "" }}
                                                    </textarea>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-center" style="border: none;padding: 20px 0px">
                                        <button type="button" class="btn btn-success" onclick="store_distribucion_tratamiento_seguimiento('{{$partyIdSolicitante}}','{{$idTratamiento}}')">
                                            <i class="fa fa-floppy-o"></i> Guardar
                                        </button>
                                    </td>
                                </tr>
                            </table>
					    </div>
                            <div class="tab-pane fade" id="documentos_tratamiento" role="tabpanel" aria-labelledby="nav-profile-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="sesion_usuario" role="tabpanel">
                                                @if(in_array($usuario->party_role->role_type->role_type_id,$roles))
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="card-actions float-right">
                                                            <div class="dropdown show">
                                                                {{--<button class="btn btn-sm btn-primary" onclick="agregarArchivoTratamiento('{{$solicitante->tratamiento_solicitado()->id_tratamiento_solicitado}}')" title="Cargar un documento al tratamiento solicitado">
                                                                               <i class="fa fa-file-text-o"></i>
                                                                           </button>--}}
                                                            </div>
                                                        </div>
                                                        <h5 class="card-title mb-0">
                                                            Documentos del tratamiento {{$solicitante->tratamiento_solicitado()->tratamiento->nombre_tratamiento}}
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="documentos_tratamiento_solicitado">
                                                            <p class="text-center">
                                                                <button class="btn btn-primary" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="true" aria-controls="multiCollapseExample1">
                                                                    <i class="fa fa-user-o"></i> Cargados por el cliente
                                                                </button>
                                                                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="true" aria-controls="multiCollapseExample2">
                                                                    <i class="fa fa-user-circle-o"></i> Cargados por el administrador
                                                                </button>
                                                                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample3" aria-expanded="true" aria-controls="multiCollapseExample3">
                                                                    <i class="fa fa-file-text-o"></i> Distribución
                                                                </button>
                                                                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample4" aria-expanded="true" aria-controls="multiCollapseExample4">
                                                                    <i class="fa fa-files-o"></i> Otros documentos
                                                                </button>
                                                            </p>
                                                            <div class="row">
                                                                <div class="col">
                                                                    <div class="collapse multi-collapse show" id="multiCollapseExample1">
                                                                        <div class="card card-body" style="height: 242px;overflow-y: auto">
                                                                            <div class="row text-center">
                                                                                <div class="text-center" style="width: 100%"><b>Cargados por el cliente</b></div>
                                                                                @if($solicitante->tratamiento_solicitado() != null)
                                                                                    @foreach($solicitante->tratamiento_solicitado()->carga_archivo_cliente as $w => $cargaArchivoCliente)
                                                                                        <div class="col-md-6 text-center p-2">
                                                                                            <input id="check_doc_cli{{$w+1}}" name="check_doc_cli{{$w+1}}" type="checkbox" style="cursor:pointer"
                                                                                                   value="{{$cargaArchivoCliente->id_carga_archivo_cliente}}" title="Enviar" class="form-control-custom" checked>
                                                                                            <label for="check_doc_cli{{$w+1}}"><b>Adjuntar</b></label>
                                                                                            <a target="_blank" href="{{'/storage/archivos/'.$cargaArchivoCliente->carpeta."/".$cargaArchivoCliente->archivo}}"
                                                                                               title="{{$cargaArchivoCliente->archivo}}" >
                                                                                                <i class="fa fa-3x fa-file-pdf-o text-danger"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    @endforeach
                                                                                @else
                                                                                    No se han cargado archivos
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col">
                                                                    <div class="collapse multi-collapse show" id="multiCollapseExample2">
                                                                        <div class="card card-body" style="height: 242px;overflow-y: auto">
                                                                            <div class="row text-center">
                                                                                <div class="text-center" style="width: 100%"><b>Cargados por el administrador</b></div>
                                                                                @if($documentoProcesoTratamiento->count() > 0)
                                                                                    @foreach($documentoProcesoTratamiento as $x => $documentoProceso)
                                                                                        @php $documento = getDocumento($documentoProceso->id_proceso) @endphp
                                                                                        <div class="col-md-6 text-center p-2">
                                                                                            <input id="check_doc_admin_{{$x+1}}" name="check_doc_admin_{{$x+1}}" type="checkbox" style="cursor:pointer"
                                                                                                   value="{{$documento->id_documento}}" title="Enviar" class="form-control-custom" checked>
                                                                                            <label for="check_doc_admin_{{$x+1}}"><b>Adjuntar</b></label>
                                                                                            @if($documento->archivo != "")
                                                                                                <a target="_blank" href="{{'/storage/archivos/documentos/'.$documento->archivo}}"
                                                                                                   title="{{$documento->nombre}}" >
                                                                                                    <i class="fa fa-3x fa-file-pdf-o text-danger"></i>
                                                                                                </a>
                                                                                            @else
                                                                                                <a target="_blank" href="{{url('alerta/documento_administrador',$documento->id_documento)}}"
                                                                                                   title="{{$documento->nombre}}" >
                                                                                                    <i class="fa fa-3x fa-file-pdf-o text-danger"></i>
                                                                                                </a>
                                                                                            @endif
                                                                                        </div>
                                                                                    @endforeach
                                                                                          @else
                                                                                              No se han cargado archivos
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col">
                                                                    <div class="collapse multi-collapse show" id="multiCollapseExample3">
                                                                        <div class="card card-body" style="height: 242px;overflow-y: auto">
                                                                            <div class="row text-center">
                                                                                <div class="text-center" style="width: 100%"><b>Distribución del tratamiento</b></div>
                                                                                <div class="col-md-12 text-center p-2">
                                                                                    <a target="_blank" title="Distribución del tratamiento"
                                                                                       href="{{url('seguimiento/distribucion_tratamiento_solicitado',[$solicitante->tratamiento_solicitado()->id_tratamiento,$solicitante->tratamiento_solicitado()->party_id,$solicitante->tratamiento_solicitado()->id_doctor])}}">
                                                                                        <i class="fa fa-3x fa-file-pdf-o text-danger"></i>
                                                                                    </a>
                                                                                    <div class="text-center">
                                                                                    @foreach ($documentoConsolidado->documento_solicitado_role_type as $x => $role_type)
                                                                                        @if(($role_type->role_type_id == $usuario->party_role->role_type->role_type_id) &&  $role_type->firma)
                                                                                            <button class="btn btn-primary" style="margin-top: 10px" onclick="firmar_pdf('{{$solicitante->tratamiento_solicitado()->id_tratamiento}}','{{$solicitante->tratamiento_solicitado()->party_id}}','{{$solicitante->tratamiento_solicitado()->id_doctor}}')">
                                                                                                <i class="fa fa-pencil"></i> Firma electrónica
                                                                                            </button>
                                                                                        @endif
                                                                                    @endforeach
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @if(isset($solicitante->tratamiento_solicitado()->pdf_firmado) && $solicitante->tratamiento_solicitado()->pdf_firmado!="")
                                                                                <div class="row text-center">
                                                                                    <div class="text-center" style="width: 100%"><b>Documento firmado</b></div>
                                                                                    <div style="width: 100%;cursor:pointer">
                                                                                        <input id="check_dist" name="check_dist" type="checkbox" checked title="Enviar"
                                                                                               value="{{$solicitante->tratamiento_solicitado()->pdf_firmado}}" class="form-control-custom">
                                                                                        <label for="check_dist"><b>Adjuntar</b></label>
                                                                                    </div>
                                                                                    <div class="col-md-12 text-center p-2">
                                                                                        <a target="_blank" title="Distribución del tratamiento"
                                                                                           href="{{url("/storage/archivos/documentos/".$solicitante->tratamiento_solicitado()->pdf_firmado)}}">
                                                                                            <i class="fa fa-3x fa-file-pdf-o text-danger"></i>
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col">
                                                                    <div class="collapse multi-collapse show" id="multiCollapseExample4">
                                                                        <div class="card card-body" style="height: 242px;overflow-y: auto">
                                                                            <div class="text-center" style="width: 100%">
                                                                                <b>Otros documentos</b>
                                                                                <button class="btn btn-sm btn-primary" onclick="agregarArchivoTratamiento('{{$solicitante->tratamiento_solicitado()->id_tratamiento_solicitado}}')" title="Cargar un documento al tratamiento solicitado">
                                                                                    <i class="fa fa-plus"></i>
                                                                                </button>
                                                                            </div>
                                                                            <div class="row text-center">
                                                                                @if($solicitante->tratamiento_solicitado()->otros_documentos->count() > 0)
                                                                                    @foreach($solicitante->tratamiento_solicitado()->otros_documentos as $y => $documento)
                                                                                        <div class="col-md-6 text-center p-2">
                                                                                            <div style="width: 100%;cursor:pointer">
                                                                                                <input id="check_otro_doc_{{$y+1}}" name="check_otro_doc_{{$y+1}}" type="checkbox"
                                                                                                       value="{{$documento->id_documento_tratamiento_solicitado}}" title="Enviar"
                                                                                                       class="form-control-custom" checked>
                                                                                                <label for="check_otro_doc_{{$y+1}}"><b>Adjuntar</b></label>
                                                                                            </div>
                                                                                            <a target="_blank" href="{{url('/storage/archivos/documentos/'.$documento->nombre)}}"
                                                                                               title="{{explode("_",$documento->nombre)[1]}}" >
                                                                                                <i class="fa fa-3x fa-file-pdf-o text-danger"></i>
                                                                                            </a>
                                                                                            <div>
                                                                                                <i class="fa fa-times-circle" title="Eliminar archivo" style="cursor: pointer"
                                                                                                   onclick="eliminar_archivo_documento_tratamiento_solicitado('{{$documento->id_documento_tratamiento_solicitado}}')">
                                                                                                </i>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                @else
                                                                                    <div class="text-center"> No se han cargado archivos </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @foreach ($documentoConsolidado->documento_solicitado_role_type as $role_type)
                                                                @if($role_type->role_type_id == $usuario->party_role->role_type->role_type_id)
                                                                    @if($role_type->correo)
                                                                        <form id="form_correo_documentos">
                                                                            <div class="row">
                                                                            <div class="card-body">
                                                                                <p>Datos del correo electrónico</p>
                                                                                <form class="form-horizontal">
                                                                                    <div class="form-group row">
                                                                                        <label class="col-sm-2">Asunto:</label>
                                                                                        <div class="col-sm-10">
                                                                                            <input id="asunto" name="asunto" type="text" class="form-control form-control-success" required>
                                                                                            <small class="form-text">
                                                                                                El correo se enviará a:
                                                                                                @isset($documentosConsolidados->correo_documento_solicitado)
                                                                                                    @foreach($documentosConsolidados->correo_documento_solicitado as $correo)
                                                                                                        {{$correo->correo .", "}}
                                                                                                    @endforeach
                                                                                                @else
                                                                                                    No se han guardado correos
                                                                                                @endif
                                                                                            </small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group row">
                                                                                        <label class="col-sm-2">Mensaje:</label>
                                                                                        <div class="col-sm-10">
                                                                                            <textarea id="mensaje" name="mensaje" class="form-control form-control-warning" required></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group row">
                                                                                        <div class="col-sm-10 offset-sm-2">
                                                                                            <button type="button" class="btn btn-success" onclick="enviar_documentos('{{$idTratamiento}}')">
                                                                                                <i class="fa fa-envelope-o"></i> Enviar correo electrónico
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                        </form>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                @else
                                                    <div class="alert alert-info text-center">Se debe asignar los permisos correspondientes a los diferentes roles, incluyendo el suyo, en el área de Configuraciones -> Documentos del tratamiento para poder usar esta sección</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <div class="tab-pane fade" id="aplicacion_medicacion" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <div class="row">
                              <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header" style="padding: 0.3rem 1.3rem;">
                                        <div class="row">
                                            <div class="col-md-12" style="margin-top: 6px;">
                                                <h4><i class="fa fa-th-large"></i> Aplicaciones </h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="container">
                                            <div class="row">
                                                <div class="process">
                                                    <div class="process-row nav nav-tabs">
                                                        @foreach($distribucion->distribucion_tratamiento as $x => $dist)
                                                        <div class="process-step">
                                                                <button type="button" class="btn  {{($x == 0) ? "btn-info" : "btn-default"}} btn-circle" data-toggle="tab" href="#menu_{{$x+1}}">
                                                                    Fase {{$x+1}}
                                                                </button>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="tab-content aplicaciones" style="width: 100%;">
                                                        @foreach($distribucion->distribucion_tratamiento as $x => $dist)
                                                        <div id="menu_{{$x+1}}" class="tab-pane {{($x == 0) ? "fade active in active show" : ""}}">
                                                            <div class="card-block p-0">
                                                                <table class="table table-bordered table-sm m-0" style="font-size: 12px;">
                                                                    <thead class="">
                                                                        <tr class="text-center">
                                                                            <th style="vertical-align: middle">Producto</th>
                                                                            <th style="vertical-align: middle;width: 65px;">Fecha de aplicación</th>
                                                                            <th style="vertical-align: middle;width: 65px">Día de la aplicación</th>
                                                                            <th style="vertical-align: middle">Cantidad</th>
                                                                            <th style="vertical-align: middle;;width: 90px;">Sitio de aplicación</th>
                                                                            <th style="vertical-align: middle">Comentarios</th>
                                                                            <th style="vertical-align: middle">Cumplido</th>
                                                                            <th style="vertical-align: middle">Acción</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @if($distribucionRegistrada)
                                                                            @php ksort($dataAplicacionMedicacion[$x]) @endphp
                                                                            @foreach($dataAplicacionMedicacion[$x] as $fechaAplicacion => $aplicacionMedicacion)
                                                                                @foreach($aplicacionMedicacion as  $fA)
                                                                                <tr class="text-center">
                                                                                    <td style="vertical-align: middle;padding: 0">{{$fA['producto']}}</td>
                                                                                    <td style="vertical-align: middle;width: 65px">
                                                                                        <input type="date" id="fecha_aplicacion_{{$fA['id_detalle_distribucion_tratamiento_doctor']}}" name="fecha_aplicacion_{{$fA['id_detalle_distribucion_tratamiento_doctor']}}"
                                                                                               style="border: none;font-size: 13px" class="form-control" value="{{$fechaAplicacion}}">
                                                                                    </td>
                                                                                    <td style="vertical-align: middle;padding: 0;width: 65px">
                                                                                        <input type="date" id="fecha_aplicacion_real_{{$fA['id_detalle_distribucion_tratamiento_doctor']}}"
                                                                                               value="{{$fA['fecha_aplicacion_real']}}"
                                                                                               style="border: none;font-size: 12px" class="form-control" name="fecha_aplicacion_real_{{$fA['id_detalle_distribucion_tratamiento_doctor']}}" >
                                                                                    </td>
                                                                                    <td style="vertical-align: middle;padding: 0">
                                                                                        <input type="number" min="0" class="form-control text-center" style="border:none;font-size: 12px"
                                                                                               id="cantidad_aplicacion_{{$fA['id_detalle_distribucion_tratamiento_doctor']}}"
                                                                                               name="cantidad_aplicacion_{{$fA['id_detalle_distribucion_tratamiento_doctor']}}"
                                                                                               value="{{$fA['cantidad']}}">
                                                                                    </td>
                                                                                    <td style="vertical-align: middle;padding: 0">
                                                                                        <input type="text" id="sitio_aplicacion_{{$fA['id_detalle_distribucion_tratamiento_doctor']}}"
                                                                                               name="sitio_aplicacion_{{$fA['id_detalle_distribucion_tratamiento_doctor']}}"
                                                                                               style="border: none;font-size: 13px;width: 90px;" class="form-control" value="{{$fA['sitio_aplicacion']}}">
                                                                                    </td>
                                                                                    <td style="vertical-align: middle;padding: 0">
                                                                                        <input type="text" id="comentario_{{$fA['id_detalle_distribucion_tratamiento_doctor']}}"
                                                                                               name="comentario_{{$fA['id_detalle_distribucion_tratamiento_doctor']}}"
                                                                                               style="border: none;width: 330px;font-size: 13px" class="form-control" value="{{$fA['comentarios']}}">
                                                                                    </td>
                                                                                    <td style="vertical-align: middle;padding: 0" >
                                                                                        <label class="custom-control custom-checkbox">
                                                                                            <input type="checkbox" class="custom-control-input" id="cumplido_{{$fA['id_detalle_distribucion_tratamiento_doctor']}}"
                                                                                                   {{isset($fA['cumplido']) ? ($fA['cumplido'] == true  ? "checked" : "") : ""}}
                                                                                                   name="cumplido_{{$fA['id_detalle_distribucion_tratamiento_doctor']}}">
                                                                                            <span class="custom-control-indicator"></span>
                                                                                        </label>
                                                                                    </td>
                                                                                    <td style="vertical-align: middle;padding: 0" class="text-center">
                                                                                        <button class="btn btn-success btn-sm"
                                                                                                onclick="storeAplicacionTratamiento('{{$fA['id_detalle_distribucion_tratamiento_doctor']}}')"
                                                                                                title="Guardar">
                                                                                            <i class="fa fa-floppy-o"></i>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                                @endforeach
                                                                            @endforeach
                                                                        @else
                                                                            <tr>
                                                                                <td colspan="8" class="alert alert-info text-center" style="font-size: 15px;">
                                                                                    Aún no se ha guardado la distribución del tratamiento por parte del doctor
                                                                                </td>
                                                                            </tr>
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
					    </div>
                        <div class="tab-pane fade" id="distribucion_medicacion" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <section class="statistics">
                                <div class="container-fluid">
                                    <div class="row d-flex estadistica">
                                        @foreach($prodcuto as $x=> $product)
                                            @php
                                                $total = 0;
                                                $usado = 0;
                                                foreach ($estadisticaUso[$product] as $item) {
                                                    $total += $item->cantidad_aplicacion;
                                                    if($item->cumplido)
                                                        $usado +=$item->cantidad_aplicacion;
                                                }

                                                $porcentaje = 100*$usado/$total;
                                                $restante = $total-$usado;
                                            @endphp
                                            <div class="@if(count($prodcuto) == 2 || count($prodcuto) == 1) col-lg-6 @else col-lg-4 @endif">
                                                <div class="card data-usage">
                                                    <h2 class="display h4">Producto: {{getProducto($product)->product_name}} </h2>
                                                    <div class="row d-flex align-items-center">
                                                        <div class="col-sm-6">
                                                            <div id="progress-circle_{{$x+1}}" class="d-flex align-items-center justify-content-center"></div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <strong class="text-primary">Usados: {{$usado}}</strong>
                                                            <span>Requeridos: {{$total}}</span>
                                                            <span>Restante: {{$restante}}</span>
                                                        </div>
                                                    </div>
                                                    <p>Distribución de uso del medicamento para el tratamiento del paciente</p>
                                                </div>
                                            </div>
                                            <script> $(function () { cargaProgreso('{{$x+1}}','{{$porcentaje}}'); })</script>
                                        @endforeach
                                    </div>
                                </div>
                            </section>
                            </div>
					    </div>
                    </div>
                </div>
            </div>
    </section>
    <script>
        function cargaProgreso(cant,porcentaje){
            $("#progress-circle_"+cant).gmpc({
                color: '#33b35a',
                line_width: 5,
                percent: Math.round(porcentaje),
                cant : cant,
            }).gmpc('animate', Math.round(porcentaje), 13000);
        }

        @isset($pasoActual->id_tratamiento_solicitado)
            step = '{{$pasoActual->proceso_actual}}';
            percent = (parseInt(step) / '{{$procesos->count()}}') * 100;
            $('.progress-bar').css({width: percent + '%'});
            $('.progress-bar').text("Paso " + step + " de "+ '{{$procesos->count()}}');
        @endisset

        $('a.tab').on('shown.bs.tab', function (e) {

            //update progress
            step = $(e.target).data('step');
            percent = (parseInt(step) / '{{$procesos->count()}}') * 100;

            $('.progress-bar').css({width: percent + '%'});
            $('.progress-bar').text("Paso " + step + " de "+ '{{$procesos->count()}}');

            //e.relatedTarget // previous tab

        });

        $('.first').click(function(){ $('#myWizard a:first').tab('show') });
        $(function () {
            $.each($("td.div_dosis"),function (i,j) { calcular_producto('producto_'+(i+1)) });
        });

    </script>
    <style>
        .process-step .btn:focus{outline:none}
        .process{display:table;width:100%;position:relative; background:#f2f2f2;padding: 5px;;border-radius:5px;}
        .process-row{display:table-row}
        .process-step button[disabled]{opacity:1 !important;filter: alpha(opacity=100) !important}
        .process-row:before{top:40px;bottom:0;position:absolute;content:" ";width:100%;height:1px;z-order:0}
        .process-step{display:table-cell;text-align:center;position:relative}
        .process-step p{margin-top:4px}
        .btn-circle{width:60px;height:60px;text-align:center;font-size:12px;border-radius:50%}
        .custom-checkbox .custom-control-indicator {
            content: "";
            display: inline-block;
            position: relative;
            width: 30px;
            height: 10px;
            background-color: #818181;
            border-radius: 15px;
            margin-right: 10px;
            -webkit-transition: background .3s ease;
            transition: background .3s ease;
            vertical-align: middle;
            margin: 0 16px;
            box-shadow: none;
        }
        .custom-checkbox .custom-control-indicator:after {
            content: "";
            position: absolute;
            display: inline-block;
            width: 18px;
            height: 18px;
            background-color: #f1f1f1;
            border-radius: 21px;
            box-shadow: 0 1px 3px 1px rgba(0, 0, 0, 0.4);
            left: -2px;
            top: -4px;
            -webkit-transition: left .3s ease, background .3s ease, box-shadow .1s ease;
            transition: left .3s ease, background .3s ease, box-shadow .1s ease;
        }
        .custom-checkbox .custom-control-input:checked ~ .custom-control-indicator {
            background-color: #84c7c1;
            background-image: none;
            box-shadow: none !important;
        }
        .custom-checkbox .custom-control-input:checked ~ .custom-control-indicator:after {
            background-color: #84c7c1;
            left: 15px;
        }
        .custom-checkbox .custom-control-input:focus ~ .custom-control-indicator {
            box-shadow: none !important;
        }
    </style>
@endsection
@section('custom_page_js')
    @include('tratamiento_cliente.script')
@endsection