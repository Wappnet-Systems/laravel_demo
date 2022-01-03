<?php

use Illuminate\Database\Seeder;
use App\Model\Email_format;
use Illuminate\Support\Facades\DB;

class EmailFormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('email_formats')->truncate();

        $emails = [
            [
                'title' => "Contest Partner Signup Completed",
                'variables' => "",
                'subject' => "Contest Partner Signup Completed",
                'emailformat' => 'Hello %full_name%,<br />
                <br />
                Welcome to Contest Partner. Contest partner admin team had created your account.<br />
                <br />
                You can login into your account for more details using below credentials.<br />
                <br />
                Email : %email%<br />
                Password : %password%<br />
                <br />
                Thank you<br />
                Contest Partner Team',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Reset Password",
                'variables' => "",
                'subject' => "Reset Password",
                'emailformat' => 'Hello %full_name%,<br />
                <br />
                Welcome to Contest Partner Here send your reset password link click on reset your password.<br />
                <br />
                Link :-&nbsp; <a href="%link%" target="_blank">Click here</a><br />
                <br />
                Reset Url :- %link%<br />
                <br />
                Thank you<br />
                Contest Partner Team',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Survey Status Disable",
                'variables' => "",
                'subject' => "Survey Status Disable",
                'emailformat' => 'Hello %name%,<br />
                <br />
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Your survey in disabled by contest partner team with below reason,<br />
                <br />
                Survey Name : %title%<br />
                Reason : %reason%<br />
                <br />
                Thank You<br />
                Contest Partner Team&nbsp;',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Survey Status Enable",
                'variables' => "",
                'subject' => "Survey Status Enable",
                'emailformat' => 'Hello %name%,<br />
                <br />
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Your survey in enable by contest partner team,<br />
                <br />
                Survey Name : %title%<br />
                <br />
                Thank You<br />
                Contest Partner Team&nbsp;',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Admin Forgot Password",
                'variables' => "",
                'subject' => "Admin Forgot Password",
                'emailformat' => 'Hello %full_name%,<br />
                <br />
                Welcome to Contest Partner Here send your password login with new password<br />
                <br />
                Email :- %email_address%<br />
                Password :- %password%<br />
                <br />
                Thank you<br />
                Contest Partner Team',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Subscription Buy",
                'variables' => "",
                'subject' => "Subscription Buy",
                'emailformat' => 'Hello %full_name%,<br />
                <br />
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Welcome to Contest Partner. Your subscription plan buy successfully below share your plan detail.<br />
                <br />
                Plan Name : %plan_name%<br />
                Plan Amount : %plan_amount% /&nbsp;%plan_interval%&nbsp;<br />
                <br />
                Thank You<br />
                Contest Partner Team',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Survey Winner",
                'variables' => "",
                'subject' => "Survey Winner",
                'emailformat' => 'Hello %name%,<br />

                <br />
                
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Congratulations, you are winner of&nbsp;%title% survey, more details mentioned at below,<br />
                
                <br />
                
                Survey Name : %title%<br />
                
                Winning Price : $%amount%<br />
                
                <br />
                
                %images%<br />
                
                <br />
                
                <br />
                
                Thank You<br />
                
                Contest Partner Team&nbsp;',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Wallet Request Approved",
                'variables' => "",
                'subject' => "Wallet Request Approved",
                'emailformat' => 'Hello %name%,<br />
                <br />
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Your $ %amount% withdrawal request approved by admin.<br />
                <br />
                Notes : %notes%<br />
                <br />
                Thank You<br />
                Contest Partner Team&nbsp;',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Wallet Request Rejected",
                'variables' => "",
                'subject' => "Wallet Request Rejected",
                'emailformat' => 'Hello %name%,<br />
                <br />
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Your $ %amount% withdrawal request rejected by admin.<br />
                <br />
                Reject reason : %notes%<br />
                <br />
                Thank You<br />
                Contest Partner Team&nbsp;',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Account Verification",
                'variables' => "",
                'subject' => "Account Verification",
                'emailformat' => 'Hello %full_name%,<br />
                <br />
                Welcome to Contest Partner. Click on below link for verify account.<br />
                <br />
                Link :-&nbsp; <a href="%link%" target="_blank">Click here</a><br />
                <br />
                Url :- %link%<br />
                <br />
                Thank you<br />
                Contest Partner Team',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Contact Us Inquiry",
                'variables' => "",
                'subject' => "Contact Us Inquiry",
                'emailformat' => 'Hello Admin,<br />
                <br />
                Come contact us inquiry, below shared details.<br />
                <br />
                Name :- %full_name%<br />
                Email :- %from_email%<br />
                Subject :- %subject%<br />
                Message :- %message%<br />
                <br />
                Thank you<br />
                Contest Partner Team',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "User Account Approved Request",
                'variables' => "",
                'subject' => "User Account Approved Request",
                'emailformat' => 'Hello Admin,
                <br>
                %user% change the some Business details show in below details :
                <br>
                User Email :- %email%
                <br>
                Business Contact Number : - %business_contact_number% 
                <br>
                Business Contact Email:- %business_contact_email% 
                <br>
                Business Name : - %organization_name% 
                <br>
                Business Website : - %business_website% 
                <br>
                Business Registration Number :- %business_registration_number%
                <br>
                Thank you
                Contest Partner Team',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "User Account Reject",
                'variables' => "",
                'subject' => "Your account is rejected By admin",
                'emailformat' => 'Hello %user%,
                <br>
                Our Contest Partner Team has been Rejected your account.
                <br>
                Reason : %reason%
                <br>
                Thank you
                Contest Partner Team',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Business User Account Approved Request",
                'variables' => "",
                'subject' => "Your account is Approved By admin.",
                'emailformat' => 'Hello, %user%,
                <br>
                Our Contest Partner Team has been Approved your account.
                <br>
                Thank you,
                Contest Partner Team.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Your Advertisement Disable By Admin",
                'variables' => "",
                'subject' => "Your Advertisement Disable By Admin.",
                'emailformat' => 'Hello %full_name%,<br />
                <br />
                Automated content scanner has generated flag on your "%column_name%". Your advertisement is temporarily disabled for now and under review. You will be notified soon once content review is completed. <br />
                <br />
                Thank you,<br />
                Contest Partner Team.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Your Contest Disable By Admin",
                'variables' => "",
                'subject' => "Your Contest Disable By Admin.",
                'emailformat' => 'Hello %full_name%,,
                <br>
                Automated content scanner has generated flag on your "%column_name%". Your Contest is temporarily disabled for now and under review. You will be notified soon once content review is completed. <br />
                <br>
                Thank you,
                Contest Partner Team.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Your Survey Disable By Admin",
                'variables' => "",
                'subject' => "Your Survey Disable By Admin.",
                'emailformat' => 'Hello %full_name%,,
                <br>
                Automated content scanner has generated flag on your "%column_name%". Your Survey is temporarily disabled for now and under review. You will be notified soon once content review is completed. <br />
                <br>
                Thank you,
                Contest Partner Team.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Your Advertisement Enabled By Admin",
                'variables' => "",
                'subject' => "Your Advertisement Enabled By Admin",
                'emailformat' => 'Hello %full_name%,,
                <br>
                Review process of your advertisement content is completed and it is in compliance with our policy. So your advertisement is now enabled back. For more details please login to your contest partner dashboard. <br />
                <br>
                Thank you,
                Contest Partner Team.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Your Contest Enable By Admin",
                'variables' => "",
                'subject' => "Your Contest Enable By Admin",
                'emailformat' => 'Hello %full_name%,,
                <br>
                Review process of your Contest content is completed and it is in compliance with our policy. So your Contest is now enabled back. For more details please login to your contest partner dashboard.<br />
                <br>
                Thank you,
                Contest Partner Team.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Your Survey Enable By Admin.",
                'variables' => "",
                'subject' => "Your Survey Enable By Admin.",
                'emailformat' => 'Hello %full_name%,,
                <br>
                Review process of your Survey content is completed and it is in compliance with our policy. So your Survey is now enabled back. For more details please login to your contest partner dashboard.<br />
                <br>
                Thank you,
                Contest Partner Team.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Your Advertisement Remove By Admin",
                'variables' => "",
                'subject' => "Your Advertisement Remove By Admin",
                'emailformat' => 'Hello %full_name%,<br />
                <br />
                The review process of your advertisement content is completed and we found the content not in compliance with &nbsp; content policy. So we have to remove the content from our platform.&nbsp;<br />
                <br />
                We suggest you to go through our content policy available on our website before you submit a new content so your content can be compliance with our policy.<br />
                <br />
                Thank you,<br />
                Contest Partner Team',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Your Contest Remove By Admin",
                'variables' => "",
                'subject' => "Your Contest Remove By Admin",
                'emailformat' => 'Hello %full_name%,<br />
                <br />
                The review process of your Contest content is completed and we found the content not in compliance with &nbsp; content policy. So we have to remove the content from our platform.&nbsp;<br />
                <br />
                we suggest you to go through our content policy available on our website before you submit a new content so your content can be compliance with our policy.<br />
                <br />
                Thank you,<br />
                Contest Partner Team',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Your Survey Remove By Admin",
                'variables' => "",
                'subject' => "Your Survey Remove By Admin",
                'emailformat' => 'Hello %full_name%,<br />
                <br />
                The review process of your Survey content is completed and we found the content not in compliance with .com content policy. So we have to remove the content from our platform.<br />
                <br />
                We suggest you to go through our content policy available on our website before you submit a new content so your content can be compliance with our policy.<br />
                <br />
                Thank you,<br />
                Contest Partner Team',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Follow Friend Request",
                'variables' => "",
                'subject' => "Follow Friend Request",
                'emailformat' => 'Hello %full_name%,<br />
                <br />
                %from_user_name% has sent follow request to you.<br />
                <br />
                Accept Link :-&nbsp; <a href="%accept_link%" target="_blank">Accept Request</a><br />
                Reject Link :-&nbsp; <a href="%reject_link%" target="_blank">Reject Request</a><br />
                <br />
                Thank you,<br />
                Contest Partner Team',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Follow Friend Request Cancelled",
                'variables' => "",
                'subject' => "Follow Friend Request Cancelled",
                'emailformat' => 'Hello %full_name%,<br />
                <br />
                %from_user_name% has been cancelled your follow request.<br />
                <br />
                Thank you,<br />
                Contest Partner Team',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => "Contest Invitation",
                'variables' => "",
                'subject' => "Contest Invitation",
                'emailformat' => 'Hello,<br />
                <br />
                %from_user_name% send contest invitation.<br />
                <br />
                <a href="%contest_link%" target="_blank">Click Here</a><br />
                <br />
                Thank you,<br />
                Contest Partner Team',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($emails as $key => $value) {
            Email_format::insertGetId($value);
        }
    }
}
