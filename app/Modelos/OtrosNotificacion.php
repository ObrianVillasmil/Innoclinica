<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class OtrosNotificacion extends Model
{
    protected $table= "otros_notificacion";

    protected $primaryKey = "id_otros_notificacion";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_notificacion',
        'texto',
        'fecha_registro'
    ];

    public function notificacion(){
        return $this->belongsTo('App\Modelos\Notificacion','id_notificacion');
    }
}
