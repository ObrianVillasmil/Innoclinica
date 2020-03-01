<?php

    Route::get('usuario','UsuarioController@inicio');
    Route::post('usuario/actualizar_estado','UsuarioController@actualizarEstado');
    //Route::post('usuario/actualizar_datos_usuario','UsuarioController@actualizarDatosUsuario');
    //Route::post('usuario/actualizar_contrasena_usuario','UsuarioController@actualizarContrasenaDatosUsuario');
    Route::post('usuario/guardar_datos_paciente','UsuarioController@guardarDatosPaciente');
    Route::post('usuario/actualizar_datos_paciente','UsuarioController@actualizarDatosPaciente');
    Route::get('usuario/nuevo','UsuarioController@usuarioNuevo');
    Route::post('usuario/store','UsuarioController@storeUsuario');
    Route::post('usuario/store_firma','UsuarioController@storeFirmaDigital');




