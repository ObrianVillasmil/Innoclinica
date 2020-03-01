<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class PartyIdentification extends Model
{
    protected $table= "party_identification";
    protected $primaryKey = "party_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'party_id',
        'party_identification_type_id',
        'id_value'
    ];

    public function tipo_identificacion(){
        return $this->belongsTo('App\Modelos\PartyIdentificationType','party_identification_type_id');
    }
}
