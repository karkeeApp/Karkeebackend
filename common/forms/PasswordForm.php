<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\User;
use common\helpers\Common;

/**
 * Login form
 */
class PasswordForm extends Model
{
    public $user_id;
    public $old;
    public $new;
    public $confirm;

    public function rules()
    {
        return [
            [['old', 'new', 'confirm'], 'trim'],
            [['new'], 'required', 'on' => ['add', 'admin_add', 'account_add']],
            [['old', 'confirm'], 'required', 'on' => ['add']],
            [['old', 'confirm'], 'validatePassword', 'on' => ['add']],
            [['user_id', 'old', 'new', 'confirm'], 'safe'],
        ];
    }

    public function validatePassword($attribute, $params)
    {   
        if ($attribute == 'old') {    
            if (Yii::$app->id == 'app-frontend') {
                $user = Yii::$app->user->getIdentity();
            } else {
                $user = User::findOne($this->user_id);
            }

            if (!$user ) {
                $this->addError('old', 'User not found.');
                return;
            }

            if (!$user->validatePassword($this->old)) {
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
