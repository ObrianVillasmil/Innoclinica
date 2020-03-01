@extends('layouts.partials.login')
@section('title')
    Login
@endsection

@section('contenido')
<div class="page login-page" style="background: url('{{isset(getConfiguracionEmpresa()->img_fondo_login) ? '/storage/'.getConfiguracionEmpresa()->img_fondo_login : ""}}'); background-repeat: no-repeat;    background-size: contain;">
    <div class="container">
        <div class="form-outer text-center d-flex align-items-center">
            <div class="form-inner">
                <div class="logo text-uppercase"><span>{{isset(getConfiguracionEmpresa()->nombre_empresa) ? getConfiguracionEmpresa()->nombre_empresa : ""}}</span><strong class="text-primary">LOGIN</strong></div>
                <p>Ingrese sus datos para el inicio de sesi&oacuten</p>
                <p>@include('flash::message')</p>
                <form method="post" class="text-left form-validate" action="{{url('login')}}">
                    {{@csrf_field()}}
                    <div class="form-group-material">
                        <input id="usuario" type="text" value="{{old('usuario')}}" name="usuario" required data-msg="Ingrese su nombre de usuario" class="input-material">
                        <label for="login-username" class="label-material">Usuario</label>
                        @if ($errors->has('usuario'))
                            <div class="text-danger">{{ $errors->first('usuario') }}</div>
                        @endif
                    </div>
                    <div class="form-group-material">
                        <input id="contrasena" type="password" name="contrasena" required data-msg="Ingrese su contraseña" class="input-material">
                        <label for="login-password" class="label-material">Contraseña</label>
                        @if ($errors->has('contrasena'))
                            <div class="text-danger">{{ $errors->first('contrasena') }}</div>
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
                        <button type="submit" id="login" class="btn btn-primary"> <i class="fa fa-sign-in"></i> Ingresar</button>
                    </div>
                </form>
                <a href="{{url('reset_password')}}" class="forgot-pass">¿Olvidaste tu contraseña?</a>
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