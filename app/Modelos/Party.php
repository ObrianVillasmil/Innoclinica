<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    protected $table= "party";

    protected $primaryKey = "party_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'party_type_id',
        'first_name',
        'last_name',
        'birth_date',
        'nacionalidad',
        'personal_title',
        'created_by_user_login'
    ];

    public function party_role(){
        return $this->belongsTo('App\Modelos\PartyRole','party_id');
    }

    public function party_roles(){
        return $this->hasMany('App\Modelos\PartyRole','party_id');
    }

    public function party_type(){
        return $this->belongsTo('App\Modelos\PartyType','party_type_id');
    }

    public function person(){
        return $this->belongsTo('App\Modelos\Person','party_id');
    }

    public function identification(){
        return $this->belongsTo('App\Modelos\PartyIdentification','party_id');
    }

    public function party_contact_mech(){
        return $this->hasMany('App\Modelos\PartyContactMech','party_id');
    }

    public function user_login(){
        return $this->hasMany('App\Modelos\UserLogin','party_id');
    }

    public function party_relationship(){
        return $this->belongsTo('App\Modelos\PartyRelationship','party_id');
    }

    public function tratamiento_solicitado(){
        return TratamientoSolicitado::where('party_id',$this->party_id)->first();
    }

    public function firma(){
        return $this->hasOne('App\Modelos\FirmaDigital','party_id');
    }

    public function inventory_item(){
        return $this->hasMany('App\Modelos\InventoryItem','party_id');
    }

    public function roles(){

        $roles=[];
        foreach ($this->party_roles as $party_rol) {
            $roles[] = $party_rol->role_type_id;
        }
        return $roles;

    }

}
