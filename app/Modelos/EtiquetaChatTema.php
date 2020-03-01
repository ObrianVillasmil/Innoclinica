<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class EtiquetaChatTema extends Model
{
    protected $table= "etiqueta_chat_tema";

    protected $primaryKey = "id_etiqueta_chat_tema";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_chat_tema',
        'nombre',
        'fecha_registro',
    ];


}
