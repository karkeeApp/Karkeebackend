<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\User;
use common\helpers\Common;

/**
 * Login form
 */
class CreditLimitForm extends Model
{
    public $user_id;
    public $credit;
    public $salary;

    public function rules()
    {
        return [
            ['credit', 'trim'],
            ['credit', 'required', 'on' => ['add', 'admin_add', 'account_add']],
            ['credit', 'validateMobile', 'on' => ['add', 'admin_add', 'account_add']],
            [['user_id', 'salary'], 'safe'],
        ];
    }

    public function validateMobile($attribute, $params)
    {   
        if (Yii::$app->id == 'app-frontend') {
            $user = Yii::$app->user->getIdentity();
        } else {
            $user = User::findOne($this->user_id);
        }

        if (!$user) {
            $this->addError($attribute, 'User not found.');
            return;
        }

        /**
         * Credit limit must be less than staff's salary
         */
        if ((float)$this->credit >= (float)$user->salary) {
            $this->addError($attribute, 'Credit limit must be less than the salary(' . $user->salary() . ')');
        }
    }

    public function attributeLabels()
    {
        return [
            'credit' => 'Credit Limit',
        ];
    }

}
