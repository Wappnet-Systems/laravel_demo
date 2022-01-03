<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserMessages extends Model
{
    protected $table = "user_messages";

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
