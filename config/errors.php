<?php

return [
    'access_denied' => ['code' => 10000, 'msg' => 'Access Denied!'],
    'validation' => ['code' => 10001, 'msg' => 'Please follow validation rules'],
    'no_record' => ['code' => 10002, 'msg' => 'No record found'],
    'sql_operation' => ['code' => 10003, 'msg' => 'Error during operation. Try again.'],
    'invalid_login' => ['code' => 10004, 'msg' => 'Invalid login credentials.'],
    'login_attempt_block' => ['code' => 10005, 'msg' => 'Too many attempts. Your account is blocked!'],
    'account_verify' => ['code' => 10006, 'msg' => 'Your account is not verified yet. Please verify your account!'],
    'wrong_login_type' => ['code' => 10007, 'msg' => 'Please login using '],
    'account_already_verified' => ['code' => 10008, 'msg' => 'Email is already verified.'],
    'email_exists' => ['code' => 10009, 'msg' => 'Email is already exists.'],
    'disable_user' => ['code' => 10010, 'msg' => 'Your account is disabled by admin, Please contact support for more detail.'],
    'general_error' => ['code' => 10011, 'msg' => 'Error Occurred. Try Again!'],
    'mail_send_error' => ['code' => 10012, 'msg' => 'Mail Send Error Occurred. Try Again!'],
    
    'permission_error'=>['code'=>10014,'msg'=>'You do not have permission to perform this action. Please contact administration for this issue.'],
    
    'otp_expired' => ['code' => 10016, 'msg' => 'Oops..OTP expired.'],
    'offercode_expired_or_invalid' => ['code' => 10017, 'msg' => 'Oops..This Offer code is expired or invalid.'],
    'offercode_already_used' => ['code' => 10018, 'msg' => 'Oops..This offer code is already used for your location.'],
    'alreadyLoggedIn' => ['code' => 10019, 'msg' => ''],
    'otp_invalid' => ['code' => 10020, 'msg' => 'Invalid OTP!']
];

