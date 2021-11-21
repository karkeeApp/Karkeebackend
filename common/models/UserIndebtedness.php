<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class UserIndebtedness extends ActiveRecord{    

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function primaryKey()
    {
        return ['indebtedness_id'];
    }

    public static function tableName()
    {
        return '{{%user_indebtedness}}';
    }
    
    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        return parent::insert($runValidation, $attributes);
    }

    public function beforeDelete()
    {
        $this->_user = $this->user;

        $event = new ModelEvent;
        $this->trigger(self::EVENT_BEFORE_DELETE, $event);

        return $event->isValid;
    }

    public function afterDelete()
    {
        $this->trigger(self::EVENT_AFTER_DELETE);

        UserHelper::calculateScore($this->_user);
    }

}