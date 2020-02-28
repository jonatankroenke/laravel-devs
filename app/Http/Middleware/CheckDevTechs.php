<?php

namespace App\Http\Middleware;

use Closure;

class CheckDevTechs
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
        $teste = $request->header('Teste');
        
        if ($teste > 1) {

            $github_username = $request->input('GITHUB');
            $request->merge(['github_username' => $github_username]);
    
            return $next($request);

        } 
        return response()->json(['error' => 'Parametro incorreto'], 400);
    }
}
