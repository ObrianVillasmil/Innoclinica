<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class PartyContactMech extends Model
{
    protected $table= "party_contact_mech";

    protected $primaryKey = "party_contact_mech_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'party_id',
        'contact_mech_id',
        'role_type_id',
        'from_date'
    ];

    public function contact_mech(){
        return $this->belongsTo('App\Modelos\ContactMech','contact_mech_id');
    }

    public function user_login(){
        return $this->belongsTo('App\Modelos\UserLogin','party_id');
    }

}
