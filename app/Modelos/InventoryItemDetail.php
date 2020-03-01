<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class InventoryItemDetail extends Model
{
    protected $table= "inventory_item_detail";

    protected $primaryKey = "inventory_item_detail_seq_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'inventory_item_id',
        'quantity_on_hand_diff',
        'available_to_promise_diff',
        'accounting_quantity_diff',
        'unit_cost',
        'order_id',
        'order_item_seq_id',
        'ship_group_seq_id',
        'shipment_id'
    ];
}
