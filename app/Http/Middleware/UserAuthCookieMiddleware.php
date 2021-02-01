<?php

namespace App\Http\Middleware;

use Closure;

class UserAuthCookieMiddleware
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
        //Verifica Se tem um cookie valido de login (criado pós senha unica)
        
        if(!isset ($_COOKIE['loginUSP'])){
            flash('Você precisa estar logado para usar esta aplicação')->warning();
            return redirect()->route('index');
        }
        return $next($request);
    }
}
