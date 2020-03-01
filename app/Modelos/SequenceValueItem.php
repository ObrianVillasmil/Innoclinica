<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class SequenceValueItem extends Model
{
    protected $table= "sequence_value_item";

    protected $primaryKey = "seq_name";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'seq_name',
        'seq_id',
        'created_stamp',
    ];
}
