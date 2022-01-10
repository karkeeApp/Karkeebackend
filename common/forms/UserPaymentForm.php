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
    public $ads_id;
    public $event_id;
    public $log_card;
    public $file_logcard;
    public $id;

    public function rules()
    {
        return [
            [['account_id','user_id','id','ads_id','event_id','renewal_id','is_premium','amount','premium_status','filename','name','log_card','file_logcard'], 'safe'],
            [['description'], 'trim'],
            [['amount'], 'double'],
            [['account_id','user_id','id','is_premium','premium_status'], 'integer'],
            [['file','description'], 'required', 'on' => 'remove-ads'],
            [['amount','description','name'], 'required', 'on' => ['create-payment', 'edit-payment']],
            [['amount','name'], 'required', 'on' => ['admin-carkee-create-payment', 'admin-carkee-edit-payment']],
            [['id'], 'required', 'on' =>  'edit-payment'],
            [['is_premium'], 'required', 'on' => 'update-is-premium'],
            [['premium_status'], 'required', 'on' => 'update-premium-status'],
            [['file','file_logcard'], 'file', 'skipOnEmpty' => FALSE, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 20, 'on' => ['create-payment']],
            [['file','file_logcard'], 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 20, 'on' => ['remove-ads']],
            [['file', 'file_logcard'], 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 20, 'checkExtensionByMimeType' => false, 'on' => ['admin-carkee-remove-ads','admin-carkee-create-payment']],
            
        ];
    }
}
