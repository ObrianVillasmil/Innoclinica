<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class DetalleDistribucionTratamiento extends Model
{
    protected $table= "detalle_distribucion_tratamiento";

    protected $primaryKey = "id_detalle_distribucion_tratamiento";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_distribucion_tratamiento',
        'cantidad_aplicacion',
        'fecha_registro',
        'intervalo',
        'cantidad_intervalo',
        'product_id'
    ];

    public function distribucion_tratamiento(){
        return $this->belongsTo('App\Modelos\DistribucionTratamiento','id_distribucion_tratamiento');
    }

    public function producto(){
        return $this->belongsTo('App\Modelos\Productos','product_id');
    }
}
