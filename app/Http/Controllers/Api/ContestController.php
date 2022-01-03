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
use App\Model\Notifications;
use App\Lib\UploadFile;
use Illuminate\Support\Facades\URL;
use App\Model\PaymentTransaction;
use App\Model\UserInfo;
use App\Model\Contest;
use App\Model\UserContestLike;
use App\Model\UserFavoriteContest;
use App\Model\ContestConducted;
use App\Model\ContestComments;
use App\Model\ContestCatalogue;
use App\Model\ContestIncrementPrices;
use App\Model\UserActivityTrack;
use DateTime;

class ContestController extends Controller
{

    public $common_task;
    private $data;
    public $upload_file;

    public function __construct()
    {
        $this->common_task = new CommonTask();
        $this->upload_file = new UploadFile();
    }

    public function contest_like_dislike(Request $request){
        $validator = Validator::make($request->all(), [
            'contest_id' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $user_id = Auth::user()->id;
        $check = UserContestLike::where('contest_id',$request->get('contest_id'))->where('user_id',$user_id)->count();
        
        if($check){
            UserContestLike::where('contest_id',$request->get('contest_id'))->where('user_id',$user_id)->delete();
            $counter = UserContestLike::where('contest_id',$request->get('contest_id'))->count();
            return response()->json(['status' => true, 'msg' => 'Unlike Successfully.', 'data' => ['like_counter'=>$counter]]);
        }else{

            $data_arr = [
                'user_id' => $user_id,
                'contest_id' => $request->get('contest_id'),
                'created_ip' => $request->ip(),
                'updated_ip' => $request->ip(),
            ];
            UserContestLike::insert($data_arr);
            $counter = UserContestLike::where('contest_id',$request->get('contest_id'))->count();
            
            $contest_data = Contest::whereId($request->get('contest_id'))->first();
            // dd($contest_data);
            
            if($contest_data['user_id'] != $user_id){

                $check_last_notification = Notifications::where('type','Contest')->where('user_id',$contest_data['user_id'])->where('contest_id',$request->get('contest_id'))->count();
                
                if($check_last_notification == 0){
                    $notification_arr = [
                        'user_id' => $contest_data['user_id'],
                        'contest_id' => $request->get('contest_id'),
                        'type' => "Contest",
                        'messages' => Auth::user()->first_name." ".Auth::user()->last_name." like ".strip_tags($contest_data['contest_title'])." contest.",
                        'created_ip' => $request->ip(),
                        'updated_ip' => $request->ip(),
                    ];
                    // dd($notification_arr);
                    $notification = new Notifications();
                    $notification->send_notification($notification_arr);
                }
            }


            return response()->json(['status' => true, 'msg' => 'Like Successfully.', 'data' => ['like_counter'=>$counter]]);
        }
    }

    public function favorite_contest(Request $request){
        $validator = Validator::make($request->all(), [
            'contest_id' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $user_id = Auth::user()->id;
        $check = UserFavoriteContest::where('contest_id',$request->get('contest_id'))->where('user_id',$user_id)->count();
        if($check){
            UserFavoriteContest::where('contest_id',$request->get('contest_id'))->where('user_id',$user_id)->delete();
            // User Activity Track
            UserActivityTrack::where(['activity'=>config('useractivity.FAVOURITE_CONTEST'),'user_id' => Auth::user()->id,'contest_id' => $request->get('contest_id')])->delete();
            
            return response()->json(['status' => true, 'msg' => 'Remove Favorite Successfully.', 'data' => []]);
        }else{
            $data_arr = [
                'user_id' => $user_id,
                'contest_id' => $request->get('contest_id'),
                'created_ip' => $request->ip(),
                'updated_ip' => $request->ip(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            UserFavoriteContest::insert($data_arr);

            // User Activity Track
            $check_track = UserActivityTrack::where(['activity'=>config('useractivity.FAVOURITE_CONTEST'),'user_id' => Auth::user()->id,'contest_id' => $request->get('contest_id')])->count();
            if($check_track == 0){
                $activity_track = new UserActivityTrack();
                $point = $activity_track->get_activity_point(config('useractivity.FAVOURITE_CONTEST'));
                $activity_track_arr = [
                    'activity' => config('useractivity.FAVOURITE_CONTEST'),
                    'point' => $point,
                    'user_id' => Auth::user()->id,
                    'contest_id' => $request->get('contest_id'),
                    'created_ip' => \Request::ip(),
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $activity_track->insert_track($activity_track_arr);
            }
            
            return response()->json(['status' => true, 'msg' => 'Add Favorite Successfully.', 'data' => []]);
        }
    }

    public function contest_favorite_list(Request $request){
        $validator = Validator::make($request->all(), [
            'page' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $request_data = $request->all();
        $page_limit = 10;
        $offset = ($request_data['page'] - 1) * $page_limit;
        $user_id = Auth::user()->id;

        $favorite = Contest::select('user_favorite_contest.contest_id','user_favorite_contest.created_at','contest.uuid','contest.contest_title','contest.contest_nick_name')
            ->join('user_favorite_contest','user_favorite_contest.contest_id','=','contest.id')
            ->where('user_favorite_contest.user_id',$user_id)
            ->offset($offset)
            ->limit($page_limit)
            ->get()
            ->makeHidden(['file_type', 'contest_file_full_path']);
        if($favorite->count()){
            foreach ($favorite as $key => $value) {
                $check_contest = ContestConducted::where('contest_id',$value['contest_id'])->where('user_id',$user_id)->count();
                if($check_contest == 0){
                    $favorite[$key]['isContestFilled'] = false;
                }else{
                    $favorite[$key]['isContestFilled'] = true;
                }
            }
            return response()->json(['status' => true, 'msg' => 'Record Found.', 'data' => $favorite]);
        }else{
            return response()->json(['status' => false, 'msg' => config("errors.no_record.msg"), 'error_code' => config("errors.no_record.code")]);
        }
    }

    public function get_contest_comments(Request $request){
        $validator = Validator::make($request->all(), [
            'contest_id' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $request_data = $request->all();
        $user_id = Auth::user()->id;
        $contest_query = Contest::select('id','uuid','contest_title','contest_nick_name','rule_instruction_exclusions','how_to_apply','contest_file','user_id')->whereId($request_data['contest_id'])->where('contest_status','Started')->with(['get_user_info','get_conducted'])->withCount(['likes','favotite','check_like']);
        $contest = $contest_query->first();

        $contest['comments_count'] = ContestComments::where('contest_id',$contest['id'])->count();

        $contest['comments'] = ContestComments::select('id','user_id','contest_id','comment','created_at')->where('contest_id',$request_data['contest_id'])->where('perent_comment_id',0)->with(['get_user_name'=> function($query) {
            return $query->select(['id','first_name','last_name','email','profile_image']);
        }])->get();

        foreach ($contest['comments'] as $key => $value) {
            $contest['comments'][$key]['sub_comments'] = ContestComments::select('id','user_id','contest_id','comment','created_at')->where('perent_comment_id',$value['id'])->with(['get_user_name'=> function($query) {
                return $query->select(['id','first_name','last_name','email','profile_image']);
            }])->get();
        }

        if($contest){
            return response()->json(['status' => true, 'msg' => 'Record Found.', 'data' => $contest]);
        }else{
            return response()->json(['status' => false, 'msg' => config("errors.no_record.msg"), 'error_code' => config("errors.no_record.code")]);
        }
    }

    public function add_contest_comment(Request $request){
        $validator = Validator::make($request->all(), [
            'contest_id' => "required",
            'comment' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $request_data = $request->all();
        $user_id = Auth::user()->id;
        // ContestComments
        $arr = [
            'user_id' => $user_id,
            'contest_id' => $request_data['contest_id'],
            'comment' => $request_data['comment'],
            'perent_comment_id' => ($request_data['perent_comment_id']) ? $request_data['perent_comment_id'] : 0,
            'created_ip' => $request->ip(),
            'updated_ip' => $request->ip(),
        ];
        if($commentId = ContestComments::insertGetId($arr)){
            $counter = ContestComments::where('contest_id',$request_data['contest_id'])->count();

            $contest_data = Contest::whereId($request_data['contest_id'])->first();
            // dd($contest_data);
            if($contest_data['user_id'] != Auth::user()->id){
                $notification_arr = [
                    'user_id' => $contest_data['user_id'],
                    'type' => "Contest",
                    'messages' => Auth::user()->first_name." ".Auth::user()->last_name." write ".strip_tags($contest_data['contest_title'])." contest comment.",
                    'created_ip' => $request->ip(),
                    'updated_ip' => $request->ip(),
                ];

                $notification = new Notifications();
                $notification->send_notification($notification_arr);
            }

            // User Activity Track
            $activity_track = new UserActivityTrack();
            $point = $activity_track->get_activity_point(config('useractivity.CONTEST_COMMENT'));
            $activity_track_arr = [
                'activity' => config('useractivity.CONTEST_COMMENT'),
                'point' => $point,
                'user_id' => Auth::user()->id,
                'contest_id' => $request->get('contest_id'),
                'comment_id' => $commentId,
                'created_ip' => \Request::ip(),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $activity_track->insert_track($activity_track_arr);

            return response()->json(['status' => true, 'msg' => 'Contest comment added successfully.', 'data' => ['comment_counter'=>$counter]]);
        }else{
            $counter = ContestComments::where('contest_id',$request->get('contest_id'))->count();
            return response()->json(['status' => false, 'msg' => config('errors.general_error.msg'), 'data' => ['comment_counter'=>$counter]]);
        }
    }

    public function remove_contest_comment(Request $request){
        $validator = Validator::make($request->all(), [
            'contest_id' => "required",
            'comment_id' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }

        $comment_query = ContestComments::where('id',$request->get('comment_id'));
        $comment_query->orWhere('perent_comment_id',$request->get('comment_id'));
        if($comment_query->delete()){
            $counter = ContestComments::where('contest_id',$request->get('contest_id'))->count();

            UserActivityTrack::where(['activity'=>config('useractivity.CONTEST_COMMENT'),'user_id' => Auth::user()->id,'contest_id' => $request->get('contest_id'),'comment_id'=>$request->get('comment_id')])->delete();

            return response()->json(['status' => true, 'msg' => 'Remove comment successfully.', 'data' => ['comment_counter'=>$counter]]);
        }
        return response()->json(['status' => false, 'msg' => config("errors.no_record.msg"), 'error_code' => config("errors.no_record.code")]);
    }

    public function apply_contest(Request $request){
        $validator = Validator::make($request->all(), [
            'uuid' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $request_data = $request->all();
        $user_id = Auth::user()->id;
        $contest = new ContestConducted();
        $contest->user_id = Auth::user()->id;
        $contest->contest_id = Contest::where('uuid',$request_data['uuid'])->value('id');
        $contest->created_ip = $request->ip();
        $contest->updated_ip = $request->ip();
        $contest->created_at = date('Y-m-d h:i:s');
        $contest->updated_at = date('Y-m-d h:i:s');
        if(isset($request_data['contest_link'])){
            $contest->contest_link = $request_data['contest_link'];
        }

        if ($request->hasFile('contest_image')) {
            $contest->contest_image = $this->upload_file->upload_s3_file($request, 'contest_image',"contest_image");
        }

        if ($request->hasFile('contest_video')) {
            $contest->contest_video = $this->upload_file->upload_s3_file($request, 'contest_video',"contest_video");
        }
        
        if ($request->hasFile('contest_audio')) {
            $contest->contest_audio = $this->upload_file->upload_s3_file($request, 'contest_audio',"contest_audio");
        }
        if($contest->save()){

            // User Activity Track
            $check_track = UserActivityTrack::where(['activity'=>config('useractivity.FILL_CONTEST'),'user_id' => Auth::user()->id,'contest_id' => $contest->contest_id])->count();
            if($check_track == 0){
                $activity_track = new UserActivityTrack();
                $point = $activity_track->get_activity_point(config('useractivity.FILL_CONTEST'));
                $activity_track_arr = [
                    'activity' => config('useractivity.FILL_CONTEST'),
                    'point' => $point,
                    'user_id' => Auth::user()->id,
                    'contest_id' => $contest->contest_id,
                    'created_ip' => \Request::ip(),
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $activity_track->insert_track($activity_track_arr);
            }

            return response()->json(['status' => true, 'msg' => 'Fill contest successfully.', 'data' => []]);
        }else{
            return response()->json(['status' => false, 'msg' => 'Error Occurred. Try Again!', 'data' => []]);
        }
    }

    public function get_filled_contest_list(Request $request){
        $validator = Validator::make($request->all(), [
            'page' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $request_data = $request->all();
        $page_limit = 10;
        $offset = ($request_data['page'] - 1) * $page_limit;
        $user_id = Auth::user()->id;

        $contest = Contest::select('contest_conducted.contest_id','contest.uuid','contest_conducted.created_at','contest.contest_title','contest.contest_nick_name')
                    ->join('contest_conducted','contest_conducted.contest_id','=','contest.id')
                    ->where('contest_conducted.user_id',$user_id)
                    ->where(function($query) use ($request_data) {
                        // search data
                        if(isset($request_data['search_data'])){
                            $query->where('contest_title', 'LIKE', '%'.$request_data['search_data'].'%');
                        }
                    })
                    ->offset($offset)
                    ->limit($page_limit)
                    ->orderBy('contest_conducted.id', 'DESC')
                    ->get()
                    ->makeHidden(['contest_file_full_path', 'file_type']);
        if($contest->count()){
            foreach ($contest as $key => $value) {
                $check_fav = UserFavoriteContest::where('user_id',$user_id)->where('contest_id',$value['contest_id'])->count();
                if($check_fav == 0){
                    $contest[$key]['isFavorite'] = false;
                }else{
                    $contest[$key]['isFavorite'] = true;
                }
            }

            return response()->json(['status' => true, 'msg' => 'record found', 'data' => $contest]);
        }else{
            return response()->json(['status' => false, 'msg' => config("errors.no_record.msg"), 'error_code' => config("errors.no_record.code")]);
        }
    }

    public function get_contest_prize(Request $request){
        $validator = Validator::make($request->all(), [
            'contest_id' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $request_data = $request->all();
        $contest_prize = ContestCatalogue::where('contest_id',$request_data['contest_id'])->with(['get_catalogue_data.get_catelogue_images'])->get();
        $contest_increment = ContestIncrementPrices::where('contest_id',$request_data['contest_id'])->get();
        if(count($contest_prize) > 0){
            return response()->json(['status' => true, 'msg' => 'record found', 'data' => ['prize'=>$contest_prize,'increment'=>$contest_increment]]);
        }else{
            return response()->json(['status' => false, 'msg' => config("errors.no_record.msg"), 'error_code' => config("errors.no_record.code")]);
        }
    }
}
