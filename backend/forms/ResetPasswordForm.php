<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
// use common\models\Admin;
use common\models\User;

/**
 * Login form
 */
class ResetPasswordForm extends Model
{
    public $email;
    public $reset_code;
    public $password;
    public $confirm_password;
    public $rememberMe = true;
    public $account_id = 0;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['rememberMe', 'safe'],
            ['email', 'email'],
            ['email', 'required', 'on' => ['reset-codes','reset-password']],
            ['email', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'email', 'filter' => ['account_id' => $this->account_id], 'message' => "Email doesn't exist!", 'on' => ['reset-codes','reset-password']],
            [['password','confirm_password','reset_code'], 'required', 'on' => ['reset-password']],
            ['password','compare','compareAttribute' => 'confirm_password', 'message' => 'Password does not matched with your Confirmed Password', 'on' => ['reset-password']],            
        ];
    }

    public function getUser()
    {
        if ($this->_user === null) {
            if(!empty($this->reset_codes)){
                $this->_user = User::find()
                                    ->where(['account_id' => $this->account_id])
                                    ->andWhere(['email' => $this->email])
                                    ->andWhere(['reset_code' => $this->reset_code])
                                    ->one();
            }else{
                $this->_user = User::findByAccountAdminEmail($this->email, $this->account_id);
            }
        }

        return $this->_user;
    }

}
