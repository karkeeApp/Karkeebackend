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
class PaymentForm extends Model
{
    public $loan_id;
    public $amount;
    public $outstanding;

    public function rules()
    {
        return [
            ['amount', 'trim'],
            ['loan_id', 'required', 'on' => ['add', 'admin_add', 'account_add']],
            ['amount', 'validateAmount', 'on' => ['add', 'admin_add', 'account_add']],
            [['loan_id', 'outstanding'], 'safe'],
        ];
    }

    public function validateAmount($attribute, $params)
    {
        $loan = HRHelper::loan($this->loan_id, FALSE);

        if (!$loan) {
            $this->addError($attribute, 'Loan not found.');
            return;
        }

        if (!$loan->isRepayment()) {
            $this->addError($attribute, 'Loan is not in repayment status.');
            return;
        }

        if ((float)$this->amount > $loan->outstanding(FALSE)) {
            $this->addError($attribute, 'Oustanding balance is only ' . $loan->outstanding() . '.');
            return;
        }
    }

    public function attributeLabels()
    {
        return [
            'outstanding' => 'Oustanding Balance',
            'amount'      => 'Amount',
        ];
    }

}
