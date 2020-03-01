<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class DocumentoTratamientoSolicitado extends Model
{
    protected $table= "documento_tratamiento_solicitado";

    protected $primaryKey = "id_documento_tratamiento_solicitado";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_tratamiento_solicitado',
        'nombre',
        'fecha_registro'
    ];

    public function tratamiento_solicitado(){
        return $this->belongsTo('App\Modelos\TratamientoSolicitado','id_tratamiento_solicitado')->where('estado',1);
    }
}
