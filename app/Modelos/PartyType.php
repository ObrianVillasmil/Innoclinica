<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class PartyType extends Model
{
    protected $table= "party_type";

    protected $primaryKey = "party_type_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'parent_type_id',
        'description'
    ];
}
