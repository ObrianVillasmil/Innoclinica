<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class TipoNotificacion extends Model
{
    protected $table= "tipo_notificacion";

    protected $primaryKey = "id_tipo_notificacion";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'fecha_registro',
    ];
}
