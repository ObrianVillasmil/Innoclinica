<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class PartyIdentificationType extends Model
{
    protected $table= "party_identification_type";

    protected $primaryKey = "party_identification_type_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'parent_type_id',
        'has_table',
        'description'
    ];
}
