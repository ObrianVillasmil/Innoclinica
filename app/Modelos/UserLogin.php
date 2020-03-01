<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    protected $table= "user_login";

    protected $primaryKey = "user_login_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'current_password',
        'enabled',
        'created_stamp',
        'party_id',
        'email',
        'token'
    ];

    public function party(){
        return $this->belongsTo('App\Modelos\Party','party_id');
    }
}
