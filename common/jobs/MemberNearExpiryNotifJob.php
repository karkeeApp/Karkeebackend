<?php
namespace common\jobs;

use common\lib\Helper;
use common\models\Account;
use common\models\Settings;
use common\models\User;
// use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\RetryableJobInterface;

class MemberNearExpiryNotifJob extends BaseObject implements JobInterface, RetryableJobInterface
{
       
    public function execute($queue)
    {
        $users = User::find()
                        ->where(['NOT','member_expire', NULL])
                        ->andWhere(['<>', 'role', User::ROLE_MEMBERSHIP])
                        ->all();
        
        if(!empty($users) AND count($users) > 0){
            $title = 'Near Expiry';
            $desc = 'Hi! There, Just a soft reminder that your membership near to expire. Please do contact admin for renewal to enjoy the full functionality of the app and receive latest updates the club may provide you. Thank You!';
                     
            $usercarkeeids = [];
            $userp9clubids = [];
            $usermclubids = [];

            // Declare and define two dates
            $dateNow = strtotime("now"); 
            foreach($users as $user){
                $dateReg = strtotime($user->member_expire); 
                $default_value = 14;
                $acnt = Account::find()->where(['account_id'=>$this->account_id])->one();
                if($acnt AND $acnt->renewal_alert) $default_value = $acnt->renewal_alert;
                else {
                    $settings = Settings::find()->one();
                    if($settings->renewal_alert) $default_value = $settings->renewal_alert;
                }
                // Formulate the Difference between two dates
                $diff = $dateNow - $dateReg; 
                if($diff >= $default_value){
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