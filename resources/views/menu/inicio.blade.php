@extends('layouts.partials.dashboard')
@section('title')
    Menú
@endsection

@section('contenido')
    <section class="statistics">
        <div class="container-fluid">
            <div class="row d-flex">
                <div class="col-lg-4">
                    <!-- Income-->
                    <div class="card income">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th colspan="3" class="text-center">
                                    <h4>
                                        MENÚ
                                    </h4>
                                </th>
                            </tr>
                            <tr>
                                <th scope="col" style="vertical-align: middle">Ícono</th>
                                <th scope="col" style="vertical-align: middle">Nombre</th>
                                <th scope="col" class="text-right">
                                    <button type="button" class="btn btn-sm btn-primary" title="Agregar menú"
                                    onclick="addMenu()">
                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                    </button>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($menu as $m)
                            <tr>
                                <th style="vertical-align: middle" scope="row">
                                    <i class="fa {{$m->icono}}" aria-hidden="true"></i>
                                </th>
                                <td style="vertical-align: middle" >{{$m->nombre}}</td>
                                <td style="vertical-align: middle" scope="row" class="text-right">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-default" title="Editar menú"
                                        onclick="addMenu('{{$m->id_menu}}')">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" title="Eliminar menú"
                                        onclick="deleteMenu('{{$m->id_menu}}')">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-5">
                    <!-- Monthly Usage-->
                    <div class="card data-usage">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th colspan="3" class="text-center">
                                    <h4>
                                        SUB MENÚ
                                    </h4>
                                </th>
                            </tr>
                            <tr>
                                <th scope="col" style="vertical-align: middle">Ícono</th>
                                <th scope="col" style="vertical-align: middle">Nombre</th>
                                <th scope="col" class="text-right">
                                    <button type="button" class="btn btn-sm btn-primary" title="Agregar menú"
                                    onclick="addSubMenu()">
                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                    </button>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($subMenu as $sb)
                                <tr>
                                    <th style="vertical-align: middle" scope="row"><i class="fa {{$sb->icono}}" aria-hidden="true"></i></th>
                                    <td style="vertical-align: middle" >{{$sb->menu->nombre."/".$sb->nombre}}</td>
                                    <td style="vertical-align: middle" scope="row" class="text-right">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-default" title="Editar menú" onclick="addSubMenu('{{$sb->id_sub_menu}}')">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" title="Eliminar menú" onclick="deletesubMenu('{{$sb->id_sub_menu}}')">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-3">
                    <!-- User Actibity-->
                    <div class="card user-activity">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th colspan="3" class="text-center">
                                    <h4>
                                        PERMISOS
                                    </h4>
                                </th>
                            </tr>
                            <tr>
                                <th scope="col" colspan="2" style="vertical-align: middle">Rol</th>
                                {{--<th scope="col" class="text-right">
                                    <button type="button" class="btn btn-sm btn-primary" title="Crear Rol" onclick="crearRol()">
                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                    </button>
                                </th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($roles as $r)
                                <tr>
                                    <td style="vertical-align: middle" >{{$r->description}}</td>
                                    <td style="vertical-align: middle" scope="row" class="text-right">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary" title="Asignar permisos" onclick="asignarPermisos('{{$r->role_type_id}}')">
                                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                            </button>
                                            {{--<button type="button" class="btn btn-sm btn-danger" title="Eliminar menú">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>--}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_page_js')
    @include('menu.script')
@endsection