<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
// check_login
Auth::routes();

// check_login
Route::name('front.')->namespace('Front')->middleware(['image-sanitize','check_login','language'])->group(function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::post('/user_log', 'HomeController@user_log')->name('user_log');
    Route::post('/notification_count', 'HomeController@notification_count')->name('notification_count');
    Route::post('/remove_notification', 'HomeController@remove_notification')->name('remove_notification');

    // Login
    Route::get('/login', 'LoginController@login')->name('login')->middleware('prevent-back-history');
    Route::get('/logout', 'LoginController@logout')->name('logout')->middleware('prevent-back-history');
    Route::post('/authenticate', 'LoginController@authenticate')->name('authenticate');

    // Register
    Route::get('/register', 'RegisterController@register')->name('register');
    Route::post('/new_user', 'RegisterController@new_user')->name('new_user');
    Route::post('/check_email', 'RegisterController@check_email')->name('check_email');

    //Forgot Password
    Route::get('/forgot_password', 'LoginController@forgot_password')->name('forgot_password');
    Route::post('/send_new_password', 'LoginController@send_new_password')->name('send_new_password');
    Route::get('/reset_password/{token}', 'LoginController@reset_password')->name('reset_password');
    Route::post('/reset_password_change', 'LoginController@reset_password_change')->name('reset_password_change');

    // Google Login
    Route::get('/google_login', 'LoginController@google_login')->name('google_login')->middleware('prevent-back-history');
    Route::get('/callback', 'LoginController@handleGoogleCallback')->middleware('prevent-back-history');

    // linkedin login
    Route::get('/linkedin_login', 'LoginController@linkedin_login')->name('linkedin_login')->middleware('prevent-back-history');
    Route::get('/linkedin_callback', 'LoginController@handleLinkedinCallback')->middleware('prevent-back-history');

    // linkedin login
    Route::get('/facebook_login', 'LoginController@facebook_login')->name('facebook_login')->middleware('prevent-back-history');
    Route::get('/facebook_callback', 'LoginController@handleFacebookCallback')->middleware('prevent-back-history');

    // Check User status in ajax
    Route::get('/check-ajax-user-status','HomeController@checkAjaxUserStatus')->name('check-ajax-user-status');

    //After login
    Route::group(['middleware' => ['front_auth','check_user_status','prevent-back-history']], function () {
        Route::get('/all_notification', 'HomeController@all_notification')->name('all_notification');
        Route::post('notification/follower/request/status/change', 'HomeController@userInviteAcceptRejectRequest')->name('notification.follower.status.change');

        //Dashboard
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
        Route::post('/save_personal_info','DashboardController@save_personal_info')->name('save_personal_info');
        Route::get('/get_profile_statistics','DashboardController@get_profile_statistics')->name('get_profile_statistics');

        // Account
        Route::get('/account','AccountController@account')->name('account');
        Route::post('/save_account_detail','AccountController@save_account_detail')->name('save_account_detail');
        Route::post('/change_password','AccountController@change_password')->name('change_password');
    });

    // Mobile Account Verification
    Route::get('/vefify_account/{token}', 'RegisterController@vefify_account')->name('vefify_account');
});

// Controllers Within The "App\Http\Controllers\Admin" Namespace
// 'image-sanitize',
Route::prefix('admin')->name('admin.')->namespace('Admin')->middleware(['check_admin_login'])->group(function () {
    Route::get('/', 'LoginController@login')->name('login');
    Route::post('/authenticate', 'LoginController@authenticate')->name('authenticate')->middleware('prevent-back-history');

    Route::get('/logout', 'LoginController@logout')->name('logout')->middleware('prevent-back-history');
    Route::get('/reset_password', 'LoginController@reset_password')->name('reset_password');
    Route::post('/forgot_password', 'LoginController@forgot_password')->name('forgot_password');

    // after login
    Route::group(['middleware' => ['auth', 'check_user_status']], function () {

        // Dashboard
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

        Route::get('/profile', 'DashboardController@profile')->name('profile');
        Route::get('/changepassword', 'DashboardController@changepassword')->name('changepassword');
        Route::post('/savepassword', 'DashboardController@savepassword')->name('savepassword');
        Route::get('/edit_profile', 'DashboardController@edit_profile')->name('edit_profile');
        Route::post('/update_user', 'UserController@update_user')->name('update_user');

        //Get Admin User List Methods
        Route::get('/user', 'UserController@index')->name('user');
        Route::get('/add_user', 'UserController@add_user')->name('add_user');
        Route::get('/get_admin_user_list', 'UserController@get_admin_user_list')->name('get_admin_user_list');
        Route::get('/change_admin_status/{id}/{status}', 'UserController@change_admin_status')->name('change_admin_status');
        Route::get('/edit_user/{id}', 'UserController@edit_user')->name('edit_user');
        Route::post('/update_user_data', 'UserController@update_user_data')->name('update_user_data');

        Route::post('/check_email', 'UserController@check_email')->name('check_email');
        Route::post('/create_user', 'UserController@create_user')->name('create_user');

        // Email Format Methods
        Route::get('/email_format', 'Email_formatController@index')->name('email_format');
        Route::get('/email_format/editemail/{id}', 'Email_formatController@editemail')->name('editemail');
        Route::post('/email_format/update', 'Email_formatController@update')->name('updateemail');

        // Role methods
        Route::get('list_roles', 'RoleController@index')->name('list_roles');
        Route::get('add_role_permission', 'RoleController@add_role_permission')->name('add_role_permission');
        Route::post('save_role_permission', 'RoleController@save_role_permission')->name('save_role_permission');
        Route::get('edit_role_permission/{id}', 'RoleController@edit_role_permission')->name('edit_role_permission');
        Route::post('update_role_permission', 'RoleController@update_role_permission')->name('update_role_permission');
        Route::get('delete_role_permission/{id}', 'RoleController@delete_role_permission')->name('delete_role_permission');
        Route::post('check_role_name', 'RoleController@check_role_name')->name('check_role_name');

        // Catalogue Method
        Route::get('/catalogue', 'CatalogueController@index')->name('catalogue');
        Route::get('/add_catalogue', 'CatalogueController@add_catalogue')->name('add_catalogue');
        Route::post('/save_catelogue', 'CatalogueController@save_catelogue')->name('save_catelogue');
        Route::get('/change_catalogue_status/{id}/{status}', 'CatalogueController@change_catalogue_status')->name('change_catalogue_status');
        Route::get('/delete_catalogue/{id}', 'CatalogueController@delete_catalogue')->name('delete_catalogue');
        Route::get('/edit_catalogue/{id}', 'CatalogueController@edit_catalogue')->name('edit_catalogue');
        Route::post('/update_catelogue', 'CatalogueController@update_catelogue')->name('update_catelogue');

        // Your Clients
        Route::get('/clients', 'YourClientController@index')->name('clients');
        Route::get('/get_client_list', 'YourClientController@get_client_list')->name('get_client_list');
        Route::get('/add_client', 'YourClientController@add_client')->name('add_client');
        Route::post('/save_client', 'YourClientController@save_client')->name('save_client');
        Route::get('/change_client_status/{id}/{status}', 'YourClientController@change_client_status')->name('change_client_status');
        Route::get('/edit_client/{id}', 'YourClientController@edit_client')->name('edit_client');
        Route::post('/update_client', 'YourClientController@update_client')->name('update_client');
    });
});
