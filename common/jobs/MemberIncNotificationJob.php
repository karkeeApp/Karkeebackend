<?php
namespace common\jobs;

use common\lib\Helper;
use common\models\User;
// use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\RetryableJobInterface;

class MemberIncNotificationJob extends BaseObject implements JobInterface, RetryableJobInterface
{
       
    public function execute($queue)
    {
        $users = User::find()
                        ->where(['status' => User::STATUS_INCOMPLETE])
                        ->andWhere(['<>', 'role', User::ROLE_MEMBERSHIP])
                        ->all();
        
        if(!empty($users) AND count($users) > 0){

            $title = 'Incomplete Registration';
            $desc = 'Hi! There, Just a soft reminder that you have not yet completed your registration. Please do complete it to enjoy the full functionality of the app and receive latest updates the club may provide you. Thank You!';
            
            $usercarkeeids = [];
            $userp9clubids = [];
            $usermclubids = [];

            // Declare and define two dates
            $dateNow = strtotime("now"); 
            foreach($users as $user){
                $dateReg = strtotime($user->created_at); 
                
                // Formulate the Difference between two dates
                $diff = $dateNow - $dateReg; 
                if($diff >= 14){
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