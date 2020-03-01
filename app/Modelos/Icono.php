<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Icono extends Model
{
    protected $table= "icono";

    protected $primaryKey = "id_icono";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'clase',
        'fecha_registro',
    ];
}
