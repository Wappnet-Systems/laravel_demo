<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Lib;

use Illuminate\Support\Facades\Config;
use App\Model\Email_format;
use App\Mail\Mails;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use DateTime;

use App\User;

/**
 * Description of CommonTask
 *
 * @author kishan
 */
class CommonTask {

    public function __construct() {

    }

    /*
     * $data contains below index
     * full_name, email, password, to_email
     */
    public function send_singup_admin($data)
    {   //Pending

        $emailData = Email_format::find(1)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%full_name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%email%", $data['email'], $mailformat);
        $mailformat = str_replace("%password%", $data['password'], $mailformat);
        // echo "<pre>";
        // print_r($mailformat);die;
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }

    /*
     * $data contains below index admin user
     * email_address, link
     */
    public function send_admin_forgot_password($data)
    {   //Pending

        $emailData = Email_format::find(5)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%full_name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%email_address%", $data['email_address'], $mailformat);
        $mailformat = str_replace("%password%", $data['password'], $mailformat);
        // echo "<pre>";
        // print_r($mailformat);die;
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }

    /*
     * $data contains below index front user
     * email_address, link
     */
    public function send_forgot_password($data)
    {   //Pending

        $emailData = Email_format::find(2)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%full_name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%link%", $data['link'], $mailformat);
        // echo "<pre>";
        // print_r($mailformat);die;
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }

    /*
     * $data contains below index
     * name, reason,title to_email
     */
    public function send_survey_status_disable($data)
    {   //Pending

        $emailData = Email_format::find(3)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%reason%", $data['reason'], $mailformat);
        $mailformat = str_replace("%title%", $data['title'], $mailformat);
        // echo "<pre>";
        // print_r($mailformat);die;
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }

    /*
     * $data contains below index
     * name, title, to_email
     */
    public function send_survey_status_enable($data)
    {   //Pending

        $emailData = Email_format::find(4)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%title%", $data['title'], $mailformat);
        // echo "<pre>";
        // print_r($mailformat);die;
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }

