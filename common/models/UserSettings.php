<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

use common\controllers\Controller;
use yii\data\Pagination;
use common\behaviors\TimestampBehavior;

class UserSettings extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function tableName()
    {
        return '{{%user_settings}}';
    }
    
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class,['user_id' => 'user_id']);
    }

    public function edit($form, $fields)
    {
        foreach($fields as $field){
            $this->{$field} = $form->{$field};
        }
                
        $updated = $this->save();

        return $updated;
    }
    public function remove($forceRecordDelete = false)
    {
        if($forceRecordDelete) $this->delete();
        else{
            $this->status = self::STATUS_DELETED;
            $this->save();
        }

        return $this;
    }
}