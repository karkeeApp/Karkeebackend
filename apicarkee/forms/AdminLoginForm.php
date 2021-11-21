<?php
namespace apicarkee\forms;

use Yii;
use yii\base\Model;

use common\models\User;
use common\models\Company;

class AdminLoginForm extends Model
{
    public $password;
    public $email;
    public $account_id = 0;

    public $fcm_token;
    public $fcm_topics;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'validateLogin'],
            [['fcm_token', 'fcm_topics'],'safe']
        ];
    }

    public function validateLogin($attr, $params)
    {
                
        $user = User::findByAccountEmail($this->email, $this->account_id);

        if (!$user || !$user->validatePassword($this->password)) {
            $this->addError('password', 'Invalid username or password.');
            return FALSE;
        }
        
        if ($user AND !$user->isAdministrator()) {
            $this->addError('password', 'Only Admin Account can access this module');
            return FALSE;
        }
        
        if (!Yii::$app->user->login($user)) {
            $this->addError('password', 'Invalid username or password.');
            return FALSE;
        }

        $user->generateAuthKey();

        $user->save();
    }
}