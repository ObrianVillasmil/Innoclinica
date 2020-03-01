<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Geo extends Model
{
    protected $table= "geo";

    protected $primaryKey = "geo_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'geo_type_id',
        'geo_name',
        'geo_code',
        'abbreviation'
    ];
}
