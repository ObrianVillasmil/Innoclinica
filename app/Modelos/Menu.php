<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table= "menu";

    protected $primaryKey = "id_menu";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'icon',
        'fecha_registro',
        'path'

    ];

    public function subMenu(){
        return $this->hasMany('App\Modelos\SubMenu' ,'id_menu')->orderBy('nombre','asc');
    }

}
