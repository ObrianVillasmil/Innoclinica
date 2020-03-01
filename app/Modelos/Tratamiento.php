<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    protected $table= "tratamiento";

    protected $primaryKey = "id_tratamiento";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'nombre_tratamiento',
        'id_especialidad',
        'fecha_registro',
        'icono',
        'estado'
    ];

    public function especialidad(){
        return $this->belongsTo('App\Modelos\Especialidad','id_especialidad');
    }

    public function procesos(){
        return $this->hasMany('App\Modelos\ProcesoTratamiento','id_tratamiento');
    }

    public function distribucion_tratamiento(){
        return $this->hasMany('App\Modelos\DistribucionTratamiento','id_tratamiento');
    }

    public function detalle_tratamiento(){
        return $this->hasOne('App\Modelos\DetalleTratamiento','id_tratamiento');
    }

}
