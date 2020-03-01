<div class="">
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
                    <label for="inputEmail4">Usuario (Correo electrónico)</label>
                    <input type="email" class="form-control" id="correo" value="" name="correo" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nombres">Nombres</label>
                    <input type="text" class="form-control" id="nombres"  name="nombres" value="" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="apellidos">Apellidos</label>
                    <input type="text" class="form-control" id="apellidos" name="apellidos" value="" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" value="" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="tipo_identificacion">Tipo identificación</label>
                    <select class="form-control" id="tipo_identificacion" name="tipo_identificacion" required>
                        @foreach($tipoIdentificacion as $tI)
                            <option value="{{$tI->party_identification_type_id}}">
                                {{ucfirst(strtolower($tI->description))}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="identificacion">Identificación</label>
                    <input type="text" class="form-control" id="identificacion" name="identificacion" value="" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="pais">País</label>
                <select class="form-control" id="pais" name="pais" required>
                    @foreach($pais as $p)
                        <option value="{{$p->geo_id}}">{{$p->geo_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="nacionalidad">Nacionalidad</label>
                <input type="text" class="form-control" value="" id="nacionalidad" name="nacionalidad" required>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6">
                <label for="telefono">Teléfono</label>
                <input type="number" class="form-control" id="telefono" value="" name="telefono" required>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" value="" name="fecha_nacimiento" required>
                </div>
            </div>
            <div class="col-md-6">
                <label for="contrsena">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" required>
            </div>
            <div class="col-md-6">
                <label for="rol">Seleccione el rol</label>
                <select id="rol" name="rol"class="form-control" required>
                    @foreach($roles as $rol)
                        <option value="{{$rol->role_type_id}}">{{$rol->description}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="text-center" style="margin-top:20px">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fa fa-ban" aria-hidden="true"></i> Cerrar
            </button>
            <button type="button" class="btn btn-primary" onclick="storeDatosUsuario()">
                <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
            </button>
        </div>
    </form>
</div>
@section('custom_page_js')
    @include('usuario.script')
@endsection