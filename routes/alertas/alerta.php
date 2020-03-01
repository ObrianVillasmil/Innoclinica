<?php
    Route::get('alerta','AlertaController@inicio');
    Route::get('alerta/list_sesion_usuario','AlertaController@listSesionUsuario');
    Route::get('alerta/list_notificaciones','AlertaController@listNotificaciones');
    Route::get('alerta/list_solicitud_tratamiento','AlertaController@listSolicitudTratamiento');
    Route::get('alerta/form_asignar_doctor','AlertaController@formAsignarDoctor');
    Route::get('alerta/asignar_asignar_doctor','AlertaController@formAsignarDoctor');
    Route::post('alerta/store_asignar_doctor','AlertaController@storeAsignarDoctor');
    Route::post('alerta/desactivar_notificacion','AlertaController@desactivarNotificacion');

