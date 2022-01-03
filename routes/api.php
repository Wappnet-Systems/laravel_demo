<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->group(function () {
    Route::post('/login', 'LoginController@login')->name('apilogin');
    Route::post('/user_register', 'LoginController@user_register')->name('user_register');
    Route::post('/forgot_password', 'LoginController@forgot_password')->name('forgot_password');
    Route::post('/resend_verification_link', 'LoginController@resend_verification_link')->name('resend_verification_link');

    // Delete user for mobile team
    Route::post('/delete_user', 'LoginController@delete_user')->name('delete_user');

    // Secured routes, all the apis which are user specific will come in this group 
    Route::middleware(['auth:sanctum'])->group(function() {
        // UserController Methods
        Route::post('/change_password', 'UserController@change_password')->name('change_password');
        
        Route::post('/logout', 'UserController@logout')->name('apilogout');
        
        Route::get('/get_profile_details', 'UserController@get_profile_details')->name('get_profile_details');
        Route::post('/update_profile_details', 'UserController@update_profile_details')->name('update_profile_details');
        Route::post('/update_profile_photo', 'UserController@update_profile_photo')->name('update_profile_photo');
        
        Route::post('/get_notification', 'UserController@get_notification')->name('get_notification');
        Route::post('/remove_notification', 'UserController@remove_notification')->name('remove_notification');
        Route::get('/clear_notification', 'UserController@clear_notification')->name('clear_notification');
    });

});