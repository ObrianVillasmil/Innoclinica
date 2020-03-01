<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class CorreoDocumentoConsolidado extends Model
{
    protected $table= "correo_documento_consolidado";

    protected $primaryKey = "correo_documento_consolidado";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_documento_consolidado',
        'correo',
        'fecha_registro'
    ];


}
