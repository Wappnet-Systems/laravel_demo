<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Session;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Lib\CommonTask;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;

class LoginController extends Controller
{

    public $common_task;

    public function __construct()
    {
        $this->common_task = new CommonTask();
    }

    public function login(){
        return view('admin.login');
        // return view('admin.dashboard.index');
    }

    public function authenticate(Request $request){
        $validator= Validator::make($request->all(),[
            'email'=>"required|email",
            'password'=>"required"
        ]);

        if($validator->fails()){
            return redirect()->route('login')->with('error','Email and password is required.');
        }

        $email=$request->input('email');
        $password=$request->input('password');
        $auth_arr=[
            'email'=>$email,
            'password'=>$password,
        ];

        $check_email = User::where('email', $email)->first();
        if($check_email){
            if (Auth::attempt($auth_arr)) {
                if (auth()->user()->user_type == 'admin') {

                    if (auth()->user()->status == 'Disabled') {
                        Auth::logout();
                        return redirect()->route('admin.login')->with('error', 'Your status in inactive please contact admin.');
                    }
                    // Session::put('login_type', "admin");
                    // dd(Session::get('login_type'));
                    return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully.');
                }
                Auth::logout();
                return redirect()->route('admin.login')->with('error', 'Invalid email or password.');
            } else {
                return redirect()->route('admin.login')->with('error', 'Invalid password please try again.');
            }
        }else{
            return redirect()->route('admin.login')->with('error', 'Invalid email please try again.');
        }
    }

    public function reset_password(){

        return view('admin.reset_password');
    }

    public function forgot_password(Request $request){

        $email = $request->get('email');
        $user_data = User::where('email',$email)->where('user_type','admin')->first();
        if($user_data){
            $mail_data = [];
            $mail_data['to_email'] = [$email];
            $mail_data['full_name'] = $user_data['first_name'] . " " . $user_data['last_name'];
            $mail_data['email_address'] = $email;
            $mail_data['password'] = $this->password_generate(10);
            // dd($mail_data);

            $user_data->password = Hash::make($mail_data['password']);
            $user_data->save();

            $this->common_task->send_admin_forgot_password($mail_data);

            return redirect()->back()->with('success','New password send in your email address.');
        }else{
            return redirect()->back()->with('error','Email in not register.');
        }
    }

    function password_generate($chars) {
      $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
      return substr(str_shuffle($data), 0, $chars);
    }

    function logout(){
        Auth::logout();
        return redirect()->route('admin.login')->with('success', 'Logout successfully.');
    }
}
