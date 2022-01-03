<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Model\UserInfo;
use App\Model\UserSubscription;
use App\Model\Settings;
use App\Lib\CommonTask;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use App\Lib\UploadFile;
use Illuminate\Support\Facades\Session;
use Redirect;
use App\Model\SiteInterest;
use App\Model\UserInterest;
use App\Model\UserWalletRequest;
use App\Model\PointRanking;

class AccountController extends Controller
{
    public $data;
    public $common_task;
    public $upload_file;

    public function __construct()
    {
        $this->common_task = new CommonTask();
        $this->upload_file = new UploadFile();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: private, no-store, max-age=0, no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
    }

    public function account()
    {
        $user = Auth::user();

        $this->data['plan_type'] = '';
        $this->data['plan_created_at'] = '';
        $this->data['get_google_key'] = '';
        $this->data['get_wallet_amount'] = 0;
        $this->data['site_interest'] = [];
        $this->data['selected_interest'] = [];
        $this->data['check_withdrawal_request'] = 0;
        $this->data['total_earn_point'] = 0;
        $this->data['current_rank_title'] = "";
        $this->data['current_rank_percent'] = 0;
        $this->data['current_color'] = "#e1e7ec";
        $this->data['next_rank_title'] = "";
        $this->data['full_icon_path'] = "";
        $this->data['start_point'] = 0;
        $this->data['end_point'] = 0;
        
        return view('front.dashboard.account', $this->data);
    }

