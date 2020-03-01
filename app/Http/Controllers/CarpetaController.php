<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;
use Storage;

class CarpetaController extends Controller
{
    public function inicio(Request $request){
        return view('carpeta.list',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Configuraciones','sub_titulo'=>'Creación de carpetas'],
            'usuario' => getParty((int)session::get('party_id')),
            'carpetas' => scandir(storage_path('app/public/archivos/'))
        ]);
    }

    public function addCarpeta(Request $request, $carpeta = null){

        return view('carpeta.partials.crear',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Proceso','sub_titulo'=>''.$carpeta != null ? 'Editar ' : 'Crear '.' carpeta'],
            'usuario' => getParty((int)session::get('party_id')),
            'carpeta' => $carpeta != null ? $carpeta : null
        ]);

    }

    public function storeCarpeta(Request $request){

        $validar = Validator::make($request->all(), [
            'nombre_carpeta' => 'required',
        ]);

        if (!$validar->fails()) {

            $existDirectorio =false;

            $nombre_carpeta = str_replace(" ","_" ,$request->nombre_carpeta);

            foreach (scandir(storage_path('app/public/archivos/')) as $item)
                if($item === $nombre_carpeta)
                    $existDirectorio = true;

            $success = false;

            if(!$existDirectorio){
                if(Storage::makeDirectory('/public/archivos/'.strtolower($nombre_carpeta))){
                    chmod(storage_path('app/public/archivos/'.strtolower($nombre_carpeta)), 0777);
                    $success = true;
                    $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                         La carpeta se ha creado con exito en el servidor.!
                   </div>';
                }else{
                    $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                                   ha ocurrido un inconveniente al intentar crear la carpeta en el servidor, intente nuevamente.!
                             </div>';
                }
            }else{
                $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                         Ya que existe una carpeta con el mismo nombre, intente con otro nombre.!
                       </div>';
            }

        }
        return [
            'success' =>$success,
            "msg" => $msg
        ];

    }

    public function deleteCarpeta(Request $request){
        $success = false;
        $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                    No se pudo eliminar la carepta, intente nuevamente.!
                </div>';

        if(count(scandir(storage_path('app/public/archivos/'.$request->nombre_carpeta))) > 2){

            $success = false;
            $msg = '<div class="alert alert-danger" role="alert" style="margin: 0">
                        Esta carpeta contiene archivos que son usados en otros procesos de tratamientos por lo cual no puede ser eliminada.!
                    </div>';

        }else{

            if(Storage::deleteDirectory('/public/archivos/'.$request->nombre_carpeta)){
                $success = true;
                $msg = '<div class="alert alert-success" role="alert" style="margin: 0">
                        Se ha eliminado la carpeta con éxito.!
                    </div>';
            }

        }


        return [
            'success' =>$success,
            "msg" => $msg
        ];
    }
}
