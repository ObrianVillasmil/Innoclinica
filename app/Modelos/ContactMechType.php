<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class ContactMechType extends Model
{
    protected $table= "contact_mech_type";

    protected $primaryKey = "contact_mech_type_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'parent_type_id',
        'description'
    ];

}
