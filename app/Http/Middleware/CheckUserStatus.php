<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
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

        $id = $request->user()->id;
        $user_data = User::find($id);
        if($user_data['status'] == "Enabled"){
            return $next($request);
        }

        if($user_data['user_type'] == "admin"){
            Auth::logout();
            return redirect()->route('admin.login')->with('error', config('constants.USER_INACTIVE'));
        }

        if($user_data['user_type'] == "web"){
            Auth::logout();
            return redirect()->route('front.login')->with('error', config('constants.USER_INACTIVE'));
        }

        return $next($request);
    }
}
