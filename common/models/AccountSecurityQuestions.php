<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\TimestampBehavior;

class AccountSecurityQuestions extends ActiveRecord{    
    const STATUS_DELETED = 0;  
    const STATUS_ACTIVE  = 1;

    public static function tableName()
    {
        return '{{%account_security_questions}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function getAccount()
    {
        return $this->hasOne(Account::class,['account_id' => 'account_id']);
    }

    public static function Create(\common\forms\AccountSecurityQuestionsForm $form, $user = NULL)
    {
        $accountsecq                 = new self;
        $accountsecq->account_id     = $form->account_id ? $form->account_id : 0;
        $accountsecq->question       = $form->question;
        $accountsecq->is_file_upload       = $form->is_file_upload;
        $accountsecq->save();

        return $accountsecq;
    }

    public function data($current_user_id = 0, $isWeb = 0){
        $attrs = $this->attributes;

        
        return $attrs;
    }
}