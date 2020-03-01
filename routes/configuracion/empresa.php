<?php

    Route::get('empresa', 'EmpresaController@inicio');
    Route::post('empresa/actualizar_datos_empresa', 'EmpresaController@storeDatosEmpresa');
    Route::post('empresa/actualizar_datos_representante', 'EmpresaController@storeDatosRepresentante');
    Route::post('empresa/actualizar_vizualizacion', 'EmpresaController@storeVisualizacion');
    Route::post('empresa/store_terminos_condiciones', 'EmpresaController@storeTerminosCondiciones');
    Route::post('empresa/store_variables_inventario', 'EmpresaController@storeVariablesInvetario');


