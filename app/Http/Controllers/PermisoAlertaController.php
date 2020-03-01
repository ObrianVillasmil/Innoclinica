<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;

class PermisoAlertaController extends Controller
{
    public function inicio(Request $request){

        return view('permiso_alerta.list',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Configuraciones','sub_titulo'=>'Asignación de alertas'],
            'usuario' => getParty((int)session::get('party_id'))
        ]);
    }

    public function crear(Request $request){



    }
}
