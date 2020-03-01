<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class CargaArchivoCliente extends Model
{
    protected $table= "carga_archivo_cliente";

    protected $primaryKey = "id_carga_archivo_cliente";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_tratamiento_solicitado',
        'archivo',
        'carpeta',
        'fecha_registro',
        'id_carga_archivo'
    ];

    public function tratamiento(){
        return $this->belongsTo('app\Modelos\Tratamiento','id_tratamiento');
    }

    public function carga_archivo(){
        return $this->belongsTo('app\Modelos\CargaArchivo','id_carga_archivo');
    }

    public function tratamiento_solicitado(){
        return $this->belongsTo('app\Modelos\TratamientoSolicitado','id_tratamiento_solicitado')->where('estado',1);
    }
}
