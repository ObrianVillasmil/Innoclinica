<?php
    Route::get('carpeta','CarpetaController@inicio');
    Route::get('carpeta/add_carpeta/{carpeta?}','CarpetaController@addCarpeta');
    Route::post('carpeta/store_carpeta','CarpetaController@storeCarpeta');
    Route::post('carpeta/delete_carpeta','CarpetaController@deleteCarpeta');