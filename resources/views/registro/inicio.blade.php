@extends('layouts.partials.login')
@section('title')
    Registro
@endsection

@section('contenido')
<div class="page login-page" style="background: url({{isset(getConfiguracionEmpresa()->img_fondo_login) ? '/storage/'.getConfiguracionEmpresa()->img_fondo_login : ""}});background-repeat: no-repeat;">
    <div class="container">
            <div class="form-outer text-center d-flex align-items-center" >
            <div class="form-inner">
                <div class="logo text-uppercase"><strong class="text-primary">Registrarse</strong></div>
                <p>Ingrese los datos solicitados para crear un cuenta y poder disfrutar de nuestros servicios</p>
                <p>@include('flash::message')</p>
                <form class="text-left form-validate" action="{{action('LoginController@storeRegistro')}}" method="POST" style="    max-width: 700px;">
                    @csrf
                    <div class="col-md-12">
                      <div class="row">
                      <div class="col-md-6">
                          {{--<div class="form-group-material">
                              <input id="usuario" type="text" name="usuario" required data-msg="Ingrese el usuario" class="input-material"
                                     value="{{old('usuario')}}">
                              <label for="usuario" class="label-material">Usuario</label>
                              @if ($errors->has('usuario'))
                                  <div class="text-danger">{{ $errors->first('usuario') }}</div>
                              @endif
                          </div>--}}
                          <div class="form-group-material">
                              <input id="nombre" type="text" name="nombre" required data-msg="Ingrese sus nombres" class="input-material"
                                     value="{{old('nombre')}}">
                              <label for="nombre" class="label-material">Nombres</label>
                              @if ($errors->has('nombre'))
                                  <div class="text-danger">{{ $errors->first('nombre') }}</div>
                              @endif
                          </div>

                          <div class="form-group-material">
                              <input id="correo" type="email" name="correo" required data-msg="Ingrese su correo electrónico" class="input-material"
                                     value="{{old('correo')}}">
                              <label for="correo" class="label-material">Correo (Será su usuario)</label>
                              @if ($errors->has('correo'))
                                  <div class="text-danger">{{ $errors->first('correo') }}</div>
                              @endif
                          </div>
                          <div class="form-group-material">
                              <input id="contrasena" type="password" name="contrasena" required data-msg="ingrese su contraseña" class="input-material">
                              <label for="contrasena" class="label-material">Contraseña </label>
                              @if ($errors->has('contrasena'))
                                  <div class="text-danger">{{ $errors->first('contrasena') }}</div>
                              @endif
                          </div>
                          {{--<div class="form-group-material" style="margin-top: 34px;">
                              <select id="rol" name="rol" required data-msg="Seleccione su rol" class="form-control" style="border-left: none;border-top: none;border-right: none;border-bottom-color: #eee;color:#a4a4a4;padding-left:0px;font-size: 15px">
                                  <option selected disabled value="">Seleccione su rol</option>
                                  @foreach($rol as $r)
                                    <option {{old('rol') == $r->role_type_id ? "selected" : ""}} value="{{$r->role_type_id}}">{{$r->description}}</option>
                                  @endforeach
                              </select>
                              @if ($errors->has('rol'))
                                  <div class="text-danger">{{ $errors->first('rol') }}</div>
                              @endif
                          </div>--}}
                          {{--<div class="form-group-material">
                              <div class="form-group-material" style="    margin-top: 34px;">
                                  <select id="pais" name="pais" required data-msg="Seleccione su país" class="form-control" style="border-left: none;border-top: none;border-right: none;border-bottom-color: #eee;color:#a4a4a4;padding-left:0px;font-size: 15px">
                                      <option selected disabled value="">Seleccione su país</option>
                                      @foreach($pais as $p)
                                          <option {{old('pais') == $p->geo_code ? "selected" : ""}} value="{{$p->geo_code}}">{{$p->geo_name}}</option>
                                      @endforeach
                                  </select>
                                  @if ($errors->has('pais'))
                                      <div class="text-danger">{{ $errors->first('pais') }}</div>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group-material">
                              <input id="nacionalidad" type="text" name="nacionalidad" value="{{old('nacionalidad')}}"  required data-msg="Ingrese su nacionalidad" class="input-material">
                              <label for="nacionalidad" class="label-material">Nacionalidad </label>
                              @if ($errors->has('nacionalidad'))
                                  <div class="text-danger">{{ $errors->first('nacionalidad') }}</div>
                              @endif
                          </div>--}}
                      </div>
                      <div class="col-md-6">
                          <div class="form-group-material">
                              <input id="apellido" type="text" name="apellido" required data-msg="Ingrese sus apellidos"
                                     class="input-material" value="{{old('apellido')}}">
                              <label for="apellido" class="label-material">Apellidos</label>
                              @if ($errors->has('apellido'))
                                  <div class="text-danger">{{ $errors->first('apellido') }}</div>
                              @endif
                          </div>
                          <div class="form-group-material">
                              <input id="telefono" type="text" name="telefono" value="{{old('telefono')}}"  required data-msg="Ingrese su teléfono" class="input-material">
                              <label for="telefono" class="label-material">Teléfono </label>
                              @if ($errors->has('telefono'))
                                  <div class="text-danger">{{ $errors->first('telefono') }}</div>
                              @endif
                          </div>
                          <div class="form-group-material">
                              <input id="contrasena_confirmation" type="password" name="contrasena_confirmation" required data-msg="Repita su contraseña" class="input-material">
                              <label for="contrasena_confirmation" class="label-material">Repita la Contraseña </label>
                              @if ($errors->has('contrasena_confirmation'))
                                  <div class="text-danger">{{ $errors->first('contrasena_confirmation') }}</div>
                              @endif
                          </div>

                      </div>
                          {{--<div class="col-md-6">

                              <div class="form-group-material">
                                  <div class="form-group-material" style="    margin-top: 34px;">
                                      <select id="pais" name="tipo_identificacion"  required data-msg="Seleccione su tipo de identificacion" class="form-control" style="border-left: none;border-top: none;border-right: none;border-bottom-color: #eee;color:#a4a4a4;padding-left:0px;font-size: 15px">
                                          <option selected disabled value="">Seleccione su tipo de identificacion</option>
                                          @foreach($tipoIdentificacion as $tI)
                                              <option {{old('tipo_identificacion') == $tI->party_identification_type_id ? "selected" : ""}}  value="{{$tI->party_identification_type_id}}">{{$tI->description}}</option>
                                          @endforeach
                                      </select>
                                      @if ($errors->has('tipo_identificacion'))
                                          <div class="text-danger">{{ $errors->first('tipo_identificacion') }}</div>
                                      @endif
                                  </div>
                              </div>
                            <div class="form-group-material">
                                <input id="identificacion" type="text" name="identificacion" value="{{old('identificacion')}}"  required data-msg="Ingrese su identificación" class="input-material">
                                <label for="telefono" class="label-material">Identificacion </label>
                                @if ($errors->has('identificacion'))
                                    <div class="text-danger">{{ $errors->first('identificacion') }}</div>
                                @endif
                            </div>

                            <div class="form-group-material">
                                <input id="direccion" type="text" name="direccion" value="{{old('direccion')}}"
                                       required data-msg="Dirección" class="input-material">
                                <label for="direccion" class="label-material">Dirección</label>
                                @if ($errors->has('direccion'))
                                    <div class="text-danger">{{ $errors->first('direccion') }}</div>
                                @endif
                            </div>
                            <div class="form-group-material">
                                <input id="fecha_nacimiento" type="date" name="fecha_nacimiento" value="{{old('fecha_nacimiento')}}"  required data-msg="Ingrese su fecha de nacimiento" class="input-material">
                                <label for="fecha_nacimiento" class="label-material">Fecha de nacimiento </label>
                                @if ($errors->has('fecha_nacimiento'))
                                    <div class="text-danger">{{ $errors->first('fecha_nacimiento') }}</div>
                                @endif
                            </div>
                        </div>--}}
                    </div>
                    </div>
                    <div class="form-group-material text-center" style="width:54%;margin: 0 auto">
                        {!! NoCaptcha::display() !!}
                        {{--<input id="captcha" type="text" name="captcha" autocomplete="off" placeholder="Ingrese el código" required data-msg="Ingrese el código mostrado" class="input-material text-center">--}}
                        @if ($errors->has('g-recaptcha-response'))
                            <div class="text-danger">{{ $errors->first('g-recaptcha-response') }}</div>
                        @endif
                    </div>
                    <div class="form-group terms-conditions text-center">

                        <label for="register-agree">
                            <a href="#" style="text-decoration: none" onclick="terminos_condiciones()">
                                Terminos y Condiciones
                            </a>
                        </label>
                    </div>
                    <div class="form-group text-center">
                        <button id="register" type="submit" class="btn btn-primary" disabled> Registrar <span id="msg_regitrar"> (Acepta los terminos y condiciones para registrate)</span></button>
                    </div>
                </form>
                <small>¿Ya tienes una cuenta? </small><a href="{{'login/incio'}}" class="signup">Login</a>
            </div>
            <div class="copyrights text-center">
                <p>Design by <a href="#" class="external">Obrian Villasmil</a></p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_page_js')
    @include('registro.script')
@endsection