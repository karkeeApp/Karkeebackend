<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\Loan;
use common\helpers\Common;
use common\helpers\HRHelper;

/**
 * Login form
 */
class UserSMSForm extends Model
{
    public $loan_id;
    public $transfer_code;
    public $currency;
    public $date_transfer;
    public $remittance_type;
    public $amount;
    public $attachment;

    public function rules()
    {
        return [
            [['transfer_code', 'currency', 'date_transfer', 'remittance_type', 'amount'], 'trim'],
            ['attachment', 'required'],
            [['loan_id', 'transfer_code', 'amount', 'remittance_type', 'date_transfer', 'attachment'], 'required', 'on' => ['admin_add']],
            ['amount', 'validateAmount', 'on' => ['admin_add']],
            ['date_transfer', 'validateDate', 'on' => ['admin_add']],
            ['remittance_type', 'validateWallet', 'on' => ['admin_add']],
            [['transfer_code', 'currency', 'date_transfer', 'remittance_type', 'amount', 'attachment'], 'safe'],
        ];
    }

    public function validateAmount($attribute, $params)
    {
        $loan = Loan::findOne($this->loan_id);

        if (!$loan) {
            $this->addError($attribute, 'Loan not found.');
            return;
        }

        if ((float)$this->amount != (float)$loan->amount) {
//            $this->addError($attribute, 'Loan amount is ' . Common::currency($loan->amount) . '.');
            $this->addError($attribute, 'Invalid amount.');
            return;
        }
    }

    public function validateDate($attribute, $params)
    {
        $loan = Loan::findOne($this->loan_id);

        if (!$loan) {
            $this->addError($attribute, 'Loan not found.');
            return;
        }

        preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $this->date_transfer, $res);

        if (empty($res)){
            $this->addError($attribute, 'Invalid transfer date.');
            return;
        }

        list($all, $year, $month, $day) = $res;

        if (!checkdate($month, $day, $year)) {
            $this->addError($attribute, 'Invalid transfer date.');
            return;
        }

        if ($this->date_transfer < date('Y-m-d', strtotime($loan->created_at)) OR $this->date_transfer > date('Y-m-d')) {
            $this->addError($attribute, 'Invalid transfer date.');
            return;
        }
    }

    public function validateWallet($attribute, $params)
    {
        $loan = Loan::findOne($this->loan_id);

        if (!$loan) {
            $this->addError($attribute, 'Loan not found.');
            return;
        }

        $user = $loan->user;
        
        if (!(int)$user->remittance_type) {
            $this->addError($attribute, 'Staff has an invalid eWallet type.');
            return;
        }

        if (empty($user->mobile)) {
            $this->addError($attribute, 'Staff has an invalid mobile number.');
            return;
        }

        if ($this->remittance_type != $user->remittance_type) {
            $this->addError($attribute, 'Staff is using ' . UserForm::remittances()[$user->remittance_type] . '.');
            return;
        }
    }

    public function attributeLabels()
    {
        return [
            'transfer_code' => 'Transfer Code',
            'remittance_type'  => 'eWallet Type',
        ];
    }

}
