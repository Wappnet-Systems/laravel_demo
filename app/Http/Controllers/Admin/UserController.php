<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\UserInfo;
use Illuminate\Support\Facades\Input;
use App\Common_query;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Email_format;
use Illuminate\Support\Facades\Mail;
use App\Mail\Mails;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Redirect;
use URL;
use Illuminate\Support\Facades\Session;
use App\Lib\Permissions;
use App\TaxDeclaration;
use App\LeaveMaster;
use App\Lib\CommonTask;
use App\Lib\UploadFile;
use Spatie\Permission\Models\Role;
use DataTables;

class UserController extends Controller {

    public $data;
    public $common_task;
    public $upload_file;

    public function __construct() {
        $this->data['module_title'] = "Admin User Management";
        $this->data['module_link'] = "admin.users";
        $this->common_task = new CommonTask();
        $this->upload_file = new UploadFile();

        $this->middleware('permission:admin-user-management-list', ['only' => ['index']]);
        $this->middleware('permission:admin-user-management-add', ['only' => ['add_user']]);
        $this->middleware('permission:admin-user-management-edit', ['only' => ['edit_user','update_user_data']]);
    }

    public function index() {
        $this->data['page_title'] = "Admin User Management";
        $login_user_id = auth()->user()->id;
        $this->data['user_list'] = User::whereNotIn('id',[$login_user_id])->where('user_type','admin')->with(['get_user_info'])->get();
        // dd($this->data['user_list']);
        return view('admin.user.index', $this->data);
    }

    public function get_admin_user_list(Request $request){

        $user_id = Auth::user()->id;

        $user = User::whereNotIn('id',[$user_id])->where('user_type','admin')->get();
        return Datatables::of($user)
                ->editColumn('role_name', function ($user) {
                    return $user->roles->first()->name;
                })
                ->editColumn('profile_image', function ($user) {
                    if($user->profile_image){
                        return $this->upload_file->get_s3_file_path("profile_image",$user->profile_image);
                    } else {
                        $defaultImage = url('admin_asset/assets/plugins/images/user_avatar.png');
                        return $defaultImage;
                     }
                })
                ->make(true);

        /* $datatable_fields = array('roles.name','users.first_name', 'users.last_name', 'users.email', 'users.status');
        $request = $request->all();

        $conditions_array = ['users.user_type' => 'admin'];

        $join_str[0]['join_type'] = 'left';
        $join_str[0]['table'] = 'model_has_roles';
        $join_str[0]['join_table_id'] = 'model_has_roles.model_id';
        $join_str[0]['from_table_id'] = 'users.id';

        $join_str[1]['join_type'] = 'left';
        $join_str[1]['table'] = 'roles';
        $join_str[1]['join_table_id'] = 'roles.id';
        $join_str[1]['from_table_id'] = 'model_has_roles.role_id';

        $getfiled = array('users.*', 'roles.name');

        $table = "users";

        echo User::get_list_datatable_ajax($table, $datatable_fields, $conditions_array, $getfiled, $request, $join_str);
        die(); */
    }

    public function add_user() {
        $this->data['page_title'] = "Add User";
        $this->data['roles'] = Role::where('guard_name','admin')->pluck('name', 'name')->all();
        // dd($this->data['roles']);
        return view('admin.user.add_user', $this->data);
    }

    public function update_user(Request $request) {
        $validator_normal = Validator::make($request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'id' => 'required'
        ]);
        if ($validator_normal->fails()) {
            return redirect()->route('admin.edit_user', ['id' => $request->input('id')])->with('error', 'Please follow validation rules.');
        }

        $user_arr = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'updated_at' => date('Y-m-d h:i:s'),
        ];


        if (User::whereId($request->get('id'))->update($user_arr)) {
            Session::flash('success', "Profile details successfully updated");
            return Redirect::back();
        } else {
            Session::flash('error', "Error Occurred. Try Again!");
            return Redirect::back();
        }
    }

    public function check_email(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);
        if ($validator->fails()) {
            echo 'false';
            die();
        }
        $email = $request->input('email');
        $user_check = User::where('email', $email)->get();
        if ($user_check->count() > 0) {
            echo 'false';
            die();
        } else {
            echo 'true';
            die();
        }
    }

    public function create_user(Request $request){
        // dd($request->all());
        $request_data = $request->all();
        $user_data = [
            'first_name' => $request_data['first_name'],
            'last_name' => $request_data['last_name'],
            'email' => $request_data['email'],
            'password' => Hash::make($request_data['password']),
            'user_type' => "admin",
            'created_by' => auth()->user()->id,
            'created_ip' => $request->ip(),
            'updated_ip' => $request->ip(),
        ];

        // profile image
        if ($request->hasFile('profile_image')) {
            $profile_img = $request->file('profile_image');
            $user_data['profile_image'] = $this->upload_file->upload_s3_file($request, 'profile_image',"profile_image");
        }
        if($user = User::create($user_data)){

            //role assign
            $user->assignRole($request->input('role'));

            //email
            $email_data = [];
            $email_data['to_email'] = [$request_data['email']];
            $email_data['email'] = $request_data['email'];
            $email_data['full_name'] = $request_data['first_name'] . " " . $request_data['last_name'];
            $email_data['password'] = $request_data['password'];

            $this->common_task->send_singup_admin($email_data);

            return redirect()->route('admin.user')->with('success', 'User inserted successfully');
        }


        return redirect()->back()->with('error', 'Error Occurred. Try Again!');
    }

    public function edit_user($id){
        $this->data['page_title'] = "Edit User";
        $user = User::find($id);
        $this->data['user_data'] = $user;

        $roles = Role::where('guard_name', 'admin')->pluck('name', 'name')->all();
        $this->data['roles'] = $roles;

        $this->data['user_role'] = $user->roles->pluck('name', 'name')->all();
        // dd($this->data);
        return view('admin.user.edit_user_data', $this->data);
    }

    public function update_user_data(Request $request){
        $request_data = $request->all();
        $user_data = [
            'first_name' => $request_data['first_name'],
            'last_name' => $request_data['last_name'],
            'created_ip' => $request->ip(),
            'updated_ip' => $request->ip(),
        ];
        // profile image
        if ($request->hasFile('profile_image')) {

            if($profile_image = User::whereId($request_data['id'])->value('profile_image')){
                $response = $this->upload_file->delete_s3_file($profile_image);
            }

            $profile_img = $request->file('profile_image');

            $user_data['profile_image'] = $this->upload_file->upload_s3_file($request, 'profile_image',"profile_image");
        }

        if (User::whereId($request_data['id'])->update($user_data)) {
            //reassign role
            DB::table('model_has_roles')->where('model_id', $request_data['id'])->delete();
            $user = User::find($request_data['id']);
            $user->assignRole($request->input('role'));

            return redirect()->route('admin.user')->with('success', 'User updated successfully');
        }


        return redirect()->back()->with('error', 'Error Occurred. Try Again!');
    }

    public function change_admin_status($id,$status){

        $user_data = [
            'status' => $status,
        ];

        if(User::whereId($id)->update($user_data)){
            return redirect()->route('admin.user')->with('success', 'Status updated successfully');
        }
        return redirect()->back()->with('error', 'Error Occurred. Try Again!');
    }
}
