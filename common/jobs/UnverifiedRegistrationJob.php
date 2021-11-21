<?php
namespace common\jobs;

use common\lib\Helper;
use common\models\User;
use common\models\UserSettings;
// use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\RetryableJobInterface;

class UnverifiedRegistrationJob extends BaseObject implements JobInterface, RetryableJobInterface
{
       
    public function execute($queue)
    {
        $users = User::find()
                    ->leftJoin('user_settings', 'user_settings.user_id = user.user_id')
                    ->where(['user_settings.is_verified' => 0])
                    ->all();
        
        if(!empty($users) AND count($users) > 0){

            $title = 'Unverified Registration';
            $desc = 'Hi! There, Just let let you know that your unverified registration had been removed. If you need to access the app, You may need to register again and please have it verified. Thank You!';
            
            $usercarkeeids = [];
            $userp9clubids = [];
            $usermclubids = [];

            // Declare and define two dates
            $dateNow = strtotime("now"); 
            foreach($users as $user){
                $dateReg = strtotime($user->created_at); 
                
                // Formulate the Difference between two dates
                $diff = $dateNow - $dateReg; 
                if($diff > 7){
                    if($user->account_id == 0){
                        $usercarkeeids[] = $user->user_id;
                    }else{
                        if(strtolower($user->account->company) == 'p9club') $userp9clubids[] = $user->user_id;
                        else $usermclubids[] = $user->user_id;
                    }
                }
            }
               
            if(count($usercarkeeids) > 0)   Helper::pushNotificationFCM_INCRegistration($title,$desc,$usercarkeeids,'Karkee',0);
            if(count($userp9clubids) > 0)   Helper::pushNotificationFCM_INCRegistration($title,$desc,$userp9clubids,'p9club',NULL);
            if(count($usermclubids) > 0)    Helper::pushNotificationFCM_INCRegistration($title,$desc,$usermclubids,'mclub',NULL);
        }

    
    }

    public function getTtr()
    {
        return 15 * 60;
    }

    public function canRetry($attempt, $error)
    {
        return ($attempt < 5) && ($error instanceof TemporaryException);
    }
}