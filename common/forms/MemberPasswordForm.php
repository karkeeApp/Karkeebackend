<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\Service;
use common\helpers\Common;

class MemberPasswordForm extends Model
{
    public $user_id;
    public $user;
    public $password_current;
    public $password_new;
    public $password_confirm;

    public function rules()
    {
        return [
            [['password_current', 'password_new', 'password_confirm'], 'required'],
            ['password_current', 'validateCurrentPassword'],
            ['password_confirm', 'validatePassword', 'when' => function($model) {
                return !empty($this->password_new);
            }],         
        ];
    }

    public function validateCurrentPassword()
    {
        if (!$this->user OR !$this->user->validatePassword($this->password_current)) {
            $this->addError('password_current', Yii::t('app', 'Invalid current password.'));
        }
    }

    public function validatePassword()
    {

        if ($this->password_new !== $this->password_confirm){
            $this->addError('password_confirm', Yii::t('app', 'Please confirm password.'));
        }
    }
}