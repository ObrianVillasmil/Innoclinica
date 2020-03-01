<?php

    Route::get('documento_consolidado','DocumentoConsolidadoController@inicio');
    Route::get('documento_consolidado/configuracion/{id_tratamiento}','DocumentoConsolidadoController@configuracionDocumentoConsolidado');
    Route::post('documento_consolidado/store_configuracion','DocumentoConsolidadoController@storeConfiguracionDocumentoConsolidado');