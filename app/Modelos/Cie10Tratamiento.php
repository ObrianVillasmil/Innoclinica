<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Cie10Tratamiento extends Model
{
    protected $table= "cie10_tratamiento";

    protected $primaryKey = "id_cied10_tratamiento";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_tratamiento',
        'id_cie10',
        'fecha_registro'
    ];

    public function tratamiento(){
        return $this->belongsTo('App\Modelos\Tratamiento','id_tratamiento');
    }

    public function cie10(){
        return $this->belongsTo('App\Modelos\Cie10','id_cie10');
    }
}
