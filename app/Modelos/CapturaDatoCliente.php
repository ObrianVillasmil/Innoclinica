<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class CapturaDatoCliente extends Model
{
    protected $table= "captura_dato_cliente";

    protected $primaryKey = "id_captura_dato_cliente";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_captura_dato',
        'id_tratamiento_solicitado',
        'texto1',
        'texto2'
    ];

    public function captura_dato(){
        return $this->belongsTo('app\Modelos\CapturaDato','id_captura_dato');
    }

    public function tratamiento_solicitado(){
        return $this->belongsTo('app\Modelos\TratamientoSolicitado','id_tratamiento_solicitado')->where('estado',1);
    }
}
