<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table= "notificacion";

    protected $primaryKey = "id_notificacion";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_tipo_notificacion',
        'nombre',
        'fecha_registro',
        'mensaje',
        'administrador',
        'representante_legal',
        'paciente',
        'otros',
        'icono'
    ];

    public function tipo_notificacion(){
        return $this->belongsTo('App\Modelos\TipoNotificacion','id_tipo_notificacion');
    }

    public function otros_notificacion(){
        return $this->hasMany('App\Modelos\OtrosNotificacion','id_notificacion');
    }

    public function tratamiento(){
        $documento = SubMenu::where('path','documento')->first();
        return ProcesoTratamiento::where([
            ['id_proceso',$this->id_documento],
            ['id_sub_menu',$documento->id_sub_menu]
        ])->select('id_tratamiento')->get();
    }
}
