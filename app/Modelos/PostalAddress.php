<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class PostalAddress extends Model
{
    protected $table= "postal_address";

    protected $primaryKey = "contact_mech_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'contact_mech_id',
        'address1',
        'city',
        'country_geo_id',
        'state_province_geo_id'
    ];

}
