<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class VariableInventario extends Model
{
    protected $table= "variables_inventario";

    protected $primaryKey = "i_variables_inventariod";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'minimo',
        'maximo',
        'intermedio',
        'fecha_registro',
        'esatdo'
    ];
}
