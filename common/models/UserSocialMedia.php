<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

use yii\helpers\Url;
use common\helpers\Common;
use common\behaviors\TimestampBehavior;

class UserSocialMedia extends ActiveRecord
{    
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    public static function tableName()
    {
        return '{{%user_social_media}}';
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }
    public static function create(\common\forms\UserForm $form, $user_id)
    {
        $user_social_media                      = new self;
        $user_social_media->user_id             = $user_id;
        $user_social_media->social_media_id     = $form->social_media_id;
        $user_social_media->social_media_type   = $form->social_media_type;
        
        $user_social_media->save();

        return $user_social_media;
    }

    public function data()
    {        
        $data = [
            'id'                => $this->id,
            'social_media_id'   => $this->social_media_id,
            'social_media_type' => $this->social_media_type
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