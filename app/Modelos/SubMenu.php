<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model
{
    protected $table= "sub_menu";

    protected $primaryKey = "id_sub_menu";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_menu',
        'nombre',
        'icon',
        'path'
    ];

    public function menu(){
        return $this->belongsTo('app\Modelos\Menu' ,'id_menu');
    }
}
