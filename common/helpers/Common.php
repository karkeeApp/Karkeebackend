<?php
namespace common\helpers;

use Yii;
use common\models\Notification;
use common\models\UserNotification;
use common\models\AccountNotification;
use common\models\User;

class Common{
    public static function json($arr, $display = TRUE)
    {
        $json = json_encode($arr);

        if ($display) 
            echo $json;
        else 
            return $json; 
    }

    public static function form($formClass)
    {
        $action = Yii::$app->request->post('action');

        if (!isset($action)) {
            eval("\$form = new {$formClass}();");
        } else{
            if (self::isCpanel()) $action = 'admin_' . $action;
            else if (self::isClub()) $action = 'account_' . $action;

            eval("\$form = new {$formClass}(['scenario' => '{$action}']);");
        }

        return $form;
    }

    public static function currency($amount=0, $decimal = 2)
    {
        return '$' . number_format($amount, $decimal);
    }

    public static function percent($amount=0)
    {
        return ($amount + 0) . '%';
    }

    public static function dump($var = [])
    {
        echo '<pre>'; print_r($var); echo '</pre>';
    }

    public static function date($date = NULL, $format = 'd-m-Y')
    {
        if (!$date) $date = date('Y-m-d');

        //checkdate()
        return date($format, strtotime($date));
    }

    public static function isApi()
    {
        return Yii::$app->id == 'app-api';
    }

    public static function isCpanel()
    {
        return Yii::$app->id == 'app-cpanel';
    }
    public static function isMobileCpanel()
    {
        return Yii::$app->id == 'app-mobilecpanel';
    }
    public static function isHR()
    {
        return Yii::$app->id == 'app-backend';
    }

    public static function isClub()
    {
        return Yii::$app->id == 'app-backend';
    }

    public static function isAccount()
    {
        return Yii::$app->id == 'app-backend';
    }

    public static function isClubMember()
    {
        return Yii::$app->id == 'app-frontend';
    }

    public static function isCarkeeApi()
    {
        return Yii::$app->id == 'app-api-carkee';
    }

    public static function isClubApi()
    {
        return Yii::$app->id == 'app-api';
    }

    public static function currencyType()
    {
        return [
            'usd' => 'USD',
        ];
    }

    public static function timeElapse($datetime, $full = false) {
        $now = new \DateTime;
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);

        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public static function notifyUser($user_id, $title, $message)
    {
        $notification = new Notification;
        $notification->message = $message;
        $notification->title = $title;

        return UserNotification::create($notification, $user_id);
    }

    public static function notifyAccount($account_id, $title, $message)
    {
        $notification = new Notification;
        $notification->message = $message;
        $notification->title = $title;

        return AccountNotification::create($notification, $user_id);
    }

    public static function leaveDays($days)
    {   
        $hours = round($days * LeaveApplication::WORKING_HOUR, 2) + 0;  
        $days = round($days, 2) + 0; 
        
        return "{$days} day(s) or {$hours} hour(s)";
    }

    public static function leaveLabel($hours)
    {   
        $days = ((float)$hours > 0) ? floor($hours / LeaveApplication::WORKING_HOUR) : 0;
        $hours = $hours - ($days * LeaveApplication::WORKING_HOUR);

        $label = '';
        $final_hours = '';

        if ($hours) {
            if($hours == 6){$final_hours = 0.75;}
            elseif($hours == 4){$final_hours = 0.5;}
            elseif($hours == 2){$final_hours = 0.25;}
        }
        $totalUsed = $days + $final_hours;

        if ($totalUsed) $label = "{$totalUsed} day";



        return $label;
    }
    
    /***Backup Old leaveLabel code*****/
    public static function leaveLabelBK($hours)
    {
        $days = ((float)$hours > 0) ? floor($hours / LeaveApplication::WORKING_HOUR) : 0;
        $hours = $hours - ($days * LeaveApplication::WORKING_HOUR);

        $label = '';

        if ($days) $label = "{$days} day(s)";

        if ($hours) {
            if (!empty($label)) $label .= ' and ';

            $label .= "{$hours} hour(s)";
        }

        return $label;
    }

    public static function yearFilter()
    {
        $years = [];

        foreach(range(2017, date('Y') + 1) as $year) {
            $years[$year] = $year;
        }

        return $years;
    }

    public static function systemDateFormat($datetime, $format = 'd-m-Y')
    {
        if ( empty($datetime) ) {
            return;
        }

        return date($format, strtotime($datetime));
    }

    public static function clubUser($id)
    {
        return self::findUser()->andWhere(['user_id' => $id])->one();
    }

    public static function findUser($account = NULL)
    {
        if (!$account AND Common::isClub()) {
            $account = Yii::$app->user->getIdentity();
        }

        return User::find()->where(['account_id' => $account->account_id]);
    }

    public static function identifyAccountID()
    {
        $user = Yii::$app->user->getIdentity();

        return (Common::isCpanel()) ? 0 : $user->account_id;
    } 
}