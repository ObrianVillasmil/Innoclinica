<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class DetalleTratamientoDoctor extends Model
{
    protected $table= "detalle_tratamiento_doctor";

    protected $primaryKey = "id_detalle_tratamiento_doctor";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_tratamiento_solicitado',
        'id_cie10',
        'justificacion_medica',
        'descripcion_patologica',
        'fecha_registro',
        'codigo_importacion'
    ];

    public function cie10(){
        return $this->belongsTo('App\Modelos\Cie10','id_cie10');
    }

    public function tratamiento_solicitado(){
        return $this->belongsTo('App\Modelos\TratamientoSolicitado','id_tratamiento_solicitado');
    }

    public function distribucion_tratamiento(){
        return $this->hasMany('App\Modelos\DistribucionTratamientoDoctor','id_detalle_tratamiento_doctor')->orderBy('id_distribucion_tratamiento_doctor','asc');
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
