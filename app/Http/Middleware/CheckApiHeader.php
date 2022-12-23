<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use App\User;
use Illuminate\Http\Response;

class CheckApiHeader
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
        $headers = getallheaders();
        if(!isset($headers['X_RECIPE_ID'])){
            return Response::json(array('error'=>'Please set custom header'));  
        }
        if(!isset($headers['X_PARTNER_BILLING_ID'])){  
            return Response::json(array('error'=>'wrong custom header'));
        }
        $user = User::where('recipe_id',$headers['X_RECIPE_ID'])->where('partner_billing_id',$headers['X_PARTNER_BILLING_ID'])->first();
        if(!empty($user->id)){
            Auth::login($user);
            return $next($request);  
        }
        return Response::json(array('error'=>'Invalid user'));
    }
}
