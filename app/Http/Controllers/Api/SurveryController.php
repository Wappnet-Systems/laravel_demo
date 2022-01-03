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
use App\Model\Survey;
use App\Model\UserSurveyLike;
use App\Model\Notifications;
use App\Model\SurveyConducted;
use App\Model\SurveyTermCondition;
use App\Lib\UploadFile;
use Illuminate\Support\Facades\URL;
use App\Model\UserFavoriteSurvey;
use App\Model\SurveyComments;
use App\Model\SurveyCatalogue;
use App\Model\PaymentTransaction;
use App\Model\SurveyConductedAnswer;
use App\Model\SurveyQuestion;
use App\Model\UserInfo;
use App\Model\SurveyConductedDraft;
use App\Model\SurveyConductedAnswerDraft;
use App\Model\Contest;
use App\Model\Advertisement;
use App\Model\AdvertisementLocation;
use App\Model\UserAdvertisementView;
use App\Model\UserAdvertisementClick;
use App\Model\AdvertisementReport;
use App\Model\ContestLocation;
use App\Model\SurveyLocation;
use App\Model\UserContestLike;
use App\Model\UserActivityTrack;
use DateTime;

class SurveryController extends Controller
{

    public $common_task;
    private $data;
    public $upload_file;

    public function __construct()
    {
        $this->common_task = new CommonTask();
        $this->upload_file = new UploadFile();
    }

    // Get user all favorite survey
    public function get_favorite_survey(Request $request){
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
        
        $favorite = Survey::select('user_favorite_survey.survey_id','user_favorite_survey.created_at','survey.uuid','survey.title','survey.servey_type','survey.user_id')
                    ->join('user_favorite_survey','user_favorite_survey.survey_id','=','survey.id')
                    ->where('user_favorite_survey.user_id',$user_id)
                    ->offset($offset)
                    ->limit($page_limit)
                    ->get()
                    ->makeHidden(['servey_full_start_date', 'servey_full_end_date','survey_start_date_days']);
        if($favorite->count()){
            foreach ($favorite as $key => $value) {
                $check_survery = SurveyConducted::where('survey_id',$value['survey_id'])->where('user_id',$user_id)->count();
                if($check_survery == 0){
                    $favorite[$key]['isSurveyFilled'] = false;
                }else{
                    $favorite[$key]['isSurveyFilled'] = true;
                }

                // get percentage of filled questions
                $total_question = SurveyQuestion::where('survey_id',$value['survey_id'])->count();
                $get_draft_survey = SurveyConductedDraft::where('survey_id',$value['survey_id'])->where('user_id',Auth::user()->id)->first();
                if($get_draft_survey != null){
                    $survey_draft_id = $get_draft_survey->id;
                    $get_draft_answer = SurveyConductedAnswerDraft::select('question_id', DB::raw('count(*) as total'))->where('survey_conducted_id',$survey_draft_id)->groupBy('question_id')->get()->count();
                    $favorite[$key]['isSurveyDraft'] = (float)number_format($get_draft_answer / $total_question * 100,0);
                }else{
                    $favorite[$key]['isSurveyDraft'] = 0;
                }
                $get_filled_survey = SurveyConducted::where('survey_id',$value['survey_id'])->where('user_id',Auth::user()->id)->first();
                if($get_filled_survey != null){
                    $survey_draft_id = $get_filled_survey->id;
                    $get_filled_answer = SurveyConductedAnswer::select('question_id', DB::raw('count(*) as total'))->where('survey_conducted_id',$survey_draft_id)->groupBy('question_id')->get()->count();
                    $favorite[$key]['isSurveyDraft'] = (float)number_format($get_filled_answer / $total_question * 100,0);
                }

            }
            return response()->json(['status' => true, 'msg' => 'Record Found.', 'data' => $favorite]);
        }else{
            return response()->json(['status' => false, 'msg' => config("errors.no_record.msg"), 'error_code' => config("errors.no_record.code")]);
        }
    }

