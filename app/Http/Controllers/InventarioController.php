<?php

namespace App\Http\Controllers;

use App\Modelos\DetalleDistribucionTratamientoDoctor;
use App\Modelos\Lote;
use App\Modelos\Productos;
use App\Modelos\TratamientoSolicitado;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use DB;

class InventarioController extends Controller
{
    public  function inicio(Request $request)
    {
        return view('inventario.inicio', [
            'url' => $request->path(),
            'titulo' => ['titulo' => 'Distribución de inventario', 'sub_titulo' => 'Por tratamiento de paciente'],
            'usuario' => getParty(session('party_id')),
            'productos' => Productos::orderBy('product_name', 'asc')
                ->join('product_category_member as pcm','product.product_id','pcm.product_id')
                ->where('pcm.product_category_id','CAT_TRATAMIENTOS')->get(),
            'lotes' => Lote::orderBy('created_stamp', 'desc')->get(),
            'tratamientoSolicitados' => TratamientoSolicitado::where('estado', 1)->get()
        ]);
    }

    public function desgloseEntregas(Request $request){

        $tratamientoSolicitados = TratamientoSolicitado::find($request->id_tratamiento_solicitado);

        return view('inventario.partials.desglose_entregas');
    }

    public function exportarDistribucionMedicacion(){

        $tratamientoSolicitados= TratamientoSolicitado::where('estado', 1)->get();

        Excel::create('Distribución medicación', function($excel) use ($tratamientoSolicitados){

            $excel->sheet('Hoja', function($sheet) use ($tratamientoSolicitados){

                $x = 1;
                $sheet->setStyle([
                    'font' => [
                        'name' => 'Calibri',
                        'size' => 12,
                    ]
                ]);

                $sheet->freezeFirstRow();

                $sheet->cell($x, function($row) {
                    $row->setFont(['bold'=>  true]);
                    $row->setAlignment('center');
                });

                if(count($tratamientoSolicitados) >0){
                    $sheet->row($x, [
                        'TRATAMIENTO',
                        'CLIENTE',
                        'PRODUCTO',
                        'REQUERIDOS',
                        'ENTREGADOS',
                        'IMPORTADOS',
                        'CÓDIGO DE IMPORTACIÓN'
                    ]);


                    foreach($tratamientoSolicitados as $ts)
                        foreach($ts->detalle_tratamiento_doctor as $dtd)
                            foreach($dtd->datos_cotizacion() as $datos_cotizacion)
                                foreach($datos_cotizacion as $data){
                                    $sheet->row($x+1, [
                                        $ts->tratamiento->nombre_tratamiento,
                                        $ts->person->first_name." ".$ts->person->last_name,
                                        getProducto($data['product_id'])->product_name,
                                        $data['cantidad'],
                                        getProductosEntregados($data['product_id'],$ts->party_id),
                                        getProductosImportados($data['product_id'],$dtd->codigo_importacion),
                                        $dtd->codigo_importacion
                                    ]);

                                    $sheet->row($x+1, function($row) {
                                        $row->setAlignment('center');
                                    });

                                    $x++;
                                }
                }else{
                    $sheet->row($x, [
                        'No se econtraron registros'
                    ]);
                }

            });
        })->export('xlsx');
   }

    public function proyeccionProductoInventario(Request $request){

        $data = DetalleDistribucionTratamientoDoctor::where('product_id',$request->product_id)
            ->select(
                DB::raw('min(fecha_aplicacion) as fecha_minima'),
                DB::raw('max(fecha_aplicacion) as fecha_maxima'))->first();

        $fechaMinima= isset($data->fecha_minima) ? $data->fecha_minima : now()->startOfMonth()->toDateString();
        $fechaMaxima= isset($data->fecha_maxima) ? $data->fecha_maxima : now()->endOfMonth()->toDateString();

        $difereciaMeses = Carbon::parse($fechaMaxima)->diffInMonths($fechaMinima);

        $data=[];
        for($x=0;$x<=$difereciaMeses;$x++){

            $detDistTraDoc = DetalleDistribucionTratamientoDoctor::where('product_id',$request->product_id)
                ->whereBetween('fecha_aplicacion',
                    [
                        Carbon::parse($fechaMinima)->startOfMonth()->toDateString(),
                        Carbon::parse($fechaMinima)->endOfMonth()->toDateString()
                    ])->select('fecha_aplicacion','cantidad_aplicacion')->get();

            foreach ($detDistTraDoc as $item)
                $data[Carbon::parse($item->fecha_aplicacion)->format('Y-m')][]= $item->cantidad_aplicacion;

            $fechaMinima = Carbon::parse($fechaMinima)->addMonth();

        }


        $producto = Productos::orderBy('product_name', 'asc')->where('product.product_id',$request->product_id)
            ->join('product_category_member as pcm','product.product_id','pcm.product_id')
            ->where('pcm.product_category_id','CAT_TRATAMIENTOS')->first();

        $totalInventario = $producto->inventario()->total_disponible;

        $dataSet = [];
        $labels=[];
        $negativos=[];
        $x =1;
        $acumula=0;

        foreach($data as $label => $d){
            $labels[]=$label;
            $dataSet[]=array_sum($d);
            $parcial=0;
            foreach ($d as $cantidad) {
                if($x==1)
                    $acumula+=$cantidad;

                if($acumula > $totalInventario){
                    if($x==1)
                        $parcial=$totalInventario-$acumula;

                    for($y=1;$y<=$cantidad;$y++){
                        $parcial++;
                    }
                    $x++;
                }else{
                    $parcial=0;
                }

            }
            $negativos[] = $parcial*(-1);

        }

         return [
             'negativos' =>$negativos,
             'labels'=>$labels,
             'data' => $dataSet,
             'producto'=>$producto->product_name
         ];
    }

}
