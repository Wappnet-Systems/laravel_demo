<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;

use App\Model\Survey;
use App\Model\Contest;

use App\Model\SurveyConducted;
use App\Model\UserActivityLog;
use App\Model\UserSubscription;
use App\Lib\CommonTask;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use App\Model\YourClients;
use App\Model\Settings;
use App\Model\Notifications;
use App\Model\AboutUsContent;
use App\Model\FaqContent;
use App\Model\PrivacyPolicyContent;
use App\Model\Key_features;
use App\Model\Homepage_banner;
use App\Model\Demonstration;
use App\Model\Enterprise_solutions;
use App\Model\UserFriendRequest;
use DB;

class HomeController extends Controller
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

    public function index()
    {
        $this->data['client'] = YourClients::where('status', "Enabled")->orderBy('id', 'asc')->get();
        
        return view('front.home', $this->data);
    }

    public function user_log(Request $request)
    {
        $expire_time = $request->get('timeSpent');
        $expire_time = substr($expire_time, 0, strpos($expire_time, '('));
        $update_at = date('Y-m-d h:i:s', strtotime($expire_time));

        if (Auth::check()) {
            $today_date = date('Y-m-d');
            $check_user = UserActivityLog::where('user_id', Auth::user()->id)->whereDate('created_at', $today_date)->first();

            if ($check_user) {
                $starttimestamp = strtotime($check_user['created_at']);
                $endtimestamp = strtotime($update_at);
                $difference = abs($endtimestamp - $starttimestamp) / 3600;

                UserActivityLog::where('user_id', Auth::user()->id)->update([
                    'updated_at' => $update_at,
                    'hours' => round($difference, 2)
                ]);
            }
        }
    }

    public function notification_count(Request $request)
    {
        if ($request->get('type')) {
            Notifications::where('user_id', Auth::user()->id)->update(['read' => "1"]);
        }

        $count = Notifications::where('user_id', Auth::user()->id)->where('read', 0)->orderBy('id', 'DESC')->count();

        $last_notification = Notifications::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->limit(3)->get();

        return response()->json(['count' => $count, 'last_notification' => $last_notification]);
    }

    public function all_notification()
    {
        Notifications::where('user_id', Auth::user()->id)->update(['read' => "1"]);

        $this->data['list'] = Notifications::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get()->toArray();

        return view('front.dashboard.all_notification', $this->data);
    }

    public function remove_notification(Request $request)
    {
        if ($request->get('notification_id') == "all") {
            $notifications = Notifications::where('user_id', Auth::user()->id)->get();

            foreach ($notifications as $key => $notification) {
                $notification->delete();
            }

            // Notifications::where('user_id', Auth::user()->id)->delete();

            return response()->json(['status' => true, 'messages' => "Remove all notification"]);
        }

        if (Notifications::whereId($request->get('notification_id'))->exists()) {
            $notification = Notifications::whereId($request->get('notification_id'))->first();
            
            $notification->delete();

            return response()->json(['status' => true, 'messages' => "Remove notification"]);
        } else {
            return response()->json(['status' => false, 'messages' => "Not remove notification"]);
        }
    }

    public function checkAjaxUserStatus(){
        if(Auth::check()){
            $user_data = Auth::user();
            if($user_data['status'] == "Disabled"){
                Auth::logout();
                return response()->json(['status' => false]);
            }else{
                return response()->json(['status' => true]);
            }
        }else{
            return response()->json(['status' => true]);
        }
    }
}
