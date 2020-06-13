<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class PreguntaRespuesta extends Model
{
    protected $table= "pregunta_respuesta";

    protected $primaryKey = "id_pregunta_respuesta";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_chat_tema',
        'pregunta',
        'respuesta',
        'enlace',
        'accion',
        'abrir_en',
        'fecha_registro'
    ];

    public function tema(){
        return $this->belongsTo('App\Modelos\ChatTema','id_chat_tema');
    }
}
