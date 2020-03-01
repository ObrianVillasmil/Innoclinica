<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class TratamientoSolicitado extends Model
{
    protected $table= "tratamiento_solicitado";

    protected $primaryKey = "id_tratamiento_solicitado";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_tratamiento',
        'party_id',
        'proceso_actual',
        'estado',
        'id_doctor',
        'fecha_registro',
        'fecha_inicio',
        'tratamiento_solicitado'
    ];

    public function tratamiento(){
        return $this->belongsTo('App\Modelos\Tratamiento','id_tratamiento');
    }

    public function person(){
        return $this->belongsTo('App\Modelos\Person','party_id');
    }

    public function doctor(){
        return $this->belongsTo('App\Modelos\Person','id_doctor');
    }

    public function carga_archivo_cliente(){
        return $this->hasMany('App\Modelos\CargaArchivoCliente','id_tratamiento_solicitado');
    }

    public function otros_documentos(){
        return $this->hasMany('App\Modelos\DocumentoTratamientoSolicitado','id_tratamiento_solicitado');
    }

    public function detalle_tratamiento_doctor(){
        return $this->hasMany('App\Modelos\DetalleTratamientoDoctor','id_tratamiento_solicitado');
    }

}


