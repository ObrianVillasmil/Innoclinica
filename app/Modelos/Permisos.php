<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Permisos extends Model
{
    protected $table= "permiso";

    protected $primaryKey = "id_permiso";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_rol',
        'id_menu',
    ];

    public function menu(){
        return $this->belongsTo('App\Modelos\Menu','id_menu');
    }
}
