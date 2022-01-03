<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Model\UserInfo;
use App\Lib\CommonTask;
use DateTime;

class DashboardController extends Controller
{
    public $data;
    public $common_task;

    public function __construct()
    {
        $this->common_task = new CommonTask();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: private, no-store, max-age=0, no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
    }

    public function hourDiff($startDate, $endDate)
    {
        $startDate = date('Y-m-d H:i:s a',strtotime($startDate));
        $endDate = date('Y-m-d H:i:s a',strtotime($endDate));
        $d1= new DateTime($startDate); // first date
        $d2= new DateTime($endDate); // second date
        $interval= $d1->diff($d2); // get difference between two dates
        $hour = ($interval->days * 24) + $interval->h; // convert days to hours and add hours from difference
        return $hour;
    }

    public function index()
    {
        return view('front.dashboard.index',$this->data);
    }

    public function save_personal_info(Request $request){
        $request_data = $request->all();

        $user_data = [
            'first_name' => $request_data['first_name'],
            'last_name' => $request_data['last_name'],
            'updated_ip' => $request->ip(),
        ];
        $data = [];
        if (User::whereId(Auth()->user()->id)->update($user_data)) {
            $user_info = [
                'user_id' => Auth()->user()->id,
                'gender' => $request_data['gender'],
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

            if(isset($request_data['organization_name'])){
                $user_info['organization_name'] = $request_data['organization_name'];
            }else{
                $user_info['organization_name'] = NULL;
            }

            $check_data = UserInfo::where('user_id', Auth()->user()->id)->first();
            if($check_data){
                unset($user_info['user_id']);
                UserInfo::where('user_id', Auth()->user()->id)->update($user_info);
            }else{
                UserInfo::insert($user_info);
            }

            $data['status'] = true;
            $data['message'] = "Profile updated successfully.";
        }else{
            $data['status'] = false;
            $data['message'] = "Profile not updated try again.";
        }
        echo json_encode($data);die;
    }

    public function get_profile_statistics(){

        $counter = Auth::user()->profile_check;

        /* $user = User::whereId(Auth()->user()->id)->first();
        $counter = 0;
        if($user->first_name && $user->last_name){
            $counter = 66.68;
        }

        // get_user_interest
        if(count($user->get_user_interest)){
            $counter += 16.67;
        }

        if($user->get_user_info && $user->get_user_info->phone_number && $user->get_user_info->gender && $user->get_user_info->address && $user->get_user_info->unit_street && $user->get_user_info->city && $user->get_user_info->state && $user->get_user_info->country && $user->get_user_info->postal_code && $user->get_user_info->organization_name){
            $counter += 16.67;
        } */

        return response()->json(['counter'=>$counter]);
    }
}
