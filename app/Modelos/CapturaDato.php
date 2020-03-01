<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class CapturaDato extends Model
{
    protected $table= "captura_dato";

    protected $primaryKey = "id_captura_dato";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_registro',
        'role_type_id',
        'solicitud_tratamiento',
        'notifica_doctor',
        'icono'
    ];

    public function detalle_captura_dato(){
        return $this->hasMany('App\Modelos\DetalleCapturaDato','id_captura_dato');
    }

    public function party_role(){
        return $this->belongsTo('App\Modelos\RoleType','role_type_id');
    }

    public function tratamiento(){
        return ProcesoTratamiento::where([
            ['id_proceso',$this->id_captura_dato],
            ['id_sub_menu',getSubMenuByPath('captura_dato')->id_sub_menu]
        ])->select('id_tratamiento')->get();
    }
}
