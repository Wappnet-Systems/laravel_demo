<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SiteSemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('site_sem')->truncate();

        $insert_arr=[
            ['title'=>'Facebook','sem_link'=>'https://facebook.com','status'=>'Enabled','created_ip'=>'::1','updated_ip'=>'::1','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
            ['title'=>'Twitter','sem_link'=>'https://twitter.com','status'=>'Enabled','created_ip'=>'::1','updated_ip'=>'::1','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
            ['title'=>'Skype','sem_link'=>'https://www.skype.com/en/','status'=>'Enabled','created_ip'=>'::1','updated_ip'=>'::1','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
            ['title'=>'WhatsApp','sem_link'=>'https://web.whatsapp.com/','status'=>'Enabled','created_ip'=>'::1','updated_ip'=>'::1','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
            ['title'=>'Instagram','sem_link'=>'https://instagram.com','status'=>'Enabled','created_ip'=>'::1','updated_ip'=>'::1','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
            ['title'=>'LinkedIn','sem_link'=>'https://www.linkedin.com/','status'=>'Enabled','created_ip'=>'::1','updated_ip'=>'::1','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
        ];

        DB::table('site_sem')->insert($insert_arr);
    }
}
