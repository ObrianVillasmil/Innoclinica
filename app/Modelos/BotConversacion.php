<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class BotConversacion extends Model
{
    protected $table= "bot_conversacion";

    protected $primaryKey = "id_bot_conversacion";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'party_id',
        'texto',
        'fecha_registro',
        'fecha_ingreso',
        'id_sesion',
    ];
}
