<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class FirmaDigital extends Model
{
    protected $table= "firma_digital";

    protected $primaryKey = "id_firma_digital";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'party_id',
        'imagen',
        'archivo',
        'fecha_registro',
        'contrasena'
    ];

    public function party(){
        return $this->belongsTo('App\Modelos\Party','party_id');
    }
}
