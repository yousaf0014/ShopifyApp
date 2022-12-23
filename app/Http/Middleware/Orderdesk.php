<?php

namespace App\Http\Middleware;
use Closure;
use Auth;

class Orderdesk
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
        if(Auth::user()->type!='order_desk'){
            return redirect('/');
        }
        return $next($request);
    }
}
