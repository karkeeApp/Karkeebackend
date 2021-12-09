<?php
namespace api\forms;

use Yii;
use yii\base\Model;

use common\models\User;
use common\models\Company;

class LoginUiidForm extends Model
{
    public $account_id;
    public $uiid;
    public $device_type;
    public $email;

    public $fcm_token;
    public $fcm_topics;

    public function rules()
    {
        return [
            [['account_id', 'uiid', 'device_type'], 'required', 'on' => ['uiid', 'biometric', 'faceid']],
            [['email'], 'required', 'on' => ['faceid', 'uiid']],
            ['uiid', 'loginBiometric', 'on' => ['biometric']],
            ['uiid', 'loginFaceid', 'on' => ['faceid', 'uiid']],
            [['fcm_token', 'fcm_topics'],'safe']
        ];
    }

    public function loginBiometric($attr, $params)
    {
        if (!in_array($this->device_type, ['ios', 'android'])) {
            return $this->addError('device_type', 'Invalid device type');   
        }

        $deviceField = $this->device_type . "_uiid";

        $user = User::find()
        ->where(['account_id' => $this->account_id])
        ->andWhere([$deviceField => $this->uiid])
        ->one();

        if (!$user) {
            $this->addError('uiid', 'Access denied.');
            return FALSE;
        }
                
        if (!Yii::$app->user->login($user)) {
            $this->addError('uiid', 'Access denied.');
            return FALSE;
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
        ->where(['account_id' => $this->account_id])
        ->andWhere([$deviceField => $this->uiid])
        ->andWhere(['email' => $this->email])
        ->one();

        if (!$user) {
            $this->addError('uiid', 'Access denied.');
            return FALSE;
        }
        
        if (!Yii::$app->user->login($user)) {
            $this->addError('uiid', 'Access denied.');
            return FALSE;
        }

        $user->generateAuthKey();
        $user->save();
    }
}