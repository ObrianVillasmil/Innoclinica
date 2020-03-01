<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class LogAdministrador extends Model
{
    protected $table= "log_administrador";

    protected $primaryKey = "id_log_administrador";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_log_administrador',
        'tabla',
        'id_registro_tabla',
        'id_usuario',
        'accion',
        'estado_notificacion',

    ];
}
