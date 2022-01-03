<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Notifications extends Model
{
    protected $table="notifications";

    protected $fillable = [
        'user_id', 
        'type',
        'messages', 
        'read',
        'created_ip',
        'updated_ip',
        'status',
        'from_user_id',
        'survey_id',
        'contest_id',
        'payload'
    ];

    public function send_notification($data){
        return Notifications::create($data);
    }

    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
    }

    public function setUpdatedAtAttribute($value)
    {
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
    }

    public function user_friend_request()
    {
        return $this->hasOne('App\Model\UserFriendRequest', 'to_user_id', 'from_user_id')->where('from_user_id', Auth::user()->id);
    }
}
