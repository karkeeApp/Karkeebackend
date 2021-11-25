<?php
namespace common\lib;
use common\models\User;

use SendGrid\Mail\Mail;
use Yii;
class SendGridEmail
{
    public static function sendEmail($to, $subject, $template = NULL, $params=[], $attachmentPath = NULL, $ignoreFormat = FALSE)
    {


        $admin_staff_emails = [
                                $to,
                                "backend@unravelstudios.co",
                                "superadmin4@yopmail.com",
                                "franklin@unravelstudios.co",
                                "abubakar@unravelstudios.co",
                                "admin@carkee.sg"
                            ];

        // Comment out the above line if not using Composer
        // require("<PATH TO>/sendgrid-php.php");
        // If not using Composer, uncomment the above line and
        // download sendgrid-php.zip from the latest release here,
        // replacing <PATH TO> with the path to the sendgrid-php.php file,
        // which is included in the download:
        // https://github.com/sendgrid/sendgrid-php/releases

        $email = new Mail(); 
        // $email->setFrom('backend@unravelstudios.co', "Admin"); // team lead acct
        $email->setFrom('bigprince2021@gmail.com', "Admin"); // jr dev acct
        //$email->setFrom('abubakar@unravelstudios.co', "Admin"); // New acct
        $email->setSubject($subject);
        $email->addTo($to,$params['name']);
        $email->addCc("backend@unravelstudios.co", "Admin");
        // $email->addBcc('franklin@unravelstudios.co', 'Info');
        $email->addBcc('abubakar@unravelstudios.co', 'Info');
        
        
        // if(!in_array($to,$admin_staff_emails)) $email->addCc("sheetalbdz@yopmail.com", "QA");

        if(!in_array($params['club_email'],$admin_staff_emails)) $email->addCc($params['club_email'], "Club Admin");
        if(!empty($params['email']) AND !in_array($params['email'],$admin_staff_emails)) $email->addCc($params['email'],"User");

                      
        if($template == "club-reset-password" OR $template == "carkee-reset-password"){

            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("reset_code", $params['reset_code']);
            $email->addDynamicTemplateData("client_email", $to);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);
            
            //  $email->setTemplateId("d-07c42e9ba2a542d787a37573df0679be"); //team lead acct
            $email->setTemplateId("d-dff8216dcd494d178786700358d18fad"); //jr Dev acct
            //$email->setTemplateId("d-0e7a4e5333a449abb5d8a563afa2f511"); //new acct

        }else if($template == "club-inquiry" OR $template == "carkee-inquiry"){

            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("address", $params['address']);
            $email->addDynamicTemplateData("message", $params['message']);
            $email->addDynamicTemplateData("client_email", $to);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);

