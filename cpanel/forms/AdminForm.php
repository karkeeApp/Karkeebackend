<?php
namespace cpanel\forms;

use Yii;
use yii\base\Model;
use common\models\Admin;
use common\helpers\Common;

class AdminForm extends Model
{
    public $admin_id;
    public $username;
    public $email;
    public $status;
    public $role;
    public $password;

    public function rules()
    {
        return [
            [['email' , 'status','role','password', 'username'], 'trim'],
            [['email' , 'status','role', 'username'], 'required', 'on' => ['admin_add', 'admin_edit']],
            [['password'], 'required', 'on' => ['admin_add']],
            [['email'], 'uniqueEmail', 'on' => ['admin_add', 'admin_edit']],
            [['username'], 'uniqueUsername', 'on' => ['admin_add', 'admin_edit']],
            ['admin_id', 'safe'],
        ];
    }

    public function uniqueUsername($attr)
    {
        if (empty($this->username)) return;

        $qry = Admin::find()->where(['username' => $this->username]);

        if ($this->admin_id) {
            $qry->andWhere(['<>', 'admin_id', $this->admin_id]);
        }

        $admin = $qry->one();

        if ($admin) {
            $this->addError('username', 'Already exists');
        }
    }

    public function uniqueEmail($attr)
    {
        if (empty($this->email)) return;

        $qry = Admin::find()->where(['email' => $this->email]);

        if ($this->admin_id) {
            $qry->andWhere(['<>', 'admin_id', $this->admin_id]);
        }

        $admin = $qry->one();

        if ($admin) {
            $this->addError('email', 'Already exists');
        }
    }
}