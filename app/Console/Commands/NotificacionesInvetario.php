<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modelos\Productos;
use App\Modelos\DetalleDistribucionTratamientoDoctor;
use App\Mail\NotificacionInventario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use DB;

class NotificacionesInvetario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificacion:inventario';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifica por correo el estatus del inventario en dependencia de las variables de configuración';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $empresa = getConfiguracionEmpresa();

        if(isset($empresa->intervalo_inventario) && isset($empresa->cantidad_intervalo)) {

            $intervalo = $empresa->intervalo_inventario;
            $cantidadIntervalo = $empresa->cantidad_intervalo;

            if ($intervalo == "D")
                $intervalo = 1;
            elseif($intervalo == "S")
                $intervalo = 7;
            else
                $intervalo = 30;

            $dias = $intervalo*$cantidadIntervalo;

            $productos = Productos::orderBy('product_name', 'asc')
                ->join('product_category_member as pcm','product.product_id','pcm.product_id')
                ->where('pcm.product_category_id','CAT_TRATAMIENTOS')->get();

            foreach ($productos as $producto) {

                $data = DetalleDistribucionTratamientoDoctor::where('product_id',$producto->product_id)
                    ->select(
                        DB::raw('min(fecha_aplicacion) as fecha_minima'),
                        DB::raw('max(fecha_aplicacion) as fecha_maxima'))->first();

                $fechaMinima= isset($data->fecha_minima) ? $data->fecha_minima : now()->startOfMonth()->toDateString();
                $fechaMaxima= isset($data->fecha_maxima) ? $data->fecha_maxima : now()->endOfMonth()->toDateString();

                $diferenciaMeses = Carbon::parse($fechaMaxima)->diffInMonths($fechaMinima);

                $data=[];
                for($x=0;$x<=$diferenciaMeses;$x++){

                    $detDistTraDoc = DetalleDistribucionTratamientoDoctor::where('product_id',$producto->product_id)
                        ->whereBetween('fecha_aplicacion',
                            [
                                Carbon::parse($fechaMinima)->startOfMonth()->toDateString(),
                                Carbon::parse($fechaMinima)->endOfMonth()->toDateString()
                            ])->select('fecha_aplicacion','cantidad_aplicacion')->get();

                    foreach ($detDistTraDoc as $item)
                        $data[Carbon::parse($item->fecha_aplicacion)->format('Y-m')][]= $item->cantidad_aplicacion;

                    $fechaMinima = Carbon::parse($fechaMinima)->addMonth();

                }

                $totalInventario = $producto->inventario()->total_disponible;
                $faltantes=0;
                $x =1;
                $acumula=0;
                $fechaTopeInicial = "";

                foreach($data as $fecha => $d){
                    $parcial=0;
                    foreach ($d as $cantidad) {
                        if($x==1)
                            $acumula+=$cantidad;

                        if($acumula > $totalInventario){
                            if($x==1){
                                $parcial=$totalInventario-$acumula;
                                $fechaTopeInicial = $fecha;
                            }

                            for($y=1;$y<=$cantidad;$y++)
                                $parcial++;


                            $x++;
                        }else{
                            $parcial=0;
                        }

                    }
                    $faltantes+= $parcial;

                }

                $notificacionDesde = Carbon::parse($fechaTopeInicial)->subDays($dias)->format('Y-m');

                if(($faltantes > 0) && (now()->format('Y-m') >= $notificacionDesde) && ($notificacionDesde<=$fechaTopeInicial)){
                    $correoextras = [$empresa->correo1_notificacion_intervalo];

                    if($empresa->correo2_notificacion_intervalo !="")
                        $correoextras[] = $empresa->correo2_notificacion_intervalo;
                    if($empresa->correo3_notificacion_intervalo !="")
                        $correoextras[] = $empresa->correo3_notificacion_intervalo;

                    Mail::to($empresa->correo_empresa)->cc($correoextras)
                        ->send(new NotificacionInventario($fechaTopeInicial,$fecha,$faltantes,$producto->product_id));
                }

            }

        }

    }
}
