<?php

namespace App\Http\Middleware;

use Closure;

class AdminAuth
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
        if(!$request->session()->exists('user_data')){
            return redirect()->route('admin.login')->with('error','Access denied. Please login using your credentials.');
        }
        return $next($request);
    }
}
