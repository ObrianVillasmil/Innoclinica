<?php

    Route::get('administracion_bot/{pregunta?}/{tema?}','AdministrarBotController@inicio');
    Route::post('administracion_bot/crear_tema','AdministrarBotController@addTema');
    Route::post('administracion_bot/store_tema','AdministrarBotController@storeTema');
    Route::post('administracion_bot/delete_tema','AdministrarBotController@deleteTema');
    Route::post('administracion_bot/add_etiqueta','AdministrarBotController@addEtiqueta');
    Route::post('administracion_bot/store_etiqueta','AdministrarBotController@storeEtiqueta');
    Route::post('administracion_bot/add_pregunta','AdministrarBotController@addPregunta');
    Route::post('administracion_bot/add_etiquetas_tema','AdministrarBotController@addEtiquetasTema');
    Route::post('administracion_bot/store_pregunta','AdministrarBotController@storePregunta');
    Route::post('administracion_bot/eliminar_pregunta','AdministrarBotController@deletePregunta');
    Route::post('administracion_bot/eliminar_etiqueta_pregunta_respuesta','AdministrarBotController@deleteEtiquetaPreguntaRespuesta');

