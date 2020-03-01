<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Response;

class Permiso
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
        $arrPath = [];
        foreach ($party->party_role->role_type->permisos as $key => $permiso) {

           if($permiso->menu->subMenu->count() < 1){
               $arrPath[] = $permiso->menu->path;
           }else{
               foreach ($permiso->menu->subMenu as $sb)
                   $arrPath[] = $sb->path;
           }

        }

        $find = false;

        foreach ($arrPath as $path)
            if($path === $request->path())
                $find = true;


       if(!$find && in_array(explode("/",$request->path())[0],$arrPath))
           $find = true;

       if($find) return $next($request);

       return new Response(view('errors.acceso_denegado',[
           'url' => $request->path(),
           'titulo' => ['titulo'=>'Permiso','sub_titulo'=>'Acceso denegado'],
           'usuario' => getParty(session('party_id')),
       ]));
    }
}
