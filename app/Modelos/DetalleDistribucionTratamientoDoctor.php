<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class DetalleDistribucionTratamientoDoctor extends Model
{
    protected $table= "detalle_distribucion_tratamiento_doctor";

    protected $primaryKey = "id_detalle_distribucion_tratamiento_doctor";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_distribucion_tratamiento_doctor',
        'cumplido',
        'intervalo',
        'cantidad_intervalo',
        'fecha_registro',
        'cantidad_aplicacion',
        'sitio_aplicacion',
        'comentarios',
        'fecha_aplicacion',
        'fecha_aplicacion_real',
        'product_id'
    ];

    public function distribucion_tratamiento_doctor(){
        return $this->belongsTo('App\Modelos\DistribucionTratamientoDoctor','id_distribucion_tratamiento_doctor');
    }


    public function producto(){
        return $this->belongsTo('App\Modelos\Productos','product_id');
    }

}
