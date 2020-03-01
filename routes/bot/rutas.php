<?php
    Route::get('bot/render','BotController@inicio');
    Route::post('bot/responder','BotController@responder');
    Route::get('cotizacion/select_producto','BotController@selectProducto');

