<?php

namespace App\Http\Middleware;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use DB;
use Closure;

class Auth
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
        if ($request->session()->has('logeado')) {
            if ($request->session()->get('logeado')) {
                if(getParty($request->session()->get('party_id'))->user_login[0]->enabled === 'Y'){
                    return $next($request);
                }else{
                    flash('El usuario no está habilitado en el sistema')->error()->important();
                    Session::flush();
                    DB::disconnect();
                }
            }
        }
        return new Response(view('login.inicio'));
    }
}
