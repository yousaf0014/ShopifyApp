<?php

namespace App\Http\Middleware;
use Closure;
use Auth;

class Customer
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
        if(Auth::user()->type!='customer'){
            return redirect('/');
        }
        return $next($request);
    }
}
