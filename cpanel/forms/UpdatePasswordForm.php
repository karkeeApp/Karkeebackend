<?php
namespace cpanel\forms;

use Yii;
use yii\base\Model;
// use common\models\Admin;
use common\models\User;

/**
 * Login form
 */
class UpdatePasswordForm extends Model
{
    public $email;
    public $password;
    public $confirm_password;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email','confirm_password', 'password'], 'required'],
            ['password','compare','compareAttribute' => 'password_confirm', 'message' => 'Password does not matched with your Confirmed Password', 'on' => ['register']],
            
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }
    
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            // $this->_user = Admin::findByUsername($this->username);z
            $this->_user = User::findByUsername($this->email);
        }

        return $this->_user;
    }
}
