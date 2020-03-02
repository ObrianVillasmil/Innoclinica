@extends('layouts.partials.dashboard')
@section('title')
    Perfil
@endsection

@section('contenido')
    @php
        $u = getUserLogin($user->user_login_id);
        isset(getUserLogin($user->user_login_id)->party->party_relationship)
            ? $uP = getUserLogin(getUserLogin($user->user_login_id)->party->party_relationship->party_id_to)
            : $uP = null;
    @endphp
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
                            <i class="fa fa-list-alt" ></i> Datos generales del usuario
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#password" role="tab">
                            <i class="fa fa-unlock-alt" ></i> Contraseña
                        </a>

                        @if(isset($user->party->party_role) && $user->party->party_role->role_type_id === "REPRESENTANTE_LEGAL")
                            <a class="list-group-item list-group-item-action " data-toggle="list" href="#paciente" role="tab">
                                <i class="fa fa-user" ></i> Datos generales del paciente
                            </a>
                        @endif
                        @if(isset($u->party->party_role->role_type_id) && count($rolesfirma) > 0 && in_array($u->party->party_role->role_type_id,$rolesfirma))
                            <a class="list-group-item list-group-item-action" data-toggle="list" href="#firma_digital" role="tab">
                                <i class="fa fa-pencil" ></i> Firma digital
                            </a>
                        @endif
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
                                <h5 class="card-title mb-0">Información general del usuario</h5>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" id="form_datos_usuario">
                                    <div class="form-row">
                                        {{--<div class="col-md-6">
                                            <div class="form-group">
                                                <label for="usuario">Usuairo</label>
                                                <input type="text" class="form-control" disabled id="usuario" value="{{$u->user_login_id}}" required>
                                            </div>
                                        </div>--}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="inputEmail4">Usuario </label>
                                                @foreach($user->party->party_contact_mech as $contact_mech)
                                                    @if($contact_mech->contact_mech->contact_mech_type_id === "EMAIL_ADDRESS")
                                                        @php $email = $contact_mech->contact_mech->info_string; @endphp
                                                    @endif
                                                    @if($contact_mech->contact_mech->contact_mech_type_id === "POSTAL_ADDRESS")
                                                        @php
                                                            $address = $contact_mech->contact_mech->posta_address->address1;
                                                            $geo = $contact_mech->contact_mech->posta_address->country_geo_id;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                <input type="email" class="form-control" id="correo" value="{{isset($email) ? $email : ""}}" name="correo" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nombres">Nombres</label>
                                                <input type="text" class="form-control" id="nombres"  name="nombres" value="{{$u->party->person->first_name}}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="apellidos">Apellidos</label>
                                                <input type="text" class="form-control" id="apellidos" name="apellidos" value="{{$u->party->person->last_name}}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="direccion">Dirección</label>
                                                <input type="text" class="form-control" id="direccion" name="direccion" value="{{isset($address) ? $address : ""}}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tipo_identificacion">Tipo identificación</label>
                                                <select class="form-control" id="tipo_identificacion" name="tipo_identificacion" required>
                                                    @foreach($tipoIdentificacion as $tI)
                                                        <option {{isset($u->party->identification) ? ($u->party->identification->tipo_identificacion->party_identification_type_id === $tI->party_identification_type_id ? "selected" : "") : ""}}
                                                                value="{{$tI->party_identification_type_id}}">
                                                            {{ucfirst(strtolower($tI->description))}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="identificacion">Identificación</label>
                                                <input type="text" class="form-control" id="identificacion" name="identificacion" value="{{isset($u->party->identification) ? $u->party->identification->id_value : ""}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            @php
                                                $address = "";
                                                $geo = "";
                                                $phone ="";
                                                foreach($u->party->party_contact_mech as $contact_mech){

                                                    if($contact_mech->contact_mech->contact_mech_type_id === "POSTAL_ADDRESS"){
                                                        $address = $contact_mech->contact_mech->posta_address->address1;
                                                        $geo = $contact_mech->contact_mech->posta_address->country_geo_id;
                                                    }
                                                    if($contact_mech->contact_mech->contact_mech_type_id === "TELECOM_NUMBER"){
                                                        $phone = $contact_mech->contact_mech->telecom_number->contact_number;
                                                    }
                                                }
                                            @endphp
                                            <label for="pais">País</label>
                                            <select class="form-control" id="pais" name="pais" required>
                                                @foreach($pais as $p)
                                                    <option {{$geo == $p->geo_id ? "selected" : ""}} value="{{$p->geo_id}}">{{$p->geo_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="nacionalidad">Nacionalidad</label>
                                            <input type="text" class="form-control" value="{{$u->party->person->nacionalidad}}" id="nacionalidad" name="nacionalidad" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            @php
                                                $address = "";
                                                $geo = "";
                                                $address="";
                                                foreach($u->party->party_contact_mech as $contact_mech)
                                                    if($contact_mech->contact_mech->contact_mech_type_id === "POSTAL_ADDRESS"){
                                                        $address = $contact_mech->contact_mech->posta_address->address1;
                                                        $geo = $contact_mech->contact_mech->posta_address->country_geo_id;
                                                    }
                                            @endphp
                                            <label for="telefono">Teléfono</label>
                                            <input type="number" class="form-control" id="telefono" value="{{isset($phone) ? $phone : ""}}" name="telefono" required>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fecha_nacimiento">Fecha de nacimiento</label>
                                                <input type="date" class="form-control" id="fecha_nacimiento" value="{{$u->party->person->birth_date}}" name="fecha_nacimiento" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary" onclick="actualizaDatosUsuario('{{$u->party_id}}')">
                                            <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @if(isset($user->party->party_role) && $user->party->party_role->role_type_id === "REPRESENTANTE_LEGAL")
                        <div class="tab-pane fade" id="paciente" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-actions float-right">
                                        <div class="dropdown show">
                                            <a href="#" data-toggle="dropdown" data-display="static">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                     stroke-linejoin="round" class="feather feather-more-horizontal align-middle">
                                                    <circle cx="12" cy="12" r="1"></circle>
                                                    <circle cx="19" cy="12" r="1"></circle>
                                                    <circle cx="5" cy="12" r="1"></circle>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    <h5 class="card-title mb-0">Información general del paciente</h5>
                                </div>
                                <div class="card-body">
                                    <form class="form-horizontal" id="form_datos_paciente">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nombres_paciente">Nombres</label>
                                                    <input type="text" class="form-control" id="nombres_paciente"  name="nombres_paciente" value="{{isset($uP) ? $uP->party->person->first_name : "" }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="apellidos_paciente">Apellidos</label>
                                                    <input type="text" class="form-control" id="apellidos_paciente" name="apellidos_paciente" value="{{isset($uP) ? $uP->party->person->last_name : ""}}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="tipo_identificacion_paciente">Tipo identificación</label>
                                                    <select class="form-control" id="tipo_identificacion_paciente" name="tipo_identificacion_paciente" required>
                                                        @foreach($tipoIdentificacion as $tI)
                                                            <option {{isset($uP) ? ($uP->party->identification->tipo_identificacion->party_identification_type_id === $tI->party_identification_type_id ? "selected" : "") : ""}}
                                                                    value="{{$tI->party_identification_type_id}}">
                                                                {{ucfirst(strtolower($tI->description))}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="identificacion_paciente">Identificación</label>
                                                    <input type="text" class="form-control" id="identificacion_paciente" name="identificacion_paciente" value="{{isset($uP) ? $uP->party->identification->id_value : ""}}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                @php
                                                    $address = "";
                                                    $geo = "";
                                                    $phone ="";

                                                    if(isset($uP)){
                                                        foreach($uP->party->party_contact_mech as $contact_mech){
                                                            if($contact_mech->contact_mech->contact_mech_type_id === "POSTAL_ADDRESS"){
                                                                $address = $contact_mech->contact_mech->posta_address->address1;
                                                                $geo = $contact_mech->contact_mech->posta_address->country_geo_id;
                                                            }
                                                            if($contact_mech->contact_mech->contact_mech_type_id === "TELECOM_NUMBER"){
                                                                $phone = $contact_mech->contact_mech->telecom_number->contact_number;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                <label for="pais_paciente">País</label>
                                                <select class="form-control" id="pais_paciente" name="pais_paciente" required>
                                                    @foreach($pais as $p)
                                                        <option {{$geo == $p->geo_id ? "selected" : ""}} value="{{$p->geo_id}}">{{$p->geo_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="nacionalidad_paciente">Nacionalidad</label>
                                                <input type="text" class="form-control" value="{{isset($uP) ? $uP->party->person->nacionalidad : ""}}" id="nacionalidad_paciente" name="nacionalidad_paciente" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    @php
                                                        $address = "";
                                                        $geo="";
                                                        if(isset($uP)){
                                                            foreach($uP->party->party_contact_mech as $contact_mech)
                                                                if($contact_mech->contact_mech->contact_mech_type_id === "POSTAL_ADDRESS"){
                                                                    $address = $contact_mech->contact_mech->posta_address->address1;
                                                                    $geo = $contact_mech->contact_mech->posta_address->country_geo_id;
                                                                }
                                                        }
                                                    @endphp
                                                    <label for="telefono_paciente">Teléfono</label>
                                                    <input type="number" class="form-control" id="telefono_paciente" value="{{$phone}}" name="telefono_paciente" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="fecha_nacimiento_paciente">Fecha de nacimiento</label>
                                                        <input type="date" class="form-control" id="fecha_nacimiento_paciente" value="{{isset($uP) ? $uP->party->person->birth_date: ""}}" name="fecha_nacimiento_paciente" required>
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="direccion_paciente">Dirección</label>
                                                    <input type="text" class="form-control" id="direccion_paciente" name="direccion_paciente" value="{{$address}}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="button" class="btn btn-primary" onclick="actualizaDatosPaciente('{{isset($uP) ? $uP->party->party_id : null}}')">
                                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="tab-pane fade" id="password" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <form id="form_contrasena">
                                    <div class="form-group">
                                        <label for="contrsena">Nueva contraseña</label>
                                        <input type="password" class="form-control" id="contrasena" name="contrasena">
                                    </div>
                                    <div class="form-group">
                                        <label for="contrasena_contrasena">Repita la contraseña</label>
                                        <input type="password" class="form-control" id="contrasena_contrasena" name="contrasena_contrasena">
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="actualizarContrasena('{{$u->party_id}}')">
                                        <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @if(count($rolesfirma) > 0 && in_array($u->party->party_role->role_type_id,$rolesfirma))

                            <div class="tab-pane fade" id="firma_digital" role="tabpanel">
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
                                        <h5 class="card-title mb-0">Cargue su firma digital</h5>
                                    </div>
                                    <form id="form_firma_digital">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Imagen de firma .PNG</label>
                                                        <input type="file" class="form-control" accept="image/png" id="firma_digital" name="firma_digital">
                                                    </div>
                                                    <span id="span_firma_digital">
                                                        @isset($u->party->firma->imagen)
                                                            <i class="fa fa-picture-o text-primary"></i>
                                                            <a target="_blank" href="{{url('storage/firmas_digital/',$u->party->firma->imagen)}}"> {{$u->party->firma->imagen}}</a>
                                                        @endisset
                                                    </span>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Archivo de firma digital</label>
                                                        <input type="file" class="form-control" id="firma_electronica" name="firma_electronica">
                                                    </div>
                                                    <span id="span_firma_electronica">
                                                        @isset($u->party->firma->archivo)
                                                            <i class="fa fa-file-code-o text-primary" ></i>
                                                            <a target="_blank" href="{{url('storage/firmas_digital/',$u->party->firma->archivo)}}"> {{$u->party->firma->archivo}}</a>
                                                        @endisset
                                                    </span>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Contraseña de firma digital</label>
                                                        <input type="text" class="form-control" id="contrasena_firma_electronica"
                                                               value="{{isset($u->party->firma->contrasena) ? $u->party->firma->contrasena : ""}}" name="contrasena_firma_electronica">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center mb-4" style="margin-top: 20px">
                                                <button type="button" class="btn btn-primary" onclick="guardarFirma('{{$u->party_id}}')">
                                                    <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_page_js')
    @include('usuario.script')
@endsection