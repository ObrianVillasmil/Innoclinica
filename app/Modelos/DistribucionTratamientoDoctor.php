<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class DistribucionTratamientoDoctor extends Model
{
    protected $table= "distribucion_tratamiento_doctor";

    protected $primaryKey = "id_distribucion_tratamiento_doctor";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_detalle_tratamiento_doctor',
        'intervalo',
        'cantidad_intervalo',
        'fecha_registro',
        'cantidad_aplicacion',
        'nuevo'
    ];

    public function detalle_distribucion_tratamiento(){
        return $this->hasMany('App\Modelos\DetalleDistribucionTratamientoDoctor','id_distribucion_tratamiento_doctor')/*->orderBy('id_detalle_distribucion_tratamiento_doctor','asc')*/;
    }

    public function detalle_tratamiento_doctor(){
        return $this->belongsTo('App\Modelos\DetalleTratamientoDoctor','id_detalle_tratamiento_doctor');
    }

}
