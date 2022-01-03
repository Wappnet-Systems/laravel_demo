<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpertiseLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('expertise_level')->truncate();

        $insert_arr=[
            ['expertise_level'=>'Novice','expertise_level_detail'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','created_ip'=>'::1','updated_ip'=>'::1','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
            ['expertise_level'=>'Intermediate','expertise_level_detail'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','created_ip'=>'::1','updated_ip'=>'::1','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
            ['expertise_level'=>'Expert','expertise_level_detail'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','created_ip'=>'::1','updated_ip'=>'::1','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
        ];

        DB::table('expertise_level')->insert($insert_arr);
    }
}
