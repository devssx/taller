<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
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
        // checar lista de permisos en database/seeds/RolesTableSeeder
        if(auth()->user()->can('listar carros')){
             return $next($request);
         }

        return redirect('/sales')->with('error', 'No cuentas permiso para acceder a la pagina solicitada.');
    }
}
