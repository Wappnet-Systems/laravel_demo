<?php

namespace App\Providers;

use App\Model\PointRanking;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Model\Settings;
use App\User;
use App\Model\SiteSem;
use Illuminate\Support\Facades\View;
use App\Model\UserSubscription;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */    
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Get setting data
        if (Schema::hasTable('settings')) {
            $setting_details = [];

            $setting_details['url'] = Settings::where('setting_title', "Site Url")->value('setting_value');
            $setting_details['develop_by'] = Settings::where('setting_title', "Developed By")->value('setting_value');

            View::share('setting_details', $setting_details);
        }

        // Get SEM data
        if (Schema::hasTable('site_sem')) {
            $sem = [];

            $sem['facebook'] = SiteSem::where('title', "Facebook")->where('status', 'Enabled')->value('sem_link');
            $sem['twiter'] = SiteSem::where('title', "Twitter")->where('status', 'Enabled')->value('sem_link');
            $sem['skype'] = SiteSem::where('title', "Skype")->where('status', 'Enabled')->value('sem_link');
            $sem['whats_app'] = SiteSem::where('title', "WhatsApp")->where('status', 'Enabled')->value('sem_link');
            $sem['insta'] = SiteSem::where('title', "Instagram")->where('status', 'Enabled')->value('sem_link');
            $sem['linkedin'] = SiteSem::where('title', "LinkedIn")->where('status', 'Enabled')->value('sem_link');

            View::share('sem', $sem);
        }

        // Get total front user data
        if (Schema::hasTable('users')) {
            $month = date('m');

            $user = [];

            $user[] = User::where('user_type', 'web')->role('Normal User', 'web')->whereMonth('created_at', '=', $month)->count();
            $user[] = User::where('user_type', 'web')->role('Premium Users', 'web')->whereMonth('created_at', '=', $month)->count();
            $user[] = User::where('user_type', 'web')->role('Business Users', 'web')->whereMonth('created_at', '=', $month)->count();

            View::share('user_signup_statistics', json_encode($user));
        }

        View::composer('*', function ($view) {
            $user = Auth::user();

            if (View::exists('front.layouts.app_dashboard')) {
                // Login user plan type check
                /* $get_subscription = UserSubscription::where('user_id', $user->id)->first();
                if (isset($get_subscription) && !empty($get_subscription)) {
                    $stripe = new \Stripe\StripeClient(
                        Settings::get_stripe_secret()
                    );

                    $plan_data = $stripe->products->retrieve($get_subscription['product_id'], []);

                    $view->with(['plan_type' => $plan_data]);
                } */

                // PointRanking
                $total_point = !empty($user->get_user_info) ? $user->get_total_activity_points->sum('point') : 0;

                $current_rank = PointRanking::where('start_point', '<=', $total_point)->where('end_point', '>=', $total_point)->first();

                $view->with(['total_point' => $total_point, 'current_rank' => $current_rank]);
            }
        });
    }
}