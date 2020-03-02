<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>@yield('title')</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="all,follow">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Bootstrap CSS-->
        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
        <!-- Font Awesome CSS-->
        <link rel="stylesheet" href="{{asset('font-awesome/css/font-awesome.min.css')}}">
        <!-- Fontastic Custom icon font-->
        <link rel="stylesheet" href="{{asset('css/fontastic.css')}}">
        <!-- Google fonts - Roboto -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
        <!-- jQuery Circle-->
        <link rel="stylesheet" href="{{asset('css/grasp_mobile_progress_circle-1.0.0.min.css')}}">
        <!-- Custom Scrollbar-->
        <link rel="stylesheet" href="{{asset('js/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css')}}">
        <!-- theme stylesheet-->
        <link rel="stylesheet" href="{{asset('css/style.default.css')}}" id="theme-stylesheet">
        <!-- chart-->
        <link rel="stylesheet" href="{{asset('css/Chart.css')}}">
        <!-- Custom stylesheet - for your changes-->
        <link rel="stylesheet" href="{{asset('css/custom.css')}}">
        <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->

        <script src="{{asset('js/jquery.min.js')}}"></script>
    </head>
    <body>
    <div class="loader"></div>
        <!-- Side Navbar -->
        @include('layouts.partials.aside')
        <div class="page">
            @include('layouts.partials.header')
            <section class="dashboard-counts section-padding">
                <section class="content-header">
                    @include('layouts.partials.breadcrumb')
                </section>
            </section>
            <div class="col-md-12">
                @include('flash::message')
                @yield('contenido')
            </div>
            @include('layouts.partials.footer')
            @include('layouts.partials.modal')
            @include('layouts.partials.bot')
        </div>
        <!-- JavaScript files-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script src="{{asset('js/popper.js/umd/popper.js')}}"></script>
        <script src="{{asset('js/bootstrap.min.js')}}"></script>
        <script src="{{asset('js/chart.js/Chart.js')}}"></script>
        <script src="{{asset('js/grasp_mobile_progress_circle-1.0.0.min.js')}}"></script>
        <script src="{{asset('js/jquery.cookie/jquery.cookie.js')}}"></script>
        <script src="{{asset('js/chart.js/Chart.min.js')}}"></script>
        <script src="{{asset('js/jquery-validation/jquery.validate.min.js')}}"></script>
        <script src="{{asset('js/jquery.validate.js')}}"></script>
        <script src="{{asset('malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js')}}"></script>
        <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
        <!-- Main File-->

        <script src="{{asset('js/front.js')}}"></script>
        <script src="{{asset('js/helper.js')}}"></script>
        <script src="{{asset('ckeditor/ckeditor.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/1.0.9/push.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

        @yield('custom_page_js')
        <script>
            load("hide");

            @if(session('log'))
                notificacionLogin('{{getUserLogin(session('party_id'))->party->person->first_name}} {{getUserLogin(session('party_id'))->party->person->last_name}}','{{getConfiguracionEmpresa()->nombre_empresa}}');
            @endif
            var activa = '{{session('activa')}}';


            @if(in_array('ADMIN',$usuario->roles()))
                activa_alerta();
            @endif

            function activa_alerta() {

                inicio = '{{session('inicio')}}';
                fin = '{{microtime(true)}}';

                tiempo = fin-inicio;
                if(tiempo > activa){
                    notificacion_alerta();
                    @php Illuminate\Support\Facades\Session::put('activa',(microtime(true)-session('inicio')+300)) @endphp
                }

            }

            function notificacion_alerta() {
                cant = '{{getNotificacionesNoVistas()}}';
                if(cant>0){
                    Push.create("Tienes alertas no vistas", {
                        body: "Haz clic en esta notificiación para ir hasta las alertas",
                        icon: '/imagenes/notificacion.png',
                        link: "alerta",
                        timeout: 60000,
                        onClick: function () {
                            window.location.href= '{{'alerta'}}';
                            this.close();
                        }
                    });
                }
            }

        </script>
    </body>
</html>