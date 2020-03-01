<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modelos\Tratamiento;
use App\Modelos\DistribucionTratamiento;

class DistribucionTratamientoController extends Controller
{
    public function inicio(Request $request){

        $tratmiento = Tratamiento::whereIn('id_tratamiento', function ($query){
            $query->select('id_tratamiento')->from('distribucion_tratamiento');
        })->get();

        return view('distribucion_tratamiento.list',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Procesos','sub_titulo'=>'Formato distribución tratamiento'],
            'usuario' => getParty(session('party_id')),
            'tratamiento' => $tratmiento
        ]);
    }

    public function deleteDistribucionTratamientoDoctor(Request $request){

        $success = false;
        $msg = '<div class="alert alert-danger">' .
            '<p class="text-center">Ha ocurrido un error al eliminar la distribución del tratamiento, intente nuevamente</p>' .
            '<ul>';

        $distribucionTratamiento = DistribucionTratamiento::where('id_tratamiento',$request->id_tratamiento);

        if($distribucionTratamiento->delete()){
            $success = true;
            $msg = '<div class="alert alert-success">' .
                '<p class="text-center">Se ha eliminado la distribución del tratamiento con éxito</p>' .
                '<ul>';
        }
        return [
            'success' =>$success,
            "msg" => $msg
        ];

    }
}
