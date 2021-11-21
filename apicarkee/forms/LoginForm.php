<?php
namespace apicarkee\forms;

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
        if (!in_array($this->device_type, ['ios', 'android'])) {
            return $this->addError('device_type', 'Invalid device type');   
        }
        
        $user = User::findByAccountEmail($this->email, $this->account_id);

        if (!$user || !$user->validatePassword($this->password)) {
            $this->addError('password', 'Invalid username or password.');
            return FALSE;
        }

        // if ($user->isMembershipExpire()){
        //     $this->addError('password', 'Your membership has expired. Please contact an admin for further details on the renewal of your membership.');
        //     return false;
        // }
        
        if (!Yii::$app->user->login($user)) {
            $this->addError('password', 'Invalid username or password.');
            return FALSE;
        }

        /**
         * Validate only if user is a club member
         */
        if ($user->account AND $user->isIncomplete()){
            $company = ($user->account->user) ? $user->account->user->company : $user->account->company;
            
            return $this->addError('email', 'Please complete your profile using ' . $company . ' app.');
        }

        $user->generateAuthKey();

        if (!empty($this->uiid) AND !empty($this->device_type)) {
            $user->{$this->device_type . '_uiid'} = $this->uiid;
        }

        $user->save();
    }
}