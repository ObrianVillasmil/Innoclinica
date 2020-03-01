<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Response;

class Administrador
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $party = getParty(session('party_id'));
        if(in_array("ADMIN" ,$party->roles()))
            return $next($request);

        return new Response(view('errors.acceso_denegado',[
            'url' => $request->path(),
            'titulo' => ['titulo'=>'Permiso','sub_titulo'=>'Acceso denegado'],
            'usuario' => getParty(session('party_id')),
        ]));
    }
}
