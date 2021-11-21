<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\AccountUser;
use common\helpers\Common;

class AccountUserForm extends Model
{
    public $user_id;
    public $account_id;
    public $username;
    public $email;
    public $status;
    public $role;
    public $password;

    public function rules()
    {
        return [
            [['username' , 'email', 'status', 'password', 'role'], 'trim'],
            [['password'], 'required', 'on' => ['admin_add', 'account_add']],
            [['username' , 'email', 'status', 'role'], 'required', 'on' => ['admin_add', 'admin_edit', 'account_add', 'account_edit', 'add', 'edit']],
            ['email', 'email', 'on' => ['admin_add', 'admin_edit', 'account_add', 'account_edit', 'add', 'edit']],
            ['email', 'validateEmail', 'on' => ['admin_add', 'admin_edit', 'account_add', 'account_edit', 'add', 'edit']],
            ['username', 'validateUsername', 'on' => ['admin_add', 'admin_edit', 'account_add', 'account_edit', 'add', 'edit']],
            [['account_id', 'user_id'], 'safe'],
        ];
    }

    public function validateUsername($attribute, $params)
    {
        $check = AccountUser::find()
            ->where(['username' => $this->username])
            ->andWhere(['<>', 'user_id', $this->user_id])
            ->andWhere(['account_id' =>(int)$this->account_id])
            ->one();

        if ($check) {
            $this->addError($attribute, 'HR name already exists.');
        }
    }

    public function validateEmail($attribute, $params)
    {
        $check = AccountUser::find()
            ->where(['email' => $this->email])
            ->andWhere(['<>', 'account_id', (int)$this->account_id])
            ->one();

        if ($check) {
            $this->addError($attribute, 'Email already exists.');
        }
    }

    

    public static function statuses()
    {
        return [
            AccountUser::STATUS_ACTIVE  => 'Active',
            AccountUser::STATUS_DELETED => 'Deleted',
        ];
    }

}
