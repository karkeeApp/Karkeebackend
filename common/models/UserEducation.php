<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

use common\helpers\UserHelper;

class UserEducation extends ActiveRecord{    

    private $_user;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function tableName()
    {
        return '{{%user_education}}';
    }
    
    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        return parent::insert($runValidation, $attributes);
    }

    public function getUser()
    {
        return $this->hasOne(User::classname(),['user_id' => 'user_id']);
    }
}