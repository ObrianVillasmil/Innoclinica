<?php

    Route::get('menu', 'MenuController@inicio');
    Route::get('menu/add_menu', 'MenuController@addMenu');
    Route::get('menu/add_sub_menu', 'MenuController@addSubMenu');
    Route::post('menu/sotre_menu', 'MenuController@storeMenu');
    Route::post('menu/delete_menu', 'MenuController@deleteMenu');
    Route::post('menu/sotre_sub_menu', 'MenuController@storeSubMenu');
    Route::post('menu/delete_sub_menu', 'MenuController@deleteSubMenu');
    Route::get('menu/asignar_permisos', 'MenuController@asignarPermisos');
    Route::post('menu/store_permisos', 'MenuController@storePermisos');


