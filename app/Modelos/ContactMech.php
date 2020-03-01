<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class ContactMech extends Model
{
    protected $table= "contact_mech";

    protected $primaryKey = "contact_mech_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'contact_mech_type_id',
        'info_string'
    ];

    public function contact_mech_type(){
       return $this->belongsTo('App\Modelos\ContactMechType','contact_mech_type_id');
    }

    public function telecom_number(){
        return $this->belongsTo('App\Modelos\TelecomNumber','contact_mech_id');
    }

    public function posta_address(){
        return $this->belongsTo('App\Modelos\PostalAddress','contact_mech_id');
    }

    public function party_contact_mech(){
        return $this->belongsTo('App\Modelos\PartyContactMech','contact_mech_id');
    }

}