    public function save_account_detail(Request $request){
        $user = Auth::user();

        if ($user->hasRole('Sub-Business Users')) {
            return redirect()->route('front.dashboard');
        }
        
        $validator_normal = Validator::make($request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'phone_number' => 'required',
                    'gender' => 'required',
                    'language' => 'required',
                    'address' => 'required',
                    'unit_street' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'country' => 'required',
                    'postal_code' => 'required',
        ]);
        if ($validator_normal->fails()) {
            return redirect()->back()->with('error', 'Please follow validation rules.');
        }
        $request_data = $request->all();
        // dd(isset($request_data['organization_name']));
        $user = UserInfo::where('user_id', Auth()->user()->id)->first(); 
        $statusChange = "0";
        // dd($user);
        /* $organization_name = "";$business_contact_number= "";
        $business_contact_email = ""; $business_website = "";
        $business_registration_number = ""; */
        if ($user->getOriginal('organization_name') ?? null || $user->getOriginal('business_contact_number') ??null ||
            $user->getOriginal('business_contact_email') ??null || $user->getOriginal('business_website') ??null || $user->getOriginal('business_registration_number') ??null) {
            if (isset($request_data['organization_name']) && $user->getOriginal('organization_name') != $request_data['organization_name']){
                // $organization_name = $request_data['organization_name'];
                $statusChange = "0";
            }elseif(isset($request_data['business_contact_number']) && $user->getOriginal('business_contact_number') != $request_data['business_contact_number']){
                // $business_contact_number = $request_data['business_contact_number'];
                $statusChange = "0";
            }elseif(isset($request_data['business_contact_email']) && $user->getOriginal('business_contact_email') != $request_data['business_contact_email']){
                $statusChange = "0";
                // $business_contact_email = $request_data['business_contact_email'];
            }elseif(isset($request_data['business_website']) && $user->getOriginal('business_website') != $request_data['business_website']){
                $statusChange = "0";
                // $business_website = $request_data['business_website'];
            }
            elseif(isset($request_data['business_registration_number']) && $user->getOriginal('business_registration_number') != $request_data['business_registration_number']){
                $statusChange = "0";
                // $business_registration_number = $request_data['business_registration_number'];
            } else {
                $statusChange = "1";
            }
        }
        $user_arr = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'business_account_approval' => $statusChange,
            'updated_at' => date('Y-m-d h:i:s'),
        ];
        if ($request->hasFile('profile_image')) {
            $profile_img = $request->file('profile_image');
            $user_arr['profile_image'] = $this->upload_file->upload_s3_file($request, 'profile_image',"profile_image");
            if(Auth::user()->profile_image){
                $response = $this->upload_file->delete_s3_file(Auth::user()->profile_image);
            }

        }
        // dd($user_arr);
        if (User::whereId(Auth::user()->id)->update($user_arr)) {
            $user_info = [
                'user_id' => Auth()->user()->id,
                'gender' => $request_data['gender'],
                'language' => $request_data['language'],
                'phone_number' => $request_data['phone_number'],
                'date_of_birth' => ($request_data['date_of_birth']) ? date('Y-m-d', strtotime($request_data['date_of_birth'])) : NULL,
                'address' => $request_data['address'],
                'latitude' => !empty($request_data['latitude']) ? $request_data['latitude'] : null,
                'longitude' => !empty($request_data['longitude']) ? $request_data['longitude'] : null,
                'unit_street' => $request_data['unit_street'],
                'city' => $request_data['city'],
                'state' => $request_data['state'],
                'country' => $request_data['country'],
                'postal_code' => $request_data['postal_code'],
                'organization_name' => (isset($request_data['organization_name'])) ? $request_data['organization_name'] : NULL,
                'business_contact_number' => (isset($request_data['business_contact_number'])) ? $request_data['business_contact_number'] : NULL,
                'business_contact_email' => (isset($request_data['business_contact_email'])) ? $request_data['business_contact_email'] : NULL,
                'business_website' => (isset($request_data['business_website'])) ? $request_data['business_website'] : NULL,
                'business_registration_number' => (isset($request_data['business_registration_number'])) ? $request_data['business_registration_number'] : NULL,
                'created_ip' => $request->ip(),
                'updated_ip' => $request->ip(),
            ];

            $check_data = UserInfo::where('user_id', Auth()->user()->id)->first();
            if($check_data){
                unset($user_info['user_id']);
                if ($request->hasFile('business_logo')) {
                    $business_logo = $request->file('business_logo');
                    $user_info['business_logo'] = $this->upload_file->upload_s3_file($request, 'business_logo',"business_logo");
                    if($check_data->business_logo){
                        $response = $this->upload_file->delete_s3_file(Auth::user()->business_logo);
                    }
        
                }

                UserInfo::where('user_id', Auth()->user()->id)->update($user_info);
                if(Auth::user()->roles[0]['name'] != "Normal User") {
                    // Business information change email code
                    if ($user->getOriginal('organization_name') ?? null || $user->getOriginal('business_contact_number') ??null || $user->getOriginal('business_contact_email') ??null || $user->getOriginal('business_website') ??null || $user->getOriginal('business_registration_number') ??null) {
                        if($user->getOriginal('organization_name') != $request_data['organization_name'] || $user->getOriginal('business_contact_number') != $request_data['business_contact_number'] || $user->getOriginal('business_contact_email') != $request_data['business_contact_email'] || $user->getOriginal('business_website') != $request_data['business_website'] || $user->getOriginal('business_registration_number') != $request_data['business_registration_number']) {
                            $user_details = User::whereId(Auth()->user()->id)->with('get_user_info')->first();
                            $superAdminEmail = User::where('user_type','admin')->pluck('email')->toArray();
                            $mail_data = [];
                            // $mail_data['to_email'] = "darshan@mailinator.com";
                            $mail_data['to_email'] = $superAdminEmail;
                            if ($user->getOriginal('organization_name') != $request_data['organization_name']){
                                $mail_data['organization_name'] = $request_data['organization_name'];
                            } else {
                                $mail_data['organization_name'] = "Not Updated";
                            }
                            if($user->getOriginal('business_contact_number') != $request_data['business_contact_number']){
                                $mail_data['business_contact_number'] = $request_data['business_contact_number'];   
                            } else{
                                $mail_data['business_contact_number'] = "Not Updated";
                            }
                            if($user->getOriginal('business_contact_email') != $request_data['business_contact_email']){
                                $mail_data['business_contact_email'] = $request_data['business_contact_email'];
                            } else{
                                $mail_data['business_contact_email'] = "Not Updated";
                            }
                            if($user->getOriginal('business_website') != $request_data['business_website']){
                                $mail_data['business_website'] = $request_data['business_website'];
                            } else{
                                $mail_data['business_website'] = "Not Updated";
                            }
                            if($user->getOriginal('business_registration_number') != $request_data['business_registration_number']){
                                $mail_data['business_registration_number'] = $request_data['business_registration_number'];
                            } else {
                                $mail_data['business_registration_number'] = "Not Updated";
                            }
                            $mail_data['full_name'] = $user_details['first_name'] . " " . $user_details['last_name'];
                            $mail_data['from_email'] = $user_details['email'];
                            $this->common_task->user_account_approved_request($mail_data);
                        }
                    }
                }
            }else{
                UserInfo::insert($user_info);
            }

            // User Interest
            UserInterest::where('user_id', Auth::user()->id)->delete();
            if($request->get('user_interest') != null) {
                if($request->get('user_interest')){
                    foreach ($request->get('user_interest') as $key => $value) {
                        UserInterest::insert([
                            'user_id' => Auth::user()->id,
                            'site_interest_id' => $value,
                            'created_ip' => $request->ip(),
                            'updated_ip' => $request->ip(),
                        ]);
                    }
                }
            }
            if ($user->getOriginal('organization_name') ??null || $user->getOriginal('business_contact_number') ??null ||
            $user->getOriginal('business_contact_email') ??null || $user->getOriginal('business_website') ??null || $user->getOriginal('business_registration_number') ??null) {
                $get_subscription = UserSubscription::where('user_id',Auth::user()->id)->first();
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
                        Session::flash('success', "Profile details successfully updated");
                        Session::flash('info', "Your ".$this->data['plan_type']." account details successfully submitted and your account is under review. You will be notified soon once your account is approved.");
                        return Redirect::back(); 
            } else if(Auth::user()->getProfileCheckAttribute() < "100"){
                        Session::flash('success', "Profile details successfully updated");
                        Session::flash('info', "please filed all Dashboard data after Your account going for approving.");
                        return Redirect::back(); 
            } else {
                Session::flash('success', "Profile details successfully updated");
                return Redirect::back();
            }
            
        } else {
            Session::flash('error', "Error Occurred. Try Again!");
            return Redirect::back();
        }
    }

    public function change_password(Request $request){
        $validator_normal = Validator::make($request->all(), [
                    'old_password' => 'required',
                    'password' => 'required',
                    'c_password' => 'required',
        ]);
        if ($validator_normal->fails()) {
            return redirect()->back()->with('error', 'Please follow validation rules.');
        }
        if (!(Hash::check($request->get('old_password'), Auth::user()->password))) {
            //The passwords matches
            return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");
        }
        if (strcmp($request->get('old_password'), $request->get('password')) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("error", "New Password cannot be same as your current password. Please choose a different password.");
        }
        $user = new User();
        $save_password = $user::where('id', Auth::User()->id)->first();
        $save_password->password = Hash::make($request->get('password'));
        if ($save_password->save()) {
            return redirect()->back()->with("success", "Password changed successfully !");
        } else {
            return redirect()->back()->with("error", "Password does not changed successfully !");
        }
    }
}
