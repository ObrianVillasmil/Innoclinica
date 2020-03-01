<?php

    Route::get('distribucion_tratamiento','DistribucionTratamientoController@inicio');
    Route::post('distribucion_tratamiento/store_distribucion_tratamiento_doctor','DistribucionTratamientoController@storeDistribucionTratamientoDoctor');
    Route::post('distribucion_tratamiento/delete_distribucion_tratamiento','DistribucionTratamientoController@deleteDistribucionTratamientoDoctor');