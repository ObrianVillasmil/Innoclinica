<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class DistribucionTratamiento extends Model
{
    protected $table= "distribucion_tratamiento";

    protected $primaryKey = "id_distribucion_tratamiento";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_detalle_tratamiento',
        'intervalo',
        'cantidad_intervalo',
        'cantidad_aplicacion',
    ];

    public function detalle_distribucion_tratamiento(){
        return $this->hasMany('App\Modelos\DetalleDistribucionTratamiento','id_distribucion_tratamiento');
    }


    public function detalle_tratamiento(){
        return $this->belongsTo('App\Modelos\DetalleTratamiento','id_detalle_tratamiento');
    }

}

