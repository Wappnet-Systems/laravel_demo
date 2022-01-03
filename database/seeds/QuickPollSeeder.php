<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuickPollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('quick_poll')->truncate();

        $insert_arr=[
            ['name'=>'Everyday','created_ip'=>'::1','updated_ip'=>'::1','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
            ['name'=>'Once a week','created_ip'=>'::1','updated_ip'=>'::1','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
            ['name'=>'Every other day','created_ip'=>'::1','updated_ip'=>'::1','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
            ['name'=>'Several times a month','created_ip'=>'::1','updated_ip'=>'::1','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
            ['name'=>'Very rarely','created_ip'=>'::1','updated_ip'=>'::1','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
        ];

        DB::table('quick_poll')->insert($insert_arr);
    }
}
