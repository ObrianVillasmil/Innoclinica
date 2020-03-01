<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table= "invoice";

    protected $primaryKey = "invoice_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'invoice_type_id',
        'party_id_from',
        'party_id',
        'role_type_id',
        'statys_id',
        'billing_account_id',
        'contact_mech_id',
        'invoice_date',
        'due_date',
        'paid_date',
        'description'
    ];
}
