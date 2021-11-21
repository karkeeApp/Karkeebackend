<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\helpers\Common;
use common\lib\Helper;


class UserNotification extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user_notification}}';
    }
    
    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        return parent::insert($runValidation, $attributes);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }

    public static function create(\common\models\Notification $notification, $user_id)
    {
        $userNotification = new self;
        $userNotification->user_id = $user_id;
        $userNotification->message = $notification->message;
        $userNotification->title = $notification->title;
        $userNotification->is_read = 0;
        $userNotification->save();

        $fcm_status = Helper::pushNotificationFCM(0, $notification->title, $notification->message);

        return $userNotification;
    }

    public static function add(\common\forms\UserNotificationForm $form, $user_id)
    {
        $userNotification = new self;
        $userNotification->user_id = $user_id;
        $userNotification->message = $form->message;
        $userNotification->title = $form->title;
        $userNotification->is_read = 0;
        $userNotification->save();

        $fcm_status = Helper::pushNotificationFCM(0, $userNotification->title, $userNotification->message);

        return $userNotification;
    }

    public function data($user = NULL)
    {
        $data = $this->attributes;
        $data['user'] = $this->user;
        return $data;
    }
   

    public function date()
    {
        return Common::date($this->created_at);
    }

    public function time()
    {
        return Common::timeElapse($this->created_at);
    }

    public function excerpt($count = 225)
    {
        $excerpt = mb_substr( $this->message, 0, $count );
        $excerpt = preg_replace( '/&[^;\s]{0,6}$/', '', $this->message );

        return $excerpt;
    }
}