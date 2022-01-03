<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pages')->truncate();

        $insert_arr=[
            ['title'=>'About Us','page_content'=>'About us page','page_slug'=>'about_us','status'=>'Enabled','created_ip'=>'::1','updated_ip'=>'::1','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
            ['title'=>'FAQS','page_content'=>'FAQS','page_slug'=>'faq','status'=>'Enabled','created_ip'=>'::1','updated_ip'=>'::1','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
            ['title'=>'Privacy Policy','page_content'=>'Privacy Policy','page_slug'=>'privacy_policy','status'=>'Enabled','created_ip'=>'::1','updated_ip'=>'::1','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
        ];

        DB::table('pages')->insert($insert_arr);
    }
}
