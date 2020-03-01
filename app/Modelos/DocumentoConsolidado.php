<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class DocumentoConsolidado extends Model
{
    protected $table= "documento_consolidado";

    protected $primaryKey = "id_documento_consolidado";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_tratamiento',
        'nombre',
        'fecha_registro',
    ];


    public function tratamiento(){
        return $this->belongsTo('App\Modelos\Tratamiento','id_tratamiento');
    }

    public function documento_solicitado_role_type(){
        return $this->hasMany('App\Modelos\DocumentoConsolidadoRoleType','id_documento_consolidado');
    }

    public function correo_documento_solicitado(){
        return $this->hasMany('App\Modelos\CorreoDocumentoConsolidado','id_documento_consolidado');
    }
}
