<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use DateTime;
use App\Lib\UploadFile;
use App\Model\UserSubscription;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name','email', 'password', 'user_type', 'created_by', 'business_user_id', 'profile_image', 'social_id', 'social_status','remember_token'
    ];

    protected $appends = ['profile_check','profile_image_path', 'age', 'is_free_subscription'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getProfileCheckAttribute(){
        if(Auth::check()){
            if(Auth::user()->user_type == "admin"){
                if(Auth::check()){
                    $user = User::whereId($this->id)->first();
                    $counter = 0;
                    if($user->first_name && $user->last_name){
                        $counter = 66.68;
                    }
                    // get_user_interest
                    if(count($user->get_user_interest)){
                        $counter += 16.67;
                    }
                    if($user->roles != null && $user->roles[0]['name'] == "Normal User"){
                        if($user->get_user_info && $user->get_user_info->phone_number && $user->get_user_info->gender && $user->get_user_info->address && $user->get_user_info->unit_street && $user->get_user_info->city && $user->get_user_info->state && $user->get_user_info->country && $user->get_user_info->postal_code){
                            $counter += 16.67;
                        }
                    }else{
                        if($user->get_user_info && $user->get_user_info->phone_number && $user->get_user_info->gender && $user->get_user_info->address && $user->get_user_info->unit_street && $user->get_user_info->city && $user->get_user_info->state && $user->get_user_info->country && $user->get_user_info->postal_code && $user->get_user_info->organization_name && $user->get_user_info->business_logo && $user->get_user_info->business_contact_number && $user->get_user_info->business_contact_email && $user->get_user_info->business_website && $user->get_user_info->business_registration_number && $user->business_account_approval == "1"){
                            $counter += 16.67;
                        }
                    }
                    return $counter;
                }else{
                    return "0";
                }
            }else{
                if(Auth::check()){
                    $user = User::whereId(Auth()->user()->id)->first();
                    $counter = 0;
                    if($user->first_name && $user->last_name){
                        $counter = 66.68;
                    }
                    // get_user_interest
                    if(count($user->get_user_interest)){
                        $counter += 16.67;
                    }
                    if(Auth::user()->roles != null && Auth::user()->roles[0]['name'] == "Normal User"){
                        if($user->get_user_info && $user->get_user_info->phone_number && $user->get_user_info->gender && $user->get_user_info->address && $user->get_user_info->unit_street && $user->get_user_info->city && $user->get_user_info->state && $user->get_user_info->country && $user->get_user_info->postal_code){
                            $counter += 16.67;
                        }
                    }else{
                        if($user->get_user_info && $user->get_user_info->phone_number && $user->get_user_info->gender && $user->get_user_info->address && $user->get_user_info->unit_street && $user->get_user_info->city && $user->get_user_info->state && $user->get_user_info->country && $user->get_user_info->postal_code && $user->get_user_info->organization_name && $user->get_user_info->business_logo && $user->get_user_info->business_contact_number && $user->get_user_info->business_contact_email && $user->get_user_info->business_website && $user->get_user_info->business_registration_number && $user->business_account_approval == "1"){
                            $counter += 16.67;
                        }
                    }
                    return $counter;
                }else{
                    return "0";
                }
            }
        }
    }

    public function getProfileImagePathAttribute(){
        if($this->profile_image){
            $upload_file = new UploadFile();
            return  $upload_file->get_s3_file_path('profile_image',$this->profile_image);
        }else{
            return "";
        }
    }

    public function getAgeAttribute()
    {
        $age = NULL;
        if(isset($this->date_of_birth)){
            $from = new DateTime($this->date_of_birth);
            $to   = new DateTime('today');
            $age = $from->diff($to)->y;
        }
        return $age;
    }

    public function getIsFreeSubscriptionAttribute()
    {
        $user_subscription = UserSubscription::where('user_id', $this->id)->first();
        
        if (isset($user_subscription->product_id) && !empty($user_subscription->product_id)) {
            if ($user_subscription->product_id == config('constants.FREE_PLAN')) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }

        return false;
    }

    public function get_user_info()
    {
        return $this->hasOne('App\Model\UserInfo', 'user_id', 'id');
    }

    public function get_business_name()
    {
        return $this->hasOne('App\User', 'id', 'business_user_id');
    }

    public static function get_list_datatable_ajax($table, $datatable_fields, $conditions_array, $getfiled, $request, $join_str = array(), $where_date = [])
    {
        //die('ok');
        $output = array();
        $data = DB::table($table)
            ->select($getfiled);

        if (!empty($join_str)) {
            //$data->where(function($query) use ($join_str) {
            foreach ($join_str as $join) {
                if (!isset($join['join_type'])) {
                    $data->join($join['table'], $join['join_table_id'], '=', $join['from_table_id']);
                } else {
                    $data->join($join['table'], $join['join_table_id'], '=', $join['from_table_id'], $join['join_type']);
                }
            }
            //});
        }
        if (!empty($conditions_array)) {
            $data->where($conditions_array);
        }
        if (!empty($where_date)) {
            foreach ($where_date as $date) {
                $data->whereDate($date[0], $date[1], $date[2]);
            }
        }
        if (!empty($request) && $request['search']['value'] != '') {
            $data->where(function ($query) use ($request, $datatable_fields) {
                for ($i = 0; $i < count($datatable_fields); $i++) {
                    if ($request['columns'][$i]['searchable'] == 'true') {
                        $query->orWhere($datatable_fields[$i], 'like', '%' . $request['search']['value'] . '%');
                    }
                }
            });
        }
        if (isset($request['order']) && count($request['order'])) {
            for ($i = 0; $i < count($request['order']); $i++) {
                if ($request['columns'][$request['order'][$i]['column']]['orderable'] == true) {
                    $data->orderBy($datatable_fields[$request['order'][$i]['column']], $request['order'][$i]['dir']);
                }
            }
        }
        $count = $data->count();
        $start =  !empty($request['start']) ? $request['start'] : 0;
        $length =  !empty($request['length']) ? $request['length'] : 0;
        $draw = !empty($request['draw']) ? $request['draw'] : 10;
        $data->skip($start)->take($length);
        //print_r(DB::getQueryLog());exit;
        $output['recordsTotal'] = $count;
        $output['recordsFiltered'] = $count;
        $output['draw'] = $draw;
        $sms_data = $data->get();

        //$response['perPageCount'] = $i;
        //print_r($sms_data); die();
        $output['data'] = $sms_data;
        return json_encode($output);
    }

    public function get_user_interest()
    {
        return $this->hasMany('App\Model\UserInterest', 'user_id', 'id');
    }

    public function check_friend_request(){
        return $this->hasOne('App\Model\UserFriendRequest', 'to_user_id', 'id')->where('status', 'Pending')->where('from_user_id',Auth::user()->id);
    }

    public function get_total_activity_points(){
        return $this->hasMany('App\Model\UserActivityTrack', 'user_id', 'id');
    }

    public function get_user_subscription()
    {
        return $this->hasOne('App\Model\UserSubscription', 'user_id', 'id');
    }

    public function get_follower_boards()
    {
        return $this->hasMany('App\Model\FollowerBoard', 'user_id', 'id')->with('get_board_users');
    }
}
