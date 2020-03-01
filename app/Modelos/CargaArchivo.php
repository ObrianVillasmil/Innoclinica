<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;
use App\Modelos\SubMenu;
use App\Modelos\ProcesoTratamiento;

class CargaArchivo extends Model
{
    protected $table= "carga_archivo";

    protected $primaryKey = "id_carga_archivo";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'carpeta',
        'fecha_registro',
        'role_type_id',
        'id_notificacion',
        'notificacion_doctor',
        'solicitud_tratamiento',
        'icono'
    ];

    public function party_role(){
        return $this->belongsTo('app\Modelos\RoleType','role_type_id');
    }

    public function notificacion(){
        return $this->belongsTo('app\Modelos\Notificacion','id_notificacion');
    }

    public function tratamiento(){
        $cargaArchivo = SubMenu::where('path','carga_archivo')->first();
        return ProcesoTratamiento::where([
            ['id_proceso',$this->id_carga_archivo],
            ['id_sub_menu',$cargaArchivo->id_sub_menu]
        ])->select('id_tratamiento')->get();
    }
}
