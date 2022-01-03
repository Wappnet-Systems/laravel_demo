<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Model\UserInfo;
use App\Model\UserActivityLog;
use App\Model\PasswordResets;
use App\Model\UserSubscription;
use App\Lib\CommonTask;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;
// use Socialite;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\URL;

class RegisterController extends Controller
{
    public $data;
    public $common_task;

    public function __construct()
    {
        $this->common_task = new CommonTask();
    }

    // register method
    public function register()
    {
        $this->data['social_data'] = [];
        $this->data['roles'] = Role::where('guard_name', 'web')->whereNotIn('name', ['Sub-Business Users'])->pluck('name', 'name')->all();
        return view('front.register',$this->data);
    }

    public function new_user(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => "required|email|unique:users",
            // 'user_name' => "required|unique:user_info",
            'first_name' => "required",
            'last_name' => "required",
            /* 'phone_number' => "required",
            'address' => "required",
            'unit_street' => "required",
            'city' => "required",
            'state' => "required",
            'country' => "required",
            'postal_code' => "required", */
            'password' => "required",
        ]);

        if ($validator->fails()) {
            // return Redirect::back()->withErrors($validator);
            return redirect()->back()->with('error', 'Please insert required fields.');
        }
        $request_data = $request->all();
        // dd($request_data);
        $remember_token = $this->password_generate(50);
        
        $user_data = [
            'first_name' => $request_data['first_name'],
            'last_name' => $request_data['last_name'],
            'email' => $request_data['email'],
            'password' => Hash::make($request_data['password']),
            'user_type' => "web",
            'created_ip' => $request->ip(),
            'updated_ip' => $request->ip(),
            'remember_token' => $remember_token
        ];

        if(isset($request_data['social_id']) && !empty($request_data['social_id'])){
            $user_data['social_id'] = $request_data['social_id'];
            $user_data['social_status'] = $request_data['social_status'];
        }
        
        if ($user = User::create($user_data)) {

            //Add User Info
            /* $user_info = [
                'user_id' => $user->id,
                'gender' => $request_data['gender'],
                'user_name' => $request_data['user_name'],
                'phone_number' => $request_data['phone_number'],
                'date_of_birth' => ($request_data['date_of_birth']) ? date('Y-m-d', strtotime($request_data['date_of_birth'])) : NULL,
                'address' => $request_data['address'],
                'unit_street' => $request_data['unit_street'],
                'city' => $request_data['city'],
                'state' => $request_data['state'],
                'country' => $request_data['country'],
                'postal_code' => $request_data['postal_code'],
                'created_ip' => $request->ip(),
                'updated_ip' => $request->ip(),
            ];

            UserInfo::insert($user_info); */

            //role assign
            /* $role = Role::findByName($request->input('role'), 'web');
            $user->assignRole($role); */

            
            $mail_data = [];
            $mail_data['to_email'] = [$user->email];
            $mail_data['full_name'] = $user['first_name'] . " " . $user['last_name'];
            $mail_data['link'] = URL::to('/')."/vefify_account/".$user['remember_token'];
            $this->common_task->vefify_account($mail_data);

            return redirect()->route('front.login')->with('success', "Verification link send in your email");

            /* Not send verification email */
            /* Auth::login($user);
            $check_plan = UserSubscription::where('user_id',Auth::user()->id)->count();
            if($check_plan == 0){
                return redirect()->route('front.plans')->with('info', config('constants.REGISTER_MSG').' Please choose any plan');
            }
            return redirect()->route('front.home')->with('success', config('constants.REGISTER_MSG')); */
        }
        return redirect()->back()->with('error', config('constants.ERROR_MSG'));
    }

    public function check_email(Request $request){
        $email = $request->input('email');
        $check = User::where('email',$email)->count();
        if($check){
            $msg = false;
        }else{
            $msg = true;
        }
        echo json_encode($msg);die;
    }

    public function vefify_account($token){
        $user = User::where('remember_token',$token)->first();
        if($user && $user->email_verified_at == ""){
            $this->data['status'] = "success";
            $this->data['message'] = "Your account is verified.";

            $user->email_verified_at = date('Y-m-d h:i:s');
            $user->save();

        }elseif($user && $user->email_verified_at){
            $this->data['status'] = "success";
            $this->data['message'] = "Your account is already verified.";
        }else{
            // abort(404);
            $this->data['status'] = "error";
            $this->data['message'] = "Invalid verfication link.";
        }
        // dd($this->data);
        return view('front.verify_account',$this->data);
    }

    public function password_generate($chars)
    {
        $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($data), 0, $chars).time();
    }
}
