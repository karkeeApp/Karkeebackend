<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\User;
use common\helpers\Common;

/**
 * Login form
 */
class EmailForm extends Model
{
    public $user_id;
    public $email;

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required', 'on' => ['add', 'admin_add', 'account_add']],
            ['email', 'email', 'on' => ['add', 'admin_add', 'account_add']],
            ['email', 'validateEmail', 'on' => ['add', 'admin_add', 'account_add']],
            ['user_id', 'safe'],
        ];
    }

    public function validateEmail($attribute, $params)
    {   
        if (Yii::$app->id == 'app-frontend') {
            $user = Yii::$app->user->getIdentity();
        } else {
            $user = User::findOne($this->user_id);
        }

        if (!$user ) {
            $this->addError($attribute, 'User not found.');
            return;
        }

        $check = User::find()
            ->where(['email' => $this->email])
            ->andWhere(['<>', 'user_id', $user->user_id])
            ->andWhere(['account_id' => $user->account_id])
            ->one();

        if ($check) {
            $this->addError($attribute, 'Email already exists.');
        }
    }

}
