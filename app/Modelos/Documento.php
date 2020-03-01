<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table= "documento";

    protected $primaryKey = "id_documento";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_registro',
        'cuerpo',
        'archivo',
        'role_type_id',
        'icono'
    ];

    public function tratamiento(){
        $documento = SubMenu::where('path','documento')->first();
        return ProcesoTratamiento::where([
            ['id_proceso',$this->id_documento],
            ['id_sub_menu',$documento->id_sub_menu]
        ])->select('id_tratamiento')->get();
    }
}
