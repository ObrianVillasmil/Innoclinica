<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table= "person";

    protected $primaryKey = "party_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'party_id',
        'first_name',
        'last_name',
        'birth_date',
        'nacionalidad',
        'personal_title'
    ];

    public function party(){
        return $this->belongsTo('App\Modelos\Party','party_id');
    }

    public function party_role(){
        return $this->belongsTo('App\Modelos\PartyRole','party_id');
    }
}
