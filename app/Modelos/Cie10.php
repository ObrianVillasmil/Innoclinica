<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Cie10 extends Model
{
    protected $table= "cie10";

    protected $primaryKey = "id_cie10";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'descripcion'
    ];
}
