<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\helpers\Common;

class UserTxn extends ActiveRecord
{
    const TYPE_LOAN_APPROVE = 1;
    const TYPE_LOAN_PAYMENT = 2;    

    public static function tableName()
    {
        return '{{%user_transaction}}';
    }
    
    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        return parent::insert($runValidation, $attributes);
    }

    public static function create(\common\models\User $user, $type, $amount, $refClass)
    {
        $className = get_class($refClass);

        $txn = new self;
        
        $txn->user_id    = $user->user_id;
        $txn->amount     = $amount;
        $txn->type       = $type;
        $txn->class_name = $className;

        $txn->save();
    }

    public function debit()
    {
        return ($this->amount < 0) ? Common::currency(abs($this->amount)) : '';
    }

    public function credit()
    {
        return ($this->amount > 0) ? Common::currency(abs($this->amount)) : '';
    }

    public function date()
    {
        return Common::date($this->created_at);
    }

    public function description()
    {
        $types = self::types();

        return (array_key_exists($this->type, $types)) ? $types[$this->type] : 'Unknown type';
    }

    public static function types()
    {
        return [
            self::TYPE_LOAN_APPROVE => 'Loan approved',
            self::TYPE_LOAN_PAYMENT => 'Payment',
        ];
    }
}