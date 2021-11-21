<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

use yii\base\ModelEvent;

use common\forms\FileForm;

use common\helpers\UserHelper;

class UserFile extends ActiveRecord
{    
    const TYPE_NATIONAL_ID           = 1;
    const TYPE_FAMILY_BOOK           = 2;
    const TYPE_EMPLOYMENT_AGREEMENT  = 3;
    const TYPE_PAYSLIP               = 4;
    const TYPE_LAND_TITLE            = 5;
    const TYPE_EDUCATION_CERTIFICATE = 6;
    const TYPE_BIRTH_CERTIFICATE     = 7;
    const TYPE_OTHER                 = 8;

    private $_user;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function tableName()
    {
        return '{{%user_file}}';
    }
    
    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        return parent::insert($runValidation, $attributes);
    }

    public function name()
    {
        $types = FileForm::statuses();

        return (array_key_exists($this->type, $types)) ? $types[$this->type] : '';
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->user) UserHelper::calculateScore($this->user);
    }    

    public function getUser()
    {
        return $this->hasOne(User::classname(),['user_id' => 'user_id']);
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