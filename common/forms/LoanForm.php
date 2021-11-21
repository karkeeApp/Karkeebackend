<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\User;
use common\helpers\Common;

/**
 * Login form
 */
class LoanForm extends Model
{
    public $loan_id;
    public $user_id;
    public $amount;
    public $fees;
    public $status;
    public $reason;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount', 'reason'], 'trim'],
            [['amount', 'reason'], 'required', 'on' => ['edit', 'add', 'account_add', 'account_edit']],
            ['amount', 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number', 'on' => ['edit', 'add', 'account_add', 'account_edit']],
            ['amount', 'validateAmount', 'on' => ['add', 'account_add',]],
            [['loan_id', 'user_id', 'fees', 'status', 'reason'], 'safe']
        ];
    }

    public function validateAmount($attribute, $params)
    {
        if (Common::isStaff()) {
            $user = Yii::$app->user->getIdentity();
        } else {
            $user = User::findOne($this->user_id);
        }

        if (!$user) {
            $this->addError('amount', 'User not found.');
            return;
        }

        $fund = $user->fund;

        $creditBalance = $fund->creditBalance();

        if ((float)$this->amount > $creditBalance) {
            $this->addError('amount', 'You only have ' . Common::currency($creditBalance) . ' remaining credits.');
            return;
        }
    }

}
