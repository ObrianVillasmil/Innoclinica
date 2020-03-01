<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class RoleType extends Model
{
    protected $table= "role_type";

    protected $primaryKey = "role_type_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'description',
        'role_type_id'
    ];

    public function permisos(){
        return $this->hasMany('App\Modelos\Permisos','id_rol');
    }
}
