<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('prueba','PruebaController@prueba');

Route::get('login/incio', 'LoginController@index');
Route::post('login', 'LoginController@login');
Route::get('logout', 'LoginController@logout');
Route::get('reset_password', 'LoginController@reiniciarContrasena');
Route::post('reset_pass', 'LoginController@restPass');
Route::get('registro', 'LoginController@registro');
Route::get('terminos_condiciones', 'LoginController@terminosCondiciones');
Route::post('registro/add_registro', 'LoginController@storeRegistro');
Route::get('autenticar/{party_id}/{token}','LoginController@autenticarUsuario');
Route::get('iconos','LoginController@iconos');


Route::group(['middleware' => 'Auth'], function () {

    Route::get('autenticar/verificar_rol','LoginController@verificarRol');
    Route::get('autenticar/add_rol','LoginController@addRol');
    Route::post('autenticar/store_rol','LoginController@storeRol');
    Route::get('/', 'DashboardController@index');
    Route::get('usuario/perfil/{party_id?}','UsuarioController@perfilUsuario');
    Route::get('alerta/documento_administrador/{id_documento}','AlertaController@cuerpoDocumento');
    Route::get('cotizacion/solicitar_cotizacion','CotizacionController@solicitarCotizacion');
    Route::post('cotizacion/crear_cotizacion','CotizacionController@crearCotizacion');
    Route::post('usuario/actualizar_datos_usuario','UsuarioController@actualizarDatosUsuario');
    Route::post('usuario/actualizar_contrasena_usuario','UsuarioController@actualizarContrasenaDatosUsuario');
    include('bot/rutas.php');

    Route::group(['middleware' => 'Administrador'], function () {
        include('configuracion/menu.php');
        include('configuracion/empresa.php');
        include('configuracion/tratamiento.php');
        include('configuracion/carpeta.php');
        include('configuracion/permiso_alerta.php');
        include('configuracion/documento_consolidado.php');
        include('configuracion/administracion_bot.php');
    });

    Route::group(['middleware' => 'Permiso'], function () {
        include('tratamiento_cliente/tratamiento_cliente.php');
        include('proceso/carga_archivo.php');
        include('proceso/documento.php');
        include('proceso/notificacion.php');
        include('proceso/distribucion_tratamiento.php');
        include('alertas/alerta.php');
        include('proceso/capruta_datos.php');
        include('configuracion/usuario.php');
        include('tratamiento_cliente/seguimiento.php');
        include('inventario/distribucion.php');
        include('proceso/cotizacion.php');
    });

});
