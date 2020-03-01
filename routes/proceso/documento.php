<?php

    Route::get('documento', 'DocumentoController@inicio');
    Route::post('documento/store_documento', 'DocumentoController@storeDocumento');
    Route::get('documento/ver_documento/{id_documento}', 'DocumentoController@verDocumento');
    Route::get('documento/editar_documento/{id_documento}/{active?}', 'DocumentoController@editarDocumento');
    Route::get('documento/add_documento', 'DocumentoController@addDocumento');
    Route::post('documento/subir_documento', 'DocumentoController@uploadDocumento');
    Route::post('documento/eliminar_documento', 'DocumentoController@deleteDocumento');

