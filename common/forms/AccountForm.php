<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use common\models\Account;
use common\helpers\Common;

class AccountForm extends ActiveRecord
{
    public $account_id;
    public $company;
    public $status;

    public $company_full_name;
    public $address;
    public $contact_name;
    public $email;
    public $member_expiry;
    public $num_days_expiry;
    public $logo;
    public $file;
    public $model;

    public static function tableName()
    {
        return '{{%account}}';
    }

    public function rules()
    {
        return [
            [['company' , 'status','company_full_name','address','contact_name','email'], 'trim'],
            [['status', 'company','company_full_name','address'], 'required', 'on' => ['admin_add', 'admin_edit','admin-carkee-add', 'admin-carkee-edit']],

            ['company', 'unique', 'targetClass' => Account::class, 'on' => ['admin_add','admin-carkee-add']],
            ['company', 'unique', 'targetClass' => Account::class, 'filter' => ['!=','account_id', $this->account_id], 'on' => ['admin_edit', 'admin-carkee-edit']],
            // ['company', 'unique', 'targetClass' => Account::class, 'filter' => function ($query) {
            //     if (!$this->getModel()->isNewRecord) {
            //         $query->andWhere(['not', ['account_id' => $this->getModel()->account_id]]);
            //     }
            // }, 'on' => ['admin_add', 'admin_edit']],

            ['file', 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 20, 'on' => ['admin_add','admin_edit','admin-carkee-add', 'admin-carkee-edit']],

            [['account_id','member_expiry','num_days_expiry','enable_ads','skip_approval','renewal_alert','logo','file'], 'safe'],
        ];
    }

    public function getModel()
    {
        if (!$this->model) $this->model = new Account();

        return $this->model;
    }

    public static function statuses()
    {
        return [
            Account::STATUS_ACTIVE => 'Active',
            Account::STATUS_DELETED => 'Deleted',
        ];
    }

}