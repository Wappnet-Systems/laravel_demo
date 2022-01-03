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
use App\Model\Login_log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;
// use Socialite;
use Laravel\Socialite\Facades\Socialite;
use App\Model\Settings;

use URL;

class LoginController extends Controller
{
    public $data;
    public $common_task;

    public function __construct()
    {
        $this->common_task = new CommonTask();
    }

    // login method
    public function login()
    {
        if(Auth::check()){
            return redirect()->route('front.home');
        }
        return view('front.login');
    }

    // logut method
    public function logout()
    {
        Auth::logout();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: private, no-store, max-age=0, no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        return redirect()->route('front.home')->with('success', config('constants.LOGOUT_MSG'));
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => "required|email",
            'password' => "required"
        ]);

        if ($validator->fails()) {
            return redirect()->route('front.login')->with('error', 'Email and password is required.');
        }

        $email = $request->input('email');
        $password = $request->input('password');
        $auth_arr = [
            'email' => $email,
            'password' => $password,
        ];
        $check_email = User::where('email', $email)->first();
        if($check_email){
            if (Auth::attempt($auth_arr)) {
                if (auth()->user()->user_type == 'web') {

                    if (auth()->user()->status == 'Disabled') {
                        Auth::logout();
                        return redirect()->route('front.login')->with('error', config('constants.USER_INACTIVE'));
                    }

                    if (auth()->user()->email_verified_at == '') {
                        Auth::logout();
                        Session::flash('account_verify_email',$email);
                        return redirect()->route('front.login')->with('account_verify', config("errors.account_verify.msg"));
                    }

                    $this->user_activity_log(auth()->user()->id);

                    if (auth()->user()->roles->first()->name != 'Sub-Business Users') {
                        $check_plan = UserSubscription::where('user_id',Auth::user()->id)->count();
                        if($check_plan == 0){
                            return redirect()->route('front.plans')->with('info', config('constants.LOGIN_MSG').' Please choose any plan');
                        }else{
    
                        }
                    }

                    $login_log_arr = [
                        'device_id' => $request->ip(),
                        'device_type' => 'Web',
                        'user_id' => Auth::user()->id,
                        'created_ip' => $request->ip()
                    ];
                    Login_log::insert($login_log_arr);

                    $get_subscription = UserSubscription::where('user_id',Auth::user()->id)->first();
                    if($get_subscription){
                        $stripe_key = Settings::get_stripe_key();
                        $stripe_secret = Settings::get_stripe_secret();
            
                        $stripe = new \Stripe\StripeClient(
                                    $stripe_secret
                                );
                        $plan_data = $stripe->products->retrieve(
                            $get_subscription['product_id'],
                        []
                        );
            
                        $this->data['plan_type'] = $plan_data['name'];
                        $this->data['plan_created_at'] = $get_subscription['created_at'];
                    }

                    $flash_arr = [];
                    $flash_arr['success'] = config('constants.LOGIN_MSG');
                    /* dd(Auth::user()->get_user_info->organization_name == null && 
                    Auth::user()->get_user_info->business_contact_number == null && 
                    Auth::user()->get_user_info->business_contact_email == null &&  
                    Auth::user()->get_user_info->business_website == null && 
                    Auth::user()->get_user_info->business_registration_number == null); */
                    if (Auth::user()->get_user_info->organization_name == null && 
                    Auth::user()->get_user_info->business_contact_number == null && 
                    Auth::user()->get_user_info->business_contact_email == null &&  
                    Auth::user()->get_user_info->business_website == null && 
                    Auth::user()->get_user_info->business_registration_number == null) {
                        $flash_arr['info'] = "please filed all Dashboard data after Your account going for approving.";
                    
                    }elseif (Auth::user()->business_account_approval == "0") {
                         
                        $flash_arr['info'] = "Your ".$this->data['plan_type']." account details successfully submitted and your account is under review. You will be notified soon once your account is approved.";
                    }
                    
                    if($get_subscription){
                        return redirect()->route('front.dashboard')->with($flash_arr);
                    }

                    return redirect()->route('front.home')->with('success', config('constants.LOGIN_MSG'));
                }
                Auth::logout();
                return redirect()->route('front.login')->with('error', config('constants.PASSWORD_INVALID'));
            } else {
                return redirect()->route('front.login')->with('error', config('constants.PASSWORD_INVALID'));
            }
        }else{
            return redirect()->route('front.login')->with('error', config('constants.EMAIL_INVALID'));
        }

    }

    public function forgot_password(){
        return view('front.forgot_password');
    }

    public function send_new_password(Request $request){
        /* $validator = Validator::make($request->all(), [
            'email' => "required|email",
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Email and password is required.');
        } */

        $email = $request->get('email');
        // dd($email);
        $data = [];
        $user_data = User::where('email',$email)->first();
        if($user_data){

            if($user_data['social_id'] != ""){

                return redirect()->back()->with("success", "You are login with ".$user_data['social_status']."so can't reset password.");
                // old code commented 
                /*    $data['status'] = false;
                $data['message'] = "You are login with ".$user_data['social_status'].". so can't reset password.";
                echo json_encode($data);die; */
            }

            $token = $this->password_generate(50);
            $mail_data = [];
            $mail_data['to_email'] = [$email];
            $mail_data['full_name'] = $user_data['first_name'] . " " . $user_data['last_name'];
            $mail_data['link'] = URL::to('/')."/reset_password/".$token;
            // dd($mail_data);

            $password_reset = [
                'email' => $email,
                'token' => $token,
                'created_at' => date('Y-m-d h:i:s'),
                'expired_at' => date('Y-m-d h:i:s', strtotime('+24 hours')),
            ];
            PasswordResets::insert($password_reset);

            $this->common_task->send_forgot_password($mail_data);
             return redirect()->back()->with('success', 'New password send in your email address.');
            /*$data['status'] = true;
            $data['message'] = config('constants.RESET_PASSWORD_LINK_SEND');
            echo json_encode($data);die;*/
        }else{
             return redirect()->back()->with('error', config('constants.EMAIL_NOT_REGISTER'));
            /*$data['status'] = false;
            $data['message'] = config('constants.EMAIL_NOT_REGISTER');
            echo json_encode($data);die;*/
        }
    }

    public function reset_password($token){
        // Hash::make($mail_data['password']);

        $current_datetime = date('Y-m-d h:i:s');
        $user_data = PasswordResets::where('token',$token)->first();

        if($user_data){
            if($current_datetime < $user_data['expired_at']){
                $this->data['status'] = "valid";
                $this->data['token'] = $token;
            }else{
                $this->data['status'] = "expired";
            }
            return view('front.forgot_password',$this->data);
        }
        return redirect()->route('front.login')->with('error', config('constants.RESET_PASSWORD_LINK_EXPIRED'));
    }

    public function reset_password_change(Request $request){
        $validator = Validator::make($request->all(), [
            'password' => "required",
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Please follow validation rules.');
        }

        if($request->get('reset_token')){
            $current_datetime = date('Y-m-d h:i:s');
            $user_data = PasswordResets::where('token',$request->get('reset_token'))->first();

            if($current_datetime < $user_data['expired_at']){

                $change_data = User::where('email',$user_data['email'])->first();
                if($change_data){
                    $change_data->password = Hash::make($request->get('password'));
                    if($change_data->save()){
                        PasswordResets::where('token',$request->get('reset_token'))->delete();
                        return redirect()->route('front.login')->with('success', config('constants.PASSWORD_CHANGED'));
                    }else{
                        return redirect()->route('front.login')->with('error', config('constants.ERROR_MSG'));
                    }
                }else{
                    return redirect()->route('front.login')->with('error', config('constants.ERROR_MSG'));
                }
            }else{
                return redirect()->back()->with('error', config('constants.RESET_PASSWORD_LINK_EXPIRED'));
            }
        }

        return redirect()->back()->with('error', config('constants.ERROR_MSG'));
    }

    public function password_generate($chars)
    {
        $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($data), 0, $chars);
    }

    public function google_login(){
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(){
        try {

            if(Auth::check()){
                return redirect()->route('front.home')->with('success', config('constants.LOGIN_MSG'));
            }

            $user = Socialite::driver('google')->user();

            $finduser = User::where('social_id', $user->id)->where('social_status', "Google")->first();

            if ($finduser) {
                // dd($finduser);
                if ($finduser->status == 'Disabled') {
                    return redirect()->route('front.login')->with('error', config('constants.USER_INACTIVE'));
                }
                Auth::login($finduser);
                if (auth()->user()->roles->first()->name != 'Sub-Business Users') {
                    $check_plan = UserSubscription::where('user_id',Auth::user()->id)->count();
                    if($check_plan == 0){
                        return redirect()->route('front.plans')->with('info', config('constants.LOGIN_MSG').' Please choose any plan');
                    }
                }
                return redirect()->route('front.home')->with('success', config('constants.LOGIN_MSG'));
            } else {
                $check_email = User::where('email', $user->email)->first();
                if($check_email){
                    if($check_email['social_status']){
                        $login_user = $check_email['social_status'];
                    }else{
                        $login_user = "Normal User";
                    }
                    return redirect()->route('front.login')->with('error', 'Your email address already registered with us using '.$login_user.'. Please try to login using same method');
                }else{
                    // new login
                    $data = [
                        'first_name' => $user->user['given_name'],
                        'last_name' => $user->user['family_name'],
                        'email' => $user->user['email'],
                        'social_id' => $user->user['sub'],
                        'user_type' => "web",
                        'social_status'=> 'Google',
                    ];

                    $user = User::create($data);
                    Auth()->login($user);

                    if (auth()->user()->roles->first()->name != 'Sub-Business Users') {
                        $check_plan = UserSubscription::where('user_id',Auth::user()->id)->count();
                        if($check_plan == 0){
                            return redirect()->route('front.plans')->with('info', config('constants.SOCIAL_REGISTER').' Please choose any plan');
                        }
                    }
                    // return view('front.home', $this->data);
                    return redirect()->route('front.home')->with('success', config('constants.SOCIAL_REGISTER'));
                }
                /* $this->data["social_data"] = [
                    'first_name' => $user->user['given_name'],
                    'last_name' => $user->user['family_name'],
                    'email' => $user->user['email'],
                    'social_id' => $user->user['sub'],
                    'social_status'=> 'Google',
                ];
                $this->data['roles'] = Role::where('guard_name', 'web')->whereNotIn('name', ['Sub-Business Users'])->pluck('name', 'name')->all(); */
                // dd($user->user);
                // dd($this->data);
                // return redirect()->route('front.register');


            }
        } catch (Exception $e) {
            // dd($e->getMessage());
            // return redirect()->route('front.login')->with('error', 'Error Occurred. Try Again!');
            return redirect('front.home')->with('error', config('constants.ERROR_MSG'));
        }
    }

    public function linkedin_login(){
        try {
            return Socialite::driver('linkedin')->scopes(['r_emailaddress', 'r_liteprofile'])->redirect();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Error Occurred. Try Again!');
        }
    }

    public function handleLinkedinCallback(Request $request)
    {
        try {
            if(!empty($request->input('error'))){
                return redirect()->route('front.login');
            }

            if(Auth::check()){
                return redirect()->route('front.home')->with('success', config('constants.LOGIN_MSG'));
            }

            $user = Socialite::driver('linkedin')->user();

            $finduser = User::where('social_id', $user->id)->where('social_status', "LinkedIn")->first();
            // dd($user);
            if ($finduser) {
                // dd($finduser);
                if ($finduser->status == 'Disabled') {
                    return redirect()->route('front.login')->with('error', config('constants.USER_INACTIVE'));
                }
                Auth::login($finduser);
                if (auth()->user()->roles->first()->name != 'Sub-Business Users') {
                    $check_plan = UserSubscription::where('user_id',Auth::user()->id)->count();
                    if($check_plan == 0){
                        return redirect()->route('front.plans')->with('info', config('constants.LOGIN_MSG').' Please choose any plan');
                    }
                }
                return redirect()->route('front.home')->with('success', config('constants.LOGIN_MSG'));
            } else {
                $check_email = User::where('email', $user->email)->first();
                if($check_email){
                    if($check_email['social_status']){
                        $login_user = $check_email['social_status'];
                    }else{
                        $login_user = "Normal User";
                    }
                    return redirect()->route('front.login')->with('error', 'Your email address already registered with us using '.$login_user.'. Please try to login using same method');
                }else{
                    // new login
                    $data = [
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'social_id' => $user->id,
                        'user_type' => "web",
                        'social_status'=> 'LinkedIn',
                    ];

                    $user = User::create($data);
                    Auth()->login($user);

                    if (auth()->user()->roles->first()->name != 'Sub-Business Users') {
                        $check_plan = UserSubscription::where('user_id',Auth::user()->id)->count();
                        if($check_plan == 0){
                            return redirect()->route('front.plans')->with('info', config('constants.SOCIAL_REGISTER').' Please choose any plan');
                        }
                    }
                    // return view('front.home', $this->data);
                    return redirect()->route('front.home')->with('success', config('constants.SOCIAL_REGISTER'));
                }

            }
        } catch (Exception $e) {
            return redirect('front.home')->with('error', config('constants.ERROR_MSG'));
        }
    }

    public function facebook_login(){
        try {
            return Socialite::driver('facebook')->redirect();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Error Occurred. Try Again!');
        }
    }

    public function handleFacebookCallback(Request $request){
        try {
            if(!empty($request->input('error'))){
                return redirect()->route('front.login');
            }

            if(Auth::check()){
                return redirect()->route('front.home')->with('success', config('constants.LOGIN_MSG'));
            }

            $user = Socialite::driver('facebook')->user();
            // dd($user);
            $finduser = User::where('social_id', $user->id)->where('social_status', "Facebook")->first();
            if ($finduser) {
                // dd($finduser);
                if ($finduser->status == 'Disabled') {
                    return redirect()->route('front.login')->with('error', config('constants.USER_INACTIVE'));
                }
                Auth::login($finduser);
                if (auth()->user()->roles->first()->name != 'Sub-Business Users') {
                    $check_plan = UserSubscription::where('user_id',Auth::user()->id)->count();
                    if($check_plan == 0){
                        return redirect()->route('front.plans')->with('info', config('constants.LOGIN_MSG').' Please choose any plan');
                    }
                }
                return redirect()->route('front.home')->with('success', config('constants.LOGIN_MSG'));
            } else {
                $check_email = User::where('email', $user->email)->first();
                if($check_email){
                    if($check_email['social_status']){
                        $login_user = $check_email['social_status'];
                    }else{
                        $login_user = "Normal User";
                    }
                    return redirect()->route('front.login')->with('error', 'Your email address already registered with us using '.$login_user.'. Please try to login using same method');
                }else{

                    // new login
                    $full_name =  explode(' ', $user->name);
                    $data = [
                        'first_name' => $full_name[0],
                        'last_name' => $full_name[1],
                        'email' => $user->email,
                        'social_id' => $user->id,
                        'user_type' => "web",
                        'social_status'=> 'Facebook',
                    ];

                    $user = User::create($data);
                    Auth()->login($user);

                    if (auth()->user()->roles->first()->name != 'Sub-Business Users') {
                        $check_plan = UserSubscription::where('user_id',Auth::user()->id)->count();
                        if($check_plan == 0){
                            return redirect()->route('front.plans')->with('info', config('constants.SOCIAL_REGISTER').' Please choose any plan');
                        }
                    }
                    // return view('front.home', $this->data);
                    return redirect()->route('front.home')->with('success', config('constants.SOCIAL_REGISTER'));
                }
            }
        }
        catch(Exception $e) {
            return redirect('front.home')->with('error', config('constants.ERROR_MSG'));
        }
    }

    public function user_activity_log($user_id){

        $today_date = date('Y-m-d');
        // $today_date = "2020-10-08";
        $check_user = UserActivityLog::where('user_id',$user_id)->whereDate('created_at',$today_date)->first();

        if($check_user){
            $starttimestamp = strtotime($check_user['created_at']);
            $endtimestamp = strtotime(date('Y-m-d h:i:s'));
            $difference = abs($endtimestamp - $starttimestamp)/3600;

            UserActivityLog::where('user_id',$user_id)->update([
                'updated_at' => date('Y-m-d h:i:s'),
                'hours' =>round($difference,2)
            ]);
        }else{
            UserActivityLog::insert([
                'user_id' => $user_id,
                'created_at' => date('Y-m-d h:i:s'),
            ]);
        }

    }
}
