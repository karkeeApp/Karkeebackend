<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\User;
use common\helpers\Common;

/**
 * Login form
 */
class AccountUserPasswordForm extends Model
{
    public $account_id;
    public $old;
    public $new;
    public $confirm;

    public function rules()
    {
        return [
            [['old', 'new', 'confirm'], 'trim'],
            [['new'], 'required', 'on' => ['admin_add', 'account_admin_add', 'account_add']],

            [['old', 'confirm'], 'required', 'on' => ['account_add']],
            [['old', 'confirm'], 'validatePassword', 'on' => ['account_add']],
            [['account_id', 'old', 'new', 'confirm'], 'safe'],            
        ];
    }

    public function validatePassword($attribute, $params)
    {   
        if ($attribute == 'old') {    
            if (Yii::$app->id == 'app-backend') {
                $account = Yii::$app->user->getIdentity();
            } else {
                $account = Account::findOne($this->account_id);
            }

            if (!$account ) {
                $this->addError('old', 'Account not found.');
                return;
            }

            if (!$account->validatePassword($this->old)) {
                $this->addError('old', 'Invalid password.');
                return;
            }

        /**
         * Check new password
         */
        }elseif ($attribute == 'confirm' AND $this->new != $this->confirm) {
            $this->addError('confirm', 'Password does not match.');
            return;
        }
    }

}
