<?php

namespace App\Http\Middleware;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Closure;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions)
    {
        $permissions_array = explode('|', $permissions);
        $user_id = $request->user()->id;
        $role_name = $request->user()->getRoleNames();
        // dd($role_name);
        if(count($role_name)){
            $role_id = Role::where('name', $role_name[0])->first()->id;
            $user_permission = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $role_id)
            ->get();
            // dd($permissions_array);
            foreach ($user_permission as $key => $value) {
                if(in_array($value['name'], $permissions_array)){
                    return $next($request);
                }
            }

        }

        return redirect()->back();
    }
}
