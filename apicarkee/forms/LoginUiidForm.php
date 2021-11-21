<?php
namespace apicarkee\forms;

use Yii;
use yii\base\Model;

use common\models\User;
use common\models\Company;

class LoginUiidForm extends Model
{
    public $uiid;
    public $device_type;
    public $email;
    public $account_id;

    public $fcm_token;
    public $fcm_topics;

    public function rules()
    {
        return [
            [['uiid', 'device_type'], 'required', 'on' => ['uiid', 'biometric', 'faceid']],
            [['email'], 'required', 'on' => ['faceid', 'uiid']],
            ['uiid', 'loginBiometric', 'on' => ['biometric']],
            ['uiid', 'loginFaceid', 'on' => ['faceid', 'uiid']],
            [['fcm_token', 'fcm_topics','account_id'],'safe']
        ];
    }

    public function loginBiometric($attr, $params)
    {
        if (!in_array($this->device_type, ['ios', 'android'])) {
            return $this->addError('device_type', 'Invalid device type');   
        }

        $deviceField = $this->device_type . "_uiid";

        $user = User::find()
        ->where([$deviceField => $this->uiid])
        ->one();

        if (!$user) {
            $this->addError('uiid', 'Access denied.');
            return FALSE;
        }

        // if ($user->isMembershipExpire()){
        //     $this->addError('password', 'Your membership has expired. Please contact an admin for further details on the renewal of your membership.');
        //     return false;
        // } 
        if (!Yii::$app->user->login($user)) {
            $this->addError('uiid', 'Access denied.');
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
        $user->save();
    }

    public function loginFaceId($attr, $params)
    {
        if (!in_array($this->device_type, ['ios', 'android'])) {
            return $this->addError('device_type', 'Invalid device type');   
        }

        $deviceField = $this->device_type . "_uiid";

        $user = User::find()
        ->where([$deviceField => $this->uiid])
        ->andWhere(['email' => $this->email])
        ->one();

        if (!$user) {
            $this->addError('uiid', 'Access denied.');
            return FALSE;
        }
        // if ($user->isMembershipExpire()){
        //     $this->addError('uiid', 'Your membership has expired. Please contact an admin for further details on the renewal of your membership.');
        //     return false;
        // } 
        if (!Yii::$app->user->login($user)) {
            $this->addError('uiid', 'Access denied.');
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
        $user->save();
    }
}