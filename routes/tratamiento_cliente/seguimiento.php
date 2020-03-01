<?php

    Route::get('seguimiento','SeguimientoController@inicio');
    Route::post('seguimiento/firma_pdf','SeguimientoController@firmaDigital');
    Route::get('seguimiento/seguimineto_tratamiento/{id_tratamiento}/{id_solicitante}','TratamientoController@seguimientoTratamiento');
    Route::get('seguimiento/distribucion_tratamiento_solicitado/{id_tratamiento}/{id_solicitante}/{id_doctor?}','TratamientoController@distribucionTratamientoSolicitado');
    Route::post('seguimiento/add_fase_distribucion_tratamiento_seguimiento','TratamientoClienteController@addFaseDistribucionTratamientoSeguimiento');
    Route::post('seguimiento/update_fecha_tratamiento_solicitado','TratamientoClienteController@updateFechaTratamientoSolicitado');
    Route::post('seguimiento/store_distribucion_tratamiento_seguimiento','TratamientoClienteController@storeDistribucionTratamientoSeguimiento');
    Route::post('seguimiento/enviar_correo_documentos','AlertaController@enviarCorreoDocumento');
    Route::post('seguimiento/store_detalle_distribucion_tratamiento_doctor','AlertaController@storeDetalleDistribucionTratamientoDoctor');
    Route::post('seguimiento/eliminar_archivo_documento_tratamiento_solicitado','AlertaController@eliminarArchivoDocumentoTratamientoSolicitado');
    Route::get('seguimiento/agregar_archivo','TratamientoController@agregarArchivoTratamiento');
    Route::post('seguimiento/carga_documento_solicitud_tratamiento','AlertaController@cargarDocumentosSolicitudTratamiento');
    Route::post('seguimiento/store_dato_importacion','SeguimientoController@storeDatoImportacion');