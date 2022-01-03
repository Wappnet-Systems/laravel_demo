<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Lib\CommonTask;
use App\Model\Settings;
use App\Model\PasswordResets;
use App\Model\UserInfo;
use App\Model\Login_log;
use App\Model\UserSubscription;
use Spatie\Permission\Models\Role;
use App\Lib\UploadFile;
use Illuminate\Support\Facades\URL;
class LoginController extends Controller
{

    public $common_task;
    private $data;
    public $upload_file;

    public function __construct()
    {
        $this->common_task = new CommonTask();
        $this->upload_file = new UploadFile();
    }

    //Login API
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => "required",
            'password' => "required",
            'fcm_token' => 'required',
            'device' => "required",
            // 'role_id' => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }

        $request_data = $request->all();
        $check_email = User::where('email', $request['email'])->get();

        if (count($check_email) == 0) {
            return response()->json(['status' => false, 'msg' => "No record found.", 'error_code' => config("errors.invalid_login.code")]);
        }

        $userObj = User::where('email', $request['email'])->get();
        // print_r($userObj->toArray());die;
        if ($userObj->count() > 0 && Hash::check($request_data['password'], $userObj[0]->password)) {
            /* if (!$userObj[0]->roles) {
                return response()->json(['status' => false, 'msg' => config("errors.general_error.msg"), 'error_code' => config("errors.general_error.code")]);
            } */
            if ($userObj[0]->status == 'Disabled') {
                return response()->json(['status' => false, 'msg' => config("errors.disable_user.msg"), 'error_code' => config("errors.disable_user.code")]);
            }

            if ($userObj[0]->email_verified_at == '') {
                return response()->json(['status' => false, 'msg' => config("errors.account_verify.msg"), 'error_code' => config("errors.account_verify.code")]);
            }
            // dd('here');
            unset($userObj[0]['password']);
            
            $userObj[0]['auth_token'] = $userObj[0]->createToken($request_data['fcm_token'] . $request_data['device'])->plainTextToken;
            $login_log_arr = [
                'device_id' => $request_data['fcm_token'],
                'device_type' => $request_data['device'],
                'user_id' => $userObj[0]->id,
                'created_ip' => $request->ip()
            ];
            Login_log::insert($login_log_arr);
            $userObj[0]->get_user_interest;
            $userObj[0]->stripe_customer_id = UserInfo::where('user_id',$userObj[0]->id)->value('stripe_customer_id');

            // $user_detail = Auth::user();
            $userObj[0]->subscription_detail = $this->get_user_current_plan($userObj[0]->id);

            return response()->json(['status' => true, 'msg' => "Successfully Logged In.", 'data' => $userObj]);
        } else {
            return response()->json(['status' => false, 'msg' => config("errors.invalid_login.msg"), 'error_code' => config("errors.invalid_login.code")]);
        }
    }

    // Get plan
    public function get_user_current_plan($user_id){
        $get_subscription = UserSubscription::where('user_id',$user_id)->first();
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
            return $plan_data['name'];
        }
    }

    //User Register
    public function user_register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => "required",
            'last_name' => "required",
            'email' => "required|email",
            'fcm_id' => "required",
            'device_type' => 'required',
            'user_type' => "required",
            'role' => "required",
            'login_type' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }

        $request_data = $request->all();
        $remember_token = $this->password_generate(50);
        $register_data = [
            'first_name' => $request_data['first_name'],
            'last_name' => $request_data['last_name'],
            'email' => $request_data['email'],
            'user_type' => $request_data['user_type'],
            'remember_token' => $remember_token,
        ];

        if ($request_data['user_type'] == 'web' && $request_data['login_type'] == "normal") {

            //check email exists
            if (!$this->accountExists($request_data['email'], 'email')) {
                return response()->json(['status' => false, 'msg' => "Email already exists. Please try again.", 'error_code' => config("errors.validation.code")]);
            }
            $register_data['password'] = Hash::make($request_data['password']);

            if (User::insert($register_data)) {
                unset($register_data['password']);

                $user = User::where('email', $request_data['email'])->first();
                
                // Select Normal User
                //role assign to user
                /* $subscription_arr = [
                    'user_id' => $user['id'],
                    'product_id' => "prod_IAZj0oJHuOIlFN",
                    'price_id' => "price_1HaEZgL12XncbYi1BAPVxTJu",
                    'amount' => 0,
                    'price_interval' => "month",
                ];
                UserSubscription::insert($subscription_arr); 
                
                $role = Role::findByName($request_data['role'], 'web');
                $user->assignRole($role); */ 
                
                UserInfo::insert(['user_id' => $user->id]);

                $mail_data = [];
                $mail_data['to_email'] = [$user->email];
                $mail_data['full_name'] = $user['first_name'] . " " . $user['last_name'];
                $mail_data['link'] = URL::to('/')."/vefify_account/".$remember_token;
                //$this->common_task->vefify_account($mail_data);
                return response()->json(['status' => true, 'msg' => "Successfully Register.", 'data' => []]);
            } else {
                return response()->json(['status' => false, 'msg' => config("errors.sql_operation.msg"), 'error_code' => config("errors.sql_operation.code")]);
            }
        } else {
            $register_data['email_verified_at'] = date('Y-m-d h:i:s');
            $register_data['social_status'] = $request_data['social_status'];
            $register_data['social_id'] = $request_data['social_id'];
            $register_data['created_ip'] = $request->ip();
            $register_data['created_at'] = date('Y-m-d h:i:s');
            
            $finduser = User::where('social_id', $request_data['social_id'])->where('social_status', $request_data['social_status'])->first();
            if ($finduser) {
                $check_email = User::where('email', $request_data['email'])->first();
                
                if ($finduser->status == 'Disabled') {
                    return response()->json(['status' => false, 'msg' => config("errors.disable_user.msg"), 'error_code' => config("errors.disable_user.code")]);
                }
                $finduser['auth_token'] = $finduser->createToken($request_data['fcm_id'] . $request_data['device_type'])->plainTextToken;
                        unset($finduser['password']);
                        //assign role array to user object
                        $finduser->roles;
                        $finduser->get_user_interest;
                        return response()->json(['status' => true, 'msg' => "Successfully Logged with {$request_data['social_status']}", 'data' => [$finduser]]);
                
                return redirect()->route('front.home')->with('success', config('constants.LOGIN_MSG'));
            } else {
                $check_email = User::where('email', $request_data['email'])->first();
                if($check_email){
                    if($check_email['social_status']){
                        $login_user = $check_email['social_status'];
                    }else{
                        $login_user = "Normal User";
                    }
                    return response()->json(['status' => false, 'msg' => 'Your email address already registered with us using '.$login_user.'. Please try to login using same method', 'error_code' => config("errors.alreadyLoggedIn.code")]);
                }else{
                    $userId = User::insertGetId($register_data);
                    $userObj = User::find($userId);
                    $userObj->refresh();
                    $userObj->auth_token = $userObj->createToken($request_data['fcm_id'] . $request_data['device_type'])->plainTextToken;
                    $role = Role::findByName($request_data['role'], 'web');
                    $userObj->assignRole($role);
                    $userObj->get_user_interest;
                    return response()->json(['status' => true, 'msg' => "Successfully signed up with {$request_data['social_status']}.", 'data' => [$userObj]]);
                }
            }
        }
    }

    public function accountExists($value, $type)
    {
        //$type will have field name like email or contact_number
        $account_check = User::where($type, $value)->get(['id'])->count();
        if ($account_check > 0) {
            return false;
        } else {
            return true;
        }
    }

    //Forgot Password
    public function forgot_password(Request $request)
    {
        //mail otp code pending
        $validator = Validator::make($request->all(), [
            'email' => "required",
            // 'role_id' => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $email = $request->get('email');
        // dd($email);
        $data = [];
        $user_data = User::where('email',$email)->first();
        if ($user_data) {
            if($user_data['social_id'] != ""){
                $data['status'] = false;
                $data['msg'] = "You are login with ".$user_data['social_status'].". so can't reset password.";
                echo json_encode($data);die;
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
            return response()->json(['status' => true, 'msg' => config('constants.RESET_PASSWORD_LINK_SEND'), 'data' => []]);
        } else {
            return response()->json(['status' => false, 'msg' => 'No record found matching with info provided. Please try again.', 'error_code' => config("errors.no_record.code")]);
        }
    }

    //delete user
    public function delete_user(Request $request)
    {

        //check email exists
        $request_data = $request->all();
        if ($this->accountExists($request_data['email'], 'email')) {
            return response()->json(['status' => false, 'msg' => "Email Not found. Please try again.", 'error_code' => config("errors.validation.code")]);
        }
        if (User::where('email', $request_data['email'])->delete())
            return response()->json(['status' => true, 'msg' => "User Delete Successfully.", 'data' => []]);
        return response()->json(['status' => false, 'msg' => "Something went wrong.", 'data' => []]);
    }

    public function password_generate($chars)
    {
        $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($data), 0, $chars).time();
    }

    public function resend_verification_link(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $email = $request->get('email');
        $remember_token = $this->password_generate(50);
        $user = User::where('email', $email)->first();
        if(!$user){
            return response()->json(['status' => false, 'msg' => 'Email not register.', 'error_code' => config("errors.no_record.code")]);
        }

        if($user['social_status'] != NUll){
            return response()->json(['status' => false, 'msg' => 'Email address register with '.$user['social_status'], 'error_code' => config("errors.no_record.code")]);
        }

        $user->remember_token = $remember_token;

        if($user->save()){
            $mail_data = [];
            $mail_data['to_email'] = [$user->email];
            $mail_data['full_name'] = $user['first_name'] . " " . $user['last_name'];
            $mail_data['link'] = URL::to('/')."/vefify_account/".$remember_token;
            $this->common_task->vefify_account($mail_data);

            return response()->json(['status' => true, 'msg' => "Resend account verification link in your registered email address.", 'data' => []]);
        }
        return response()->json(['status' => false, 'msg' => 'No record found matching with info provided. Please try again.', 'error_code' => config("errors.no_record.code")]);
    }
}
