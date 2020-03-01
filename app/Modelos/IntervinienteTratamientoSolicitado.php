<?php

namespace App\Modelos;;

use Illuminate\Database\Eloquent\Model;

class IntervinienteTratamientoSolicitado extends Model
{
    protected $table= "interviniente_tratamiento_solicitado";

    protected $primaryKey = "id_interviniente_tratamiento_solicitado";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_tratamiento_solicitado',
        'party_id',
        'proceso_actual',
        'estado',
        'fecha_registro'
    ];

    public function tratamiento_solicitado(){
        return $this->belongsTo('App\Modelos\TratamientoSolicitado','id_tratamiento_solicitado')->where('esatdo',1);
    }
}
