<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionTableSeeder::class,
            AdminSeeder::class,
            RoleSeeder::class,
            EmailFormatSeeder::class,
            PagesSeeder::class,
            PlanSeeder::class,
            SettingSeeder::class,
            SiteSemSeeder::class,
            SiteSeoSeeder::class,
            ExpertiseLevelSeeder::class,
            AboutContentSeeder::class,
            FaqContentSeeder::class,
            PrivacyPolicyContentSeeder::class,
            ActivityPointsSeeder::class,
            QuickPollSeeder::class
        ]);
    }
}
