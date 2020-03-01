<?php

    Route::get('carga_archivo','CargaArchivoController@inicio');
    Route::get('carga_archivo/add_carga_archivo/{id_carga_archivo?}','CargaArchivoController@addCargaArchivo');
    Route::post('carga_archivo/store_carga_archivo','CargaArchivoController@storeCargaArchivo');
    Route::post('carga_archivo/delete_carga_archivo','CargaArchivoController@deleteCargaArchivo');