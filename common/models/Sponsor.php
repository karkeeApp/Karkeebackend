<?php
namespace common\models;

use common\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;

use yii\helpers\Url;
use common\helpers\Common;

class Sponsor extends ActiveRecord
{    
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    public static function tableName()
    {
        return '{{%sponsor}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public static function create(\common\forms\SponsorForm $form, $user_id)
    {
        $sponsor                  = new self;
        $sponsor->name            = $form->name;
        $sponsor->filename        = $form->filename;
        $sponsor->description     = $form->description;
        $sponsor->user_id         = $user_id;
        $sponsor->category        = $form->category;
        
        $sponsor->save();

        return $sponsor;
    }

    public function imagelink($hash_id = NULL)
    {

        // if (Common::isApi() OR Common::isCarkeeApi()) {
        //     return ($this->filename)? Url::home(TRUE) . 'file/banner?id=' . $this->id . "?t={$this->filename}" . ($hash_id ? '&account_id=' . $hash_id : NULL)  : '';
        // } else if(Common::isAccount() OR Common::isCpanel()){
        if(Common::isApi()){
            return ($this->filename)? Url::home(TRUE) . 'file/sponsor?id=' . $this->id . "?t={$this->filename}" . ($hash_id ? '&account_id=' . $hash_id : NULL)  : '';
        }

        return ($this->filename)? Url::home(TRUE) . 'file/sponsor/' . $this->id . "?t={$this->filename}" . ($hash_id ? '&account_id=' . $hash_id : NULL) : NULL;
    }

    public function data($user = NULL)
    {        
        $isWeb = Yii::$app->request->get('web',0);

        $attributes = $this->attributes;

        if($isWeb == 0){
            foreach($attributes as $key => $val) {
                $attributes[$key] = (string)$val;
            }
        }

        $attributes['sponsor_logo'] = $this->imagelink();
        
        return $attributes;
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

    public function isCarkeeSponsor()
    {
        return $this->account_id == 0;
    }
}