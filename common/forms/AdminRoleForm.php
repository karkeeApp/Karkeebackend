<?php
namespace common\forms;

use common\models\AccountUser;
use Yii;
use yii\base\Model;
use common\models\User;
use common\helpers\Common;

class AdminRoleForm extends Model
{
    public $role;
    public $user_id;

    public function rules()
    {
        return [
            ['role', 'required', 'on' => ['account_admin_add']],
            ['role', 'validateRole', 'on' => ['account_admin_add']],
            ['user_id', 'safe'],
        ];
    }

    public function validateRole($attr)
    {
        $roles = User::roles();

        if (!array_key_exists($this->role, $roles)){
            $this->addError('role', 'Role not found.');
        }
    }
}