<?php

    Route::get('captura_dato','CapturaDatosController@inicio');
    Route::get('captura_dato/add_captura_dato/{id_captura_dato?}','CapturaDatosController@addCapturaDatos');
    Route::get('captura_dato/add_campo','CapturaDatosController@addCampo');
    Route::post('captura_dato/store_captura_datos','CapturaDatosController@storeCapturaDato');
    Route::post('captura_dato/delete_captura_datos','CapturaDatosController@deleteCapturaDato');
