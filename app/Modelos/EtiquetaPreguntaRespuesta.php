<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class EtiquetaPreguntaRespuesta extends Model
{
    protected $table= "etiqueta_pregunta_respuesta";

    protected $primaryKey = "id_etiqueta_pregunta_respuesta";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_pregunta_respuesta',
        'etiqueta',
        'fecha_registro'
    ];

    public function pregunta_respuesta(){
        return $this->belongsTo('App\Modelos\EtiquetaChatTema','id_pregunta_respuesta');
    }
}
