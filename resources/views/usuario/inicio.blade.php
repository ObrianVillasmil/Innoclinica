@extends('layouts.partials.dashboard')
@section('title')
    Usuario
@endsection

@section('contenido')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" style="padding: 0.3rem 1.3rem;">
                <div class="row">
                    <div class="col-md-4" style="margin-top: 6px;">
                        <h4>Usuarios</h4>
                    </div>
                    <div class="col-md-8 text-right">
                        <form method="GET" id="buscar_usuario" action="{{action("UsuarioController@inicio")}}">
                            <div class="form-group row" style="margin-bottom: 0">
                                <label class="col-sm-9" style="margin-bottom: 0;margin-top: 5px;">Buscar usuario</label>
                                <div class="col-sm-3 input-group" style="margin-bottom: 0">
                                    <select class="form-control form-control-sm" name="estado" style="font-size: 13px" onchange="document.getElementById('buscar_usuario').submit()">
                                        <option value="" >Estado</option>
                                        <option {{$estado == "Y" ? "selected" : ""}} value="Y" >Activo</option>
                                        <option {{$estado == "N" ? "selected" : ""}} value="N" >Inactivo</option>
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn  btn-sm btn-success" title="Crear usuario" onclick="crear_usuario()">
                                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                        <tr>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($usuarios->count() >0)
                            @foreach($usuarios as $u)
                                @php $user = getUserLogin($u->user_login_id); @endphp
                                @if($user->current_password !== null)
                                    @if(isset($user->party->party_role))
                                        @if($user->party->party_role->role_type->role_type_id !== "ADMIN")
                                        <tr>
                                            <td style="vertical-align: middle">{{$user->party->person->first_name}}</td>
                                            <td style="vertical-align: middle">{{$user->party->person->last_name}}</td>
                                            <td style="vertical-align: middle">
                                                @foreach($user->party->party_contact_mech as $contact_mech)
                                                    @if($contact_mech->contact_mech->contact_mech_type_id === "EMAIL_ADDRESS")
                                                        <a style="text-decoration: none;" href="mailto:{{$contact_mech->contact_mech->info_string}}">
                                                            {{$contact_mech->contact_mech->info_string}}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td style="vertical-align: middle">
                                                @foreach($user->party->party_contact_mech as $contact_mech)
                                                    @if($contact_mech->contact_mech->contact_mech_type_id === "TELECOM_NUMBER")
                                                        <a style="text-decoration: none;" href="tel:{{$contact_mech->contact_mech->telecom_number->contact_number}}">
                                                            {{$contact_mech->contact_mech->telecom_number->contact_number}}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td style="vertical-align: middle">{{$user->party->party_role->role_type->description }}</td>
                                            <td scope="row" style="vertical-align: middle">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-{{$user->enabled === "Y" ? "danger" : "success"}}" title="{{$user->enabled === "Y" ? "Deshabilitar" : "Habilitar"}} usuario"
                                                            onclick="actualizarEstadoUsuario('{{$user->party_id}}','{{$user->enabled}}')">
                                                        <i class="fa {{$user->enabled === "Y" ? "fa-user-times" : "fa-undo"}}"></i>
                                                    </button>
                                                    <a href="{{url('usuario/perfil',$user->party_id)}}" class="btn btn-sm btn-warning" {{$user->enabled === "N" ? "disabled" : ""}} title="Editar usuario">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                    @endif
                                @endif
                            @endforeach

                        @else
                            <tr>
                                <td colspan="6" style="vertical-align: middle">
                                    <div class="alert alert-info text-center" role="alert">
                                      No se encontraron registro
                                    </div>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <div class="text-center" style="width: 100%;">
                        {!! !empty($usuarios->links()) ? $usuarios->appends(request()->input())->links() : '' !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_page_js')
    @include('usuario.script')
@endsection