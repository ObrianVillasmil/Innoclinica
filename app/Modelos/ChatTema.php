<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class ChatTema extends Model
{
    protected $table= "chat_tema";

    protected $primaryKey = "id_chat_tema";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'fecha_registro',
    ];

    public function etiqueta_chat_tema(){
        return $this->hasMany('App\Modelos\EtiquetaChatTema','id_chat_tema');
    }
    public function pregunta_respuesta(){
        return $this->hasMany('App\Modelos\PreguntaRespuesta','id_chat_tema');
    }

}
