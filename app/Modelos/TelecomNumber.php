<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class TelecomNumber extends Model
{
    protected $table= "telecom_number";

    protected $primaryKey = "contact_mech_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'contact_mech_id',
        'country_code',
        'contact_number'
    ];
}
