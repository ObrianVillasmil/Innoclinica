<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;
use DB;

class Lote extends Model
{
    protected $table= "lot";

    protected $primaryKey = "lot_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'quantity',
        'expiration_date',
        'accounting_quantity_total'
    ];



    public function inventory_item(){
        return InventoryItem::where('lot_id',$this->lot_id)//$this->hasMany('App\Modelos\InventoryItem','lot_id')
            //->where('quantity_on_hand_total','>',0)
            ->select(
                DB::raw('SUM(quantity_on_hand_total) as total_disponible'),
                DB::raw('SUM(accounting_quantity_total) as total_comprado'),
                'inventory_item.product_id'
            )->join('product as p','inventory_item.product_id','p.product_id')
            ->join('product_category_member as pcm','p.product_id','pcm.product_id')
            ->where('pcm.product_category_id','CAT_TRATAMIENTOS')
            ->groupBy('lot_id','inventory_item.product_id')->first();
    }
}
