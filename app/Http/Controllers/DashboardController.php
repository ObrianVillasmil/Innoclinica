<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modelos\Tratamiento;

class DashboardController extends Controller
{
    public function index(Request $request){

        $dataTratamientos = Tratamiento::get();

        if($dataTratamientos->count() == 0)
            flash('No existen tratamientos disponibles')->error();

        getParty(session('party_id'))->party_role->role_type->role_type_id === "MEDICO_USUARIO"
            ? $tratamientos = Tratamiento::where([['tratamiento.estado',1],['ts.id_doctor',session('party_id')]])->join('tratamiento_solicitado as ts','tratamiento.id_tratamiento','ts.id_tratamiento')->orderBy('nombre_tratamiento','asc')->get()
            : $tratamientos = Tratamiento::where('estado',1)->orderBy('nombre_tratamiento','asc')->get();

        return view('layouts.indicadores.dashboard',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Página de inicio','sub_titulo'=>'Dashboard'],
            'usuario' => getParty(session('party_id')),
            'tratamientos' => $tratamientos
        ]);
    }

}