    // Add/Remove favorite survey
    public function favorite_survey(Request $request){
        $validator = Validator::make($request->all(), [
            'survey_id' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $user_id = Auth::user()->id;
        $check = UserFavoriteSurvey::where('survey_id',$request->get('survey_id'))->where('user_id',$user_id)->count();
        if($check){
            UserFavoriteSurvey::where('survey_id',$request->get('survey_id'))->where('user_id',$user_id)->delete();

            // User Activity Track
            UserActivityTrack::where(['activity'=>config('useractivity.FAVOURITE_SURVEY'),'user_id' => Auth::user()->id,'survey_id' => $request->get('survey_id')])->delete();

            return response()->json(['status' => true, 'msg' => 'Remove Successfully.', 'data' => []]);
        }else{
            $data_arr = [
                'user_id' => $user_id,
                'survey_id' => $request->get('survey_id'),
                'created_ip' => $request->ip(),
                'updated_ip' => $request->ip(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            UserFavoriteSurvey::insert($data_arr);

            // User Activity Track
            $check_track = UserActivityTrack::where(['activity'=>config('useractivity.FAVOURITE_SURVEY'),'user_id' => Auth::user()->id,'survey_id' => $request->get('survey_id')])->count();
            if($check_track == 0){
                $activity_track = new UserActivityTrack();
                $point = $activity_track->get_activity_point(config('useractivity.FAVOURITE_SURVEY'));
                $activity_track_arr = [
                    'activity' => config('useractivity.FAVOURITE_SURVEY'),
                    'point' => $point,
                    'user_id' => Auth::user()->id,
                    'survey_id' => $request->get('survey_id'),
                    'created_ip' => \Request::ip(),
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $activity_track->insert_track($activity_track_arr);
            }

            return response()->json(['status' => true, 'msg' => 'Added Successfully.', 'data' => []]);
        }
    }

    // Like/Unlike survey
    public function survey_like_dislike(Request $request){
        $validator = Validator::make($request->all(), [
            'survey_id' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $user_id = Auth::user()->id;
        $check = UserSurveyLike::where('survey_id',$request->get('survey_id'))->where('user_id',$user_id)->count();
        if($check){
            UserSurveyLike::where('survey_id',$request->get('survey_id'))->where('user_id',$user_id)->delete();
            $counter = UserSurveyLike::where('survey_id',$request->get('survey_id'))->count();
            return response()->json(['status' => true, 'msg' => 'Unlike Successfully.', 'data' => ['like_counter'=>$counter]]);
        }else{

            $data_arr = [
                'user_id' => $user_id,
                'survey_id' => $request->get('survey_id'),
                'created_ip' => $request->ip(),
                'updated_ip' => $request->ip(),
            ];
            UserSurveyLike::insert($data_arr);
            $counter = UserSurveyLike::where('survey_id',$request->get('survey_id'))->count();

            $survey_data = Survey::whereId($request->get('survey_id'))->first();
            // dd($survey_data);
            if($survey_data['user_id'] != $user_id){

                $check_last_notification = Notifications::where('type','Survey')->where('user_id',$survey_data['user_id'])->where('survey_id',$request->get('survey_id'))->count();
                if($check_last_notification == 0){
                    $notification_arr = [
                        'user_id' => $survey_data['user_id'],
                        'survey_id' => $request->get('survey_id'),
                        'type' => "Survey",
                        'messages' => Auth::user()->first_name." ".Auth::user()->last_name." like ".strip_tags($survey_data['survey_strip_tags_title'])." survey.",
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

    // Get user filled survey
    public function get_filled_survey_list(Request $request){
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

        $survey = Survey::select('survey_conducted.survey_id','survey.uuid','survey_conducted.created_at','survey.title','survey.servey_type','survey.survey_status','survey.winner_user_id','survey.amount_pay_fill_survey')
                    ->join('survey_conducted','survey_conducted.survey_id','=','survey.id')
                    ->where('survey_conducted.user_id',$user_id)
                    ->where(function($query) use ($request_data) {
                        // search data
                        if(isset($request_data['search_data'])){
                            $query->where('title', 'LIKE', '%'.$request_data['search_data'].'%');
                        }
                    })
                    ->offset($offset)
                    ->limit($page_limit)
                    ->orderBy('survey_conducted.id', 'DESC')
                    ->get()
                    ->makeHidden(['servey_full_start_date', 'servey_full_end_date','survey_start_date_days']);
        if($survey->count()){
            foreach ($survey as $key => $value) {
                if($value['winner_user_id'] == $user_id){
                    $survey[$key]['winner'] = true;
                }else{
                    $survey[$key]['winner'] = false;
                }
                unset($survey[$key]['winner_user_id']);
                $check_fav = UserFavoriteSurvey::where('user_id',$user_id)->where('survey_id',$value['survey_id'])->count();
                if($check_fav == 0){
                    $survey[$key]['isFavorite'] = false;
                }else{
                    $survey[$key]['isFavorite'] = true;
                }
            }

            return response()->json(['status' => true, 'msg' => 'record found', 'data' => $survey]);
        }else{
            return response()->json(['status' => false, 'msg' => config("errors.no_record.msg"), 'error_code' => config("errors.no_record.code")]);
        }
    }

    // Get survey winner term condition
    public function get_survey_winner_term_condition(Request $request){
        $validator = Validator::make($request->all(), [
            'survey_id' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }

        $terms = SurveyTermCondition::where('survey_id',$request->get('survey_id'))->get(['term_condition']);
        if($terms->count()){
            return response()->json(['status' => true, 'msg' => 'Record Found.', 'data' => $terms]);
        }else{
            return response()->json(['status' => false, 'msg' => config("errors.no_record.msg"), 'error_code' => config("errors.no_record.code")]);
        }
    }

    //get timeline contest
    public function get_timeline_contest($age,$city){
        $locIds = ContestLocation::pluck('contest_id')->toArray();
        $user_id = Auth::user()->id;
        $contest_count = Contest::whereNotIn('user_id',[$user_id])->where('contest_status','Started')->with(['get_user_info','check_favotite','get_comment','get_conducted'])->count();
        if($contest_count >= 5){
            $login_user_data = Auth::user()->get_user_info;
            // $contest = Contest::whereNotIn('user_id',[$user_id])->where('contest_status','Started')->with(['get_user_info','check_favotite','get_comment','get_conducted','check_like'])->get()->random(5);
            $contest_query = Contest::whereNotIn('user_id',[$user_id])->where('contest_status','Started')->with(['get_user_info','check_favotite','get_comment','get_conducted','get_site_location'])->withCount(['likes','favotite','comments','check_like']);
            
            if (empty($login_user_data->latitude) && empty($login_user_data->longitude)) {
                $contest_query->whereNotIn('id', $locIds);
            }

            if (!empty($login_user_data->latitude) && !empty($login_user_data->longitude)) {
                $contest_query->orWhereHas('get_site_location', function($query) use ($login_user_data) { 
                    $query->isWithinMaxDistance($login_user_data->toArray());
                })
                ->orderByRaw(DB::raw("FIELD(id, ". implode(',', $locIds) .") DESC"));
            }

            $contest_query->where(function ($query) use ($age, $city) {
                /* foreach ($filters as $column => $key) {
                    $query->when(array_get($input, $key), function ($query, $value) use ($column) {
                        $query->where($column, $value);
                    });
                } */
            });
            $contest = $contest_query->get()->random(1);
        }else{
            $login_user_data = Auth::user()->get_user_info;
            $contest_query = Contest::whereNotIn('user_id',[$user_id])->where('contest_status','Started')->with(['get_user_info','check_favotite','get_comment','get_conducted','get_site_location'])->withCount(['likes','favotite','comments','check_like']);
            
            if (empty($login_user_data->latitude) && empty($login_user_data->longitude)) {
                $contest_query->whereNotIn('id', $locIds);
            }

            if (!empty($login_user_data->latitude) && !empty($login_user_data->longitude)) {
                $contest_query->orWhereHas('get_site_location', function($query) use ($login_user_data) { 
                    $query->isWithinMaxDistance($login_user_data->toArray());
                })
                ->orderByRaw(DB::raw("FIELD(id, ". implode(',', $locIds) .") DESC"));
            }

            $contest_query->where(function ($query) use ($age, $city) {
                /* foreach ($filters as $column => $key) {
                    $query->when(array_get($input, $key), function ($query, $value) use ($column) {
                        $query->where($column, $value);
                    });
                } */
            });
            $contest = $contest_query->get();
        }
        return $contest;
    }

    //get timeline advertisement
    public function get_timeline_advertisement(){
        $user_id = Auth::user()->id;
        $login_user_data = Auth::user()->get_user_info;

        $locIds = AdvertisementLocation::pluck('advertisement_id')->toArray();

        $advertisement_id = UserAdvertisementView::where('user_id',$user_id)->pluck('advertisement_id')->toArray();

        if (empty($login_user_data->latitude) && empty($login_user_data->longitude)) {
            $advertisement_id = array_merge($advertisement_id, $locIds);
        }

        // print_r($advertisement_id);die;
        $advertisement = Advertisement::whereNotIn('user_id',[$user_id])->whereNotIn('id',$advertisement_id)->where('position_type','Timeline Between Survey')->where('status','Enabled')->where('advertisement_status','Started')->with(['get_business_user','get_organization_name','get_ads_location']);

        if (!empty($login_user_data->latitude) && !empty($login_user_data->longitude)) {
            $advertisement->orWhereHas('get_ads_location', function($query) use ($login_user_data) { 
                $query->isWithinMaxDistance($login_user_data->toArray());
            })
            ->orderByRaw(DB::raw("FIELD(id, ". implode(',', $locIds) .") DESC"));
        }

        $advertisement = $advertisement->get();
        
        // dd($advertisement->count());
        if (!$advertisement->isEmpty()) {
            if($advertisement->count() == 1){
                $advertisement = $advertisement->random(1);
            }else{
                $advertisement = $advertisement->random(1);
            }
            if($advertisement->isEmpty()){
                return [];
            }
        }else{
            // UserAdvertisementView::where('user_id',$user_id)->delete();
            $advertisement = Advertisement::whereNotIn('user_id',[$user_id])->where('position_type','Timeline Between Survey')->where('status','Enabled')->where('advertisement_status','Started')->with(['get_business_user','get_organization_name','get_ads_location']);
            
            if (!empty($login_user_data->latitude) && !empty($login_user_data->longitude)) {
                $advertisement->orWhereHas('get_ads_location', function($query) use ($login_user_data) { 
                    $query->isWithinMaxDistance($login_user_data->toArray());
                })
                ->orderByRaw(DB::raw("FIELD(id, ". implode(',', $locIds) .") DESC"));
            }
            
            $advertisement = $advertisement->get();

            if (!$advertisement->isEmpty()) {
                if($advertisement->count() == 1){
                    $advertisement = $advertisement->random(1);
                }else{
                    $advertisement = $advertisement->random(1);
                }
            }else{
                return response()->json([]);
            }
        }
        
        foreach($advertisement as $key => $value){
            $last_ads = UserAdvertisementView::where('user_id',$user_id)->where('advertisement_id',$value['id'])->count();
            if($last_ads == 0){
                UserAdvertisementView::insert([
                    'user_id' => $user_id,
                    'advertisement_id' => $value['id'],
                    'created_ip' => \Request::ip(),
                    'updated_ip' => \Request::ip(),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                // User Activity Track
                $check_track = UserActivityTrack::where(['activity'=>config('useractivity.VIEW_ADVERTISEMENT'),'user_id' => Auth::user()->id,'advertisement_id' => $value['id']])->count();
                if($check_track == 0){
                    $activity_track = new UserActivityTrack();
                    $point = $activity_track->get_activity_point(config('useractivity.VIEW_ADVERTISEMENT'));
                    $activity_track_arr = [
                        'activity' => config('useractivity.VIEW_ADVERTISEMENT'),
                        'point' => $point,
                        'user_id' => Auth::user()->id,
                        'advertisement_id' => $value['id'],
                        'created_ip' => \Request::ip(),
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    $activity_track->insert_track($activity_track_arr);
                }

                // Auto close ads
                $settings = new Settings();
                if($value['media_type'] == "Image"){
                    $view_charge_ads = $settings->get_view_charge_image_ads();
                }elseif($value['media_type'] == "Video"){
                    $view_charge_ads = $settings->get_view_charge_video_ads();
                }else{
                    $view_charge_ads = $settings->get_view_charge_youtube_ads();
                }
                $count_view = UserAdvertisementView::where('advertisement_id',$value['id'])->count();
                $count_click = UserAdvertisementClick::where('advertisement_id',$value['id'])->count();
                $count = $count_view + $count_click;
                $budget_amount = $value['budget_amount'];
                $filled_amount = $view_charge_ads * $count;
                if($budget_amount <= $filled_amount){
                    $end_update_arr = [
                        'advertisement_status' => "Completed",
                        'end_date' => date('Y-m-d H:i:s'),
                    ];
                    Advertisement::where('id',$value['id'])->update($end_update_arr);
                }


                // Add wallet
                $user_info = UserInfo::where('user_id',$user_id)->first();
                if($user_info){
                    $user_info->increment('wallet_amount',$view_charge_ads);
                }else{
                    UserInfo::insert(['user_id'=>$user_id,'wallet_amount'=>$view_charge_ads]);
                }
                
                // PaymentTransaction
                $transaction_arr = [
                    'user_id' => $user_id,
                    'transaction_id' => "cp_".rand(),
                    'amount' => $view_charge_ads,
                    'transaction_type' => "Advertisement View",
                    'type' => "Credit",
                    'created_ip' => \Request::ip(),
                    'updated_ip' => \Request::ip(),
                ];
                PaymentTransaction::create($transaction_arr);

                $notification_arr = [
                    'user_id' => $user_id,
                    'type' => "Advertisement View",
                    'messages' => "$ ".$view_charge_ads." amount added in your wallet.",
                    'created_ip' => \Request::ip(),
                    'updated_ip' => \Request::ip(),
                ];

                $notification = new Notifications();
                $notification->send_notification($notification_arr);

            }
            if($value['media_type'] != "Youtube Link")
                $advertisement[$key]['advertisement_image'] = config('app.s3_link')."/".$value['advertisement_image'];
        }

        return $advertisement;
    }

    // Timeline 
    public function timeline(Request $request){
        $validator = Validator::make($request->all(), [
            'page' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $request_data = $request->all();
        $page_limit = 4;
        if($request_data['page'] == 0){
            $request_data['page'] = 1;
        }
        $offset = ($request_data['page'] - 1) * $page_limit;
        $user_id = Auth::user()->id;
        
        $login_user_data = Auth::user()->get_user_info;
        $user_interest = Auth::user()->get_user_interest;
        // Age
        if($login_user_data && $login_user_data->date_of_birth){
            $from = new DateTime($login_user_data->date_of_birth);
            $to   = new DateTime('today');
            $age = $from->diff($to)->y;
            // $age = 0;
        }else{
            $age = 0;
        }
        // City
        if($login_user_data && $login_user_data->city){
            $city = $login_user_data->city;
        }else{
            $city = "";
        }

        if($user_interest && $user_interest->pluck('site_interest_id')){
            $int_arr = $user_interest->pluck('site_interest_id')->toArray();
        }else{
            $int_arr = [];
        }

        $locIds = SurveyLocation::pluck('survey_id')->toArray();

        $survey_ids = SurveyConducted::where('user_id',Auth::user()->id)->pluck('survey_id')->toArray();

        if (empty($login_user_data->latitude) && empty($login_user_data->longitude)) {
            $survey_ids = array_merge($survey_ids, $locIds);
        }
        
        $survey_query = Survey::where(function ($query) use($age) {
            // $query->orWhereNull('survey_filler_age');
            // $query->orWhere('survey_filler_age','<=',$age);
        });

        $survey_query->whereNotIn('id',$survey_ids);
        $survey_query->where('admin_status','Enabled')->where('survey_status','Started')->orderBy('servey_start_date','DESC');
        
        $survey_query->with(['get_user_name','get_questions', 'get_questions.get_question_option','get_survey_interest','get_survey_location','get_survey_conducted']);

        if (!empty($login_user_data->latitude) && !empty($login_user_data->longitude)) {
            $survey_query->orWhereHas('get_survey_location', function($query) use ($login_user_data) { 
                $query->isWithinMaxDistance($login_user_data->toArray());
            })
            ->orderByRaw(DB::raw("FIELD(id, ". implode(',', $locIds) .") DESC"));
        }
        /* $survey_query->whereHas('get_survey_interest', function ($query) use ($int_arr) {
            if(count($int_arr)){
                $query->orWhereIn('site_interest_id', $int_arr);
            }
        });
        $survey_query->whereHas('get_survey_location', function ($query) use ($city) {
            if($city){
                $query->orWhereIn('city', [$city]);
            }
        }); */

        // search data
        if(isset($request_data['search_data'])){
            $survey_query->where('title', 'LIKE', "%{$request_data['search_data']}%"); 
        }

        $survey = $survey_query->orderBy('id','DESC')->get()->toArray();
        // $survey = $survey_query->orderBy('id','DESC')->toSql();
        // echo "<pre>";print_r($survey);die;
        // dd($survey);

        $survey_total = count($survey);
        foreach($survey as $key => $value){
            $survey[$key]['check_like'] = $this->check_survey_like($value['id']);
            $survey[$key]['like_counter'] = UserSurveyLike::where('survey_id',$value['id'])->count();
            $survey[$key]['comments_counter'] = SurveyComments::where('survey_id',$value['id'])->count();
            $survey[$key]['winning_price'] = SurveyCatalogue::where('survey_id',$value['id'])->sum('amount');
            $survey[$key]['check_favorite'] = UserFavoriteSurvey::where('survey_id',$value['id'])->where('user_id',Auth::user()->id)->count();


            // get percentage of filled questions
            $total_question = SurveyQuestion::where('survey_id',$value['id'])->count();
            $get_draft_survey = SurveyConductedDraft::where('survey_id',$value['id'])->where('user_id',Auth::user()->id)->first();
            if($get_draft_survey != null){
                $survey_draft_id = $get_draft_survey->id;
                $get_draft_answer = SurveyConductedAnswerDraft::select('question_id', DB::raw('count(*) as total'))->where('survey_conducted_id',$survey_draft_id)->groupBy('question_id')->get()->count();
                $survey[$key]['isSurveyDraft'] = (float)number_format($get_draft_answer / $total_question * 100,0);
            }else{
                $survey[$key]['isSurveyDraft'] = 0;
            }
        }

        $survey_list = array_slice( $survey, $offset, $page_limit );
        if(count($survey_list)){
            return response()->json(['status' => true, 'msg' => 'Record Found.', 'data' => ['survey_list' => $survey_list, 'advertisement' => $this->get_timeline_advertisement(),'contest' => $this->get_timeline_contest($age,$city)]]);
        }else{
            return response()->json(['status' => false, 'msg' => config("errors.no_record.msg"), 'error_code' => config("errors.no_record.code")]);
        }
    }

    public function check_survey_like($id){
        $user_id = Auth::user()->id;
        return UserSurveyLike::where('survey_id',$id)->where('user_id',$user_id)->count();
    }

    // Get survey comments
    public function get_survey_comments(Request $request){
        $validator = Validator::make($request->all(), [
            'survey_id' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $request_data = $request->all();
        $user_id = Auth::user()->id;

        $survey_query = Survey::where('id',$request_data['survey_id']);
        
        $survey_query->with(['get_user_name','get_questions', 'get_questions.get_question_option','get_survey_interest','get_survey_location','get_survey_conducted']);
        
        $survey = $survey_query->first();
        if(!$survey){
            return response()->json(['status' => false, 'msg' => config("errors.no_record.msg"), 'error_code' => config("errors.no_record.code")]);
        }
        $survey['check_like'] = $this->check_survey_like($survey['id']);
        $survey['like_counter'] = UserSurveyLike::where('survey_id',$survey['id'])->count();
        $survey['comments_counter'] = SurveyComments::where('survey_id',$survey['id'])->count();
        $survey['winning_price'] = SurveyCatalogue::where('survey_id',$survey['id'])->sum('amount');
        $survey['check_favorite'] = UserFavoriteSurvey::where('survey_id',$survey['id'])->where('user_id',Auth::user()->id)->count();
        

        $survey['comments'] = SurveyComments::select('id','user_id','survey_id','comment','created_at')->where('survey_id',$request_data['survey_id'])->where('perent_comment_id',0)->with(['get_user_name'=> function($query) {
            return $query->select(['id','first_name','last_name','email','profile_image']);
        }])->get();

        foreach ($survey['comments'] as $key => $value) {
            $survey['comments'][$key]['sub_comments'] = SurveyComments::select('id','user_id','survey_id','comment','created_at')->where('perent_comment_id',$value['id'])->with(['get_user_name'=> function($query) {
                return $query->select(['id','first_name','last_name','email','profile_image']);
            }])->get();
        }
        if($survey){
            return response()->json(['status' => true, 'msg' => 'Record Found.', 'data' => $survey]);
        }else{
            return response()->json(['status' => false, 'msg' => config("errors.no_record.msg"), 'error_code' => config("errors.no_record.code")]);
        }
    }

    // Add survey comments
    public function add_survey_comment(Request $request){
        $validator = Validator::make($request->all(), [
            'survey_id' => "required",
            'comment' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $request_data = $request->all();
        $user_id = Auth::user()->id;
        // SurveyComments
        $arr = [
            'user_id' => $user_id,
            'survey_id' => $request_data['survey_id'],
            'comment' => $request_data['comment'],
            'perent_comment_id' => ($request_data['perent_comment_id']) ? $request_data['perent_comment_id'] : 0,
            'created_ip' => $request->ip(),
            'updated_ip' => $request->ip(),
        ];
        
        if($commentId = SurveyComments::create($arr)){
            $counter = SurveyComments::where('survey_id',$request_data['survey_id'])->count();

            $survey_data = Survey::whereId($request_data['survey_id'])->first();
            // dd($survey_data);
            if($survey_data['user_id'] != Auth::user()->id){
                $notification_arr = [
                    'user_id' => $survey_data['user_id'],
                    'type' => "Survey",
                    'messages' => Auth::user()->first_name." ".Auth::user()->last_name." write ".strip_tags($survey_data['survey_strip_tags_title'])." survey comment.",
                    'created_ip' => $request->ip(),
                    'updated_ip' => $request->ip(),
                ];

                $notification = new Notifications();
                $notification->send_notification($notification_arr);
            }

            // User Activity Track
            $activity_track = new UserActivityTrack();
            $point = $activity_track->get_activity_point(config('useractivity.SURVEY_COMMENT'));
            $activity_track_arr = [
                'activity' => config('useractivity.SURVEY_COMMENT'),
                'point' => $point,
                'user_id' => Auth::user()->id,
                'survey_id' => $request->get('survey_id'),
                'comment_id' => $commentId->id,
                'created_ip' => \Request::ip(),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $activity_track->insert_track($activity_track_arr);
            return response()->json(['status' => true, 'msg' => 'Survey comment added successfully.', 'data' => ['comment_counter'=>$counter]]);
        }else{
            $counter = SurveyComments::where('survey_id',$request->get('survey_id'))->count();
            return response()->json(['status' => false, 'msg' => config('errors.general_error.msg'), 'data' => ['comment_counter'=>$counter]]);
        }
    }

    // Remove survey comments
    public function remove_survey_comment(Request $request){
        $validator = Validator::make($request->all(), [
            'survey_id' => "required",
            'comment_id' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $comment_query = SurveyComments::where('id',$request->get('comment_id'));
        $comment_query->orWhere('perent_comment_id',$request->get('comment_id'));
        if($comment_query->delete()){

            UserActivityTrack::where(['activity'=>config('useractivity.SURVEY_COMMENT'),'user_id' => Auth::user()->id,'survey_id' => $request->get('survey_id'),'comment_id'=>$request->get('comment_id')])->delete();

            $counter = SurveyComments::where('survey_id',$request->get('survey_id'))->count();
            return response()->json(['status' => true, 'msg' => 'Remove comment successfully.', 'data' => ['comment_counter'=>$counter]]);
        }
        return response()->json(['status' => false, 'msg' => config("errors.no_record.msg"), 'error_code' => config("errors.no_record.code")]);
    }

    public function get_survey(Request $request){
        $validator = Validator::make($request->all(), [
            'uuid' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }

        $uuid = $request->get('uuid');
        $user_id = Auth::user()->id;
        $survey_data = Survey::select('id','uuid','title','description','question_per_page','survey_status','servey_type')->where("uuid",$uuid)->with(['get_questions'=> function($query) {
            return $query->select(['id','survey_id','question','question_description','question_file','question_type']);
        }, 'get_questions.get_question_option'=> function($query) {
            return $query->select(['id','survey_id','question_id','question_option']);
        }])->first()->makeHidden(['servey_full_start_date','servey_full_end_date','survey_start_date_days']);

        
        // Get draft status
        $conduct_survey = SurveyConductedDraft::where('survey_id', $survey_data['id'])->where('user_id', Auth::user()->id)->first();
        if($conduct_survey != null){
            $conduct_survey_id = $conduct_survey['id'];
            foreach ($survey_data['get_questions'] as $key => $value) {
                if ($value['question_type'] == "textbox" || $value['question_type'] == "textarea" || $value['question_type'] == "file") {
                    if($value['question_type'] == "file"){
                        $file_name = $this->get_draft_selected_answer_text($conduct_survey_id, $value['id']);
                        if($file_name){
                            $survey_data['get_questions'][$key]['question_answer'] = config('app.s3_link')."/".$file_name;
                        }else{
                            $survey_data['get_questions'][$key]['question_answer'] = "";
                        }
                    }else{
                        $survey_data['get_questions'][$key]['question_answer'] = $this->get_draft_selected_answer_text($conduct_survey_id, $value['id']);
                    }
                } else {
                    foreach ($value['get_question_option'] as $key1 => $value1) {
                        $survey_data['get_questions'][$key]['get_question_option'][$key1]['check_option'] = $this->get_draft_selected_option_status($conduct_survey_id, $value['id'], $value1['id']);
                    }
                }
            }
            $survey_data['isSurveyDraft'] = true;
        }else{
            $survey_data['isSurveyDraft'] = false;
        }

        // Get filled survey status
        $conduct_survey = SurveyConducted::where('survey_id', $survey_data['id'])->where('user_id', Auth::user()->id)->first();
        if(!$conduct_survey){
            $survey_data['isSurveyFilled'] = false;

            // User Activity Track
            $check_track = UserActivityTrack::where(['activity'=>config('useractivity.VIEW_SURVEY'),'user_id' => Auth::user()->id,'survey_id' => $survey_data['id']])->count();
            if($check_track == 0){
                $activity_track = new UserActivityTrack();
                $point = $activity_track->get_activity_point(config('useractivity.VIEW_SURVEY'));
                $activity_track_arr = [
                    'activity' => config('useractivity.VIEW_SURVEY'),
                    'point' => $point,
                    'user_id' => Auth::user()->id,
                    'survey_id' => $survey_data['id'],
                    'created_ip' => \Request::ip(),
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $activity_track->insert_track($activity_track_arr);
            }

        }else{
            $conduct_survey_id = $conduct_survey['id'];

            $survey_data['created_at'] = $conduct_survey['created_at'];
            foreach ($survey_data['get_questions'] as $key => $value) {
                if ($value['question_type'] == "textbox" || $value['question_type'] == "textarea") {
                    $survey_data['get_questions'][$key]['question_answer'] = $this->get_selected_answer_text($conduct_survey_id, $value['id']);
                }elseif($value['question_type'] == "file"){
                    $quetion_file = $this->get_selected_answer_text($conduct_survey_id, $value['id']);
                    $survey_data['get_questions'][$key]['question_answer'] = config('app.s3_link')."/".$quetion_file;
                }else {
                    foreach ($value['get_question_option'] as $key1 => $value1) {
                        $survey_data['get_questions'][$key]['get_question_option'][$key1]['check_option'] = $this->get_selected_option_status($conduct_survey_id, $value['id'], $value1['id']);
                    }
                }
            }

            $check_survery = SurveyConducted::where('survey_id',$survey_data['id'])->where('user_id',$user_id)->count();
            if($check_survery == 0){
                $survey_data['isSurveyFilled'] = false;
            }else{
                $survey_data['isSurveyFilled'] = true;
            }
        }
        $check_fav = UserFavoriteSurvey::where('user_id',$user_id)->where('survey_id',$survey_data['id'])->count();
        if($check_fav == 0){
            $survey_data['isFavorite'] = false;
        }else{
            $survey_data['isFavorite'] = true;
        }

        if(!$survey_data['question_per_page']){
            $survey_data['question_per_page'] = 0;
        }

        return response()->json(['status' => true, 'msg' => 'Record Found.', 'data' => $survey_data]);
    }

    public function get_draft_selected_option_status($conduct_survey_id, $question_id, $option_id)
    {
        $check = SurveyConductedAnswerDraft::where('survey_conducted_id', $conduct_survey_id)->where('question_id', $question_id)->where('option_id', $option_id)->count();
        return $check;
    }

    public function get_draft_selected_answer_text($conduct_survey_id, $question_id)
    {
        $text = SurveyConductedAnswerDraft::where('survey_conducted_id', $conduct_survey_id)->where('question_id', $question_id)->first();
        return ($text && $text['answer_text']) ? $text['answer_text'] : "";
    }

    public function fill_survey_by_user(Request $request){
        $validator = Validator::make($request->all(), [
            'survey_id' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }

        $request_data = $request->all();
        $survey_data = Survey::whereId($request_data['survey_id'])->first();
        
        if($survey_data['survey_status'] === "Paused"){
            return response()->json(['status' => false, 'msg' => "This survey is paused.", 'error_code' => config("errors.no_record.code")]);
        }

        $data_filled = SurveyConducted::firstOrNew(array('survey_id' => $request_data['survey_id'],'user_id' => Auth::user()->id));
        $data_filled->survey_id = $request_data['survey_id'];
        $data_filled->user_id = Auth::user()->id;
        $data_filled->created_ip = $request->ip();
        $data_filled->updated_ip = $request->ip();
        $data_filled->created_at = date('Y-m-d H:i:s');
        $data_filled->updated_at = date('Y-m-d H:i:s');
        $data_filled->save();
        $survey_conducted_id = $data_filled->id;

        $get_draft_survey = SurveyConductedDraft::where('survey_id',$request_data['survey_id'])->where('user_id',Auth::user()->id)->first();
        $survey_draft_id = $get_draft_survey->id;
        $get_draft_answer = SurveyConductedAnswerDraft::where('survey_conducted_id',$survey_draft_id)->get();
        
        foreach($get_draft_answer as $ans_key => $ans_value){
            SurveyConductedAnswer::insert([
                'survey_conducted_id' => $survey_conducted_id,
                'question_id' => $ans_value['question_id'],
                'option_id' => $ans_value['option_id'],
                'answer_text' => $ans_value['answer_text'],
                'created_ip' => $request->ip(),
                'updated_ip' => $request->ip(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // Delete Draft Survey
        SurveyConductedDraft::where('survey_id',$request_data['survey_id'])->where('user_id',Auth::user()->id)->delete();

        $survey_filled_count = SurveyConducted::where('survey_id',$request_data['survey_id'])->count();
        
        // Add User Wallet
        if($survey_data['amount_pay_fill_survey']){
            $user_info = UserInfo::where('user_id',Auth::user()->id)->first();
            if($user_info){
                $user_info->increment('wallet_amount',$survey_data['amount_pay_fill_survey']);
            }else{
                UserInfo::insert(['user_id'=>Auth::user()->id,'wallet_amount'=>$survey_data['amount_pay_fill_survey']]);
            }
            
            // PaymentTransaction
            $transaction_arr = [
                'user_id' => Auth::user()->id,
                'transaction_id' => "cp_".rand(),
                'amount' => $survey_data['amount_pay_fill_survey'],
                'transaction_type' => "Fill Survey",
                'type' => "Credit",
                'created_ip' => $request->ip(),
                'updated_ip' => $request->ip(),
            ];
            PaymentTransaction::create($transaction_arr);

            $notification_arr = [
                'user_id' => Auth::user()->id,
                'type' => "Transaction",
                'messages' => "$ ".$survey_data['amount_pay_fill_survey']." amount added in your wallet.",
                'created_ip' => $request->ip(),
                'updated_ip' => $request->ip(),
            ];

            $notification = new Notifications();
            $notification->send_notification($notification_arr);

            // Survey auto close when budget over
            if($survey_data['survey_amount'] && $survey_data['amount_pay_fill_survey'] * $survey_filled_count == $survey_data['survey_amount']){
                $update_data = [
                    'servey_end_date' => date('Y-m-d H:i:s'),
                    'survey_status' => "Completed",
                    'survey_status_updated_date' => date('Y-m-d H:i:s'),
                ];
                Survey::whereId($request_data['survey_id'])->update($update_data);
            }
        }
        
        // Survey auto close by number of fill
        if($survey_data['number_survey_fill'] && $survey_data['number_survey_fill'] == $survey_filled_count){
            $update_data = [
                'servey_end_date' => date('Y-m-d H:i:s'),
                'survey_status' => "Completed",
                'survey_status_updated_date' => date('Y-m-d H:i:s'),
            ];
            Survey::whereId($request_data['survey_id'])->update($update_data);
        }
        // User Activity Track
        $check_track = UserActivityTrack::where(['activity'=>config('useractivity.FILL_SURVEY'),'user_id' => Auth::user()->id,'survey_id' => $survey_data['id']])->count();
        if($check_track == 0){
            $activity_track = new UserActivityTrack();
            $point = $activity_track->get_activity_point(config('useractivity.FILL_SURVEY'));
            $activity_track_arr = [
                'activity' => config('useractivity.FILL_SURVEY'),
                'point' => $point,
                'user_id' => Auth::user()->id,
                'survey_id' => $survey_data['id'],
                'created_ip' => \Request::ip(),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $activity_track->insert_track($activity_track_arr);
        }

        return response()->json(['status' => true, 'msg' => 'Survey filled successfully.', 'data' => []]);
    }

    public function fill_survey_by_user_direct_save(Request $request){
        $validator = Validator::make($request->all(), [
            'survey_id' => "required",
            'question.*' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }

        $request_data = $request->all();
        if(!isset($request_data['question'])){
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $survey_data = Survey::whereId($request_data['survey_id'])->first();
        
        if($survey_data['survey_status'] === "Paused"){
            return response()->json(['status' => false, 'msg' => "This survey is paused.", 'error_code' => config("errors.no_record.code")]);
        }

        $survey_conducted_arr = [
            'survey_id' => $request_data['survey_id'],
            'user_id' => Auth::user()->id,
            'created_ip' => $request->ip(),
            'updated_ip' => $request->ip(),
        ];
        // dd($survey_conducted_arr);
        SurveyConducted::where('survey_id',$request_data['survey_id'])->where('user_id',Auth::user()->id)->delete();
        $data_filled = SurveyConducted::create($survey_conducted_arr);
        $survey_conducted_id = $data_filled->id;
        // dd($survey_conducted_id);
        foreach ($request_data['question'] as $key => $value) {
            $question_type = SurveyQuestion::whereId($key)->value('question_type');
            /* echo $question_type;
            echo "<br>"; */
            // print_r($value);

            $conducted_answer_arr = [];

            if($question_type == 'option' || $question_type == 'radio' || $question_type == 'checkbox'){
                if($question_type == 'checkbox'){
                    foreach($value as $check_key => $check_val){
                        $conducted_answer_arr[$check_key]['survey_conducted_id'] = $survey_conducted_id;
                        $conducted_answer_arr[$check_key]['question_id'] = $key;
                        $conducted_answer_arr[$check_key]['option_id'] = $check_val;
                        $conducted_answer_arr[$check_key]['created_ip'] = $request->ip();
                        $conducted_answer_arr[$check_key]['updated_ip'] = $request->ip();
                    }
                }else{
                    $conducted_answer_arr['survey_conducted_id'] = $survey_conducted_id;
                    $conducted_answer_arr['question_id'] = $key;
                    $conducted_answer_arr['option_id'] = $value;
                    $conducted_answer_arr['created_ip'] = $request->ip();
                    $conducted_answer_arr['updated_ip'] = $request->ip();
                }
            }elseif ($question_type == 'file') {
                $conducted_answer_arr['survey_conducted_id'] = $survey_conducted_id;
                $conducted_answer_arr['question_id'] = $key;
                $conducted_answer_arr['answer_text'] = $this->upload_file->upload_s3_file_multiple($value, 'question_file',"","");
                $conducted_answer_arr['created_ip'] = $request->ip();
                $conducted_answer_arr['updated_ip'] = $request->ip();
            }else{
                $conducted_answer_arr['survey_conducted_id'] = $survey_conducted_id;
                $conducted_answer_arr['question_id'] = $key;
                $conducted_answer_arr['answer_text'] = $value;
                $conducted_answer_arr['created_ip'] = $request->ip();
                $conducted_answer_arr['updated_ip'] = $request->ip();
            }
            SurveyConductedAnswer::insert($conducted_answer_arr);
            // dd($conducted_answer_arr);
        }


        $survey_filled_count = SurveyConducted::where('survey_id',$request_data['survey_id'])->count();
        
        // Add User Wallet
        if($survey_data['amount_pay_fill_survey']){
            $user_info = UserInfo::where('user_id',Auth::user()->id)->first();
            if($user_info){
                $user_info->increment('wallet_amount',$survey_data['amount_pay_fill_survey']);
            }else{
                UserInfo::insert(['user_id'=>Auth::user()->id,'wallet_amount'=>$survey_data['amount_pay_fill_survey']]);
            }
            
            // PaymentTransaction
            $transaction_arr = [
                'user_id' => Auth::user()->id,
                'transaction_id' => "cp_".rand(),
                'amount' => $survey_data['amount_pay_fill_survey'],
                'transaction_type' => "Fill Survey",
                'type' => "Credit",
                'created_ip' => $request->ip(),
                'updated_ip' => $request->ip(),
            ];
            PaymentTransaction::create($transaction_arr);

            $notification_arr = [
                'user_id' => Auth::user()->id,
                'type' => "Transaction",
                'messages' => "$ ".$survey_data['amount_pay_fill_survey']." amount added in your wallet.",
                'created_ip' => $request->ip(),
                'updated_ip' => $request->ip(),
            ];

            $notification = new Notifications();
            $notification->send_notification($notification_arr);

            // Survey auto close when budget over
            if($survey_data['survey_amount'] && $survey_data['amount_pay_fill_survey'] * $survey_filled_count == $survey_data['survey_amount']){
                $update_data = [
                    'servey_end_date' => date('Y-m-d H:i:s'),
                    'survey_status' => "Completed",
                    'survey_status_updated_date' => date('Y-m-d H:i:s'),
                ];
                Survey::whereId($request_data['survey_id'])->update($update_data);
            }
        }
        
        // Survey auto close by number of fill
        if($survey_data['number_survey_fill'] && $survey_data['number_survey_fill'] == $survey_filled_count){
            $update_data = [
                'servey_end_date' => date('Y-m-d H:i:s'),
                'survey_status' => "Completed",
                'survey_status_updated_date' => date('Y-m-d H:i:s'),
            ];
            Survey::whereId($request_data['survey_id'])->update($update_data);
        }

        return response()->json(['status' => true, 'msg' => 'Survey filled successfully.', 'data' => []]);
    }

    public function fill_survey_info(Request $request){
        $validator = Validator::make($request->all(), [
            'uuid' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }

        $uuid = $request->get('uuid');
        $user_id = Auth::user()->id;
        $survey_data = Survey::select('id','uuid','title','description','question_per_page','survey_status','servey_type')->where("uuid",$uuid)->with(['get_questions'=> function($query) {
            return $query->select(['id','survey_id','question','question_description','question_file','question_type']);
        }, 'get_questions.get_question_option'=> function($query) {
            return $query->select(['id','survey_id','question_id','question_option']);
        }])->first()->makeHidden(['servey_full_start_date','servey_full_end_date','survey_start_date_days']);

        
        $conduct_survey = SurveyConducted::where('survey_id', $survey_data['id'])->where('user_id', Auth::user()->id)->first();
        if(!$conduct_survey){
            $survey_data['isSurveyFilled'] = false;
        }else{
            $conduct_survey_id = $conduct_survey['id'];

            $survey_data['created_at'] = $conduct_survey['created_at'];
            foreach ($survey_data['get_questions'] as $key => $value) {
                if ($value['question_type'] == "textbox" || $value['question_type'] == "textarea") {
                    $survey_data['get_questions'][$key]['question_answer'] = $this->get_selected_answer_text($conduct_survey_id, $value['id']);
                }elseif($value['question_type'] == "file"){
                    $quetion_file = $this->get_selected_answer_text($conduct_survey_id, $value['id']);
                    $survey_data['get_questions'][$key]['question_answer'] = config('app.s3_link')."/".$quetion_file;
                }else {
                    foreach ($value['get_question_option'] as $key1 => $value1) {
                        $survey_data['get_questions'][$key]['get_question_option'][$key1]['check_option'] = $this->get_selected_option_status($conduct_survey_id, $value['id'], $value1['id']);
                    }
                }
            }

            $check_survery = SurveyConducted::where('survey_id',$survey_data['id'])->where('user_id',$user_id)->count();
            if($check_survery == 0){
                $survey_data['isSurveyFilled'] = false;
            }else{
                $survey_data['isSurveyFilled'] = true;
            }
        }
        $check_fav = UserFavoriteSurvey::where('user_id',$user_id)->where('survey_id',$survey_data['id'])->count();
        if($check_fav == 0){
            $survey_data['isFavorite'] = false;
        }else{
            $survey_data['isFavorite'] = true;
        }

        if(!$survey_data['question_per_page']){
            $survey_data['question_per_page'] = 0;
        }
        return response()->json(['status' => true, 'msg' => 'Record Found.', 'data' => $survey_data]);
    }

    public function get_selected_answer_text($conduct_survey_id, $question_id)
    {
        $text = SurveyConductedAnswer::where('survey_conducted_id', $conduct_survey_id)->where('question_id', $question_id)->first();
        return $text['answer_text'];
    }

    public function get_selected_option_status($conduct_survey_id, $question_id, $option_id)
    {
        return SurveyConductedAnswer::where('survey_conducted_id', $conduct_survey_id)->where('question_id', $question_id)->where('option_id', $option_id)->count();
    }

    public function save_survey_draft(Request $request){
        /* echo "<pre>";
        print_r($request->all());die; */
        $request_data = $request->all();
        
        $validator = Validator::make($request->all(), [
            'survey_id' => "required",
            'question.*' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }

        $request_data = $request->all();
        if(!isset($request_data['question'])){
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $survey_data = Survey::whereId($request_data['survey_id'])->first();
        
        if($survey_data['survey_status'] === "Paused"){
            return response()->json(['status' => false, 'msg' => "This survey is paused.", 'error_code' => config("errors.no_record.code")]);
        }

        $survey_conducted_arr = [
            'survey_id' => $request_data['survey_id'],
            'user_id' => Auth::user()->id,
            'created_ip' => $request->ip(),
            'updated_ip' => $request->ip(),
        ];
        // SurveyConductedDraft::where('survey_id',$request_data['survey_id'])->where('user_id',Auth::user()->id)->delete();
        $data_filled = SurveyConductedDraft::firstOrNew(array('survey_id' => $request_data['survey_id'],'user_id' => Auth::user()->id));
        $data_filled->save($survey_conducted_arr);
        $survey_conducted_id = $data_filled->id;
        // dd($survey_conducted_id);
        try {
            foreach ($request_data['question'] as $key => $value) {
                if($value != ""){
                    $question_type = SurveyQuestion::whereId($key)->value('question_type');
                    $conducted_answer_arr = [];
                    if($question_type == 'option' || $question_type == 'radio' || $question_type == 'checkbox'){
                        if($question_type == 'checkbox'){
                            foreach($value as $check_key => $check_val){
                                $conducted_answer_arr[$check_key]['survey_conducted_id'] = $survey_conducted_id;
                                $conducted_answer_arr[$check_key]['question_id'] = $key;
                                $conducted_answer_arr[$check_key]['option_id'] = $check_val;
                                $conducted_answer_arr[$check_key]['created_ip'] = $request->ip();
                                $conducted_answer_arr[$check_key]['updated_ip'] = $request->ip();
                            }
                        }else{
                            $conducted_answer_arr['survey_conducted_id'] = $survey_conducted_id;
                            $conducted_answer_arr['question_id'] = $key;
                            $conducted_answer_arr['option_id'] = $value;
                            $conducted_answer_arr['created_ip'] = $request->ip();
                            $conducted_answer_arr['updated_ip'] = $request->ip();
                        }
                    }elseif ($question_type == 'file') {
                       
                        $quetion_file = SurveyConductedAnswerDraft::where('survey_conducted_id',$survey_conducted_id)->where('question_id',$key)->value('answer_text');
                        $conducted_answer_arr['survey_conducted_id'] = $survey_conducted_id;
                        $conducted_answer_arr['question_id'] = $key;
                        if(is_string ($value)) {
                            $conducted_answer_arr['answer_text'] = $quetion_file;
                        } else {
                            $conducted_answer_arr['answer_text'] =  $this->upload_file->upload_s3_file_multiple($value, 'question_file',"","");
                        }
                        
                        $conducted_answer_arr['created_ip'] = $request->ip();
                        $conducted_answer_arr['updated_ip'] = $request->ip();
                    }else{
                        $conducted_answer_arr['survey_conducted_id'] = $survey_conducted_id;
                        $conducted_answer_arr['question_id'] = $key;
                        $conducted_answer_arr['answer_text'] = $value;
                        $conducted_answer_arr['created_ip'] = $request->ip();
                        $conducted_answer_arr['updated_ip'] = $request->ip();
                    }
                    $check_last = SurveyConductedAnswerDraft::where('survey_conducted_id',$survey_conducted_id)->where('question_id',$key)->delete();
                    
                    SurveyConductedAnswerDraft::insert($conducted_answer_arr);
                    /* if($check_last == 0){
                    }else{
                        unset($conducted_answer_arr['survey_conducted_id']);
                        unset($conducted_answer_arr['question_id']);
                        SurveyConductedAnswerDraft::where('survey_conducted_id',$survey_conducted_id)->where('question_id',$key)->update($conducted_answer_arr);
                    } */
                    // print_r($conducted_answer_arr);
                }
            }
            return response()->json(['status' => true, 'msg' => "Survey save in draft.", 'data' => []]);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['status' => false, 'msg' => config("errors.sql_operation.msg"), 'error_code' => config("errors.sql_operation.code")]);
        }
        
    }

    public function advertisement_report(Request $request){
        $validator = Validator::make($request->all(), [
            'advertisement_id' => "required",
            'report_text' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $user_id = Auth::user()->id;
        $check_count = AdvertisementReport::where('advertisement_id',$request->get('advertisement_id'))->where('user_id',$user_id)->count();
        if($check_count == 0){
            AdvertisementReport::insert([
                'advertisement_id' => $request->get('advertisement_id'),
                'user_id' => $user_id,
                'report_text' => $request->get('report_text'),
                'created_ip' => \Request::ip(),
                'updated_ip' => \Request::ip(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            return response()->json(['status' => true, 'msg' => 'report added successfully.','data' => []]);
        }elseif($check_count > 0){
            return response()->json(['status' => true, 'msg' => 'report already added.','data' => []]);
        }else{
            return response()->json(['status' => false, 'msg' => 'report not added.','data' => []]);
        }
    }

    public function click_advertisement(Request $request){
        $validator = Validator::make($request->all(), [
            'advertisement_id' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => config("errors.validation.msg"), 'error_code' => config("errors.validation.code")]);
        }
        $user_id = Auth::user()->id;
        $ads_id = $request->get('advertisement_id');
        $advertisement = Advertisement::whereId($ads_id)->with(['get_business_user','get_organization_name'])->first();

        $settings = new Settings();
        if($advertisement['media_type'] == "Image"){
            $click_charge_ads = $settings->get_click_charge_image_ads();
            $view_charge_ads = $settings->get_view_charge_image_ads();

        }elseif($advertisement['media_type'] == "Video"){
            $click_charge_ads = $settings->get_click_charge_video_ads();
            $view_charge_ads = $settings->get_view_charge_video_ads();
        }else{
            $click_charge_ads = $settings->get_click_charge_youtube_ads();
            $view_charge_ads = $settings->get_view_charge_youtube_ads();
        }
            $count_view = UserAdvertisementView::where('advertisement_id',$ads_id)->count();
            $count_click = UserAdvertisementClick::where('advertisement_id',$ads_id)->count();
            $count = $count_view * $view_charge_ads + $count_click * $click_charge_ads;
            $budget_amount = $advertisement['budget_amount'];
            $pending_amount = $budget_amount - $count ;

        if ($pending_amount >= 2 ) {
            $check = UserAdvertisementClick::where('advertisement_id',$ads_id)->where('user_id',$user_id)->count();
            if($check == 0){
                UserAdvertisementClick::insert([
                    'user_id' => $user_id,
                    'advertisement_id' => $ads_id,
                    'created_ip' => \Request::ip(),
                    'updated_ip' => \Request::ip(),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);        
            } 
            // User Activity Track
            $check_track = UserActivityTrack::where(['activity'=>config('useractivity.CLICK_ADVERTISEMENT'),'user_id' => Auth::user()->id,'advertisement_id' => $ads_id])->count();
            if($check_track == 0){
                $activity_track = new UserActivityTrack();
                $point = $activity_track->get_activity_point(config('useractivity.CLICK_ADVERTISEMENT'));
                $activity_track_arr = [
                    'activity' => config('useractivity.CLICK_ADVERTISEMENT'),
                    'point' => $point,
                    'user_id' => Auth::user()->id,
                    'advertisement_id' => $ads_id,
                    'created_ip' => \Request::ip(),
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $activity_track->insert_track($activity_track_arr);
            }
                $after_count_view = UserAdvertisementView::where('advertisement_id',$ads_id)->count();
                $after_count_click = UserAdvertisementClick::where('advertisement_id',$ads_id)->count();
                $after_count = $after_count_view * $view_charge_ads + $after_count_click * $click_charge_ads;
                $after_budget_amount = $advertisement['budget_amount'];
                $after_pending_amount = $after_budget_amount - $after_count ;
                if($after_pending_amount == 0){
                    $end_update_arr = [
                        'advertisement_status' => "Completed",
                        'end_date' => date('Y-m-d H:i:s'),
                    ];
                    Advertisement::where('id',$ads_id)->update($end_update_arr);
                }

                // Add wallet
            $user_info = UserInfo::where('user_id',$user_id)->first();
            if($user_info){
                $user_info->increment('wallet_amount',$click_charge_ads);
            }else{
                UserInfo::insert(['user_id'=>$user_id,'wallet_amount'=>$click_charge_ads]);
            }
            // PaymentTransaction
            $transaction_arr = [
                'user_id' => $user_id,
                'transaction_id' => "cp_".rand(),
                'amount' => $click_charge_ads,
                'transaction_type' => "Advertisement Click",
                'type' => "Credit",
                'created_ip' => $request->ip(),
                'updated_ip' => $request->ip(),
            ];
            PaymentTransaction::create($transaction_arr);

            $notification_arr = [
                'user_id' => $user_id,
                'type' => "Advertisement Click",
                'messages' => "$ ".$click_charge_ads." amount added in your wallet.",
                'created_ip' => $request->ip(),
                'updated_ip' => $request->ip(),
            ];

            $notification = new Notifications();
            $notification->send_notification($notification_arr);
             
        }
        
        /* if($check == 0){
            UserAdvertisementClick::insert([
                'user_id' => $user_id,
                'advertisement_id' => $ads_id,
                'created_ip' => \Request::ip(),
                'updated_ip' => \Request::ip(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            // User Activity Track
            $check_track = UserActivityTrack::where(['activity'=>config('useractivity.CLICK_ADVERTISEMENT'),'user_id' => Auth::user()->id,'advertisement_id' => $ads_id])->count();
            if($check_track == 0){
                $activity_track = new UserActivityTrack();
                $point = $activity_track->get_activity_point(config('useractivity.CLICK_ADVERTISEMENT'));
                $activity_track_arr = [
                    'activity' => config('useractivity.CLICK_ADVERTISEMENT'),
                    'point' => $point,
                    'user_id' => Auth::user()->id,
                    'advertisement_id' => $ads_id,
                    'created_ip' => \Request::ip(),
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $activity_track->insert_track($activity_track_arr);
            }

            // Auto close ads
            $advertisement = Advertisement::whereId($ads_id)->with(['get_business_user','get_organization_name'])->first();
            $settings = new Settings();
            if($advertisement['media_type'] == "Image"){
                $click_charge_ads = $settings->get_click_charge_image_ads();
            }elseif($advertisement['media_type'] == "Video"){
                $click_charge_ads = $settings->get_click_charge_video_ads();
            }else{
                $click_charge_ads = $settings->get_click_charge_youtube_ads();
            }
            $count_view = UserAdvertisementView::where('advertisement_id',$ads_id)->count();
            $count_click = UserAdvertisementClick::where('advertisement_id',$ads_id)->count();
            $count = $count_view + $count_click;
            $budget_amount = $advertisement['budget_amount'];
            $filled_amount = $click_charge_ads * $count;
            if($budget_amount <= $filled_amount){
                $end_update_arr = [
                    'advertisement_status' => "Completed",
                    'end_date' => date('Y-m-d H:i:s'),
                ];
                Advertisement::where('id',$ads_id)->update($end_update_arr);
            }

            // Add wallet
            $user_info = UserInfo::where('user_id',$user_id)->first();
            if($user_info){
                $user_info->increment('wallet_amount',$click_charge_ads);
            }else{
                UserInfo::insert(['user_id'=>$user_id,'wallet_amount'=>$click_charge_ads]);
            }
            
            // PaymentTransaction
            $transaction_arr = [
                'user_id' => $user_id,
                'transaction_id' => "cp_".rand(),
                'amount' => $click_charge_ads,
                'transaction_type' => "Advertisement Click",
                'type' => "Credit",
                'created_ip' => $request->ip(),
                'updated_ip' => $request->ip(),
            ];
            PaymentTransaction::create($transaction_arr);

            $notification_arr = [
                'user_id' => $user_id,
                'type' => "Advertisement Click",
                'messages' => "$ ".$click_charge_ads." amount added in your wallet.",
                'created_ip' => $request->ip(),
                'updated_ip' => $request->ip(),
            ];

            $notification = new Notifications();
            $notification->send_notification($notification_arr);
        } */
        return response()->json(['status' => true, 'msg' => 'click successfully.','data' => []]);
    }
}
