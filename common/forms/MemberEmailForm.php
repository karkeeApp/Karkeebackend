<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\User;
use common\helpers\Common;

class MemberEmailForm extends Model
{
    public $user;
    public $email;

    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
            [['email'], 'isUnique'],
        ];
    }

    public function isUnique($attr)
    {
        if (!$this->user) {
            return $this->addError('email', 'User not found.');
        }

        $found = User::find()
        ->where(['account_id' => $this->user->account_id])
        ->andWhere(['<>', 'user_id', $this->user->user_id])
        ->andWhere(['email' => $this->email])
        ->one();
        
        if ($found) {
            return $this->addError('email', 'Email address already exists.');
        }
    }
}