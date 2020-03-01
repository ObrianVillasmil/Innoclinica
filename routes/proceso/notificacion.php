<?php

    Route::get('notificacion', 'NotificacionController@inicio');
    Route::get('notificacion/add_notificacion/{id_notificacion?}', 'NotificacionController@addNotificacion');
    Route::post('notificacion/store_notificacion', 'NotificacionController@storeNotificacion');
    Route::get('notificacion/partials_otros', 'NotificacionController@partialsOtros');
    Route::post('notificacion/delete_notificacion', 'NotificacionController@deleteNotificacion');
