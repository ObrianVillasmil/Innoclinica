<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class OrderRole extends Model
{
    protected $table= "order_role";

    protected $primaryKey = "order_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'party_id',
        'role_type_id',
    ];
}
