<?php

    Route::get('tratamiento','TratamientoController@inicio');
    Route::get('tratamiento/add_tratamiento/{id_tratamiento?}','TratamientoController@addTratamiento');
    Route::post('tratamiento/sotre_tratamiento','TratamientoController@storeTratamiento');
    Route::get('tratamiento/add_procesos_tratamiento/{id_tratamiento}','TratamientoController@addProcesosTratamiento');
    Route::post('tratamiento/sotre_proceso_tratamiento','TratamientoController@storeProcesoTratamiento');
    Route::post('tratamiento/updates_estado_tratamiento','TratamientoController@updateEstadoTratamiento');
    Route::post('tratamiento/delete_tratamiento','TratamientoController@deleteTratamiento');
    Route::get('tratamiento/form_distribucion_tratamiento','TratamientoController@formDistribucionTratamiento');
    Route::get('tratamiento/input_distribucion_tratamiento','TratamientoController@inputDistribucionTratamiento');
    Route::get('tratamiento/formato_distribucion_tratamiento','TratamientoController@formatoDistribucionTratamiento');
    Route::get('tratamiento/listado_enfermedades',function (){ return getEnfermedades(); });
    Route::post('tratamiento/store_distribucion_tratamiento','TratamientoController@storeDistribucionTratamiento');
    Route::get('tratamiento/visualizarTratamiento/{id_tratamiento}','TratamientoController@visualizarTratamiento');

