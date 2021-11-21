<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class LoanPayment extends ActiveRecord{    

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function primaryKey()
    {
        return ['payment_id'];
    }

    public static function tableName()
    {
        return '{{%user_loan_payments}}';
    }
    
    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        return parent::insert($runValidation, $attributes);
    }

}