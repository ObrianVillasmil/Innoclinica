<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class DetalleTratamiento extends Model
{
    protected $table= "detalle_tratamiento";

    protected $primaryKey = "id_detalle_tratamiento";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_tratamiento',
        'calculo_intervalo',
        'fecha_registro'
    ];

    public function tratamiento(){
        return $this->belongsTo('App\Modelos\Tratamiento','id_tratamiento');
    }

    public function distribucion_tratamiento(){
        return $this->hasMany('App\Modelos\DistribucionTratamiento','id_detalle_tratamiento');
    }

    public function datos_cotizacion(){

        if(isset($this->distribucion_tratamiento)){
            $arrData =[];
            foreach ($this->distribucion_tratamiento as $dist) {
                $detalles = [];
                foreach ($dist->detalle_distribucion_tratamiento as $detalle_distribucion_tratamiento)
                    $detalles[$detalle_distribucion_tratamiento->id_distribucion_tratamiento][$detalle_distribucion_tratamiento->product_id][] = $detalle_distribucion_tratamiento;

                $arrData[] = array_values($detalles);
            }

            $producto= [];
            foreach ($arrData as $arrDatum)
                foreach ($arrDatum as $item)
                    foreach ($item as $y => $i)
                        $producto[$y][] =$i;

            $valoresProducto=[];

            foreach ($producto as $product_id => $p1) {
                $cantidad = 0;
                foreach ($p1 as $p2) {
                    foreach ($p2 as $p3) {
                        $cantidad += $p3->cantidad_aplicacion;
                    }
                }
                $valoresProducto[] = [
                    'product_id' => $product_id,
                    'cantidad' =>$cantidad
                ];
            }
        }
        return [
            'producto' => $valoresProducto
        ];
    }
}
