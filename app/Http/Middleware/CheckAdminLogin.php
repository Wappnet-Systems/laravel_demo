<?php

namespace App\Http\Middleware;
use \Illuminate\Support\Facades\Auth;
use Closure;

class CheckAdminLogin
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
        if(Auth::check()){
            if(auth()->user()->user_type == "web"){
                return redirect()->route('front.home');
            }else{
                return $next($request);
            }
        }else{
            return $next($request);
        }
        return $next($request);
    }
}
