<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table= "especialidad";

    protected $primaryKey = "id_especialidad";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_especialidad',
        'codigo',
        'nombre',
        'fecha_registro'
    ];

    public function tratamientos(){
        return $this->hasMany('App\Modelos\Tratamiento','id_especialidad');
    }
}
