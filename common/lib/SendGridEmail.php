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

        $email->setFrom('admin@karkee.biz', "Admin"); 
        $email->setSubject($subject);
        $email->addTo($to,$params['name']);
        $email->addCc("admin@karkee.biz", "Admin");
        $email->addBcc('abubakar@unravelstudios.co', 'Info');
         
        if(!in_array($params['club_email'],$admin_staff_emails)) $email->addCc($params['club_email'], "Club Admin");
        if(!empty($params['email']) AND !in_array($params['email'],$admin_staff_emails)) $email->addCc($params['email'],"User");

                      
        if($template == "club-reset-password" OR $template == "carkee-reset-password"){

            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("reset_code", $params['reset_code']);
            $email->addDynamicTemplateData("client_email", $to);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);
             
            $email->setTemplateId("d-ffcaa631f2a848c590997b43841016eb"); 
            
        }else if($template == "club-inquiry" OR $template == "carkee-inquiry"){

            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("address", $params['address']);
            $email->addDynamicTemplateData("message", $params['message']);
            $email->addDynamicTemplateData("client_email", $to);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);

            $email->setTemplateId("d-08dca03733bd401da5f424728515f704"); 
            

        }else if($template == "club-inquiry-reply" OR $template == "carkee-inquiry-reply"){

            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("address", $params['address']);
            $email->addDynamicTemplateData("inquiry", $params['inquiry']);
            $email->addDynamicTemplateData("message", $params['message']);
            $email->addDynamicTemplateData("client_email", $params['email']);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);

           $email->setTemplateId("d-44e04578a3764ff7a47276d29d3aa423"); 
        
        }
        else if($template == "club-register" OR $template == "carkee-register"){
            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("user_id", $params['user_id']);
            $email->addDynamicTemplateData("client_email", $to);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);
 
            $email->setTemplateId("d-2428db09101648379b948b0acfb3a0ea");      

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

            $email->setTemplateId("d-52b11884119c4231874e588be99c19c6"); 
                           

        }else if($template == "admin-notification"){

            $email->addDynamicTemplateData("heading", $params['heading']);
            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("client_email", $params['email']);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);

            $email->setTemplateId("d-697ff5bf543f4a0493b35b09ef45e6c4"); 

        }else if($template == "club-membership"){

            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("status", $params['status']);
            $email->addDynamicTemplateData("client_email", $to);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);

            $email->setTemplateId("d-a9e3ed8d1331468cb8761afaf8a8a61f");  

        }else if($template == "approve-reject"){

            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("event", $params['event']);
            $email->addDynamicTemplateData("status", $params['status']);
            $email->addDynamicTemplateData("client_email", $to);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);

            $email->setTemplateId("d-a031dcac661a449bb490ac6261703b43"); 
            
        } else if($template == "test"){

            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("email", $params['email']);
            $email->addDynamicTemplateData("client_email", $to);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);

           $email->setTemplateId("d-2428db09101648379b948b0acfb3a0ea");

            //$email->addContent("text/html","This is just a test email message... ".$params['name']."!");
        }

        $apiKey = Yii::$app->params['sendgrid_api_key'];   
             
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
        

        $email = new Mail(); 

        $email->setFrom('admin@karkee.biz', "Admin"); 
        $email->setSubject($subject);
        $email->addTo($to,$params['name']);
        $email->addCc("admin@karkee.biz", "Admin");
        $email->addBcc('abubakar@unravelstudios.co', 'Info');
        // $email->addBccs($admin);
        // $email->addBccs($subAdmin);
        // $email->addBccs($superAdmin);

        if (!empty($admin)) $email->addBccs($admin);
        if (!empty($subAdmin)) $email->addBccs($subAdmin);
        if (!empty($superAdmin)) $email->addBccs($superAdmin);

        if(!in_array($params['club_email'],$admin_staff_emails)) $email->addCc($params['club_email'], "Club Admin");
        if(!empty($params['email']) AND !in_array($params['email'],$admin_staff_emails)) $email->addCc($params['email'],"User");

        
        if($template == "admin-notification"){

            $email->addDynamicTemplateData("heading", $params['heading']);
            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("client_email", $params['email']);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);

           $email->setTemplateId("d-697ff5bf543f4a0493b35b09ef45e6c4");


        }else if($template == "test"){

            $email->addDynamicTemplateData("name", $params['name']);
            $email->addDynamicTemplateData("email", $params['email']);
            $email->addDynamicTemplateData("client_email", $to);
            $email->addDynamicTemplateData("club_name", $params['club_name']);
            $email->addDynamicTemplateData("club_link", $params['club_link']);
            $email->addDynamicTemplateData("club_logo", $params['club_logo']);

           $email->setTemplateId("d-2428db09101648379b948b0acfb3a0ea");

        }


        $apiKey = Yii::$app->params['sendgrid_api_key']; 
                    
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


