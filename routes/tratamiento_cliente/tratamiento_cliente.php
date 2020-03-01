<?php

    Route::get('tratamientos_clientes','TratamientoClienteController@inicio');
    Route::get('tratamientos_clientes/proceso_tratamiento/{id_tratamiento}/{id_tratamiento_solicitado?}/{party_id_solicitante?}','TratamientoClienteController@procesoTratamiento');
    Route::get('tratamientos_clientes/verificar_pasos','TratamientoClienteController@verificarPasosTratamiento');
    Route::post('tratamientos_clientes/store_archivo_cliente','TratamientoClienteController@storeArchivoCliente');
    Route::post('tratamientos_clientes/store_notificacion_tratamiento','TratamientoClienteController@storeNotificacionTratamiento');
    Route::post('tratamientos_clientes/eliminar_archivo','TratamientoClienteController@deleteArchivoTratamiento');
    Route::get('tratamientos_clientes/doctores',function (){ return getDoctores(); });
    Route::post('tratamientos_clientes/store_distribucion_tratamiento_doctor','TratamientoClienteController@storeDistribucionTratamientoDoctor');
    Route::post('tratamientos_clientes/delete_fase_distribucion_tratamiento_seguimiento','TratamientoClienteController@deleteFaseDistribucionTratamientoSeguimiento');
    //Route::get('tratamientos_clientes/proceso_tratamiento/{id_tratamiento}','TratamientoClienteController@verProcesoTratamiento');
    Route::post('tratamientos_clientes/store_datos_doctor','TratamientoClienteController@storeDatosDoctor');