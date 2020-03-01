<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;
use DB;

class Productos extends Model
{
    protected $table= "product";

    protected $primaryKey = "product_id";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'product_type_id',
        'internal_name',
        'brand_name',
        'product_name',
        'is_virtual',
        'is_variant',
        'bill_of_material_leve'
    ];


    public function inventario(){
        return $this->hasMany('App\Modelos\InventoryItem','product_id')
            ->where('quantity_on_hand_total','>',0)
            ->select(
                DB::raw('SUM(quantity_on_hand_total) as total_disponible'),
                DB::raw('SUM(available_to_promise_total) as total_reserva')
            )->first();
    }
}