            //  $email->setTemplateId("d-dddd351051bc4dcc941aa31d6b298d10"); //team lead acct
            $email->setTemplateId("d-70749a0b295d4cb28887025cf126e8b5"); //jr Dev acct
            // $email->setTemplateId("d-8120a60b99864482ae43dffce75379fd"); //new acct

        }else if($template == "club-inquiry-reply" OR $template == "carkee-inquiry-reply"){

            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("address", $params['address']);
            $email->addDynamicTemplateData("inquiry", $params['inquiry']);
            $email->addDynamicTemplateData("message", $params['message']);
            $email->addDynamicTemplateData("client_email", $params['email']);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);

           //  $email->setTemplateId("d-ddea77b127c14b99808b021976917856"); //team lead acct
            $email->setTemplateId("d-41bcc68ceacb4822a9bab0a398c0697a"); ///jr Dev acct
           //$email->setTemplateId("d-4b7887f92cea48f491fb6e03254cad43"); //new acct
        
        }
        else if($template == "club-register" OR $template == "carkee-register"){
            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("user_id", $params['user_id']);
            $email->addDynamicTemplateData("client_email", $to);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);

            //$email->setTemplateId("d-5f110b3a80a540c9b3a9282739f782a1");    //team lead acct    
            $email->setTemplateId("d-bf34496cd19e4e5aa75601a26f96e7e2");    ///jr Dev acct  
            //$email->setTemplateId("d-c769a11f89454211b4f0dd7bd709a973"); //new acct 

        }
        else if($template == "club-register-verify" OR $template == "carkee-register-verify"){
            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("user_id", $params['user_id']);
            $email->addDynamicTemplateData("client_email", $to);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);
            $email->addDynamicTemplateData("reg_codes", $params['reg_codes']);
            $email->addDynamicTemplateData("api_link", $params['api_link']);
            $email->addDynamicTemplateData("account_id", $params['account_id']);

            //$email->setTemplateId("d-ade018fbd002402d81b0da7b8f1691d4");      //team lead acct  
            $email->setTemplateId("d-4bb74990ba1f4c12a6e4e81a79fcb4d7");      //jr Dev acct
           // $email->setTemplateId("d-326b7881815542d49440bb919c220151"); //new acct  
                           

        }else if($template == "admin-notification"){

            $email->addDynamicTemplateData("heading", $params['heading']);
            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("client_email", $params['email']);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);

            //$email->setTemplateId("d-a79bb2c4cd0d435e845ba1f7c9d6368e"); //team lead acct
            $email->setTemplateId("d-66c52d7844054e43b11bdf150477e67e"); //jr Dev acct
            //$email->setTemplateId("d-b4813a944d1c491d99a4cf333b719d74 "); //new acct

        }else if($template == "club-membership"){

            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("status", $params['status']);
            $email->addDynamicTemplateData("client_email", $to);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);

            //$email->setTemplateId("d-4822689e8df543a7b2c126b80de7a3bd");    //team lead acct
            $email->setTemplateId("d-fad47e28975c4ef191107f33e9608a09");    //jr Dev acct
           //$email->setTemplateId("d-792d7b83ab724f7188bf67bae42e1487"); //new acct

            //$email->addContent("text/html","This is just a test email message... ".$params['name']."!");
        }else if($template == "approve-reject"){

            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("event", $params['event']);
            $email->addDynamicTemplateData("status", $params['status']);
            $email->addDynamicTemplateData("client_email", $to);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);

            //$email->setTemplateId("d-032f1174c64c4209ab5653302a7bddab");    //team lead acct
            $email->setTemplateId("d-b672ec6e2bdd44f8b95370df48ff4873");    //jr Dev acct
           // $email->setTemplateId("d-fcc789619a9146c58e8dd1ba227b288f"); //new acct

            //$email->addContent("text/html","This is just a test email message... ".$params['name']."!");
        } else if($template == "test"){

            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("email", $params['email']);
            $email->addDynamicTemplateData("client_email", $to);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);

           //$email->setTemplateId("d-5f110b3a80a540c9b3a9282739f782a1"); //team lead acct
           $email->setTemplateId("d-bf34496cd19e4e5aa75601a26f96e7e2"); //jr Dev acct
           //$email->setTemplateId("d-c769a11f89454211b4f0dd7bd709a973"); //new acct


            //$email->addContent("text/html","This is just a test email message... ".$params['name']."!");
        }

                       
        $sendgrid = new \SendGrid($apiKey);
        try {
            $response = $sendgrid->send($email);
            Yii::info($response->statusCode(),'carkee');
            Yii::info($response->headers(),'carkee');
            Yii::info($response->body(),'carkee');
            return $response;
            // print $response->statusCode() . "\n";
            // print_r($response->headers());
            // print $response->body() . "\n";
        } catch (\Exception $e) {
            // echo 'Caught exception: '. $e->getMessage() ."\n";
            return $e->getMessage();
        }        



    }




    public static function sendNotification($to, $subject, $template = NULL, $admin, $subAdmin, $superAdmin, $params=[], $attachmentPath = NULL, $ignoreFormat = FALSE)
    {

        $admin_staff_emails = [
                                $to,
                                "backend@unravelstudios.co",
                                "superadmin4@yopmail.com",
                                // "sheetalbdz@yopmail.com",
                                "franklin@unravelstudios.co",
                                "admin@carkee.sg"
                            ];

        // Comment out the above line if not using Composer
        // require("<PATH TO>/sendgrid-php.php");
        // If not using Composer, uncomment the above line and
        // download sendgrid-php.zip from the latest release here,
        // replacing <PATH TO> with the path to the sendgrid-php.php file,
        // which is included in the download:
        // https://github.com/sendgrid/sendgrid-php/releases

        $bccEmails = [
            "abubakar@unravelstudios.co" => "Jr Dev",
            "franklin@unravelstudios.co" => "Team Lead"
        ];
        

        $email = new Mail(); 
        // $email->setFrom('backend@unravelstudios.co', "Admin"); // team lead acct
        $email->setFrom('bigprince2021@gmail.com', "Admin"); // jr dev acct
        // $email->setFrom('abubakar@unravelstudios.co', "Admin"); // New acct
        $email->setSubject($subject);
        $email->addTo($to,$params['name']);
        $email->addCc("backend@unravelstudios.co", "Admin");
        $email->addBcc('abubakar@unravelstudios.co', 'Info');
        // $email->addBccs($bccEmails);
        $email->addBccs($admin);
        $email->addBccs($subAdmin);
        $email->addBccs($superAdmin);
    
        
        // if(!in_array($to,$admin_staff_emails)) $email->addCc("sheetalbdz@yopmail.com", "QA");


        if(!in_array($params['club_email'],$admin_staff_emails)) $email->addCc($params['club_email'], "Club Admin");
        if(!empty($params['email']) AND !in_array($params['email'],$admin_staff_emails)) $email->addCc($params['email'],"User");

        
        if($template == "admin-notification"){

            $email->addDynamicTemplateData("heading", $params['heading']);
            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("client_email", $params['email']);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);

            // $email->setTemplateId("d-a79bb2c4cd0d435e845ba1f7c9d6368e"); //team lead acct
            $email->setTemplateId("d-66c52d7844054e43b11bdf150477e67e"); //jr dev acct
           //$email->setTemplateId("d-b4813a944d1c491d99a4cf333b719d74"); //new acct


        }else if($template == "test"){

            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("email", $params['email']);
            $email->addDynamicTemplateData("client_email", $to);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);

            // $email->setTemplateId("d-5f110b3a80a540c9b3a9282739f782a1"); //team lead acct
            $email->setTemplateId("d-bf34496cd19e4e5aa75601a26f96e7e2"); //jr dev acct
           //  $email->setTemplateId("d-c769a11f89454211b4f0dd7bd709a973"); //new acct


        }

        $sendgrid = new \SendGrid($apiKey);
        try {
            $response = $sendgrid->send($email);
            Yii::info($response->statusCode(),'carkee');
            Yii::info($response->headers(),'carkee');
            Yii::info($response->body(),'carkee');
            return $response;
            // print $response->statusCode() . "\n";
            // print_r($response->headers());
            // print $response->body() . "\n";
        } catch (\Exception $e) {
            // echo 'Caught exception: '. $e->getMessage() ."\n";
            return $e->getMessage();
        }


    }


}


