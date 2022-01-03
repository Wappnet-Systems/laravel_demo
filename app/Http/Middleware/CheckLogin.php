<?php

namespace App\Http\Middleware;
use \Illuminate\Support\Facades\Auth;
use Closure;

class CheckLogin
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
        /* dd($request->route()->getPrefix());
        if(Auth::user()){
            return $next($request);
        }
        return redirect('/'); */
        // dd($request->route()->getPrefix());
        if(Auth::check()){

            if(auth()->user()->user_type == "admin"){
                return redirect()->route('admin.dashboard');
            }else{
                return $next($request);
            }
        }else{
            return $next($request);
        }
        return $next($request);
    }
}
