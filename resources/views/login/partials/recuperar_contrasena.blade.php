@extends('layouts.partials.login')
@section('title')
    Login
@endsection

@section('contenido')
    <div class="page login-page" style="background: url({{isset(getConfiguracionEmpresa()->img_fondo_login) ? '/storage/'.getConfiguracionEmpresa()->img_fondo_login : ""}});background-repeat: no-repeat;">
        <div class="container">
            <div class="form-outer text-center d-flex align-items-center">
                <div class="form-inner">
                    <div class="logo text-uppercase"><span>Innforam</span></div>
                    <p>Ingrese el correo usado cuando creo su cuenta, le será enviada una nueva contraseña provisional la cual podrá cambiar desde la sección de perfil</p>
                    <p>@include('flash::message')</p>
                    <form method="post" class="text-left form-validate" action="{{url('reset_pass')}}">
                        {{@csrf_field()}}
                        <div class="form-group-material">
                            <input id="correo" type="text" value="{{old('correo')}}" name="correo" required data-msg="Ingrese su nombre de correo" class="input-material">
                            <label for="correo" class="label-material">Correo</label>
                            @if($errors->has('correo'))
                                <div class="text-danger">{{ $errors->first('correo') }}</div>
                            @endif
                        </div>
                        <div class="form-group-material text-center">
                            {!! NoCaptcha::display() !!}
                            {{--<input id="captcha" type="text" name="captcha" autocomplete="off" placeholder="Ingrese el código mostrado" required data-msg="Ingrese el código mostrado" class="input-material text-center">--}}
                            @if ($errors->has('g-recaptcha-response'))
                                <div class="text-danger">{{ $errors->first('g-recaptcha-response') }}</div>
                            @endif
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" id="login" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Enviar</button>
                        </div>
                    </form>
                    <small>¿Ya tienes una cuenta? </small>
                    <a href="{{'login/incio'}}" class="signup">Login</a><br />
                    <small>¿No tienes una cuenta? </small>
                    <a href="{{url('registro')}}" class="signup">Creala</a>
                </div>
                <div class="copyrights text-center">
                    <p>Design by <a href="#" class="external">Obrian Villasmil</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection