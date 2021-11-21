<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

use yii\helpers\Url;
use common\helpers\Common;

class UserSponsor extends ActiveRecord
{    
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    public static function tableName()
    {
        return '{{%user_sponsor}}';
    }

    public static function create(\common\forms\UserSponsorForm $form, $user_id)
    {
        $user_sponsor                  = new self;
        $user_sponsor->sponsor_id      = $form->sponsor_id;
        $user_sponsor->user_id         = $user_id;
        // $user_sponsor->category        = $form->category;
        
        $user_sponsor->save();

        return $user_sponsor;
    }

    public function data($user = NULL)
    {        
        
        $data = [
            'id'         => $this->id,
            'sponsors'   => $this->sponsors
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