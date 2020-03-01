<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class PartyRole extends Model
{
    protected $table= "party_role";

    protected $primaryKey = "party_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'party_id',
        'role_type_id',
        'status'
    ];

    public function role_type(){
        return $this->belongsTo('App\Modelos\RoleType', 'role_type_id');
    }


}
