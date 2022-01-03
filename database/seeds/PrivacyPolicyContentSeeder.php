<?php

use Illuminate\Database\Seeder;

class PrivacyPolicyContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('privacy_policy_content')->truncate();

        $insert_arr=[
            ['content'=>'{"banner_content":"We only ask for personal information when we truly need it to provide a service to you. We collect it by fair and lawful means, with your knowledge and consent. We also let you know why we\u2019re collecting it and how it will be used."}','status'=>"Enabled"],
            ['content'=>'{"section1_heading":"View our basic Privacy Policy","section1_content":"test"}','status'=>"Enabled"],
            ['content'=>'{"section2_heading1":"View our terms of service template:","section2_heading2":"YOURCOMPANYHERE Terms of Service","question_answer":[{"question":"question1","answer":"<p>answer1<\/p>"},{"question":"question2","answer":"answer2"}]}','status'=>"Enabled"],
            ['content'=>'{"section3_heading":"Privacy Policy template & Sample Disclaimer","section3_content":"test"}','status'=>"Enabled"],
        ];
        // dd($insert_arr);
        DB::table('privacy_policy_content')->insert($insert_arr);
    }
}
