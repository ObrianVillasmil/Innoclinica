<?php

    Route::get('distribucion_inventario','InventarioController@inicio');
    Route::get('distribucion_inventario/desglose_entrega','InventarioController@desgloseEntregas');
    Route::get('distribucion_inventario/exportar_distribucion','InventarioController@exportarDistribucionMedicacion');
    Route::get('distribucion_inventario/proyeccion_producto_inventario','InventarioController@proyeccionProductoInventario');