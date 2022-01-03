<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Lib\UploadFile;
use App\Lib\CommonTask;
use App\Model\UserInfo;
use App\Model\Notifications;

class UserController extends Controller
{
    private $upload_file;
    private $common_task;

    public function __construct()
    {
        $this->upload_file = new UploadFile();
        $this->common_task = new CommonTask();
    }

    // User change password
    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => "required",
            'new_password' => "required",
            'confirm_password' => 'required|same:new_password'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $request_data = $request->all();

        if (!(Hash::check($request_data['old_password'], Auth::user()->password))) {
            //The passwords matches
            return response()->json(['status' => false, 'msg' => "Your current password does not matches with the password you provided. Please try again.", 'data' => []]);
        }


        $user = new User();
        $save_password = $user::where('id', Auth::User()->id)->first();
        $save_password->password = Hash::make($request->get('new_password'));
        if ($save_password->save()) {
            return response()->json(['status' => true, 'msg' => "Your password is successfully updated.", 'data' => []]);
        } else {
            return response()->json(['status' => false, 'msg' => config("errors.invalid_login.msg"), 'error_code' => config("errors.invalid_login.code")]);
        }
    }

    // User logout from app
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status' => true, 'msg' => "You are successfully logged out.", 'data' => []]);
    }

    // User profile details
    public function get_profile_details()
    {
        $user_detail = Auth::user();
        $user_detail->get_user_info = Auth::user()->get_user_info;
        return response()->json(['status' => true, 'msg' => 'Record Found.', 'data' => $user_detail]);
    }

    // User update profile details
    public function update_profile_details(Request $request)
    {
        $validator_normal = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'gender' => 'required',
            // 'organization_name' => 'required',
            'address' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'unit_street' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'postal_code' => 'required',
            'user_interest' => 'required',
        ]);
        if ($validator_normal->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $request_data = $request->all();

        $user_arr = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'updated_at' => date('Y-m-d h:i:s'),
        ];

        if (User::whereId(Auth::user()->id)->update($user_arr)) {

            $user_info = [
                'user_id' => Auth::user()->id,
                'gender' => $request_data['gender'],
                'phone_number' => $request_data['phone_number'],
                'date_of_birth' => ($request_data['date_of_birth']) ? date('Y-m-d', strtotime($request_data['date_of_birth'])) : NULL,
                'address' => $request_data['address'],
                'latitude' => $request_data['latitude'],
                'longitude' => $request_data['longitude'],
                'unit_street' => $request_data['unit_street'],
                'city' => $request_data['city'],
                'state' => $request_data['state'],
                'country' => $request_data['country'],
                'postal_code' => $request_data['postal_code'],
                // 'organization_name' => (isset($request_data['organization_name'])) ? $request_data['organization_name'] : NULL,
                'created_ip' => $request->ip(),
                'updated_ip' => $request->ip(),
            ];

            $check_data = UserInfo::where('user_id', Auth()->user()->id)->first();
            if ($check_data) {
                unset($user_info['user_id']);
                UserInfo::where('user_id', Auth()->user()->id)->update($user_info);
            } else {
                UserInfo::insert($user_info);
            }

            // User Interest
            UserInterest::where('user_id', Auth::user()->id)->delete();
            if ($request->user_interest) {
                $interest = explode(',', $request->get('user_interest'));
                foreach ($interest as $key => $value) {
                    UserInterest::insert([
                        'user_id' => Auth::user()->id,
                        'site_interest_id' => $value,
                        'created_ip' => $request->ip(),
                        'updated_ip' => $request->ip(),
                    ]);
                }
            }

            return response()->json(['status' => true, 'msg' => "Profile details successfully updated.", 'data' => []]);
        } else {
            return response()->json(['status' => false, 'msg' => config("errors.invalid_login.msg"), 'error_code' => config("errors.invalid_login.code")]);
        }

        return response()->json(['status' => true, 'msg' => "Profile updated successfully.", 'data' => []]);
    }

    // User update profile photo
    public function update_profile_photo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_image' => "required|image|mimes:jpeg,png,jpg",
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }

        $profile_image = $this->upload_file->upload_s3_file($request, 'profile_image', "profile_image");
        if ($profile_image) {
            if (Auth::user()->profile_image) {
                $this->upload_file->delete_s3_file(Auth::user()->profile_image);
            }
            $user_arr = [
                'profile_image' => $profile_image,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_ip' => $request->ip()
            ];
            User::where('id', Auth::user()->id)->update($user_arr);

            //get updated user data
            $updated_user = Auth::user()->fresh();

            return response()->json(['status' => true, 'msg' => "Profile photo successfully updated.", 'data' => $updated_user]);
        }

        return response()->json(['status' => false, 'msg' => config("errors.general_error.msg"), 'error_code' => config("errors.general_error.code")]);
    }

    // Notification
    public function get_notification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }

        $request_data = $request->all();

        $page_limit = 10;

        $offset = ($request_data['page'] - 1) * $page_limit;

        Notifications::where('user_id', Auth::user()->id)->update(['read' => "1"]);

        $list = Notifications::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->offset($offset)->limit($page_limit)->get()->toArray();

        if (count($list)) {
            return response()->json(['status' => true, 'msg' => "Record Found.", 'data' => $list]);
        } else {
            return response()->json(['status' => false, 'msg' => config("errors.no_record.msg"), 'error_code' => config("errors.no_record.code")]);
        }
    }

    // Remove Notification
    public function remove_notification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notification_id' => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }

        if (Notifications::whereId($request->get('notification_id'))->exists()) {
            $notification = Notifications::whereId($request->get('notification_id'))->first();

            $notification->delete();

            return response()->json(['status' => true, 'msg' => "Remove notification", 'data' => []]);
        } else {
            return response()->json(['status' => false, 'msg' => "Not remove notification", 'data' => []]);
        }
    }

    // Clear Notification
    public function clear_notification(Request $request)
    {
        $notifications = Notifications::where('user_id', Auth::user()->id)->get();

        foreach ($notifications as $key => $notification) {
            $notification->delete();
        }

        return response()->json(['status' => true, 'msg' => "Clear notification", 'data' => []]);
    }
}