    /*
     * $data contains below index
     * to_email, full_name, plan_name, plan_amount, plan_interval
     */
    public function send_subscription_email($data){
        $emailData = Email_format::find(6)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%full_name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%plan_name%", $data['plan_name'], $mailformat);
        $mailformat = str_replace("%plan_amount%", $data['plan_amount'], $mailformat);
        $mailformat = str_replace("%plan_interval%", $data['plan_interval'], $mailformat);

        /* echo "<pre>";
        print_r($mailformat);die; */
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat,$data['invoice_pdf']));
    }

    /*
     * $data contains below index
     * name, title, to_email
     */
    public function send_survey_winner_email($data)
    {   //Pending

        $emailData = Email_format::find(7)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%title%", $data['title'], $mailformat);
        $mailformat = str_replace("%amount%", $data['amount'], $mailformat);
        $mailformat = str_replace("%images%", $data['coupon_images'], $mailformat);
        // echo "<pre>";
        // print_r($mailformat);die;
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }

    /*
     * $data contains below index
     * name, amount, notes, to_email
     */
    public function wallet_request_approved_email($data)
    {   //Pending

        $emailData = Email_format::find(8)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%amount%", $data['amount'], $mailformat);
        $mailformat = str_replace("%notes%", $data['notes'], $mailformat);
        
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }
    /*
     * $data contains below index
     * name, amount, notes, to_email
     */
    public function wallet_request_rejected_email($data)
    {   //Pending

        $emailData = Email_format::find(9)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%amount%", $data['amount'], $mailformat);
        $mailformat = str_replace("%notes%", $data['notes'], $mailformat);
        // echo "<pre>";
        // print_r($mailformat);die;
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }

    /*
     * $data contains below index
     * full_name, link, to_email
     */
    public function vefify_account($data)
    {   //Pending

        $emailData = Email_format::find(10)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%full_name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%link%", $data['link'], $mailformat);
        // echo "<pre>";
        // print_r($mailformat);die;
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }
    
    /*
     * $data contains below index
     * to_email, full_name, from_email, subject, message
     */
    public function send_contactus_inquiry($data)
    {   //Pending

        $emailData = Email_format::find(11)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%full_name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%from_email%", $data['from_email'], $mailformat);
        $mailformat = str_replace("%subject%", $data['subject'], $mailformat);
        $mailformat = str_replace("%message%", $data['message'], $mailformat);
        // echo "<pre>";
        // print_r($mailformat);die;
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }

    public function user_account_approved_request($data)
    {   
        $emailData = Email_format::find(12)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%user%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%organization_name%", $data['organization_name'], $mailformat);
        $mailformat = str_replace("%business_contact_number%", $data['business_contact_number'], $mailformat);
        $mailformat = str_replace("%business_contact_email%", $data['business_contact_email'], $mailformat);
        $mailformat = str_replace("%business_website%", $data['business_website'], $mailformat);
        $mailformat = str_replace("%business_registration_number%", $data['business_registration_number'], $mailformat);
        $mailformat = str_replace("%email%", $data['from_email'], $mailformat);
        // echo "<pre>";
        // print_r($mailformat);die;
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }
    public function user_account_reject_request($data)
    {   
        $emailData = Email_format::find(13)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%user%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%reason%", $data['reason'], $mailformat);
        $mailformat = str_replace("%email%", $data['from_email'], $mailformat);
        // echo "<pre>";
        // print_r($mailformat);die;
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }
    
    public function business_user_account_approve_request($data)
    {   
        $emailData = Email_format::find(14)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%user%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%email%", $data['from_email'], $mailformat);
        // echo "<pre>";
        // print_r($mailformat);die;
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }
    public function advertisement_disable_by_admin($data)
    {   
        $emailData = Email_format::find(15)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%full_name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%email%", $data['from_email'], $mailformat);
        $mailformat = str_replace("%column_name%", $data['column_name'], $mailformat);
        /* echo "<pre>";
        print_r($mailformat);die; */
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }

    public function contest_disable_by_admin($data)
    {   
        $emailData = Email_format::find(16)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%full_name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%email%", $data['from_email'], $mailformat);
        $mailformat = str_replace("%column_name%", $data['column_name'], $mailformat);
        /* echo "<pre>";
        print_r($mailformat);die; */
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }
    public function survey_disable_by_admin($data)
    {   
        $emailData = Email_format::find(17)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%full_name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%email%", $data['from_email'], $mailformat);
        $mailformat = str_replace("%column_name%", $data['column_name'], $mailformat);
        /* echo "<pre>";
        print_r($mailformat);die; */
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }
    public function advertisement_enable_by_admin($data)
    {   
        $emailData = Email_format::find(18)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];
        $mailformat = str_replace("%full_name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%email%", $data['from_email'], $mailformat);
        /* echo "<pre>";
        print_r($mailformat);die; */
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }
    public function contest_enable_by_admin($data)
    {   
        $emailData = Email_format::find(19)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];
        $mailformat = str_replace("%full_name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%email%", $data['from_email'], $mailformat);
        /* echo "<pre>";
        print_r($mailformat);die; */
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }
    
    public function survey_enable_by_admin($data)
    {   
        $emailData = Email_format::find(20)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];
        $mailformat = str_replace("%full_name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%email%", $data['from_email'], $mailformat);
        /* echo "<pre>";
        print_r($mailformat);die; */
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }
    public function advertisement_remove_by_admin($data)
    {   
        $emailData = Email_format::find(21)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];
        $mailformat = str_replace("%full_name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%email%", $data['from_email'], $mailformat);
        /* echo "<pre>";
        print_r($mailformat);die; */
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }
    public function contest_remove_by_admin($data)
    {   
        $emailData = Email_format::find(22)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];
        $mailformat = str_replace("%full_name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%email%", $data['from_email'], $mailformat);
        /* echo "<pre>";
        print_r($mailformat);die; */
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }
    public function survey_remove_by_admin($data)
    {   
        $emailData = Email_format::find(23)->toArray();
        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];
        $mailformat = str_replace("%full_name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%email%", $data['from_email'], $mailformat);
        /* echo "<pre>";
        print_r($mailformat);die; */
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }

    public function send_follow_request($data)
    {
        $emailData = Email_format::find(24)->toArray();

        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%full_name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%from_user_name%", $data['from_user_name'], $mailformat);
        $mailformat = str_replace("%accept_link%", $data['accept_link'], $mailformat);
        $mailformat = str_replace("%reject_link%", $data['reject_link'], $mailformat);
        
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }
    
    public function send_cancel_follow_request($data)
    {
        $emailData = Email_format::find(25)->toArray();

        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%full_name%", $data['full_name'], $mailformat);
        $mailformat = str_replace("%from_user_name%", $data['from_user_name'], $mailformat);
        
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }

    public function send_contest_invitation($data)
    {
        $emailData = Email_format::find(26)->toArray();

        $subject = $emailData['subject'];
        $mailformat = $emailData['emailformat'];

        $mailformat = str_replace("%from_user_name%", $data['from_user_name'], $mailformat);
        $mailformat = str_replace("%contest_link%", $data['contest_link'], $mailformat);
        
        Mail::to($data['to_email'])->queue(new Mails($subject, $mailformat));
    }
}
