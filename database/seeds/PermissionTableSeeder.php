<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();

        $permissions = [
            ['name' => 'role-permission-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'role-permission-add', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'role-permission-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'role-permission-delete', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'admin-user-management-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'admin-user-management-add', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'admin-user-management-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'admin-user-management-delete', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'email-format-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'email-format-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'front-user-management-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'front-user-management-add', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'front-user-management-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'front-user-management-delete', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'plan-subscription-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'plan-subscription-add', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'plan-subscription-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'seo-setting-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'seo-setting-add', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'seo-setting-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'seo-setting-delete', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'sem-setting-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'sem-setting-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'page-setting-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'page-setting-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'admin-catalogue-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'admin-catalogue-add', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'admin-catalogue-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'admin-catalogue-delete', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'general-setting-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'general-setting-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'survey-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'survey-add', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'survey-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'survey-conducted-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'template-category-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'template-category-add', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'template-category-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'survey-template-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'survey-template-add', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'survey-template-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'client-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'client-add', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'client-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'expertise-level-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'expertise-level-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'site-interest-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'site-interest-add', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'site-interest-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'advertisement-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'advertisement-add', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'advertisement-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'advertisement-category-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'advertisement-category-add', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'advertisement-category-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'advertisement-approve-reject', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'payment-transaction-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'contest-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'contest-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'wallet-request-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'wallet-request-approve', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'wallet-request-reject', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'homepage-setting-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'homepage-setting-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'activity-points-setting-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'activity-points-setting-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'activity-points-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'activity-points-add', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'activity-points-delete', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'point-ranking-setting-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'point-ranking-setting-add', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'point-ranking-setting-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'poll-list', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'poll-add', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'poll-edit', 'guard_name' => 'admin', 'module_name' => ''],
            ['name' => 'timeline', 'guard_name' => 'web', 'module_name' => 'Timeline'],
            ['name' => 'all_notification', 'guard_name' => 'web', 'module_name' => 'Notification'],
            ['name' => 'create_survey', 'guard_name' => 'web', 'module_name' => 'Survey'],
            ['name' => 'survey_template', 'guard_name' => 'web', 'module_name' => 'Survey'],
            ['name' => 'add_survey', 'guard_name' => 'web', 'module_name' => 'Survey'],
            ['name' => 'survey_list', 'guard_name' => 'web', 'module_name' => 'Survey'],
            ['name' => 'survey_winner_declare', 'guard_name' => 'web', 'module_name' => 'Survey'],
            ['name' => 'user_survey_edit', 'guard_name' => 'web', 'module_name' => 'Survey'],
            ['name' => 'fill_survey_list', 'guard_name' => 'web', 'module_name' => 'Survey'],
            ['name' => 'show_fill_survey_info', 'guard_name' => 'web', 'module_name' => 'Survey'],
            ['name' => 'filled_draft_list', 'guard_name' => 'web', 'module_name' => 'Survey'],
            ['name' => 'fill_survey', 'guard_name' => 'web', 'module_name' => 'Survey'],
            ['name' => 'favorite', 'guard_name' => 'web', 'module_name' => 'Favorite'],
            ['name' => 'favorite_contest', 'guard_name' => 'web', 'module_name' => 'Favorite'],
            ['name' => 'contest', 'guard_name' => 'web', 'module_name' => 'Contest Campaign'],
            ['name' => 'add_contest', 'guard_name' => 'web', 'module_name' => 'Contest Campaign'],
            ['name' => 'edit_contest', 'guard_name' => 'web', 'module_name' => 'Contest Campaign'],
            ['name' => 'contest_start_end_status', 'guard_name' => 'web', 'module_name' => 'Contest Campaign'],
            ['name' => 'show_contest', 'guard_name' => 'web', 'module_name' => 'Contest Campaign'],
            ['name' => 'your_participate_contest', 'guard_name' => 'web', 'module_name' => 'Contest Campaign'],
            ['name' => 'transaction_list', 'guard_name' => 'web', 'module_name' => 'Transactions'],
            ['name' => 'campaigns', 'guard_name' => 'web', 'module_name' => 'Advertisement'],
            ['name' => 'create_campaign_group', 'guard_name' => 'web', 'module_name' => 'Advertisement'],
            ['name' => 'advertisements', 'guard_name' => 'web', 'module_name' => 'Advertisement'],
            ['name' => 'add_advertisement', 'guard_name' => 'web', 'module_name' => 'Advertisement'],
            ['name' => 'edit_advertisement', 'guard_name' => 'web', 'module_name' => 'Advertisement'],
            ['name' => 'analyze_advertisement', 'guard_name' => 'web', 'module_name' => 'Advertisement'],
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'guard_name' => $permission['guard_name'],
                'module_name' => $permission['module_name']
            ]);
        }
    }
}
