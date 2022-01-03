<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SiteSeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $meta_keywords="Default Keywords";
        $meta_description="Default Keywords";
        DB::table('site_seo')->truncate();
        DB::table('site_seo')->insert([
            'meta_keywords' => $meta_keywords,
            'meta_description' => $meta_description,
            'created_ip' => "::1",
            'updated_ip' => "::1",
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
