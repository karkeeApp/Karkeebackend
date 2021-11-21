<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

use yii\helpers\Url;
use common\helpers\Common;
use common\behaviors\TimestampBehavior;

class UserFcmToken extends ActiveRecord
{    
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    const NOTIF_TYPE_NONE = 0;
    const NOTIF_TYPE_NEWS = 1;
    const NOTIF_TYPE_EVENTS = 2;
    const NOTIF_TYPE_ALL = 3;
    const NOTIF_TYPE_APPROVED_SPONSOR = 4;
    const NOTIF_TYPE_APP_REGISTRATION = 5;

    public static function tableName()
    {
        return '{{%user_fcm_token}}';
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }
    public static function create(\common\forms\UserForm $form, $user_id)
    {
        $user_fcm_token                 = new self;
        $user_fcm_token->user_id        = $user_id;
        $user_fcm_token->fcm_token      = $form->fcm_token;
        $user_fcm_token->fcm_topics     = $form->fcm_topics;
        
        $user_fcm_token->save();

        return $user_fcm_token;
    }

    public function data()
    {        
        $data = [
            'id'               => $this->id,
            'user_fcm_token'   => $this->fcm_token,
            'user_fcm_topics'  => $this->fcm_topics
        ];
        
        return $data;
    }

    public function status()
    {
        return self::statuses()[$this->status];
    }

    public static function statuses()
    {
        return [
            self::STATUS_ACTIVE        => "Active",
            self::STATUS_DELETED       => 'Deleted'
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class,['id' => 'user_id']);
    }
}