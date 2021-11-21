<?php
namespace common\forms;

use Yii;
use yii\base\Model;

class UserPaymentForm extends Model
{
    public $file;
    public $filename;
    public $name;
    public $description;
    public $user_id;
    public $is_premium;
    public $premium_status;
    public $payment_for;
    public $account_id = 0;
    public $amount;
    public $renewal_id;
    public $id;

    public function rules()
    {
        return [
            [['account_id','user_id','id','is_premium','amount','premium_status','filename','name'], 'safe'],
            [['description'], 'trim'],
            [['amount'], 'double'],
            [['account_id','user_id','id','is_premium','premium_status'], 'integer'],
            [['file','description'], 'required', 'on' => 'remove-ads'],
            [['amount','description','name'], 'required', 'on' => ['create-payment', 'edit-payment']],
            [['amount','name'], 'required', 'on' => ['admin-carkee-create-payment', 'admin-carkee-edit-payment']],
            [['id'], 'required', 'on' =>  'edit-payment'],
            [['is_premium'], 'required', 'on' => 'update-is-premium'],
            [['premium_status'], 'required', 'on' => 'update-premium-status'],
            ['file', 'file', 'skipOnEmpty' => FALSE, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 20, 'on' => ['remove-ads','create-payment']],
            ['file', 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 20, 'on' => ['admin-carkee-remove-ads','admin-carkee-create-payment']],
            
        ];
    }
}
