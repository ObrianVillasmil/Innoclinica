<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class DocumentoConsolidadoRoleType extends Model
{
    protected $table= "documento_consolidado_role_type";

    protected $primaryKey = "id_documento_consolidado_role_type";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_documento_consolidado',
        'role_type_id',
        'correo',
        'firma',
        'fecha_registro'
    ];

}
