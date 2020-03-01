@extends('layouts.partials.dashboard')
@section('title')
    Distribución del inventario
@endsection

@section('contenido')
    <section id="tabs">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 ">
                    <nav style="background: #f1f1f1">
                        <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-profile-tab" data-toggle="tab" href="#tab_distribucion_inventario" role="tab" aria-controls="documentos_tratamiento" aria-selected="false">
                                <i class="fa fa-sitemap"></i> Distribución
                            </a>
                            <a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#tab_productos_inventario" role="tab" aria-controls="distribucion_tratamiento" aria-selected="true">
                                <i class="fa fa-cubes"></i> Inventario
                            </a>
                        </div>
                    </nav>
                    <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="tab_distribucion_inventario" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="sesion_usuario" role="tabpanel">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="tab-pane fade show" id="inventario" role="tabpanel">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <div class="card-actions float-right">
                                                                    <div class="dropdown show">
                                                                        <a href="{{url('distribucion_inventario/exportar_distribucion')}}"  class="bnt btn-success btn-sm"
                                                                           title="Exportar excel" >
                                                                            <i class="fa fa-file-excel-o"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <h5 class="card-title mb-0">Distribución de la medicación por tratamiento solicitado</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="form-row">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-responsive-sm table-striped table-sm">
                                                                            <thead>
                                                                            <tr>
                                                                                <th class="text-center">Tratamiento</th>
                                                                                <th class="text-center">Cliente</th>
                                                                                <th class="text-center">Producto</th>
                                                                                <th class="text-center">Requeridos por el tratamiento</th>
                                                                                <th class="text-center">Entregados</th>
                                                                                <th class="text-center">Importados</th>
                                                                                <th class="text-center">Código de importación</th>
                                                                                {{--<th class="text-center">Acciones</th>--}}
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            @if(count($tratamientoSolicitados) >0)
                                                                                @foreach($tratamientoSolicitados as $ts)
                                                                                    @foreach($ts->detalle_tratamiento_doctor as $dtd)
                                                                                        @foreach($dtd->datos_cotizacion() as $datos_cotizacion)
                                                                                            @foreach($datos_cotizacion as $data)
                                                                                                <tr>
                                                                                                    <td style="vertical-align: middle" class="text-center">{{$ts->tratamiento->nombre_tratamiento}}</td>
                                                                                                    <td style="vertical-align: middle" class="text-center">{{$ts->person->first_name." ".$ts->person->last_name}}</td>
                                                                                                    <td style="vertical-align: middle" class="text-center">{{getProducto($data['product_id'])->product_name}}</td>
                                                                                                    <td style="vertical-align: middle" class="text-center">{{$data['cantidad']}} Unidades</td>
                                                                                                    <td style="vertical-align: middle" class="text-center">{{getProductosEntregados($data['product_id'],$ts->party_id)}} Unidades</td>
                                                                                                    <td style="vertical-align: middle" class="text-center">{{getProductosImportados($data['product_id'],$dtd->codigo_importacion)}} Unidades</td>
                                                                                                    <td style="vertical-align: middle" class="text-center">{{$dtd->codigo_importacion}}</td>
                                                                                                    {{--<td style="vertical-align: middle" class="text-center">
                                                                                                        <button class="btn btn-sm btn-success" title="Ver desglose de entregas" onclick="desglose_entregas('{{$ts->id_tratamiento_solicitado}}')">
                                                                                                            <i class="fa fa-eye"></i>
                                                                                                        </button>
                                                                                                    </td>--}}
                                                                                                </tr>
                                                                                            @endforeach
                                                                                        @endforeach
                                                                                    @endforeach
                                                                                @endforeach
                                                                            @else
                                                                                <tr>
                                                                                    <td colspan="7" style="vertical-align: middle">
                                                                                        <div class="alert alert-info text-center" role="alert">
                                                                                         No se han encontrado registros
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                              @endif
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="tab_productos_inventario" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div class="row">
                                <div class="col-md-6" >
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="sesion_usuario" role="tabpanel">
                                            <div class="card">
                                                <div class="card-header ">
                                                    <div class="card-actions float-right">
                                                        <div class="dropdown show">

                                                        </div>
                                                    </div>
                                                    <h5 class="card-title mb-0">
                                                        Productos
                                                    </h5>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control form-control-sm" id="input_productos">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body" style="height: 450px;overflow-y: auto">
                                                    <div id="productos_inventario">
                                                        @foreach($productos as $producto)
                                                            <div class="progress-group mt-2">
                                                                {{--<span style="cursor: pointer" title="{{$producto->description}}">--}}
                                                                <button type="button" class="btn btn-sm btn-success" onclick="proyeccion_producto_inventario('{{$producto->product_id}}')">
                                                                    <i class="fa fa-cubes"></i> {{strtoupper(strtoupper($producto->product_name))}}
                                                                </button>
                                                                {{--</span>--}}
                                                                <span class="float-right" style="    margin-top: 8px;">
                                                                    <b title="Diponible para reservar">{{number_format($producto->inventario()->total_reserva)}}</b>/
                                                                    <b title="Diponible para vender">{{number_format($producto->inventario()->total_disponible)}}</b>
                                                                </span>
                                                                <hr />
                                                                {{--<div class="progress progress-sm">
                                                                    <div class="progress-bar bg-primary" style="width: 80%"></div>
                                                                </div>--}}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" >
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="tab_lotes" role="tabpanel">
                                            <div class="card">
                                                <div class="card-header ">
                                                    <div class="card-actions float-right">
                                                        <div class="dropdown show">

                                                        </div>
                                                    </div>
                                                    <h5 class="card-title mb-0">
                                                        Lotes
                                                    </h5>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control form-control-sm" id="input_lotes">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body" style="height: 450px;overflow-y: auto">
                                                    <div id="lotes_inventario">
                                                        <div class="progress-group">
                                                            <div class="card">
                                                                <div class="card-body p-0">
                                                                    <ul class="products-list product-list-in-card pl-2 pr-2">
                                                                        @foreach($lotes as $lote)
                                                                            @if($lote->inventory_item() !=null && $lote->inventory_item()->total_disponible>0)
                                                                                <li class="item">
                                                                                    <div class="product-img" style="height: 0;">
                                                                                        <i class="fa fa-4x fa-cube"></i>
                                                                                    </div>
                                                                                    <div class="product-info">
                                                                                        <span class="product-title"><b>Lote:</b>
                                                                                            {{$lote->lot_id}},
                                                                                        </span>
                                                                                        <span ><b>Producto:</b> {{strtolower(ucwords($lote->inventory_item()->producto->product_name))}}</span><br />
                                                                                        <span class="product-title"><b>Fecha de expiración:</b> {{\Carbon\Carbon::parse($lote->expiration_date)->format('d-m-Y')}}</span><br />
                                                                                        <span class="product-description">
                                                                                            <b>Items comprados:</b> {{isset($lote->inventory_item()->total_comprado) ? number_format($lote->inventory_item()->total_comprado) :0}} ,
                                                                                            <b>Restantes :</b>{{isset($lote->inventory_item()->total_disponible) ? number_format($lote->inventory_item()->total_disponible) : 0}}
                                                                                        </span>
                                                                                    </div>
                                                                                </li>
                                                                            @endif
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                  </div>
              </div>
          </div>
    </section>

@endsection
@section('custom_page_js')
    @include('inventario.script')
@endsection