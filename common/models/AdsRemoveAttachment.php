<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

use yii\helpers\Url;
use common\helpers\Common;

class AdsRemoveAttachment extends ActiveRecord
{    
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;

    public static function tableName()
    {
        return '{{%ads_remove_attachment}}';
    }

    public static function create(\common\forms\AdsRemoveAttachmentForm $form, $user_id)
    {
        $ads                  = new self;
        $ads->account_id      = $form->account_id;
        $ads->user_id         = $user_id;
        $ads->name            = $form->name;
        $ads->ads_id          = $form->ads_id;
        $ads->description     = $form->description;
        
        $ads->save();

        return $ads;
    }

    public function data($user = NULL)
    {        
        $data = $this->attributes;
        $data['link'] = $this->filelink();

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

    public function filelink()
    {
        if (Common::isApi() OR Common::isCarkeeApi()) {
            return ($this->filename)? Url::home(TRUE) . 'file/remove-attachments?id=' . $this->ads_id . '&access-token=' . Yii::$app->request->get('access-token') : '';
        } else if(Common::isAccount() OR Common::isCpanel()){
            return ($this->filename)? Url::home(TRUE) . 'file/remove-attachments?id=' . $this->ads_id : '';
        }

        return ($this->filename)? Url::home(TRUE) . 'file/remove-attachments/' . $this->ads_id . '?access-token=' . Yii::$app->request->get('access-token') : NULL;
    }

    public function isCarkeeSponsor()
    {
        return $this->account_id == 0;
    }

    public function getUser()
    {
        return $this->hasOne(User::classname(),['id' => 'user_id']);
    }

    public function getSponsors()
    {
        return $this->hasMany(Sponsor::classname(),['id' => 'sponsor_id']);
    }
}