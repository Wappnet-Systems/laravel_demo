<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Lib;

/**
 * Description of Permissions
 *
 * @author kishan
 */
use App\Role_module;

class Permissions {

    public static function checkPermission($module_id=0, $permission_id=0) {
        
        $role_id = \Illuminate\Support\Facades\Auth::user()->role;

        if ($role_id == 1) {
            return true;
        } else {
            
            if($module_id==0 || $permission_id==0){
                return false;
            }
            
            //get permissions list
            $permission_detail = Role_module::where(['module_id' => $module_id, 'role_id' => $role_id])->get();
            
            if ($permission_detail->count() == 0) {
                return false;
            }
            $permission_arr = explode(',', $permission_detail[0]->access_level);
            if (in_array($permission_id, $permission_arr)) {
                return true;
            } else {
                return false;
            }
        }
    }

}
