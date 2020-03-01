<nav class="side-navbar">
    <div class="side-navbar-wrapper">
        <div class="sidenav-header d-flex align-items-center justify-content-center">
            <div class="sidenav-header-inner text-center">
                @if(isset(getConfiguracionEmpresa()->logo_empresa))
                    <a href="{{url('')}}"><img src="/{{getConfiguracionEmpresa()->logo_empresa}}"></a>
                @else
                    <i class="fa fa-user-circle" style="font-size: 50px" aria-hidden="true"></i>
                @endif
                <h2 class="h5">{{$usuario->person->first_name ." ".$usuario->person->last_name}}</h2><span>{{isset($usuario->party_role->role_type->description) ? $usuario->party_role->role_type->description : ""}}</span>
            </div>
            <div class="sidenav-header-logo"><a href="#" class="brand-small text-center"> <strong>B</strong><strong class="text-primary">D</strong></a></div>
        </div>
        <div class="main-menu">
            <h5 class="sidenav-heading">Menú</h5>
            <ul id="side-main-menu" class="side-menu list-unstyled">
                <li><a href="{{url("/")}}"> <i class="icon-home"></i>Inicio</a></li>
                @isset($usuario->party_role)
                    @if($usuario->party_role->role_type->role_type_id === "ADMIN")
                        <li>
                            <a href="#exampledropdownDropdown" aria-expanded="false" data-toggle="collapse">
                                <i class="fa fa-cogs" aria-hidden="true"></i>Configuraciones </a>
                            <ul id="exampledropdownDropdown" class="collapse list-unstyled ">
                                <li>
                                    <a href="{{url('menu')}}">
                                        <i class="fa fa-circle-thin" aria-hidden="true"></i>
                                        Administración menú
                                    </a>
                                </li>
                                <li>
                                    <a href="{{url('administracion_bot')}}">
                                        <i class="fa fa-circle-thin" aria-hidden="true"></i>
                                        Administración bot
                                    </a>
                                </li>
                                <li>
                                    <a href="{{url('carpeta')}}">
                                        <i class="fa fa-circle-thin" aria-hidden="true"></i>
                                        Ceración de carpetas
                                    </a>
                                </li>
                                <li style="white-space: nowrap;">
                                    <a href="{{url('documento_consolidado')}}">
                                        <i class="fa fa-circle-o"></i>
                                        Documentos del tratamiento
                                    </a>
                                </li>
                                <li>
                                    <a href="{{url('empresa')}}">
                                        <i class="fa fa-circle-thin" aria-hidden="true"></i>
                                        Empresa
                                    </a>
                                </li>
                                <li>
                                    <a href="{{url("tratamiento")}}">
                                        <i class="fa fa-circle-thin" aria-hidden="true"></i> Tratamientos
                                    </a>
                                </li>
                                <li>
                                    <a href="{{url("permiso_alerta")}}">
                                        <i class="fa fa-circle-thin" aria-hidden="true"></i> Permisos alertas
                                    </a>
                                </li>

                            </ul>
                        </li>
                        {{--<li><a href="{{url("usuario")}}"><i class="fa fa-user-circle-o"></i> Usuarios</a></li>--}}

                    @endif
                @endisset
                <li><a href="{{url('usuario/perfil')}}"> <i class="fa fa-id-card-o"></i> Perfil</a></li>
                @isset($usuario->party_role)
                    @foreach($usuario->party_role->role_type->permisos as $key => $permiso)
                        @php $menu = getMenu($permiso->id_menu) @endphp
                        @if($menu->subMenu->count() < 1)
                            <li>
                                <a href="{{url($menu->path)}}">
                                    <i class="fa {{$menu->icono}}" aria-hidden="true"></i> {{ucfirst($menu->nombre)}}
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="#exampledropdownDropdown_{{$key+1}}" aria-expanded="false" data-toggle="collapse">
                                    <i class="fa {{$menu->icono}}" aria-hidden="true"></i>{{ucfirst($menu->nombre)}} </a>
                                <ul id="exampledropdownDropdown_{{$key+1}}" class="collapse list-unstyled">
                                    @foreach($menu->subMenu as $sb)
                                        <li>
                                            <a href="{{url($sb->path)}}">
                                                <i class="fa {{$sb->icono}}"></i>
                                                {{$sb->nombre}}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                @endisset
            </ul>
        </div>
        {{--<div class="admin-menu">
            <h5 class="sidenav-heading">Second menu</h5>
            <ul id="side-admin-menu" class="side-menu list-unstyled">
                <li> <a href="#"> <i class="icon-screen"> </i>Demo</a></li>
                <li> <a href="#"> <i class="icon-flask"> </i>Demo
                        <div class="badge badge-info">Special</div></a></li>
                <li> <a href=""> <i class="icon-flask"> </i>Demo</a></li>
                <li> <a href=""> <i class="icon-picture"> </i>Demo</a></li>
            </ul>
        </div>--}}
    </div>
</nav>