<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Lib\CommonTask;
use App\Lib\NotificationTask;
use App\Model\Settings;

class DashboardController extends Controller {

    public $data;
    private $module_id = 4;
    private $common_task;
    private $notification_task;

    public function __construct() {

        $this->data['module_title'] = "Dashboard";
        $this->data['module_link'] = "admin.dashboard";
        $this->common_task = new CommonTask();
        $this->notification_task = new NotificationTask();
    }

    public function index() {

        $this->data['total_admin_user'] = User::where('user_type', 'admin')->count();
        $this->data['total_normal_user'] = User::where('user_type', 'web')->role('Normal User', 'web')->count();
        $this->data['total_premium_user'] = User::where('user_type', 'web')->role('Premium Users', 'web')->count();
        $this->data['total_business_user'] = User::where('user_type', 'web')->role('Business Users', 'web')->count();
        $this->data['total_sub_business_user'] = User::where('user_type', 'web')->role('Sub-Business Users', 'web')->count();

        return view('admin.dashboard.index', $this->data);
    }

    public function changepassword() {
        $this->data['page_title'] = "Change Password";
        return view('admin.dashboard.changepassword', $this->data);
    }

    public function savepassword(Request $request) {

        $rules = array(
            'old_password' => 'required', // check old_password is empty or not
            'new_password' => 'required|min:8', // check new_password is empty or not
            're_password' => 'required|min:8|same:new_password' // check re_password is empty or not and new password and confirm password match
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('admin.changepassword')->with('error', 'Please follow validation rules.');
        }

        if (!(Hash::check($request->get('old_password'), Auth::user()->password))) {
            //The passwords matches
            return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");
        }
        if (strcmp($request->get('old_password'), $request->get('new_password')) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("error", "New Password cannot be same as your current password. Please choose a different password.");
        }

        $user = new User();
        $save_password = $user::where('id', Auth::User()->id)->first();
        $save_password->password = Hash::make($request->get('new_password'));
        if ($save_password->save()) {
            return redirect()->back()->with("success", "Password changed successfully !");
        } else {
            return redirect()->back()->with("error", "Password does not changed successfully !");
        }
    }

    public function profile() {
        $id = Auth::user()->id;

        $this->data["user_detail"] = User::where('users.id', $id)->first();
        $this->data['page_title'] = "Profile";
        $this->data['id'] = $id;
        $this->data['is_profile'] = true;
        // dd($this->data);
        return view('admin.user.edit_user', $this->data);
    }
}
