<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $table= "inventory_item";

    protected $primaryKey = "inventory_item_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'party_id',
        'facility_id',
        'lot_id',
        'quantity_on_hand_total',
        'aviable_to_promise_total',
        'available_to_promise_total',
        'unit_cost',
        'currency_uom_id',
    ];

    public function inventory_item_detail(){
        return $this->hasMany('App\Modelos\inventory_item_detail','inventory_item_id');
    }


    public function producto(){
        return $this->belongsTo('App\Modelos\Productos','product_id');
    }
    /*public function inventory_item_detail_compras(){

        $a = $this->inventory_item_detail->where([
            ['receipt_id','!=',''],
            ['available_to_promise_diff','>',0],
            ['quantity_on_hand_diff','>',0]
        ])->count();

        dd($a);
    }*/

}
