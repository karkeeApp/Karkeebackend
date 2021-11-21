<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\helpers\Common;

class UserExisting extends ActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function primaryKey()
    {
        return ['id'];
    }

    public static function tableName()
    {
        return '{{%user_existing}}';
    }

    public function getUser()
    {
        return User::find()
            ->where(['account_id' => $this->account_id])
            ->andWhere(['plate_no' => $this->plate_no])
            ->andWhere('plate_no <> ""')
            ->one();
    }
    
    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        return parent::insert($runValidation, $attributes);
    }

    public static function create($name, $plate, $account_id)
    {
        $member = self::findOne([
            'name'     => $name,
            'plate_no' => $plate
        ]);

        if (!$member){
            $member             = new self;
            $member->name       = $name;
            $member->plate_no   = $plate;
            $member->account_id = $account_id;
            $member->save();
        }

        return $member;
    }  
}