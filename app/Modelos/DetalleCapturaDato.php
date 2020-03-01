<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class DetalleCapturaDato extends Model
{
    protected $table= "detalle_captura_dato";

    protected $primaryKey = "id_detalle_captura_dato";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_captura_dato',
        'texto',
        'tlf',
        'mail',
        'requerido',
        'label',
        'doctor',
        'id_campo'
    ];

    public function captura_dato(){
        return $this->belongsTo('app\Modelos\CapturaDato','id_captura_dato');
    }
}
