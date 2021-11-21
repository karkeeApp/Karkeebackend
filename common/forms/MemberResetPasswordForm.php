<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\Service;
use common\helpers\Common;

class MemberResetPasswordForm extends Model
{
    public $email;
    public $account_id;
    public $reset_code;
    public $password_new;
    public $password_confirm;

    public function rules()
    {
        return [
            [['email', 'account_id'], 'required', 'on' => ['forgot', 'confirm', 'update']],
            ['reset_code', 'required', 'on' => ['confirm', 'update']],
            [['password_new', 'password_confirm'], 'required', 'on' => ['update']],
            ['password_confirm', 'validatePassword', 'on' => ['update'], 'when' => function($model) {
                return !empty($this->password_new);
            }],         
        ];
    }

    public function validatePassword()
    {
        if ($this->password_new !== $this->password_confirm){
            $this->addError('password_confirm', Yii::t('app', 'Please confirm password.'));
        }
    }
}