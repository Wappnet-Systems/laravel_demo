<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public $data;

    public function __construct()
    {
        $this->middleware('permission:role-permission-list', ['only' => ['index']]);
        $this->middleware('permission:role-permission-add', ['only' => ['add_role_permission', 'save_role_permission']]);
        $this->middleware('permission:role-permission-edit', ['only' => ['edit_role_permission', 'update_role_permission']]);
        $this->middleware('permission:role-permission-delete', ['only' => ['delete_role_permission']]);
    }

    public function index(){
        $this->data['page_title'] = "Role /Permission Management";
        $this->data['roles'] = Role::where('guard_name','admin')->orderBy('id', 'DESC')->get();
        // dd($this->data['roles']);
        return view('admin.role_permission.index', $this->data);
    }

    public function add_role_permission(){
        $this->data['page_title'] = "Add Role /Permission";
        $this->data['permission'] = Permission::where('guard_name','admin')->orderBy('id', 'asc')->get();
        // dd($this->data['permission']);
        return view('admin.role_permission.create', $this->data);
    }

    public function save_role_permission(Request $request){
        $role = Role::create(['name' => $request->input('name'), 'guard_name' => 'admin']);
        if($role){
            $role->syncPermissions($request->input('permission'));
            return redirect()->route('admin.list_roles')->with('success', 'Role inserted successfully');
        }
        return redirect()->back()->with('error', 'Error Occurred. Try Again!');
    }

    public function edit_role_permission($id){
        $this->data['page_title'] = "Edit Role /Permission";
        $this->data['role'] = Role::find($id);
        $this->data['permission'] = Permission::where('guard_name', 'admin')->orderBy('id', 'asc')->get();
        $this->data['rolePermissions'] = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
                                            ->where("role_has_permissions.role_id", $id)
                                            ->get();
        // dd($this->data);
        return view('admin.role_permission.edit', $this->data);
    }

    public function update_role_permission(Request $request){
        // dd($request->all());
        $id = $request->get('id');
        $role = Role::find($id);
        $role->name = $request->input('name');
        if($role->save()){
            $role->syncPermissions($request->input('permission'));

            return redirect()->route('admin.list_roles')->with('success', 'Role updated successfully');
        }

        return redirect()->back()->with('error', 'Error Occurred. Try Again!');
    }

    public function delete_role_permission($id){
        if(DB::table("roles")->where('id', $id)->delete()){
            return redirect()->route('admin.list_roles')->with('success', 'Role deleted successfully');
        }
        return redirect()->back()->with('error', 'Error Occurred. Try Again!');
    }

    public function check_role_name(Request $request){
        if(!empty($request->get('id'))){
            $check = Role::whereNotIn('id', [$request->get('id')])->where('name', $request->get('name'))->where('guard_name', 'admin')->count();
        }else{
            $check = Role::where('name', $request->get('name'))->where('guard_name', 'admin')->count();
        }
        if($check){
            return "false";
        }else{
            return "true";
        }
    }
}
