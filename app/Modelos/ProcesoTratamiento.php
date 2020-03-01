<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class ProcesoTratamiento extends Model
{
    protected $table= "proceso_tratamiento";

    protected $primaryKey = "id_proceso_tratamiento";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_tratamiento',
        'id_sub_menu',
        'id_proceso',
        'fecha_registro'
    ];

    public function sub_menu(){
        return $this->belongsTo('App\Modelos\SubMenu', 'id_sub_menu');
    }


}
