<?php

use Illuminate\Database\Seeder;

class FaqContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('faq_content')->truncate();

        $insert_arr=[
            ['content'=>'{"banner_content":"We only ask for personal information when we truly need it to provide a service to you. We collect it by fair and lawful means, with your knowledge and consent. We also let you know why we\u2019re collecting it and how it will be used."}','status'=>"Enabled"],
            ['content'=>'{"faq_heading":"Frequently Asked Questions"}','status'=>"Enabled"],
            ['content'=>'[{"question":"What is Contest Partner Surveys?","answer":"First answer"},{"question":"How lorem ipsum has been the industrys standard dummy text?","answer":"Second Answer"}]','status'=>"Enabled"],
        ];
        // dd($insert_arr);
        DB::table('faq_content')->insert($insert_arr);
    }
}
