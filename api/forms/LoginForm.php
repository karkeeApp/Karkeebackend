<?php
namespace api\forms;

use common\controllers\api\Controller;
use Yii;
use yii\base\Model;

use common\models\User;
use common\models\Company;

class LoginForm extends Model
{
    public $password;
    public $email;
    public $account_id;
    public $uiid;
    public $device_type;

    public $fcm_token;
    public $fcm_topics;

    public function rules()
    {
        return [
            [['account_id', 'email', 'password', 'uiid', 'device_type'], 'required'],
            ['email', 'validateLogin'],
            [['fcm_token', 'fcm_topics'],'safe']
        ];
    }

    public function validateLogin($attr, $params)
    {
        if (!in_array($this->device_type, ['ios', 'android', 'web'])) {
            return $this->addError('device_type', 'Invalid device type');   
        }
        
        $user = User::findByAccountEmail($this->email, $this->account_id);

        if (!$user || !$user->validatePassword($this->password)) {
            $this->addError('password', 'Invalid username or password.');
            return FALSE;
        }
           
        
        
        if (!Yii::$app->user->login($user)) {
            $this->addError('password', 'Invalid username or password.');
            return FALSE;
        }

        $user->generateAuthKey();

        if (!empty($this->uiid) AND !empty($this->device_type)) {
            $user->{$this->device_type . '_uiid'} = $this->uiid;
        }

        $user->save();

        
    }
}